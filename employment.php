<?
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
$empCount = 0;

$FormAction = "index.php?pg={$nextPage}&PersonID=" . $PersonID . "&CD=" . $CD;

echo '<form method="post" action="' . $FormAction . '" name="ALCATEL">
				<div class="general-page">
					<div class="sub-menu">&nbsp;</div>

					<div class="sub-page">
						<div class="grid-x margins person-form">

							<div class="cell small-12">
								<h3>
									' . $compname . ' Web Application Portal<br>
									<img src="files/horizontal-line.gif" height="3" width="100%">
								</h3>
							</div>

							<div class="cell small-12">
								<span class="sub-heading">Employment History</span><br>
								Please provide your current and most recent 3 employments or 7 years of employments.<br />&nbsp;
							</div>';

$currentEmployer = 'N';

if(!$testLayout) {
	$maxEmpID = $dbo->query("Select max(EmpID) from App_Employment where PersonID = ".$PersonID.";")->fetchColumn();

	if($maxEmpID > 0) {
		$selectemp = "select EmpID, EmpName, EmpCity, EmpState, EmpStateOther, EmpStreet, EmpZip, EmpDateFrom, EmpDateTo, EmpSupervisor, EmpReasonForLeaving, EmpTitle, EmpPhone, EmpSupervisorPhone, EmpSupervisorEmail, EmpMayWeContact, EmpCurrent, EmpDotReg, EmpDotTst from App_Employment where PersonID = :PersonID;";

		$emp_result = $dbo->prepare($selectemp);
		$emp_result->bindValue(':PersonID', $PersonID);
		$emp_result->execute();
		$i = 0;

		while($row = $emp_result->fetch(PDO::FETCH_BOTH)) {
			$empCount++;

			if($row[16] == 'Y') {
				$currentEmployer = 'Y';

				echo '<div class="cell small-8">
								<h3>Current Employment</h3>
							</div>
							<div class="cell small-4 right">
								<span class="add-employment"><img class="icon" src="images/plus.png" alt="Add Employment" title="Add Employment"/></span>
							</div>

							<div class="cell small-12 medium-3">
								<label>May we contact your current employer?</label>
							</div>
							<div class="cell small-12 medium-3">
								' . ($row[15] == "Y" ? "Yes" : "No") . '
							</div>
							<div class="cell small-12 medium-6"></div>';
			}
			else if($i == 0) {
				echo '<div class="cell small-12 right">
								<span class="add-employment"><img class="icon" src="images/plus.png" alt="Add Employment" title="Add Employment" /></span>
							</div>';
			}

			echo '<div class="cell small-12 medium-3">
							<label>Company Name:</label>
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[1]) . '
						</div>
						<div class="cell small-12 medium-6"></div>

						<div class="cell small-12 medium-3">
							<label>Company Address:</label>
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[5]) . '<br />
							' . htmlspecialchars($row[2]) . '<br />
							' . ($row[4] > '' ? htmlspecialchars($row[4]) : htmlspecialchars($row[3])) . '
						</div>
						<div class="cell small-12 medium-6"></div>';

			if($row[7] == '1900-01-01') {
				$fromdate = '';
			}
			else {
				$fromdate = date("m/d/Y", strtotime($row[7]));
			}

			if($row[8] == '1900-01-01') {
				$todate = '';
			}
			else {
				$todate = date("m/d/Y", strtotime($row[8]));
			}

			if($fromdate != '' && $todate != '') {
				$datediff = strtotime($todate) - strtotime($fromdate);
				$days = $days + floor($datediff / (60 * 60 * 24));
			}

			echo '  <div class="cell small-12 medium-3">
								<label>Dates:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($fromdate) . ' - ' . htmlspecialchars($todate) . '
							</div>
							<div class="cell small-12 medium-6"></div>

							<div class="cell small-12 medium-3">
								<label>Position:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[11]) . '
							</div>
							<div class="cell small-12 medium-3">
								<label>Supervisor:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[9]) . '
							</div>

							<div class="cell small-12 medium-3">
								<label>Phone:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[12]) . '
							</div>
							<div class="cell small-12 medium-3">
								<label>Supervisor Phone:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[13]) . '
							</div>

							<div class="cell small-12 medium-3">
								<label>Reason for Leaving:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[10]) . '
							</div>
							<div class="cell small-12 medium-3">
								<label>Supervisor Email:</label>
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[14]) . '
							</div>

							<div class="cell small-12 right">
								<span onclick="updateemp(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Employment" title="Edit Employment"/></span>&nbsp;&nbsp;&nbsp;
								<span onclick="deleteemp(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete Employment" title="Delete Employment"/></span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>';

			$i++;
		} // end while

		if($days > 0){
			$YR = floor($days / 365);
			$MO = floor(($days - (floor($days / 365) * 365)) / 30);
			$DY = $days - (($YR * 365) + ($MO * 30));
		}
		else {
			$YR = 0;
			$MO = 0;
			$DY = 0;
		}
	} // end if()$maxEmpID > 0)
	else {
		$maxEmpID = 0;
	}
} // end if(!$testLayout))
else {
	echo '<div class="cell small-8">
					<h3>Current Employment</h3>
				</div>
				<div class="cell small-4 right">
					<span class="add-employment"><img class="icon" src="images/plus.png" alt="Add Employment" title="Add Employment"/></span>
				</div>

				<div class="cell small-12 medium-3">
					<label>May we contact your current employer?</label>
				</div>
				<div class="cell small-12 medium-3">
					No
				</div>
				<div class="cell small-12 medium-6"></div>

				<div class="cell small-12 medium-3">
					<label>Company Name:</label>
				</div>
				<div class="cell small-12 medium-3">
					Zero300 Studios
				</div>
				<div class="cell small-12 medium-6"></div>

				<div class="cell small-12 medium-3">
					<label>Company Address:</label>
				</div>
				<div class="cell small-12 medium-3">
					Address Line 1<br />
					Loveland, CO<br />
					USA
				</div>
				<div class="cell small-12 medium-6"></div>

				<div class="cell small-12 medium-3">
					<label>Dates:</label>
				</div>
				<div class="cell small-12 medium-3">
					01/01/2001 - 05/04/2018
				</div>
				<div class="cell small-12 medium-6"></div>

				<div class="cell small-12 medium-3">
					<label>Position:</label>
				</div>
				<div class="cell small-12 medium-3">
					Owner
				</div>
				<div class="cell small-12 medium-3">
					<label>Supervisor:</label>
				</div>
				<div class="cell small-12 medium-3">
					Myself
				</div>

				<div class="cell small-12 medium-3">
					<label>Phone:</label>
				</div>
				<div class="cell small-12 medium-3">
					970-123-1234
				</div>
				<div class="cell small-12 medium-3">
					<label>Supervisor Phone:</label>
				</div>
				<div class="cell small-12 medium-3">
					303-987-6543
				</div>

				<div class="cell small-12 medium-3">
					<label>Reason for Leaving:</label>
				</div>
				<div class="cell small-12 medium-3">
					I didn\'t leave
				</div>
				<div class="cell small-12 medium-3">
					<label>Supervisor Email:</label>
				</div>
				<div class="cell small-12 medium-3">
					303-987-6543
				</div>

				<div class="cell small-12 right">
					<span onclick="updateemp(1)"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Employment" title="Edit Employment"/></span>&nbsp;&nbsp;&nbsp;
					<span onclick="deleteemp(1)"><img class="icon" src="images/deletetrashcan.png" alt="Delete Employment" title="Delete Employment"/></span>
				</div>

				<div class="cell small-12">
					<hr>
				</div>';
}

