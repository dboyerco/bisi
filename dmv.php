<?php
$FormAction = "index.php?pg=address&PersonID=" . $PersonID . "&CD=" . $CD;

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

if(!$testLayout) {
	$maxRecID = $dbo->query("Select max(RecID) from App_DMV where PersonID = ".$PersonID.";")->fetchColumn();

	if($maxRecID > 0) {
		$selectstmt = "select RecID, Driver_License, Date_Expires, Issue_State, Issue_StateOther, Current_DMV from App_DMV where PersonID = :PersonID;";
		$dmv = $dbo->prepare($selectstmt);
		$dmv->bindValue(':PersonID', $PersonID);
		$dmv->execute();

		while($DMV = $dmv->fetch(PDO::FETCH_BOTH)) {
			$dateExpires = date("m/d/Y", strtotime($DMV[2]));

			echo '<div class="cell small-4">
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

			echo '<div class="cell small-3 right">
							<span onclick="updatedmv(' . $DMV[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit DMV" title="Edit DMV"/></span>&nbsp;&nbsp;&nbsp;
							<span onclick="dltdmv(' . $DMV[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete DMV" title="Delete DMV"/></span>
						</div>
						<div class="cell small-12">
							<hr>
						</div>';
		}
	}
}
else {
	echo '<div class="cell small-4">
					92-123456
				</div>
				<div class="cell small-4">
					01/30/2025
				</div>
				<div class="cell small-1">
					CO
				</div>
				<div class="cell small-3 right">
					<span onclick="updatedmv(1)"><img class="icon" src="images/pen-edit-icon.png" alt="Edit DMV" title="Edit DMV"/></span>&nbsp;&nbsp;&nbsp;
					<span onclick="dltdmv(1)"><img class="icon" src="images/deletetrashcan.png" alt="Delete DMV" title="Delete DMV"/></span>
				</div>
				<div class="cell small-12">
					<hr>
				</div>';
}

echo '<div class="cell small-12">
				<h3>Add Driver License</h3>
			</div>
			<div class="cell small-12 required">
				* Required Fields To Continue
			</div>
			<div class="cell medium-6 small-12">
				Drive License # <span class="required">*</span>
			</div>
			<div class="cell medium-6 small-12">
				<input type="text" maxlength="40" name="newdl" id="newdl">
			</div>
			<div class="cell medium-6 small-12">
				Expires <span class="required">*</span>
			</div>
			<div class="cell medium-6 small-12">
				<input type="text" name="newdle" id="newdle" maxlength="10" placeholder="mm/dd/yyyy" value="" onKeyUp="return frmtdate(this,\'up\')">
			</div>
			<div class="cell medium-6 small-12">
				State/Country Issued <span class="required">*</span>
			</div>
			<div class="cell medium-6 small-12">
				<select name="newdlstate" id="newdlstate">
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
				<select name="newdlstateother" id="newdlstateother">
					<option value="">Select a Country</option>';

if(!$testLayout) {
	$sql = "select Alpha2Code, FullName from isocountrycodes Order By FullName;";
	$country_result = $dbo->prepare($sql);
	$country_result->execute();

	while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
		echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
	}
}
else {
	echo '	<option value="usa">USA</option>';
}

echo '	</select>
			</div>
			<div class="cell small-12">
				<input class="float-center" type="button" id="add_new_dmv" value="Save License">
			</div>

			<div class="cell small-12">
				<input class="float-center" type="submit" value="Next">
			</div>

			<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
	  	<input type="hidden" name="Current" id="Current" value="' . $currentDMV . '">
	  	<input type="hidden" name="RecID" id="RecID" value="' . $maxRecID . '">

			<div name="DMV_dialog" id="DMV_dialog" title="Dialog Title">
				<div>
					<input type="hidden" name="dlgrecid" id="dlgrecid">
					<table width="100%" align="left" border="3" bgcolor="#eeeeee">
						<tr>
							<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Driver\'s License #</font></td>
							<td width="351">
								<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
									<input type="text" name="dlgDL" id="dlgDL" size="20" maxlength="40">
								</font>
							</td>
						</tr>
						<tr>
							<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Date Expires</font></td>
							<td nowrap>
								<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
									<input type="text" name="dlgDLE" id="dlgDLE" size="10" maxlength="10" placeholder="mm/dd/yyyy"
									onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
								</font>
							</td>
						</tr>
						<tr>
							<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">State of Issue</font></td>
							<td>
								<select name="dlgDLstate" id="dlgDLstate">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</td>
						</tr>
						<tr>
							<td><font size="2">&nbsp;</font></td>
							<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> OR If license issued out of the US, please select the Country</font></td>
						</tr>
						<tr>
							<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Country of Issue</font></td>
							<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
								<select name="dlgDLstateother" id="dlgDLstateother">
									<option value="">Select a Country</option>';

if(!$testLayout) {
	$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
	$country_result = $dbo->prepare($sql);
	$country_result->execute();

	while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
		echo '				<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
	}
}
else {
	echo '					<option value="usa">USA</option>';
}

