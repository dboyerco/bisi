<?
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;

echo '<form method="post" action="' . $FormAction . '" name="ALCATEL"
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
								<span class="sub-heading">Driving Information</span><br>
								&nbsp;
							</div>

							<div class="cell medium-6 small-12">
								<b>Have you ever driven a commercial vehicle?</b>
							</div>
							<div class="cell medium-6 small-12">
								<select name="haveyoudriven" id="haveyoudriven" onchange="turnonquestions();">
									<option value="">Select Option (Required)</option>
									<option value="No">No</OPTION>
									<option value="Yes">Yes</option>
								</select>
							</div>';

$maxRecID = $dbo->query("Select max(RecID) from App_DRV_EXP where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxRecID == '') {
	$maxRecID = 0;
}

if($maxRecID > 0) {
	$selectdrvexp = "select * from App_DRV_EXP where PersonID = :PersonID;";
	$drvexp_result = $dbo->prepare($selectdrvexp);
	$drvexp_result->bindValue(':PersonID', $PersonID);
	$drvexp_result->execute();

	while($row = $drvexp_result->fetch(PDO::FETCH_BOTH)) {
		if($row["Date_Driven_From"] == '1900-01-01') {
			$fromdate = '';
		}
		else {
			$fromdate = date("m/d/Y", strtotime($row["Date_Driven_From"]));
		}

		if($row["Date_Driven_To"] == '1900-01-01') {
			$todate = '';
		}
		else {
			$todate = date("m/d/Y", strtotime($row["Date_Driven_To"]));
		}

		echo '		<div class="cell small-6 sub-heading">
								' .	htmlspecialchars($row["Vehicle_Type"]) . '
							</div>
							<div class="cell small-6 right">
								<span onclick="updatedrvexp(' . $row["RecID"] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit License" title="Edit License"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deletedrvexp(' . $row["RecID"] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete License" title="Delete License"/></span>
							</div>

							<div class="cell small-12 medium-3">
								<b>Approx Miles::</b> ' . htmlspecialchars($row["Approx_Miles"]) . '
							</div>
							<div class="cell small-12 medium-3">
								<b>Dates:</b> ' . htmlspecialchars($fromdate) . ' - ' . htmlspecialchars($todate) . '
							</div>

							<div class="cell small-12">
								<hr>
							</div>';
		}
	}

echo '				<div class="cell small-12">
								<span class="add-driving-exp add-button"><img class="icon" src="images/plus.png" alt="Add Driving Experience" title="Add Driving Experience" /> Add Driving Experience</span>
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

						<div class="grid-x margins person-form" name="DRV_EXP_dialog" id="DRV_EXP_dialog" title="Dialog Title">
							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell medium-6 small-12">
								Type of Vehicle <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="Vehicle_Type" id="Vehicle_Type">
	        				<option value="">Select Vehicle Type (Required)</option>
	        				<option value="Straight Truck">Straight Truck</OPTION>
	        				<option value="Tractor Semi-Trailer">Tractor Semi-Trailer</option>
	        				<option value="Tractor Twin Trailers">Tractor Twin Trailers</option>
	        				<option value="Tractor Tanker">Tractor Tanker</option>
	        				<option value="Other">Other (Specify Below)</option>
	      				</select>
							</div>

							<div class="cell medium-6 small-12">
								If you selected Other, please enter Vehicle type here:
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="Vehicle_Type_Other" id="Vehicle_Type_Other" maxlength="50">
							</div>

							<div class="cell medium-6 small-12">
								Approx miles <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="Approx_Miles" id="Approx_Miles" maxlength="25">
							</div>

							<div class="cell medium-6 small-12">
								Date Driven From <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select id="Date_Driven_From_month" name="Date_Driven_From_month" style="width: 33%">
									' . $months_list . '
								</select>
								/
								<select id="Date_Driven_From_day" name="Date_Driven_From_day" style="width: 27%">
									' . $days_list . '
								</select>
								/
								<select id="Date_Driven_From_year" name="Date_Driven_From_year" style="width: 29%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								Date Driven To <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select id="Date_Driven_To_month" name="Date_Driven_To_month" style="width: 33%">
									' . $months_list . '
								</select>
								/
								<select id="Date_Driven_To_day" name="Date_Driven_To_day" style="width: 27%">
									' . $days_list . '
								</select>
								/
								<select id="Date_Driven_To_year" name="Date_Driven_To_year" style="width: 29%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell small-12 padding-bottom">
								<br /><br /><input id="save_drv_exp" class="float-center" type="button" value="Save Driving Experience">
							</div>
						</div>

						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
	  				<input type="hidden" name="RecID" id="RecID" value="' . $maxRecID . '">
						<input type="hidden" name=recid" id="recid" value="">
					</div>
				</div>
			</form>';

?>

<script language="JavaScript" type="text/javascript">
 	$("#DRV_EXP_dialog").dialog({ autoOpen: false });

	$(".add-driving-exp").click(function() {
		addDrivingExp();
	});

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function addDrivingExp() {
		$("#recid").val('');
		$("#Vehicle_Type").val('');
		$("#Vehicle_Type_Other").val('');
		$("#Approx_Miles").val('');
		$("#Date_Driven_From_month").val('');
		$("#Date_Driven_From_day").val('');
		$("#Date_Driven_From_year").val('');
		$("#Date_Driven_To_month").val('');
		$("#Date_Driven_To_day").val('');
		$("#Date_Driven_To_year").val('');

		$("#DRV_EXP_dialog").dialog("option", "title", "Add Driving Experience");
		$("#DRV_EXP_dialog").dialog("option", "modal", true);
		$("#DRV_EXP_dialog").dialog("option", "width", "100%");
		$("#DRV_EXP_dialog").dialog("open");
	}

	$().ready(function() {
		turnonquestions();
	});

	function turnonquestions() {
		console.log("turnonquestions: " + $("#RecID").val());
		if($("#RecID").val() > 0) {
			$('.add-driving-exp').show();
			$("#haveyoudriven").val('Yes');
		}
		else {
			$('.add-driving-exp').hide();
		}

		if($("#haveyoudriven").val() == 'Yes') {
			if($("#RecID").val() == 0) {
				addDrivingExp();
			}

			$('.add-driving-exp').show();
		}
		else {
			if ($("#haveyoudriven").val() == 'No') {
				$("#next").show();
			}
			else {
				$("#next").hide();
			}

			$('.add-driving-exp').hide();
		}

		return true;
	}

	function updatedrvexp(recid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_drv_exp.php",
			data: { personid: personid, recid: recid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					var fd = obj2.Date_Driven_From.split("-");
					var Date_Driven_From_day = fd[2];
					var Date_Driven_From_month = fd[1];
					var Date_Driven_From_year = fd[0];
					var td = obj2.Date_Driven_To.split("-");
					var Date_Driven_To_day = td[2];
					var Date_Driven_To_month = td[1];
					var Date_Driven_To_year = td[0];

					$("#RecID").val(obj2.RecID);
					$("#Vehicle_Type").val(obj2.Vehicle_Type);
					$("#Vehicle_Type_Other").val(obj2.Vehicle_Type_Other);
					$("#Approx_Miles").val(obj2.Approx_Miles);
					$("#Date_Driven_From_month").val(Date_Driven_From_month);
					$("#Date_Driven_From_day").val(Date_Driven_From_day);
					$("#Date_Driven_From_year").val(Date_Driven_From_year);
					$("#Date_Driven_To_month").val(Date_Driven_To_month);
					$("#Date_Driven_To_day").val(Date_Driven_To_day);
					$("#Date_Driven_To_year").val(Date_Driven_To_year);

					$("#DRV_EXP_dialog").dialog("option", "title", "Edit Driving Experience");
					$("#DRV_EXP_dialog").dialog("option", "modal", true);
					$("#DRV_EXP_dialog").dialog("option", "width", "100%");
					$("#DRV_EXP_dialog").dialog("open");
				}
				else {
					alert('No Driving Experience Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_drv_exp").click(function() {
		var personid = $("#PersonID").val();
		var recid = $("#recid").val();

		var saveLocation = "../App_Ajax_New/ajax_add_drv_exp.php";

		if(recid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_drv_exp.php";
		}

 		if($("#Vehicle_Type").val() == '' && $("#Vehicle_Type_Other").val() == '') {
			$('#Vehicle_Type').focus();
			alert("Vehicle Type or Vehicle Type Other is required");
			return;
		}
		else {
			var vehicle_type = $("#Vehicle_Type").val();
			var vehicle_type_other = $("#Vehicle_Type_Other").val();
		}

		if($("#Vehicle_Type_Other").val() > '') {
			var vehicle_type_other = $("#Vehicle_Type_Other").val();
		}

		if($("#Approx_Miles").val() > '') {
			var approx_miles = $("#Approx_Miles").val();
		}
		else {
			document.ALCATEL.Approx_Miles.focus();
			alert("Approx Miles is required");
			return;
		}

		if($("#Date_Driven_From_day").val() > '' && $("#Date_Driven_From_month").val() > '' && $("#Date_Driven_From_year").val() > '') {
			var date_driven_from = $("#Date_Driven_From_year").val() + '-' + $("#Date_Driven_From_month").val() + '-' + $("#Date_Driven_From_day").val();
		}
		else {
			$("#Date_Driven_From_month").focus();
			alert("Date Driven From is required");
			return;
		}

		if($("#Date_Driven_To_day").val() > '' && $("#Date_Driven_To_month").val() > '' && $("#Date_Driven_To_year").val() > '') {
			var date_driven_to = $("#Date_Driven_To_year").val() + '-' + $("#Date_Driven_To_month").val() + '-' + $("#Date_Driven_To_day").val();
		}
		else {
			$("#Date_Driven_To_month").focus();
			alert("Date Driven To is required");
			return;
		}

		if(!isValidDiff(date_driven_from, date_driven_to)) {
			$('#Date_Driven_From_month').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		var data = {
			personid: personid,
			recid: recid,
			vehicle_type: vehicle_type,
			vehicle_type_other: vehicle_type_other,
			approx_miles: approx_miles,
			date_driven_from: date_driven_from,
			date_driven_to: date_driven_to
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
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

	function deletedrvexp(recid) {
		if(confirm('Are you sure you want to delete this driving experience record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_drv_exp.php",
				data: { personid: personid, recid: recid },
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

	$("#close_drv_exp").click(function() {
		$("#DRV_EXP_dialog").dialog("close");
	});

 	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var RecID = $("#RecID").val();

		if($("#haveyoudriven").val() == '') {
				$("#haveyoudriven").focus();
				alert('Please select Yes/No');
				return false;
		}

		if($("#haveyoudriven").val() == 'Yes') {
			if(RecID > 0) {
				return true;
			}
			else {
				$("#Vehicle_Type").focus();
				alert('You have not entered any driving experiences');
				return false;
			}
		}
		else {
			return true;
		}
	}
</script>
