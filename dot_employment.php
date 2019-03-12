<?
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
$pastYears = 2;

if($Package == 'package2' || $Package == 'package3') {
	$releasefnd = $dbo->query("Select count(*) from App_Uploads where PersonID = " . $PersonID . " and UploadType = 'DOT Questionnaire FMCSA-PHMSA Form';")->fetchColumn();
	//$FormAction = "drv_experience.php?PersonID=" . $PersonID;
}
else {
	$releasefnd = 1;
}

if($Package == 'package2') {
	$pastYears = 3;
}

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
								<span class="sub-heading">Employment History</span><br />
								Per DOT Regulations, please complete 10 yrs of employment history beginning with your most current.<br />
								For the past ' . $pastYears . ' yrs, the DOT form must be completed for each employer.<br />
								Click on "Save Employment" or "Download Form" for additional instructions.<br />&nbsp;
							</div>';

$currentEmployer = 'N';

$maxEmpID = $dbo->query("Select max(EmpID) from App_Employment where PersonID = ".$PersonID.";")->fetchColumn();

if($maxEmpID > 0) {
	$selectemp = "SELECT EmpID, EmpName, EmpCity, EmpState, EmpStateOther, EmpStreet, EmpZip, EmpDateFrom, EmpDateTo, EmpSupervisor, EmpReasonForLeaving, EmpTitle, EmpPhone, EmpSupervisorPhone, EmpSupervisorEmail, EmpMayWeContact, EmpCurrent, EmpDotReg, EmpDotTst FROM App_Employment WHERE PersonID = :PersonID;";

	$emp_result = $dbo->prepare($selectemp);
	$emp_result->bindValue(':PersonID', $PersonID);
	$emp_result->execute();

	while($row = $emp_result->fetch(PDO::FETCH_BOTH)) {
		if($row[16] == 'Y') {
			$currentEmployer = 'Y';

			echo '<div class="cell small-12">
							<h3>Current Employment</h3>
						</div>

						<div class="cell small-12 medium-3">
							<label>May we contact your current employer?</label>
						</div>
						<div class="cell small-12 medium-3">
							' . ($row[15] == "Y" ? "Yes" : "No") . '
						</div>
						<div class="cell small-12 medium-6"></div>';
		}

		echo '	<div class="cell small-12 medium-3">
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
						</div>';

		if($Package == "package2" || $Package == "package3") {
			$dotreg = 'No';
			$dottst = 'No';

			if($row[17] == 'Y') {
				$dotreg = 'Yes';
			}

			if($row[18] == 'Y') {
				$dottst = 'Yes';
			}

			echo '	<div class="cell small-12 medium-6">
								FMCSA or PHMSA Safety Regulations:&nbsp;&nbsp;' . $dotreg . '
							</div>
							<div class="cell small-12 medium-6">
								Designated as safety sensitive: &nbsp;&nbsp;' . $dottst . '
							</div>';
	  }

		echo '		<div class="cell small-12 right">
								<span onclick="updateemp(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Employment" title="Edit Employment"/></span>&nbsp;&nbsp;&nbsp;
								<span onclick="deleteemp(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete Employment" title="Delete Employment"/></span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>';
	}

	if($days > 0) {
		$YR = floor($days / 365);
		$MO = floor(($days - (floor($days / 365) * 365)) / 30);
		$DY = $days - (($YR * 365) + ($MO * 30));
	}
	else {
		$YR = 0;
		$MO = 0;
		$DY = 0;
	}
}
else {
	$maxEmpID = 0;
}

echo '				<div class="cell small-12">
								<span class="add-employment add-button"><img class="icon" src="images/plus.png" alt="Add Employment" title="Add Employment"/> Add Employment</span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>';

$shownext = false;

if($Package == 'package2' && $releasefnd > 0 && $days > 1095) {
	$shownext = true;
}

if($Package == 'package3' && $releasefnd > 0 && $days > 730) {
	$shownext = true;
}