echo '	<div class="cell small-12 padding-bottom">
					<input class="float-center" type="submit" value="Next">
				</div>
			</div>

			<div class="grid-x margins person-form" name="Employment_dialog" id="Employment_dialog" title="Dialog Title">
				<div class="cell small-12">
					<h3>Add Employment</h3>
				</div>
				<div class="cell medium-6 small-12 required">
					* Required Fields To Continue
				</div>
				<div class="cell medium-6 small-12">
					You have entered ' . $YR . ' years ' . $MO . ' months ' . $DY . ' days
				</div>

				<div class="cell small-12 medium-6 current-emp">
					Current employer? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6 current-emp">
					<select name="current" id="current">
						<option value="Y">Yes</option>
						<option value="N">No</option>
					</select>
				</div>

				<div class="cell small-12 medium-6 current-emp">
					May we contact your current employer? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6 current-emp">
					<select name="empcontact" id="empcontact">
						<option value="N">No</option>
						<option value="Y">Yes</option>
					</select>
				</div>

				<div class="cell small-12 medium-6">
					Company Name <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="empname" id="empname" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Address <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="empstreet" id="empstreet" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					City <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="empcity" id="empcity" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					State <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="empstate" id="empstate">
						<option value="">Select a State</option>
						<option value="">-Other-</option>
						' . $state_options . '
					</select>
				</div>

				<div class="cell small-12">
					OR If Employment was out of the US, please select the Country
				</div>

				<div class="cell small-12 medium-6">
					Country
				</div>
				<div class="cell small-12 medium-6">
					<select name="empcountry" id="empcountry">
						<option value="">Select a Country</option>
						' . $country_options . '
					</select>
				</div>

				<div class="cell small-12 medium-6">
					Phone <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" text="number" name="empphone" id="empphone" value="" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					From Date <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="date" name="empfromdate" id="empfromdate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					To Date <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="date" name="emptodate" id="emptodate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					Position <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="emptitle" id="emptitle" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Supervisor <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="empsuper" id="empsuper" value="" maxlength="40"></font>
				</div>

				<div class="cell small-12 medium-6">
					Supervisor Phone <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" text="number" name="sphone" id="sphone" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					Supervisor Email <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="semail" id="semail" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Reason for leaving <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="reason" id="reason" maxlength="40">
				</div>';

