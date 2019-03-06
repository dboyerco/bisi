<?php
$Accident = 0;

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
								<span class="sub-heading">Accident Information</span><br>
								&nbsp;
							</div>';
					echo '	<div class="cell medium-6 small-12">
								<b>Have you been in an accident within the last 3 years?</b>
							</div>
							<div class="cell medium-6 small-12">
							    <select name="accident" id="accident" onchange="turnonquestions()">
        							<option value="">Select Option (Required)</option>
        							<option value="No">No</OPTION>
        							<option value="Yes">Yes</option>
      							</select>
							</div>';

$maxRecID = $dbo->query("Select max(RecID) from App_Accident_Info where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxRecID == '') {
	$maxRecID = 0;
}

if($maxRecID > 0) {
	$selectaccident="select * from App_Accident_Info where PersonID = :PersonID;";

	$accident_result = $dbo->prepare($selectaccident);
	$accident_result->bindValue(':PersonID', $PersonID);
	$accident_result->execute();

	while($row = $accident_result->fetch(PDO::FETCH_BOTH)) {
		if($row["Accident_Date"] == '1900-01-01') {
			$accidentdate = '';
		}
		else {
			$accidentdate = date("m/d/Y", strtotime($row["Accident_Date"]));
		}

		echo '		<div class="cell small-6 sub-heading">
								&nbsp;' .	htmlspecialchars($accidentdate) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</div>
							<div class="cell small-6 right">
								<span onclick="updateaccident(' . $row["RecID"] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit License" title="Edit License"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deleteaccident(' . $row["RecID"] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete License" title="Delete License"/></span>
							</div>
							<div class="cell small-4 medium-3">
								<b>Fatalities:</b> ' . htmlspecialchars($row["Fatalities"]) . '
							</div>
							<div class="cell small-4 medium-3">
								<b>Injuries:</b> ' . htmlspecialchars($row["Injuries"]) . '
							</div>
							<div class="cell small-4 medium-3">
								<b>HazMat Spill:</b> ' . htmlspecialchars($row["HazMat"]) . '
							</div>
							<div class="cell medium-3"></div>

							<div class="cell small-9 medium-9">
								' . htmlspecialchars($row["Accident_Info"]) . '
							</div>
							<div class="cell small-3 medium-3"></div>

							<div class="cell small-12">
								<hr>
							</div>';
	}
}

echo '				<div class="cell small-12">
								<span class="add-accident-info add-button"><img class="icon" src="images/plus.png" alt="Add Accident Information" title="Add Accident Information" /> Add Accident Information</span>
							</div>
							<div class="cell small-12">
								<hr>
							</div>
							<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>';
#						if($maxRecID > 0 || $Accident == 'No') {
							echo '<div class="cell small-6">
									<input class="button float-center" type="submit" id="next" value="Next">
								</div>';
#						}
echo				'</div>';
echo				'
						<div class="grid-x margins person-form" name="Accident_Info_dialog" id="Accident_Info_dialog" title="Dialog Title">
							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell medium-6 small-12">
								Accident Date <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select id="accident_date_month" name="accident_date_month" style="width: 30%">
									' . $months_list . '
								</select>
								/
								<select id="accident_date_day" name="accident_date_day" style="width: 30%">
									' . $days_list . '
								</select>
								/
								<select id="accident_date_year" name="accident_date_year" style="width: 30%">
									' . $years_list . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								Nature of Accident <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
			      				<textarea name="accident_info" id="accident_info" rows="5" cols="45" maxlength="256"></textarea>
							</div>
							<div class="cell medium-6 small-12">
								Fatalities?
							</div>
							<div class="cell medium-6 small-8">
								<input name="fatalities" id="fatalities" maxlength="25">
							</div>

							<div class="cell medium-6 small-12">
								Injuries? <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input name="injuries" id="injuries" maxlength="25">
							</div>
							<div class="cell medium-6 small-12">
								Hazard Material Spill? <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
			      				<input name="hazmat" id="hazmat" maxlength="25">
							</div>

							<div class="cell small-12 padding-bottom">
								<br /><br /><input id="save_accident_info" class="float-center" type="button" value="Save Accident Infomation">
							</div>
						</div>

						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
						<input type="hidden" name=RecID" id="RecID" value="' . $maxRecID . '">
						<input type="hidden" name=recid" id="recid" value="">
						<input type="hidden" name=Accident" id="Accident" value="' .$Accident . '">
					</div>
				</div>
			</form>';
?>
<script language="JavaScript" type="text/javascript">
 	$("#Accident_Info_dialog").dialog({ autoOpen: false });

//<?php
//	if($maxRecID == 0) {
//		echo 'addAccidentInfo();';
//	}
//?>

	$(".add-accident-info").click(function() {
		addAccidentInfo();
	});

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function addAccidentInfo() {
		$("#recid").val('');
		$("#accident_date_month").val('');
		$("#accident_date_day").val('');
		$("#accident_date_year").val('');
		$("#accident_info").val('');
		$("#fatalities").val('');
		$("#injuries").val('');
		$("#hazmat").val('');

		$("#Accident_Info_dialog").dialog("option", "title", "Add Accident Information");
		$("#Accident_Info_dialog").dialog("option", "modal", true);
		$("#Accident_Info_dialog").dialog("option", "width", "100%");
		$("#Accident_Info_dialog").dialog("open");
	}

	$().ready(function() {
		turnonquestions();
	});
	function turnonquestions() {
//		alert('turnonquestions');
		if ($("#RecID").val() > 0) {
			$("#accident").val('Yes');
		}
		if ($("#accident").val() == 'Yes') {
			if ($("#RecID").val() == 0) {
				addAccidentInfo();
			}
		} else {
			if ($("#accident").val() == 'No') {
				$("#next").show();
			} else {
				$("#next").hide();
			}
//			eldiv = document.getElementById("overlay");
//			eldiv.style.visibility = "hidden";
//			eldiv2 = document.getElementById("overlay2");
//			eldiv2.style.visibility = "visible";
		};
		return true;
	}

 	function updateaccident(recid) {
		var personid = $("#PersonID").val();
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_accident_info.php",
			data: { personid: personid, recid: recid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];
				console.log(obj2);
				if(obj2) {
					$("#recid").val(obj2.RecID);
					var fd = obj2.Accident_Date.split("-");
					var Accident_Date_Day = fd[2];
					var Accident_Date_Month = fd[1];
					var Accident_Date_Year = fd[0];

					$("#accident_date_month").val(Accident_Date_Month);
					$("#accident_date_day").val(Accident_Date_Day);
					$("#accident_date_year").val(Accident_Date_Year);
					$("#accident_info").val(obj2.Accident_Info);
					$("#fatalities").val(obj2.Fatalities);
					$("#injuries").val(obj2.Injuries);
					$("#hazmat").val(obj2.HazMat);

					$("#Accident_Info_dialog").dialog("option", "title", "Edit Accident Information");
					$("#Accident_Info_dialog").dialog("option", "modal", true);
					$("#Accident_Info_dialog").dialog("option", "width", "100%");
					$("#Accident_Info_dialog").dialog("open");
				}
				else {
					alert('No Accident Information Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_accident_info").click(function() {
		var personid = $("#PersonID").val();
		var recid = $("#recid").val();
		var saveLocation = "../App_Ajax_New/ajax_add_accident_info.php";

		if(recid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_accident_info.php";
		}

		if($("#accident_date_day").val() > '' && $("#accident_date_month").val() > '' && $("#accident_date_year").val() > '') {
			var accident_date = $("#accident_date_year").val() + '-' + $("#accident_date_month").val() + '-' + $("#accident_date_day").val();
		}
		else {
			$("#accident_date_day").focus();
			alert("Accident Date is required");
			return;
		}

		if($("#accident_info").val()) {
			var accident_info = $("#accident_info").val();
		} else {
			$("#accident_info").focus();
			alert("Accident Info is required");
			return;
		}

		if($("#fatalities").val() > '') {
			var fatalities = $("#fatalities").val();
		}
		else {
			$("#fatalities").focus();
			alert("Fatalities is required");
			return;
		}
		if($("#injuries").val() > '') {
			var injuries = $("#injuries").val();
		}
		else {
			$("#injuries").focus();
			alert("Injuries is required");
			return;
		}
		if($("#hazmat").val() > '') {
			var hazmat = $("#hazmat").val();
		}
		else {
			$("#hazmat").focus();
			alert("Hazmat Spill is required");
			return;
		}

		var data = {
			personid: personid,
			recid: recid,
			accident_date: accident_date,
			accident_info: accident_info,
			fatalities: fatalities,
			injuries: injuries,
			hazmat: hazmat
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
					$("#Accident_Info_dialog").dialog("close");
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

	function deleteaccident(RecID) {
	alert('RecID: '+RecID);
		if(confirm('Are you sure you want to delete this Accident Information?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_accident_info.php",
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
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_accident_info").click(function() {
		$("#Accident_Info_dialog").dialog("close");
	});
 </script>
