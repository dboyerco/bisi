<?
if($noemail == 'Y') {
	$FormAction = "index.php?pg=certification&PersonID=" . $PersonID . "&CD=" . $CD;
}
else {
	$FormAction = "index.php?pg={$nextPage}&PersonID=" . $PersonID . "&CD=" . $CD;
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
								<span class="sub-heading">Education History</span><br>
								List Your Highest Degree Information.<br />&nbsp;
							</div>';

$highestdegree = 'N';

$maxEduID = $dbo->query("Select max(EduID) from App_Education where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxEduID > 0) {
	$selectstmt = "select EduID, EduCollegeName, EduCity, EduState, EduDatesAttendedFrom, EduDatesAttendedTo, EduCollegeMajor, EduCollegeDegree, EduGraduated, EduIsHighestDegree from App_Education where PersonID='$PersonID';";
	$result2 = $dbo->prepare($selectstmt);
	$result2->bindValue(':PersonID', $PersonID);
	$result2->execute();
	$i = 0;

	while($row = $result2->fetch(PDO::FETCH_BOTH)) {
		if($row[9] == 'Y') {
			$highestdegree = 'Y';

			echo '<div class="cell small-12">
							<h3>Highest Degree</h3>
						</div>';
		}

		if($row[4] == '1900-01-01') {
			$fromdate = '';
		}
		else {
			$fromdate = date("m/d/Y", strtotime($row[4]));
		}

		if($row[5] == '1900-01-01') {
			$todate = '';
		}
		else {
			$todate = date("m/d/Y", strtotime($row[5]));
		}

		echo '	<div class="cell small-12 medium-11">
							<div class="grid-x">
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[1]) . '
								</div>
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[2]) . ', ' . htmlspecialchars($row[3]) . '
								</div>
								<div class="cell small-6 medium-3">
									' . htmlspecialchars($fromdate) . ' - ' . htmlspecialchars($todate) . '
								</div>
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[6]) . '
								</div>
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[7]) . '
								</div>
								<div class="cell small-6 medium-1">
									' . ($row[8] == 'Y' ? "Graduated" : "Did Not Graduate") . '
								</div>
							</div>
						</div>

						<div class="cell small-12 medium-1 top right">
							<span onclick="updateedu(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Education" title="Edit Education"/></span>&nbsp;&nbsp;
							<span onclick="deleteedu(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete Education" title="Delete Education"/></span>
						</div>

						<div class="cell small-12">
							<hr>
						</div>';

		$i++;
	}
}

echo '				<div class="cell small-12">
								<span class="add-education add-button"><img class="icon" src="images/plus.png" alt="Add Education" title="Add Education"/> Add Education</span>
							</div>
							<div class="cell small-12">
								<hr>
							</div>

							<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>
							<div class="cell small-6">
								<input class="button float-center" type="submit" value="Next">
							</div>
						</div>

						<div class="grid-x margins person-form" name="Education_dialog" id="Education_dialog" title="Dialog Title">
							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>';

if($highestdegree == 'N') {
	echo '			<div class="cell small-12 medium-6">
								Highest Degree? <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select name="eduhighest" id="eduhighest">
									<option value="Y">Yes</option>
									<option value="N">No</option>
								</select>
							</div>';
}
else {
	echo '			<input type="hidden" name="eduhighest" id="eduhighest" value="N">';
}