if(!$shownext) {
	echo '			<div class="cell small-12">
								<input class="button float-center" type="button" id="requireddoc" value="Download/Upload DOT Questionnaire FMCSA-PHMSA Form">
							</div>

							<div class="cell small-12">
								<hr>
							</div>';

	include('Upload/UploadDialog.php');
}

echo '				<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>';

if($shownext) {
	echo '			<div class="cell small-6">
								<input class="button float-center" type="submit" value="Next">
							</div>';
}

echo '			</div>

						<div class="grid-x margins person-form" name="Employment_dialog" id="Employment_dialog" title="Dialog Title">
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
								<input type="text" text="number" name="empphone" id="empphone" value="" placeholder="### ### #### #####" onKeyUp="return frmtphone(this,\'up\')">
							</div>

							<div class="cell small-12 medium-6">
								From Date <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select id="empfromdate_month" name="empfromdate_month" style="width: 30%">
									' . $months_list . '
								</select>
								/
								<select id="empfromdate_day" name="empfromdate_day" style="width: 30%">
									' . $days_list . '
								</select>
								/
								<select id="empfromdate_year" name="empfromdate_year" style="width: 30%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell small-12 medium-6">
								To Date <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select id="emptodate_month" name="emptodate_month" style="width: 30%">
									' . $months_list . '
								</select>
								/
								<select id="emptodate_day" name="emptodate_day" style="width: 30%">
									' . $days_list . '
								</select>
								/
								<select id="emptodate_year" name="emptodate_year" style="width: 30%">
									' . $years_list . '
								</select>
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
								<input type="text" text="number" name="sphone" id="sphone" placeholder="### ### #### #####" onKeyUp="return frmtphone(this,\'up\')">
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

