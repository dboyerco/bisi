<?php
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
								<span class="sub-heading">Professional License(s)</span><br>';

if($profLicense == "Y") {
	echo 'List all professional license(s) in relation to this position <span class="required">(at least one is required)</span>.';
}
else {
	echo 'List all or any professional license(s) in relation to this position.';
}

echo '					<br />&nbsp;
							</div>';

$maxProfID = $dbo->query("Select max(ProfID) from App_ProfLicenses where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxProfID == '') {
	$maxProfID = 0;
}

if($maxProfID > 0) {
	$selectstmt = "select ProfID, ProfLicenseType, ProfState, ProfStateOther, ProfLicenseNumber from App_ProfLicenses where PersonID= :PersonID;";
	$result2 = $dbo->prepare($selectstmt);
	$result2->bindValue(':PersonID', $PersonID);
	$result2->execute();

	while($row = $result2->fetch(PDO::FETCH_BOTH)) {
		echo '		<div class="cell small-9">
								<div class="grid-x margins">
									<div class="cell small-6 medium-4">
										' . htmlspecialchars($row[1]) . '
									</div>
									<div class="cell small-6 medium-1">
										' . htmlspecialchars($row[2]) . '
									</div>
									<div class="cell small-6 medium-3">
										' . htmlspecialchars($row[3]) . '
									</div>
									<div class="cell small-6 medium-4">
										' . htmlspecialchars($row[4]) . '
									</div>
								</div>
							</div>
							<div class="cell small-3 right">
								<span onclick="updateprof(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit License" title="Edit License"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deleteprof(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete License" title="Delete License"/></span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>';
	}
}

echo '				<div class="cell small-12">
								<span class="add-prof-license add-button"><img class="icon" src="images/plus.png" alt="Add Professional License" title="Add Professional License" /> Add Professional License</span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>';

if(!$profLicense || $maxProfID > 0) {
	echo '			<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>
							<div class="cell small-6">
								<input class="button float-center" type="submit" value="Next">
							</div>';
}

echo '			</div>

						<div class="grid-x margins person-form" name="ProfLicense_dialog" id="ProfLicense_dialog" title="Dialog Title">
							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell medium-6 small-12">
								License Type <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="proflictype" id="proflictype" maxlength="40" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								State <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="profstate" id="profstate">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</div>

							<div class="cell small-12">
								OR If address is out of the US, please select the Country
							</div>

							<div class="cell medium-6 small-12">
								Country
							</div>
							<div class="cell medium-6 small-12">
								<select name="profstateother" id="profstateother">
									<option value="">Select a Country</option>
									' . $country_options . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								License Number <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="proflicnum" id="proflicnum" maxlength="40" placeholder="Required">
							</div>

							<div class="cell small-12 padding-bottom">
								<input id="save_prof_license" class="float-center" type="button" value="Save Professional License">
							</div>
						</div>

						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
						<input type="hidden" name="ProfID" id="ProfID" value="' . $maxProfID . '">
					</div>
				</div>
			</form>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#ProfLicense_dialog").dialog({ autoOpen: false });

	$(".add-prof-license").click(function() {
		addProfLicense();
	});

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function addProfLicense() {
		$("#profid").val('');
		$("#proflictype").val('');
		$("#profstate").val('');
		$("#profstateother").val('');
		$("#proflicnum").val('');


		$("#ProfLicense_dialog").dialog("option", "title", "Add Address");
		$("#ProfLicense_dialog").dialog("option", "modal", true);
		$("#ProfLicense_dialog").dialog("option", "width", "100%");
		$("#ProfLicense_dialog").dialog("open");
	}

 	function updateprof(profid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_proflicense.php",
			data: { personid: personid, profid: profid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];
				console.log(obj2);
				if(obj2) {
					$("#profid").val(obj2.ProfID);
					$("#proflictype").val(obj2.ProfLicenseType);
					$("#profstate").val(obj2.ProfState);
					$("#profstateother").val(obj2.ProfStateOther);
					$("#proflicnum").val(obj2.ProfLicenseNumber);

					$("#ProfLicense_dialog").dialog("option", "title", "Edit License");
					$("#ProfLicense_dialog").dialog("option", "modal", true);
					$("#ProfLicense_dialog").dialog("option", "width", "100%");
					$("#ProfLicense_dialog").dialog("open");
				}
				else {
					alert('No License Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_prof_license").click(function() {
		var personid = $("#PersonID").val();
		var profid = $("#profid").val();
		var saveLocation = "../App_Ajax_New/ajax_add_proflicense.php";

		if(profid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_proflicense.php";
		}

		if($("#proflictype").val() > '') {
			var proflictype = $("#proflictype").val();
		}
		else {
			$("#proflictype").focus();
			alert("License Type is required");
			return;
		}

		if($("#profstate").val() == '' && $("#profstateother").val() == '' ) {
			$("#profstate").focus();
			alert("State or Country is required");
			return;
		}
		else {
			var profstate = $("#profstate").val();
			var profstateother = $("#profstateother").val();
		}

		if($("#proflicnum").val() > '') {
			var proflicnum = $("#proflicnum").val();
		}
		else {
			$("#proflicnum").focus();
			alert("License # is required");
			return;
		}

		var data = {
			personid: personid,
			profid: profid,
			proflictype: proflictype,
			profstate: profstate,
			profstateother: profstateother,
			proflicnum: proflicnum
		};

		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				console.log(obj2);
				if(obj2.length > 30) {
					alert(obj2);
				}
				else {
					$("#ProfLicense_dialog").dialog("close");
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

	function deleteprof(profid) {
		if(confirm('Are you sure you want to delete this License?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_proflicense.php",
				data: { personid: personid, profid: profid },
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

	$("#close_proflicense").click(function() {
		$("#ProfLicense_dialog").dialog("close");
	});
 </script>