if($Package == "mountain") {
	echo '<div class="cell small-12 medium-6">
					Were you subject to FMCSA or PHMSA Safety Regulations while employed? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="empdotreg" id="empdotreg">
						<option value="N">NO</option>
						<option value="Y">YES</option>
					</select>
				</div>

				<div class="cell small-12 medium-6">
					Was this job designated as a safety sensitive function in any DOT regulated mode and therefore subject to alcohol and controlled substances testing requirements? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="empdottst" id="empdottst">
						<option value="N">NO</option>
						<option value="Y">YES</option>
					</select>
				</div>';
}

echo '<div class="cell small-12 padding-bottom">
				<input id="save_employment" class="float-center" type="button" value="Save Employment">
			</div>
		<input type="hidden" name="empid" id="empid">
			<input type="hidden" name="PersonID" id="PersonID" VALUE="' . $PersonID . '">
	  	<input type="hidden" name="EmpID" id="EmpID" VALUE=" ' . $maxEmpID . '">
	  	<input type="hidden" name="Package" id="Package" VALUE="' . $Package . '">
	  	<input type="hidden" name="nodays" ID="nodays" VALUE=" ' . $days . '">
		</div>
	</form>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#Employment_dialog").dialog({ autoOpen: false });

	var currentEmployer = true;

<?php
	if($currentEmployer == 'N') {
		echo 'currentEmployer = false;';
	}

	if($days < 2557 && $empCount < 3) {
		echo 'addEmployment();';
	}