if($Package == "package2" || $Package == "package3") {
	echo '			<div class="cell small-12 medium-6">
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

echo '				<div class="cell small-12 padding-bottom">
								<input id="save_employment" class="float-center" type="button" value="Save Employment">
							</div>

							<input type="hidden" name="empid" 			id="empid">
							<input type="hidden" name="PersonID" 		id="PersonID" 	value="' . $PersonID . '">
					  	<input type="hidden" name="EmpID" 			id="EmpID" 			value=" ' . $maxEmpID . '">
					  	<input type="hidden" name="Package" 		id="Package" 		value="' . $package . '">
					  	<input type="hidden" name="nodays" 			id="nodays" 		value=" ' . $days . '">
							<input type="hidden" name="UploadType" 	id="UploadType" value="DOT Questionnaire FMCSA-PHMSA Form">
							<input type="hidden" name="releasefnd" 	id="releasefnd" value="' . $releasefnd . '">
						</div>

						<div class="grid-x margins person-form" name="reqdoc" id="reqdoc" title="Dialog Title">
							<div class="cell small-12">
								Please download and complete the DOT Questionnaire FMCSA-PHMSA Form for this employer.<span class="required">*</span>
							</div>

							<div class="cell small-12">
								<input class="button float-center" type="button" name="dlform" id="dlform" value="Download Form" onclick="return downloadForm()">
							</div>

							<div class="cell small-12">
								Clicking on the "Download Form" button will open the form in a separate tab.
							</div>

							<div class="cell small-12 padding-bottom">
								<strong>Print the form, complete Section 1, and sign with a handwritten signature.</strong><br />&nbsp;
							</div>

							<div class="cell small-12 center">
								<a href="#openPhotoDialog">Click Here To Upload The Document.</a><br />&nbsp;
							</div>

							<div class="cell small-12">
								You will need to complete a form for each employer entered within a ' . $pastYears . ' year period.<br />
								Your background screening will not be processed until this form is completed and returned to BISI.
							</div>
						</div>
					</form>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#Employment_dialog").dialog({ autoOpen: false });
	$("#reqdoc").dialog({ autoOpen: false });

	$(document).ready(function() {
		if($("#releasefnd").val() > 0) {
			$("#submitid").show();
		}
	});

	function downloadForm() {
//   		el = $("#submitid");
//  		elbutton = $("#dlform");
//		elbutton.style.visibility = "hidden";
//		el.style.visibility = "visible";
		window.open('https://proteus.bisi.com/pusgcorpoffice/Peak-Utility-Services-Group-DOT-Form-2-2019.pdf');
	}

	$("#requireddoc").click(function() {
		$("#reqdoc").dialog("option", "title", "Down and Upload DOT Form");
		$("#reqdoc").dialog("option", "modal", true);
		$("#reqdoc").dialog("option", "width", "100%");
		$("#reqdoc").dialog("open");
	});

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
					for (var i = 0; i < obj2.length; i++) {
						var EmpID = obj2[i].EmpID;
						var EmpName = obj2[i].EmpName;
						var EmpStreet = obj2[i].EmpStreet;
						var EmpCity = obj2[i].EmpCity;
						var EmpState = obj2[i].EmpState;
						var EmpStateOther = obj2[i].EmpStateOther;
						var fd = obj2[i].EmpDateFrom;
						var EmpDateFrom = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var td = obj2[i].EmpDateTo;
						var EmpDateTo = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
						var EmpSupervisor = obj2[i].EmpSupervisor;
//						var EmpReasonForLeaving = obj2[i].EmpReasonForLeaving;
						var EmpTitle = obj2[i].EmpTitle;
						var EmpPhone = obj2[i].EmpPhone;
						var EmpCurrent = obj2[i].EmpCurrent;
						var EmpMayWeContact = obj2[i].EmpMayWeContact;
						var EmpSupervisorPhone = obj2[i].EmpSupervisorPhone;
						var EmpSupervisorEmail = obj2[i].EmpSupervisorEmail;
						var EmpDotReg = obj2[i].EmpDotReg;
						var EmpDotTst = obj2[i].EmpDotTst;
			    	}
					$("#dlgempid").val() = EmpID;
					$("#dlgcontact").val() = EmpMayWeContact;
					$("#dlgcurrent").val() = EmpCurrent;
					$("#dlgempname").val() = EmpName;
					$("#dlgaddr").val() = EmpStreet;
					$("#dlgcity").val() = EmpCity;
					$("#dlgstate").val() = EmpState;
					$("#dlgstateother").val() = EmpStateOther;
					$("#dlgfromdate").val() = EmpDateFrom;
					$("#dlgtodate").val() = EmpDateTo;
					$("#dlgsuper").val() = EmpSupervisor;
//					$("#dlgreason").val() = EmpReasonForLeaving;
					$("#dlgtitle").val() = EmpTitle;
					$("#dlgphone").val() = EmpPhone;
					$("#dlgsphone").val() = EmpSupervisorPhone;
					$("#dlgsemail").val() = EmpSupervisorEmail;
					if ($("#Package").val() == 'package2' || $("#Package").val() == "package3") {
						$("#dlgempdotreg").val() = EmpDotReg;
						$("#dlgempdottst").val() = EmpDotTst;
					}

					$( "#Employment_dialog" ).dialog( "option", "title", "Edit Employment");
					$( "#Employment_dialog" ).dialog( "option", "modal", true );
					$( "#Employment_dialog" ).dialog( "option", "width", 700 );
					$( "#Employment_dialog" ).dialog( "open" );
				} else {
					alert('No Employment Data Found');
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	}

 	$("#save_employment").click(function() {
		var personid = $("#PersonID").val();
		var empid = $("#dlgempid").val();
		var current_employment = $("#dlgcurrent").val();

		if ($("#dlgempname").val() > '') {
			var empname = $("#dlgempname").val();
		} else {
			document.ALCATEL.dlgempname.focus();
			$('#dlgempname').focus();
			alert("Company Name is required");
			return;
		}
		contact = $("#dlgcontact").val();

		if ($("#dlgaddr").val() > '') {
			var addr = $("#dlgaddr").val();
		} else {
			$('#dlgaddr').focus();
			alert("Street is required");
			return;
		}

		if ($("#dlgcity").val() > '') {
			var city = $("#dlgcity").val();
		} else {
			document.ALCATEL.dlgcity.focus();
			$('#dlgcity').focus();
			alert("City is required");
			return;
		}

		if ($("#dlgstate").val() == '' && $("#dlgstateother").val() == '' ) {
			$('#dlgstate').focus();
			alert("State or Country is required");
			return;
		} else {
			var state = $("#dlgstate").val();
			var stateother = $("#dlgstateother").val();
		}
		if ($("#dlgphone").val() > '') {
			var phone = $("#dlgphone").val();
		} else {
			$('#dlgphone').focus();
			alert("Phone is required");
			return;
		}

		if ($("#dlgfromdate").val() > '') {
			if (!isValidDate('dlgfromdate')) {
				$('#dlgfromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {
				var fromdate = $("#dlgfromdate").val();
			}
		} else {
			document.ALCATEL.dlgfromdate.focus();
			alert("From Date is required");
			return;
		}

		if ($("#dlgtodate").val() > '') {
			if (!isValidDate('dlgtodate')) {
				$('#dlgtodate').focus();
				alert("Invalid To Date");
				return false;
			} else {
				var todate = $("#dlgtodate").val();
			}
		} else {
			document.ALCATEL.dlgtodate.focus();
			$('#dlgtodate').focus();
			alert("To Date is required");
			return;
		}
		if (!isValidDiff(fromdate,todate)) {
			$('#dlgfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if ($("#dlgtitle").val() > '') {
			var position = $("#dlgtitle").val();
		} else {
			$('#dlgtitle').focus();
			alert("Position is required");
			return;
		}

		if ($("#dlgsuper").val() > '') {
			var supervisor = $("#dlgsuper").val();
		} else {
			$('#dlgsuper').focus();
			alert("Supervisor is required");
			return;
		}
		if ($("#dlgsphone").val() > '') {
			var sphone = $("#dlgsphone").val();
		} else {
			$('#dlgsphone').focus();
			alert("Supervisor Phone is required");
			return;
		}
		if ($("#dlgsemail").val() > '') {
			var semail = $("#dlgsemail").val();
		} else {
			$('#dlgsemail').focus();
			alert("Supervisor Email is required");
			return;
		}
		var reason = '';
		if ($("#Package").val() == "package2" || $("#Package").val() == "package3") {
			var empdotreg = $("#dlgempdotreg").val();
			var empdottst = $("#dlgempdottst").val();
		} else {
			var empdotreg = '';
			var empdottst = '';
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_employment.php",
			data: {personid: personid, empid: empid, empname: empname, addr: addr, city: city, state: state, stateother: stateother, phone: phone, fromdate: fromdate, todate: todate, current_employment: current_employment, contact: contact, position: position, supervisor: supervisor, sphone: sphone, semail: semail, reason: reason, empdotreg: empdotreg, empdottst: empdottst},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {
					$( "#Employment_dialog" ).dialog( "close" );
					location.reload();
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

	function deleteemp(EmpID) {
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this employment record?')) {
			var personid = $("#PersonID").val();
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_employment.php",
				data: {personid: personid, EmpID: EmpID},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					} else {
						location.reload();
						return;
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				}
			});
		}
	}
	$( "#close_employment" ).click(function() {
		$( "#Employment_dialog" ).dialog( "close" );
	});
 	$( "#close_reqdoc" ).click(function() {
		$( "#reqdoc" ).dialog( "close" );
		location.reload(true);
	});

 	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var empid = $("#EmpID").val();
		var nodays = $("#days").val();

		if(nodays >= 3650) {
			return true;
		}
		else {
			$("#newempname").focus();
			alert('You have not entered 10 years of employment.');
			return false;
		}
	}
</script>
<script src="Upload/Upload.js"></script>
