<?php
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
								<span class="sub-heading">Driver License Information</span><br>
								List all your driver licenses.<br />&nbsp;
							</div>';

$currentDMV = 'N';
$maxRecID = 0;
$maxRecID = $dbo->query("Select max(RecID) from App_DMV where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxRecID > 0) {
	$selectstmt = "select RecID, Driver_License, Date_Expires, Issue_State, Issue_StateOther, Current_DMV from App_DMV where PersonID = :PersonID;";
	$dmv = $dbo->prepare($selectstmt);
	$dmv->bindValue(':PersonID', $PersonID);
	$dmv->execute();

	while($DMV = $dmv->fetch(PDO::FETCH_BOTH)) {
		$dateExpires = date("m/d/Y", strtotime($DMV[2]));

		echo '	<div class="cell small-4">
							' . htmlspecialchars($DMV[1]) . '
						</div>
						<div class="cell small-4">
							' . htmlspecialchars($dateExpires) . '
						</div>';

		if($DMV[4] > '') {
			echo '<div class="cell small-1">
							' . htmlspecialchars($DMV[4]) . '
						</div>';
		}
		else {
			echo '<div class="cell small-1">
							' . htmlspecialchars($DMV[3]) . '
						</div>';
		}

		echo '	<div class="cell small-3 right">
							<span onclick="updatedmv(' . $DMV[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit License" title="Edit License"/></span>&nbsp;&nbsp;&nbsp;
							<span onclick="dltdmv(' . $DMV[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete License" title="Delete License"/></span>
						</div>
						<div class="cell small-12">
							<hr>
						</div>';
	}
}
else {
	$maxRecID = 0;
}

echo '				<div class="cell small-12">
								<span class="add-dmv add-button"><img class="icon" src="images/plus.png" alt="Add License" title="Add License"/> Add License</span>
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

						<div class="grid-x margins person-form" name="DMV_dialog" id="DMV_dialog" title="Dialog Title">
						  <input type="hidden" name="recid" id="recid">

							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>
							<div class="cell medium-6 small-12">
								Drive License # <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" maxlength="40" name="dl" id="dl">
							</div>
							<div class="cell medium-6 small-12">
								Expires <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select id="dle_month" name="dle_month" style="width: 30%">
									' . $months_list . '
								</select>
								/
								<select id="dle_day" name="dle_day" style="width: 30%">
									' . $days_list . '
								</select>
								/
								<select id="dle_year" name="dle_year" style="width: 30%">
									' . $years_list . '
								</select>
							</div>
							<div class="cell medium-6 small-12">
								State/Country Issued <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="dlstate" id="dlstate">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</div>
							<div class="cell small-12">
								OR If license issued out of the US, please select the Country
							</div>
							<div class="cell medium-6 small-12">
								Country
							</div>
							<div class="cell medium-6 small-12">
								<select name="dlcountry" id="dlcountry">
									<option value="">Select a Country</option>
									' . $country_options . '
								</select>
							</div>
							<div class="cell small-12">
								<input class="float-center" type="button" id="save_dmv" value="Save License">
							</div>

							<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
					  	<input type="hidden" name="Current" id="Current" value="' . $currentDMV . '">
					  	<input type="hidden" name="RecID" id="RecID" value="' . $maxRecID . '">
						</div>
					</div>
				</div>
			</form>';
?>

<script>
	$("#DMV_dialog").dialog({ autoOpen: false });

<?php
	if($maxRecID == 0) {
		echo 'addDMV();';
	}
?>

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function addDMV() {
		$("#recid").val('');
		$("#dl").val('');
		$("#dle_day").val('');
		$("#dle_month").val('');
		$("#dle_year").val('');
		$("#dlstate").val('');
		$("#dlcountry").val('');

		$("#DMV_dialog").dialog("option", "title", "Add License");
		$("#DMV_dialog").dialog("option", "modal", true);
		$("#DMV_dialog").dialog("option", "width", "100%");
		$("#DMV_dialog").dialog("open");
	}

	$(".add-dmv").click(function() {
		addDMV();
	});

	$("#save_dmv").click(function() {
		var personid = $("#PersonID").val();
		var recid = $("#recid").val();
		var saveLocation = "../App_Ajax_New/ajax_add_dmv.php";

		if(recid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_dmv.php";
		}

		if($("#dl").val() > '') {
			var dl = $("#dl").val();
		}
		else {
			$("#newdl").focus();
			alert("Driver's License is required");
			return false;
		}

		if($("#dle_day").val() > '' && $("#dle_month").val() > '' && $("#dle_year").val() > '') {
			var dle = $("#dle_year").val() + '-' + $("#dle_month").val() + '-' + $("#dle_day").val();
		}
		else {
			$("#dle_day").focus();
			alert("Expiration Date is required");
			return false;
		}

		if($("#dlstate").val() == '' && $("#dlcountry").val() == '' ) {
			$("#newdlstate").focus();
			alert("State or Country of Issue is required");
			return false;
		}
		else {
			var dlstate = $("#dlstate").val();
			var dlcountry = $("#dlcountry").val();
		}

		var data = {
			personid: personid,
			recid: recid,
			DL: dl,
			DLE: dle,
			DLstate: dlstate,
			DLstateother: dlcountry
		};

		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > 30) {
					alert(obj2);
					return false;
				}
				else {
					var RecID = obj2;

					$("#dl").val('');
					$("#dle_day").val('');
					$("#dle_month").val('');
					$("#dle_year").val('');
					$("#dlstate").val('');
					$("#dlcointry").val('');

					location.reload(true);
					return;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

	function updatedmv(recid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_dmv.php",
			data: { personid: personid, recid: recid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					var dateExpires = '';

					de = obj2.Date_Expires.split("-");
					var dateExpiresDay = de[2];
					var dateExpiresMonth = de[1];
					var dateExpiresYear = de[0];

					$("#recid").val(obj2.RecID);
					$("#dl").val(obj2.Driver_License);
					$("#dle_day").val(dateExpiresDay);
					$("#dle_month").val(dateExpiresMonth);
					$("#dle_year").val(dateExpiresYear);
					$("#dlstate").val(obj2.Issue_State);
					$("#dlcountry").val(obj2.Issue_StateOther);

					$("#DMV_dialog").dialog("option", "title", "Edit DMV");
					$("#DMV_dialog").dialog("option", "modal", true);
					$("#DMV_dialog").dialog("option", "width", "100%");
					$("#DMV_dialog").dialog("open");
				}
				else {
					alert('No DMV Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function dltdmv(RecID) {
		if(confirm('Are you sure you want to delete this DMV record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_dmv.php",
				data: { personid: personid, RecID: RecID },
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

	$("#close_dmv").click(function() {
		$("#DMV_dialog").dialog("close");
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var RecID = $("#RecID").val();

		if(RecID == 0) {
			document.ALCATEL.newdl.focus();
			alert('You have not entered at least one Driver License');
			return false;
		}
		else {
			return true;
		}
	}
</script>
