<?
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
$empCount = 0;

$FormAction = "index.php?pg=education&PersonID=" . $PersonID . "&CD=" . $CD;

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

							<div class="cell small-12 medium-6">
								May we contact your current employer?
							</div>
							<div class="cell small-12 medium-6">
								' . ($row[15] == "Y" ? "Yes" : "No") . '
							</div>';
			}
			else {
				echo '<div class="cell small-12 right">
								<span class="add-employment"><img class="icon" src="images/plus.png" alt="Add Employment" title="Add Employment"/></span>
							</div>';
			}

			echo '<div class="cell small-12 medium-6">
							Company Name:
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[1]) . '
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[5]) . '<br />
							' . htmlspecialchars($row[2]) . '<br />
							' . ($row[4] > '' ? htmlspecialchars($row[4]) : htmlspecialchars($row[3])) . '
						</div>';

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

			echo '  <div class="cell small-12 medium-6">
								Dates:
							</div>
							<div class="cell small-12 medium-6">
								' . htmlspecialchars($fromdate) . ' - ' . htmlspecialchars($todate) . '
							</div>

							<div class="cell small-12 medium-3">
								Position:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[11]) . '
							</div>
							<div class="cell small-12 medium-3">
								Supervisor:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[9]) . '
							</div>

							<div class="cell small-12 medium-3">
								Phone:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[12]) . '
							</div>
							<div class="cell small-12 medium-3">
								Supervisor Phone:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[13]) . '
							</div>

							<div class="cell small-12 medium-3">
								Reason for Leaving:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[10]) . '
							</div>
							<div class="cell small-12 medium-3">
								Supervisor Email:
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

echo '</div>
			<div class="grid-x margins person-form" name="Employment_dialog" id="Employment_dialog" title="Dialog Title">
				<div class="cell small-12">
					<h3>Add Employment</h3>
				</div>
				<div class="cell medium-6 small-12 required">
					* Required Fields To Continue
				</div>
				<div class="cell medium-6 small-12">
					You have entered ' . $YR . ' years ' . $MO . ' months ' . $DY . ' days
				</div>';

if($currentEmployer == 'N') {
	echo '<div class="cell small-12 medium-6">
					Current employer? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="current" id="current">
						<option value="Y">Yes</option>
						<option value="N">No</option>
					</select>
				</div>

				<div class="cell small-12 medium-6">
					May we contact your current employer? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="empcontact" id="empcontact">
						<option value="N">No</option>
						<option value="Y">Yes</option>
					</select>
				</div>';
}
else {
	echo '			<input type="hidden" name="current" id="current" value="Y">';
}

echo '  <div class="cell small-12 medium-6">
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
					<input type="text" name="empfromdate" id="empfromdate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					To Date <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="emptodate" id="emptodate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
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

			<div class="cell small-12 padding-bottom">
				<input class="float-center" type="submit" value="Next">
			</div>

			<input type="hidden" name="PersonID" id="PersonID" VALUE="' . $PersonID . '">
	  	<input type="hidden" name="EmpID" id="EmpID" VALUE=" ' . $maxEmpID . '">
	  	<input type="hidden" name="Package" id="Package" VALUE="' . $Package . '">
	  	<input type="hidden" name="nodays" ID="nodays" VALUE=" ' . $days . '">
		</div>
	</form>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#Employment_dialog").dialog({ autoOpen: false });

<?php
	if($days < 2557 && $empCount < 3) {
		echo 'addEmployment();';
	}