echo '				<div class="cell small-12 medium-6">
								Name of Institution <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<input type="text" name="eduname" id="eduname" value="" maxlength="40">
							</div>

							<div class="cell small-12 medium-6">
								City <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<input type="text" name="educity" id="educity" value="" maxlength="40">
							</div>

							<div class="cell small-12 medium-6">
								State <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select name="edustate" id="edustate">
									<option value="">Select a State</option>
									' . $state_options . '
								</select>
							</div>

							<div class="cell small-12 medium-6">
								OR If Education was out of the US, please select the Country
							</div>
							<div class="cell small-12 medium-6">
								<select name="educountry" id="educountry">
									<option value="">Select a Country</option>
									' . $country_options . '
								</select>
							</div>

							<div class="cell small-12 medium-6">
								Attended From <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select id="edufromdate_month" name="edufromdate_month" style="width: 30%">
									' . $months_list . '
								</select>
								/
								<select id="edufromdate_day" name="edufromdate_day" style="width: 30%">
									' . $days_list . '
								</select>
								/
								<select id="edufromdate_year" name="edufromdate_year" style="width: 30%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell small-12 medium-6">
								Attended To <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select id="edutodate_month" name="edutodate_month" style="width: 30%">
									' . $months_list . '
								</select>
								/
								<select id="edutodate_day" name="edutodate_day" style="width: 30%">
									' . $days_list . '
								</select>
								/
								<select id="edutodate_year" name="edutodate_year" style="width: 30%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell small-12 medium-6">
								Major <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<input type="text" name="edumajor" id="edumajor" value="" maxlength="40">
							</div>

							<div class="cell small-12 medium-6">
								Degree <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select name="edudegree" id="edudegree">
									<option value="">Select Degree</option>
									<option value="Associates">Associates</option>
									<option value="Bachelor of Arts">Bachelor of Arts</option>
									<option value="Bachelor of Science">Bachelor of Science</option>
									<option value="Certificate">Certificate</option>
									<option value="Doctorate">Doctorate</option>
									<option value="GED or Equilavent">GED or Equilavent</option>
									<option value="High School Diploma">High School Diploma</option>
									<option value="Judicial Doctorate">Judicial Doctorate</option>
									<option value="Masters">Masters</option>
								</select>
							</div>

							<div class="cell small-12 medium-6">
								Did You Graduate? <span class="required">*</span>
							</div>
							<div class="cell small-12 medium-6">
								<select name="edugraduated" id="edugraduated">
									<option value="N">No</option>
									<option value="Y">Yes</option>
								</select>
							</div>

							<div class="cell small-12 padding-bottom">
								<input id="save_education" class="float-center" type="button" value="Save Education">
							</div>

							<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
					  	<input type="hidden" name="EduID" id="EduID" value="">
						</div>
					</div>
				</div>
			</FORM>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#Education_dialog").dialog({ autoOpen: false });

<?php
	if($maxEduID <= 0) {
		echo 'addEducation();';
	}