echo '					</select></font>
							</td>
						</tr>
					</table>
					<table width="100%" bgcolor="#eeeeee">
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td align="center">
								<INPUT TYPE="button" id="save_dmv" VALUE="Save DMV">
								<INPUT TYPE="button" id="close_dmv" VALUE="Close">
							</td>
						</tr>
					</table>
				</div>
				</div>
			</form>';
?>

<script>
	$("#DMV_dialog").dialog({ autoOpen: false });

	$("#add_new_dmv").click(function() {
		var personid = document.getElementById("PersonID").value;

		if(document.getElementById("newdl").value > '') {
			var DL = document.getElementById("newdl").value;
		}
		else {
			document.ALCATEL.newdl.focus();
			alert("Driver's License is required");
			return false;
		}

		if(document.getElementById("newdle").value > '') {
			if(!isValidEDate('newdle')) {
				$('#newdle').focus();
				alert("Invalid Expiration Date");
				return false;
			}
			else {
				var DLE = document.getElementById("newdle").value;
			}
		}
		else {
			document.ALCATEL.newdle.focus();
			alert("Expiration Date is required");
			return false;
		}

		if(document.getElementById("newdlstate").value == '' && document.getElementById("newdlstateother").value == '' ) {
			document.ALCATEL.newdlstate.focus();
			alert("State or Country of Issue is required");
			return false;
		}
		else {
			var DLstate = document.getElementById("newdlstate").value;
			var DLstateother = document.getElementById("newdlstateother").value;
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_dmv.php",
			data: {personid: personid, DL: DL, DLE: DLE, DLstate: DLstate, DLstateother: DLstateother},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > 30) {
					alert(obj2);
					return false;
				} else {
					var RecID = obj2;
					document.getElementById("newdl").value = '';
					document.getElementById("newdle").value = '';
					document.getElementById("newdlstate").value = '';
					document.getElementById("newdlstateother").value = '';
					location.reload(true);
					return;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

	function updatedmv(recid) {
		alert("In updatedmv");
		var personid = document.getElementById("PersonID").value;

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_dmv.php",
			data: {personid: personid, recid: recid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) {
					for (var i = 0; i < obj2.length; i++) {
						var RecID = obj2[i].RecID;
						var Driver_License = obj2[i].Driver_License;
						var de = obj2[i].Date_Expires;
						var Issue_State = obj2[i].Issue_State;
						var Issue_StateOther = obj2[i].Issue_StateOther;
						var Date_Expires = de.substr(5,2)+"/"+de.substr(8)+"/"+de.substr(0,4);
			    	}
					document.getElementById("dlgrecid").value = RecID;
					document.getElementById("dlgDL").value = Driver_License;
					document.getElementById("dlgDLE").value = Date_Expires;
					document.getElementById("dlgDLstate").value = Issue_State;
					document.getElementById("dlgDLstateother").value = Issue_StateOther;

					$( "#DMV_dialog" ).dialog( "option", "title", "Edit DMV");
					$( "#DMV_dialog" ).dialog( "option", "modal", true );
					$( "#DMV_dialog" ).dialog( "option", "width", 700 );
					$( "#DMV_dialog" ).dialog( "open" );
				} else {
					alert('No DMV Data Found');
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	}

	$("#save_dmv").click(function() {
		var personid = document.getElementById("PersonID").value;
		var recid = document.getElementById("dlgrecid").value;

		if (document.getElementById("dlgDL").value > '') {
			var DL = document.getElementById("dlgDL").value;
		} else {
			document.ALCATEL.dlgDL.focus();
			alert("Driver's License is required");
			return false;
		}
		if (document.getElementById("dlgDLE").value > '') {
			if (!isValidEDate('dlgDLE')) {
				$('#dlgDLE').focus();
				alert("Invalid Expiration Date");
				return false;
			} else {
				var DLE = document.getElementById("dlgDLE").value;
			}
		} else {
			document.ALCATEL.dlgDLE.focus();
			alert("Expiration Date is required");
			return false;
		}
		if (document.getElementById("dlgDLstate").value == '' && document.getElementById("dlgDLstateother").value == '' ) {
			document.ALCATEL.dlgDLstate.focus();
			alert("State or Country of Issue is required");
			return false;
		} else {
			var DLstate = document.getElementById("dlgDLstate").value;
			var DLstateother = document.getElementById("dlgDLstateother").value;
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_dmv.php",
			data: {personid: personid, recid: recid, DL: DL, DLE: DLE, DLstate: DLstate, DLstateother: DLstateother},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {
					$( "#DMV_dialog" ).dialog( "close" );
					location.reload(true);
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

	function dltdmv(RecID) {
		alert("In dltdmv");

		if(confirm('Are you sure you want to delete this DMV record?')) {
			var personid = document.getElementById("PersonID").value;

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_dmv.php",
				data: {personid: personid, RecID: RecID},
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