?>

	$(".add-employment").click(function() {
		addEmployment();
	});

	function addEmployment() {
		$("#current").val('');
		$("#empid").val('');

		$("#Employment_dialog").dialog("option", "title", "Add Address");
		$("#Employment_dialog").dialog("option", "modal", true);
		$("#Employment_dialog").dialog("option", "width", "100%");
		$("#Employment_dialog").dialog("open");
	}

 	function updateemp(empid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_employment.php",
			data: { personid: personid, empid: empid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(valor.length > 0) {
					for(var i = 0; i < obj2.length; i++) {
						var EmpID = obj2[i].EmpID;
						var EmpName = obj2[i].EmpName;
						var EmpStreet = obj2[i].EmpStreet;
						var EmpCity = obj2[i].EmpCity;
						var EmpState = obj2[i].EmpState;
						var EmpCountry = obj2[i].EmpStateOther;
						var fd = obj2[i].EmpDateFrom;
						var EmpDateFrom = fd.substr(5, 2) + "/" + fd.substr(8) + "/" + fd.substr(0, 4);
						var td = obj2[i].EmpDateTo;
						var EmpDateTo = td.substr(5, 2) + "/" + td.substr(8) + "/" + td.substr(0, 4);
						var EmpSupervisor = obj2[i].EmpSupervisor;
						var EmpReasonForLeaving = obj2[i].EmpReasonForLeaving;
						var EmpTitle = obj2[i].EmpTitle;
						var EmpPhone = obj2[i].EmpPhone;
						var EmpCurrent = obj2[i].EmpCurrent;
						var EmpMayWeContact = obj2[i].EmpMayWeContact;
						var EmpSupervisorPhone = obj2[i].EmpSupervisorPhone;
						var EmpSupervisorEmail = obj2[i].EmpSupervisorEmail;
						var EmpDotReg = obj2[i].EmpDotReg;
						var EmpDotTst = obj2[i].EmpDotTst;
			    }

					$("#empid").val(EmpID);
					$("#contact").val(EmpMayWeContact);
					$("#current").val(EmpCurrent);
					$("#empname").val(EmpName);
					$("#addr").val(EmpStreet);
					$("#city").val(EmpCity);
					$("#state").val(EmpState);
					$("#country").val(EmpCountry);
					$("#fromdate").val(EmpDateFrom);
					$("#todate").val(EmpDateTo);
					$("#super").val(EmpSupervisor);
					$("#reason").val(EmpReasonForLeaving);
					$("#title").val(EmpTitle);
					$("#phone").val(EmpPhone);
					$("#sphone").val(EmpSupervisorPhone);
					$("#semail").val(EmpSupervisorEmail);

					if($("#Package").val() == 'mountain') {
						$("#empdotreg").val(EmpDotReg);
						$("#empdottst").val(EmpDotTst);
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
		var current_employment = $("#current").val();

		var saveLocation = "../App_Ajax/ajax_add_employment.php";

		if(empid > 0) {
			saveLocation = "../App_Ajax/ajax_save_employment.php";
		}

		if($("#empname").val() > '') {
			var empname = $("#empname").val();
		}
		else {
			document.ALCATEL.dlgempname.focus();
			alert("Company Name is required");
			return;
		}

		contact = $("#contact").val();

		if($("#addr").val() > '') {
			var addr = $("#addr").val();
		}
		else {
			document.ALCATEL.dlgaddr.focus();
			alert("Street is required");
			return;
		}

		if($("#city").val() > '') {
			var city = $("#city").val();
		}
		else {
			document.ALCATEL.dlgcity.focus();
			alert("City is required");
			return;
		}

		if($("#state").val() == '' && $("#country").val() == '' ) {
			document.ALCATEL.dlgstate.focus();
			alert("State or Country is required");
			return;
		}
		else {
			var state = $("#state").val();
			var stateother = $("#country").val();
		}

		if($("#phone").val() > '') {
			var phone = $("#phone").val();
		}
		else {
			document.ALCATEL.dlgphone.focus();
			alert("Phone is required");
			return;
		}

		if($("#fromdate").val() > '') {
			if(!isValidDate('fromdate')) {
				$('#fromdate').focus();
				alert("Invalid From Date");
				return false;
			}
			else {
				var fromdate = $("#fromdate").val();
			}
		}
		else {
			document.ALCATEL.dlgfromdate.focus();
			alert("From Date is required");
			return;
		}

		if($("#todate").val() > '') {
			if(!isValidDate('todate')) {
				$('#todate').focus();
				alert("Invalid To Date");
				return false;
			}
			else {
				var todate = $("#todate").val();
			}
		}
		else {
			document.ALCATEL.dlgtodate.focus();
			alert("To Date is required");
			return;
		}

		if(!isValidDiff(fromdate, todate)) {
			$('#fromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if($("#title").val() > '') {
			var position = $("#title").val();
		}
		else {
			document.ALCATEL.dlgtitle.focus();
			alert("Position is required");
			return;
		}

		if($("#super").val() > '') {
			var supervisor = $("#super").val();
		}
		else {
			document.ALCATEL.dlgsuper.focus();
			alert("Supervisor is required");
			return;
		}

		if($("#sphone").val() > '') {
			var sphone = $("#sphone").val();
		}
		else {
			var sphone = '';
		}

		if($("#semail").val() > '') {
			var semail = $("#semail").val();
		}
		else {
			var semail = '';
		}

		if($("#reason").val() > '') {
			var reason = $("#reason").val();
		}
		else {
			var reason = '';
		}

		if($("#Package").val() == "mountain") {
			var empdotreg = $("#empdotreg").val();
			var empdottst = $("#empdottst").val();
		}
		else {
			var empdotreg = '';
			var empdottst = '';
		}

		$.ajax({
			type: "POST",
			url: saveLocation,
			data: { personid: personid, empid: empid, empname: empname, addr: addr, city: city, state: state, stateother: stateother, phone: phone, fromdate: fromdate, todate: todate, current_employment: current_employment, contact: contact, position: position, supervisor: supervisor, sphone: sphone, semail: semail, reason: reason, empdotreg: empdotreg, empdottst: empdottst },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					$( "#Employment_dialog" ).dialog( "close" );
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
//		alert("In dltaka");
		if(confirm('Are you sure you want to delete this employment record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_employment.php",
				data: { personid: personid, EmpID: EmpID },
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

//		alert('Number of Days: '+nodays);
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