?>

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	$(".add-education").click(function() {
		addEducation();
	});

	function addEducation() {
		$("#EduID").val('');
		$("#eduname").val('');
		$("#educity").val('');
		$("#edustate").val('');
		$("#educountry").val('');
		$("#edufromdate_month").val('');
		$("#edufromdate_day").val('');
		$("#edufromdate_year").val('');
		$("#edutodate_month").val('');
		$("#edutodate_day").val('');
		$("#edutodate_year").val('');
		$("#edumajor").val('');
		$("#edudegree").val('');
		$("#edugraduated").val('');
		$("#eduhighest").val('');

		$("#Education_dialog").dialog("option", "title", "Add Education");
		$("#Education_dialog").dialog("option", "modal", true);
		$("#Education_dialog").dialog("option", "width", "100%");
		$("#Education_dialog").dialog("open");
	}

 	function updateedu(eduid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_education.php",
			data: { personid: personid, eduid: eduid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					fd = obj2.EduDatesAttendedFrom.split("-");
					var fromDateMonth = fd[1];
					var fromDateDay = fd[2];
					var fromDateYear = fd[0];
					td = obj2.EduDatesAttendedTo.split("-");
					var toDateMonth = td[1];
					var toDateDay = td[2];
					var toDateYear = td[0];

					$("#EduID").val(obj2.EduID);
					$("#eduname").val(obj2.EduCollegeName);
					$("#educity").val(obj2.EduCity);
					$("#edustate").val(obj2.EduState);
					$("#educountry").val(obj2.EduStateOther);
					$("#edufromdate_month").val(fromDateMonth);
					$("#edufromdate_day").val(fromDateDay);
					$("#edufromdate_year").val(fromDateYear);
					$("#edutodate_month").val(toDateMonth);
					$("#edutodate_day").val(toDateDay);
					$("#edutodate_year").val(toDateYear);
					$("#edumajor").val(obj2.EduCollegeMajor);
					$("#edudegree").val(obj2.EduCollegeDegree);
					$("#edugraduated").val(obj2.EduGraduated);
					$("#eduhighest").val(obj2.EduIsHighestDegree);

					$("#Education_dialog").dialog("option", "title", "Edit Education");
					$("#Education_dialog").dialog("option", "modal", true);
					$("#Education_dialog").dialog("option", "width", "100%");
					$("#Education_dialog").dialog("open");
				}
				else {
					alert('No Education Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_education").click(function() {
		var personid = $("#PersonID").val();
		var eduid = $("#EduID").val();

		var saveLocation = "../App_Ajax_New/ajax_add_education.php";

		if(eduid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_education.php";
		}

		if($("#eduname").val() > '') {
			var eduname = $("#eduname").val();
		}
		else {
			$("#eduname").focus();
			alert("Name of Instition is required");
			return;
		}

		if($("#educity").val() > '') {
			var educity = $("#educity").val();
		}
		else {
			$("#educity").focus();
			alert("City is required");
			return;
		}

		if($("#edustate").val() == '' && $("#educountry").val() == '' ) {
			$("#edustate").val().focus();
			alert("State or Country is required");
			return;
		}
		else {
			var edustate = $("#edustate").val();
			var educountry = $("#educountry").val();
		}

		if($("#edufromdate_month").val() > '' && $("#edufromdate_day").val() > '' && $("#edufromdate_year").val() > '') {
			var edufromdate = $("#edufromdate_year").val() + '-' + $("#edufromdate_month").val() + '-' + $("#edufromdate_day").val();
		}
		else {
			$("#edufromdate_day").focus();
			alert("Attended From Date is required");
			return;
		}

		if($("#edutodate_month").val() > '' && $("#edutodate_day").val() > '' && $("#edutodate_year").val() > '') {
			var edutodate = $("#edutodate_year").val() + '-' + $("#edutodate_month").val() + '-' + $("#edutodate_day").val();
		}
		else {
			$("#edutodate_day").focus();
			alert("Attended To Date is required");
			return;
		}

		if(!isValidDiff(edufromdate, edutodate)) {
			$('#edufromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if($("#edumajor").val() > '') {
			var edumajor = $("#edumajor").val();
		}
		else {
			$("#edumajor").focus();
			alert("Major is required");
			return;
		}

		if($("#edudegree").val() > '') {
			var edudegree = $("#edudegree").val();
		}
		else {
			$("#edudegree").focus();
			alert("Degree is required");
			return;
		}

		if ($("#edugraduated").val() > '') {
			var edugraduated = $("#edugraduated").val();
		}
		else {
			$("#edugraduated").focus();
			alert("Did you graduate is required");
			return;
		}

		var eduhighest = $("#eduhighest").val();

		var data = {
			personid: personid,
			eduid: eduid,
			eduname: eduname,
			educity: educity,
			edustate: edustate,
			edustateother: educountry,
			edufromdate: edufromdate,
			edutodate: edutodate,
			edumajor: edumajor,
			edudegree: edudegree,
			edugraduated: edugraduated,
			eduhighest: eduhighest
		};
		console.log(data);
		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					$("#Education_dialog").dialog("close");
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

	function deleteedu(eduid) {
		if(confirm('Are you sure you want to delete this education record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_education.php",
				data: {personid: personid, EduID: eduid},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0, 4) == 'Error') {
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

	$("#close_education").click(function() {
		$("#Education_dialog").dialog("close");
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var eduid = $("#EduID").val();

		if(eduid == 0) {
			$('#newbankname').focus();
			alert('You have not entered your highest degree earned');
			return false;
		}
		else {
			return true;
		}
	}
</script>
