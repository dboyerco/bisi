<?php
$DLDSRCI = $dbo->query("Select Driver_License_D_S_R_C_Info from App_DL_D_S_R_C_Info where PersonID = " . $PersonID . ";")->fetchColumn();

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
								<span class="sub-heading">Traffic Violations</span><br>
								&nbsp;
							</div>

							<div class="cell medium-6 small-12">
								<b>Have you ever had any driver\'s license denied, suspended, revoked or cancelled by any issuing state agency?</b><br />&nbsp;
							</div>
							<div class="cell medium-6 small-12">
								<select name="Driver_License_DSRC" id="Driver_License_DSRC" onchange="turnonDSRCInfo();">
									<option value="">Select Option</option>
									<option value="No">No</OPTION>
									<option value="Yes">Yes</option>
								</select>
							</div>

							<div class="cell small-12 DSRCInfo">
								<b>Please explain reason and date of occurrence. Be as specific as possible.</b>
							</div>
							<div class="cell small-12 DSRCInfo">
								<textarea name="Driver_License_DSRC_Info" id="Driver_License_DSRC_Info" rows="5" cols="60" maxlength="256" onblur="adddsrcinfo()">' . htmlspecialchars($DLDSRCI) . '</textarea>
							</div>

							<div class="cell small-12">
								<hr>
							</div>

							<div class="cell medium-6 small-12">
								<b>Have you ever had a traffic violation/conviction within the last 3 years (other than parking violations)?</b><br />&nbsp;
							</div>
							<div class="cell medium-6 small-12">
								<select name="violation" id="violation" onchange="turnonViolation();">
									<option value="">Select Option</option>
									<option value="No">No</OPTION>
									<option value="Yes">Yes</option>
								</select>
							</div>';