?>

	$(".add-employment").click(function() {
		addEmployment();
	});

	function addEmployment() {
		$("#current").val('N');
		$("#empid").val('');

		if(currentEmployer) {
			hideCurrentEmployer();
		}
		else {
			showCurrentEmployer();
		}

		$("#Employment_dialog").dialog("option", "title", "Add Address");
		$("#Employment_dialog").dialog("option", "modal", true);
		$("#Employment_dialog").dialog("option", "width", 700);
		$("#Employment_dialog").dialog("open");
	}

	function showCurrentEmployer() {
		console.log("showCurrentEmployer");
		$(".current-emp").css("display", "block");
	}

	function hideCurrentEmployer() {
		console.log("hideCurrentEmployer");
		$(".current-emp").css("display", "none");
		$("#current").val('N');
	}

 	function updateemp(empid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_employment.php",
			data: { personid: personid, empid: empid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				console.log(obj2);
				if(obj2) {
					$("#empid").val(obj2.EmpID);
					$("#contact").val(obj2.EmpMayWeContact);
					$("#current").val(obj2.EmpCurrent == "Y" ? "Y" : "N");
					$("#empname").val(obj2.EmpName);
					$("#empstreet").val(obj2.EmpStreet);
					$("#empcity").val(obj2.EmpCity);
					$("#empstate").val(obj2.EmpState);
					$("#empcountry").val(obj2.EmpStateOther);
					$("#empfromdate").val(obj2.EmpDateFrom);
					$("#emptodate").val(obj2.EmpDateTo);
					$("#empsuper").val(obj2.EmpSupervisor);
					$("#reason").val(obj2.EmpReasonForLeaving);
					$("#emptitle").val(obj2.EmpTitle);
					$("#empphone").val(obj2.EmpPhone);
					$("#sphone").val(obj2.EmpSupervisorPhone);
					$("#semail").val(obj2.EmpSupervisorEmail);

					if($("#Package").val() == 'mountain') {
						$("#empdotreg").val(obj2.EmpDotReg);
						$("#empdottst").val(obj2.EmpDotTst);
					}

					if(currentEmployer) {
						console.log("we have a current employer");
						if($("#current").val() == "Y") {
							showCurrentEmployer();
						}
						else {
							hideCurrentEmployer();
						}
					}
					else {
						console.log("we do not have a current employer");
						showCurrentEmployer();
					}

					$("#Employment_dialog").dialog("option", "title", "Edit Employment");
					$("#Employment_dialog").dialog("option", "modal", true);
					$("#Employment_dialog").dialog("option", "width", 700);
					$("#Employment_dialog").dialog("open");
				}
				else {
					alert('No Employment Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_employment").click(function() {
		var personid = $("#PersonID").val();
		var empid = $("#empid").val();
		var current = $("#current").val();
		var saveLocation = "../App_Ajax_New/ajax_add_employment.php";

		if(empid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_employment.php";
		}
		if($("#empname").val() > '') {
			var empname = $("#empname").val();
		}
		else {
			$("#empname").focus();
			alert("Company Name is required");
			return;
		}

		empcontact = $("#empcontact").val();

		if($("#empstreet").val() > '') {
			var empstreet = $("#empstreet").val();
		}
		else {
			$("#empstreet").focus();
			alert("Address is required");
			return;
		}

		if($("#empcity").val() > '') {
			var empcity = $("#empcity").val();
		}
		else {
			$("#empcity").focus();
			alert("City is required");
			return;
		}

		if($("#empstate").val() == '' && $("#empcountry").val() == '' ) {
			$("#empstate").focus();
			alert("State or Country is required");
			return;
		}
		else {
			var empstate = $("#empstate").val();
			var empcountry = $("#empcountry").val();
		}

		if($("#empphone").val() > '') {
			var empphone = $("#empphone").val();
		}
		else {
			$("#empphone").focus();
			alert("Phone is required");
			return;
		}

		if($("#empfromdate").val() > '') {
			var empfromdate = $("#empfromdate").val();
		}
		else {
			$("#empfromdate").focus();
			alert("From Date is required");
			return;
		}

		if($("#emptodate").val() > '') {
			var emptodate = $("#emptodate").val();
		}
		else {
			$("#emptodate").focus();
			alert("To Date is required");
			return;
		}

		if(!isValidDiff(empfromdate, emptodate)) {
			$('#empfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if($("#emptitle").val() > '') {
			var emptitle = $("#emptitle").val();
		}
		else {
			$("#emptitle").focus();
			alert("Position is required");
			return;
		}

		if($("#empsuper").val() > '') {
			var empsuper = $("#empsuper").val();
		}
		else {
			$("#empsuper").focus();
			alert("Supervisor is required");
			return;
		}

		if($("#sphone").val() > '') {
			var sphone = $("#sphone").val();
		}
		else {
			$("#sphone").focus();
			alert("Supervisor Phone # is required");
			return;
		}

		if($("#semail").val() > '') {
			var semail = $("#semail").val();
		}
		else {
			$("#semail").focus();
			alert("Supervisor Email Address is required");
			return;
		}

		if($("#reason").val() > '') {
			var reason = $("#reason").val();
		}
		else {
			$("#reason").focus();
			alert("Reason is required");
			return;
		}

		if($("#Package").val() == "mountain") {
			var empdotreg = $("#empdotreg").val();
			var empdottst = $("#empdottst").val();
		}
		else {
			var empdotreg = '';
			var empdottst = '';
		}

		var data = {
			personid: personid,
			empid: empid,
			empname: empname,
			addr: empstreet,
			city: empcity,
			state: empstate,
			stateother: empcountry,
			phone: empphone,
			fromdate: empfromdate,
			todate: emptodate,
			current_employment: current,
			contact: empcontact,
			position: emptitle,
			supervisor: empsuper,
			sphone: sphone,
			semail: semail,
			reason: reason,
			empdotreg: empdotreg,
			empdottst: empdottst
		};
		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				console.log(valor);
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				}
				else {
					$("#Employment_dialog").dialog("close");
					location.reload();
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

	function deleteemp(EmpID) {
		if(confirm('Are you sure you want to delete this employment record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_employment.php",
				data: {
					personid: personid,
					EmpID: EmpID
				},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					}
					else {
						location.reload();
						return;
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_employment").click(function() {
		$("#Employment_dialog").dialog("close");
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var empid = $("#EmpID").val();
		var nodays = $("#nodays").val();

		if(empid > 2 || nodays >= 2555) {
			return true;
		}
		else {
			document.ALCATEL.newempname.focus();
			alert('You have not entered at least 3 employments or 7 years of employments');
			return false;
		}
	}
</script>