$maxRecID = $dbo->query("Select max(RecID) from App_Traffic_Violations where PersonID = " . $PersonID . ";")->fetchColumn();
$DLDSRC = $dbo->query("Select Driver_License_D_S_R_C from App_DL_D_S_R_C_Info where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxRecID > 0) {
	$selectaccident = "select * from App_Traffic_Violations where PersonID = :PersonID;";
	$accident_result = $dbo->prepare($selectaccident);
	$accident_result->bindValue(':PersonID', $PersonID);
	$accident_result->execute();

	while($row = $accident_result->fetch(PDO::FETCH_BOTH)) {
		if($row["Violation_Date"] == '1900-01-01') {
			$violationdate = '';
		}
		else {
			$violationdate = date("m/d/Y", strtotime($row["Violation_Date"]));
		}

		echo '		<div class="cell small-6 sub-heading">
								' .	htmlspecialchars($violationdate) . '
							</div>
							<div class="cell small-6 right">
								<span onclick="updatetraffic_violation(' . $row["RecID"] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Traffic Violation" title="Edit Traffic Violation"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deletetraffic_violation(' . $row["RecID"] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Traffic Violation" title="Delete Traffic Violation"/></span>
							</div>

							<div class="cell small-12 medium-3">
								<b>Info:</b> ' . htmlspecialchars($row["Violation_Info"]) . '
							</div>
							<div class="cell small-12 medium-3">
								<b>State:</b> ' . htmlspecialchars($row["Violation_State"]) . '
							</div>
							<div class="cell small-12 medium-3">
								<b>Commercial Vehicle:</b> ' . htmlspecialchars($row["Commercial_Vehicle"]) . '
							</div>
							<div class="cell medium-3"></div>

							<div class="cell small-12 medium-3">
								<b>Convicted:</b> ' . htmlspecialchars($row["Convicted_Paid_Fine"]) . '
							</div>
							<div class="cell small-12 medium-3">
								<b>Info:</b> ' . htmlspecialchars($row["Convicted_Info"]) . '
							</div>
							<div class="cell medium-3"></div>
							<div class="cell medium-3"></div>

							<div class="cell small-12">
								<hr>
							</div>';
	}
}
else {
	echo '			<div class="cell small-12 add-traffic-violation-button">
								<hr>
							</div>';
}

echo '				<div class="cell small-12 add-traffic-violation-button">
								<span class="add-traffic-violation add-button"><img class="icon" src="images/plus.png" alt="Add Traffic Violation" title="Add Traffic Violation" /> Add Traffic Violation</span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>

							<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>

							<div class="cell small-6">
								<input class="button float-center" type="submit" id="next" value="Next">
							</div>
						</div>

						<div class="grid-x margins person-form" name="Traffic_Violation_dialog" id="Traffic_Violation_dialog" title="Dialog Title">
							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell small-12">
								Add Traffic Violations/Convictions Information (last 3 years from date of application)<br />&nbsp;
							</div>

							<div class="cell medium-6 small-12">
								Date <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select id="Violation_Date_month" name="Violation_Date_month" style="width: 38%">
									' . $months_list . '
								</select>
								/
								<select id="Violation_Date_day" name="Violation_Date_day" style="width: 24%">
									' . $days_list . '
								</select>
								/
								<select id="Violation_Date_year" name="Violation_Date_year" style="width: 29%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								Type of Violation <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<textarea name="Violation_Info" id="Violation_Info" rows="5" cols="60" maxlength="256"></textarea>
							</div>

							<div class="cell medium-6 small-12">
								State <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="Violation_State" id="Violation_State">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								Commercial Vehicle? <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="Commercial_Vehicle" id="Commercial_Vehicle">
									<option value="">Select Option</option>
									<option VALUE="No">No</OPTION>
									<option value="Yes">Yes</option>
								</select>
							</div>

							<div class="cell medium-6 small-12">
								Convicted(Yes or No)? <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="Convicted_Paid_Fine" id="Convicted_Paid_Fine" onchange="convictedquestion();">
									<option value="">Select Option</option>
									<option VALUE="No">No</OPTION>
									<option value="Yes">Yes</option>
								</select>
							</div>

							<div class="cell medium-6 small-12 convicted-detail">
								List your Penalty and Sentence <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12 convicted-detail">
								<textarea name="Convicted" id="Convicted" rows="5" cols="60" maxlength="256"></textarea>
							</div>

							<div class="cell small-12 padding-bottom">
								<input id="save_traffic_violation" class="float-center" type="button" value="Save Traffic Violation">
							</div>
						</div>

						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
					  <input type="hidden" name="RecID"    id="RecID"    value="' . $maxRecID . '">
						<input type="hidden" name="recid"    id="recid"    value="">
					  <input type="hidden" name="Package"  id="Package"  value="' . $package . '">
					  <input type="hidden" name="DLDSRC"   id="DLDSRC"   value="' . $DLDSRC . '">
					</div>
				</div>
			</form>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#Traffic_Violation_dialog").dialog({ autoOpen: false });

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	$(".add-traffic-violation").click(function() {
		addTrafficViolation();
	});

	function addTrafficViolation() {
		$("#recid").val("");
		$("#Violation_Date_month").val("");
		$("#Violation_Date_day").val("");
		$("#Violation_Date_year").val("");
		$("#Violation_Info").val("");
		$("#Violation_State").val("");
		$("#Commercial_Vehicle").val("");
		$("#Convicted_Paid_Fine").val("");
		$("#Convicted").val("");

		$('.convicted-detail').hide();

		$("#Traffic_Violation_dialog").dialog("option", "title", "Add Traffic Violation");
		$("#Traffic_Violation_dialog").dialog("option", "modal", true);
		$("#Traffic_Violation_dialog").dialog("option", "width", "100%");
		$("#Traffic_Violation_dialog").dialog("open");
	}

	$().ready(function() {
		turnonDSRCInfo();
		turnonViolation();
	});

	function turnonDSRCInfo() {
		if($("#Driver_License_DSRC").val() == "No" && $("#Driver_License_DSRC_Info").val() != "") {
			$("#Driver_License_DSRC_Info").val("");
			adddsrcinfo();
		}

		if($("#Driver_License_DSRC_Info").val() != "") {
			$("#Driver_License_DSRC").val("Yes");
		}

		if($("#Driver_License_DSRC").val() == "Yes") {
			$(".DSRCInfo").show();
		}
		else {
			$(".DSRCInfo").hide();
		};

		return true;
	}

	function turnonViolation() {

		if($("#violation").val() == "No" && $("#RecID").val() > 0) {
			alert("Please remove all Traffic Violation records before setting this answer to 'No'.");
		}

		if($("#RecID").val() > 0) {
			$("#violation").val("Yes");
		}

		if($("#violation").val() == "Yes") {
			$(".add-traffic-violation-button").show();
		}
		else {
			$(".add-traffic-violation-button").hide();
		};

		return true;
	}

	function convictedquestion() {
		if($("#Convicted_Paid_Fine").val() == 'Yes') {
			$('.convicted-detail').show();
		}
		else {
			$('.convicted-detail').hide();
		};

		return true;
	}

	function adddsrcinfo() {
		var personid = $("#PersonID").val();
		var Driver_License_DSRC = $('#Driver_License_DSRC').val();

		if($('#Driver_License_DSRC').val() == 'Yes') {
			if($('#Driver_License_DSRC_Info').val() == '') {
				alert('Reason and Date of Occurrence is required');
				return;
			}
			else {
				var Driver_License_DSRC_Info = $('#Driver_License_DSRC_Info').val();
			}
		}
		else {
			var Driver_License_DSRC_Info = '';
		}

		var data = {
			personid: personid,
			Driver_License_D_S_R_C: Driver_License_DSRC,
			Driver_License_D_S_R_C_Info: Driver_License_DSRC_Info
		};

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_update_driver_license_d_s_r_c.php",
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2.length > 30) {
					alert(obj2);
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function updatetraffic_violation(recid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_traffic_violation.php",
			data: { personid: personid, recid: recid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					var RecID = obj2[i].RecID;
					var fd = obj2.Violation_Date.split("-");
					var Violation_Date_day = fd[2];
					var Violation_Date_month = fd[1];
					var Violation_Date_year = fd[0];
					var Violation_Info = obj2[i].Violation_Info;
					var Violation_State = obj2[i].Violation_State;
					var Commercial_Vehicle = obj2[i].Commercial_Vehicle;
					var Convicted_Paid_Fine = obj2[i].Convicted_Paid_Fine;
					var Convicted_Info = obj2[i].Convicted_Info;

					$("#recid").val(obj2.RecID);
					$("#Violation_Date_month").val(Violation_Date_month);
					$("#Violation_Date_day").val(Violation_Date_day);
					$("#Violation_Date_year").val(Violation_Date_year);
					$("#Violation_Info").val(obj2.Violation_Info);
					$("#Violation_State").val(obj2.Violation_State);
					$("#Commercial_Vehicle").val(obj2.Commercial_Vehicle);
					$("#Convicted_Paid_Fine").val(obj2.Convicted_Paid_Fine);

					if($("#Convicted_Paid_Fine").val() == 'Yes') {
						$("#Convicted").val(obj2.Convicted_Info);
						$('.convicted-detail').show();
					}
					else {
						$("#Convicted").val("");
						$('.convicted-detail').hide();
					};

					$("#Traffic_Violation_dialog").dialog("option", "title", "Edit Traffic violation");
					$("#Traffic_Violation_dialog").dialog("option", "modal", true);
					$("#Traffic_Violation_dialog").dialog("option", "width", "100%");
					$("#Traffic_Violation_dialog").dialog("open");
				}
				else {
					alert('No Traffic Violation Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}


 	$("#save_traffic_violation").click(function() {
		var personid = $("#PersonID").val();
		var recid = $("#recid").val();
		var saveLocation = "../App_Ajax_New/ajax_add_traffic_violation.php";

		if(recid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_traffic_violation.php";
		}

		if($("#Violation_Date_day").val() > '' && $("#Violation_Date_month").val() > '' && $("#Violation_Date_year").val() > '') {
			var violation_date = $("#Violation_Date_year").val() + '-' + $("#Violation_Date_month").val() + '-' + $("#Violation_Date_day").val();
		}
		else {
			$("#Violation_Date_day").focus();
			alert("Violation Date is required");
			return;
		}

		if($("#Violation_Info").val() == '') {
			$('#Violation_Info').focus();
			alert("Violation Info is required");
			return;
		}
		else {
			var violation_info = $("#Violation_Info").val();
		}

		if($("#Violation_State").val() > '') {
			var violation_state = $("#Violation_State").val();
		}
		else {
			$('#Violation_State').focus();
			alert("Violation State is required");
			return;
		}

		if($("#Commercial_Vehicle").val() > '') {
			var commercial_vehicle = $("#Commercial_Vehicle").val();
		}
		else {
			$('#Commercial_Vehicle').focus();
			alert("Commercial Vehicle is required");
			return;
		}

		if($("#Convicted_Paid_Fine").val() == '') {
			$('#Convicted_Paid_Fine').focus();
			alert("Convicted Paid Fine is required");
			return;
		}
		else {
			var convicted_paid_fine = $("#Convicted_Paid_Fine").val();
		}

		if(convicted_paid_fine == 'Yes') {
			if($("#Convicted").val() == '') {
				$('#Convicted').focus();
				alert("Convicted is required");
				return;
			}
			else {
				var convicted_info = $("#Convicted").val();
			}
		}
		else {
			var convicted_info = '';
		}

		var data = {
			personid: personid,
			recid: recid,
			violation_date: violation_date,
			violation_info: violation_info,
			violation_state: violation_state,
			commercial_vehicle: commercial_vehicle,
			convicted_paid_fine: convicted_paid_fine,
			convicted_info: convicted_info
		};

		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2.length > 30) {
					alert(obj2);
				}
				else {
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

	function deletetraffic_violation(RecID) {
		if(confirm('Are you sure you want to delete this Traffic Violation record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_traffic_violation.php",
				data: {personid: personid, RecID: RecID},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					}
					else {
						location.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_traffic_violation").click(function() {
		$("#Traffic_Violation_dialog").dialog("close");
	});

 	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var recid = $("#RecID").val();

		if($("#accident").val() == '') {
				$("#accident").focus();
				alert('Please select Yes/No');
				return false;
		}

		if($("#accident").val() == 'Yes') {
			if(recid > 0) {
				return true;
			}
			else {
				$("#Vehicle_Type").focus();
				alert('You have not entered any accident info');
				return false;
			}
		}
		else {
			return true;
		}
	}
</script>
