<?php
require_once('../pdotriton.php');
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	

$FormAction = "address.php?PersonID=".$PersonID;  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<style type="text/css">
			.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
		</style>
	</head>

<body bgcolor="#E4E8E8">

<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  	<tbody><tr>
    <td></td>
    <td class="submenu" height="27" width="763">&nbsp;</td>
  	</tr>
	</tbody></table>
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tbody>
			<tr>
    			<td>
	 				<h3 align="left">
						<font color="#00248E"><?php echo $compname; ?> Web Application Portal</font> 
						<img src="files/horizontal-line.gif" height="3" width="700">
					</h3>
					<br>
				</td>
			</tr>
		</tbody>
	</table>				
<?	
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
echo '<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Driver License Information</strong> </font></p>
			</td>
		</tr>	
		<tr>
			<td colspan="2"> 
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">List all your driver licenses.</font></p>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp</font></p>
				<p>&nbsp;</p>
			</td>
		</tr>
	</table>';
	$currentDMV = 'N';
	$maxRecID = 0;
	$maxRecID = $dbo->query("Select max(RecID) from App_DMV where PersonID = ".$PersonID.";")->fetchColumn();
	if ($maxRecID > 0) { 
		$selectstmt="select RecID, Driver_License, Date_Expires, Issue_State, Issue_StateOther, Current_DMV, Vehicle_Make, Vehicle_Model, Vehicle_Year, Vehicle_License_Plate from App_DMV where PersonID = :PersonID;";

		$dmv = $dbo->prepare($selectstmt);
		$dmv->bindValue(':PersonID', $PersonID);
		$dmv->execute();
		while($DMV = $dmv->fetch(PDO::FETCH_BOTH)) {	
			$dateExpires = date("m/d/Y", strtotime($DMV[2]));
			echo '<table width="763" bgcolor="#E4E8E8"><tr> 
				<td align="left" width="15%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($DMV[1]).'</font></td>
				<td align="left" width="15%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.htmlspecialchars($dateExpires).'</font></td>';
			if ($DMV[4] > '') {	
				echo '<td align="left" width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars($DMV[4]).'</font></td>';
			} else {
				echo '<td align="left" width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars($DMV[3]).'</font></td>';
			}
			echo '<td align="left" width="20%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($DMV[6]).'</font></td>';
			echo '<td align="left" width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($DMV[7]).'</font></td>';
			echo '<td align="left" width="5%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($DMV[8]).'</font></td>';
			echo '<td align="left" width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($DMV[9]).'</font></td>';
			echo '<td align="left" width="25%">
					<a http="#" onclick="updatedmv('.$DMV[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit DMV" title="Edit DMV"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a http="#" onclick="dltdmv('.$DMV[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete DMV" title="Delete DMV"/></a>
				</td></tr>';
		}
	}	
	echo '</table>';
		echo '<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>';
echo '<fieldset><legend><strong>Add Driver License</strong></legend>
	<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>&nbsp;</td>
		</tr>';
#	if ($currentDMV == 'N') {	
#		echo '<tr>
#			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Current Driver License<font color="#FF0000">*</font></font></td>
#			<td>
#				<select name="newcurrent" id="newcurrent" onchange="setdate()">
#					<option VALUE="N">No</OPTION>
#					<option value="Y">Yes</option>
#				</select>
#			</td>
#		</tr>';
#	}		
	echo '<tr>
	<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Drive License #<font color="#FF0000">*</font></font></td>
		<td width="351"><input type="text" size="20" maxlength="40" name="newdl" id="newdl"></td>
		</tr>
		<tr>
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Expires<font color="#FF0000">*</font></font></td>
			<td width="351"><input name="newdle" id="newdle" size="10" maxlength="10" placeholder="mm/dd/yyyy" value="" onKeyUp="return frmtdate(this,\'up\')"></td>
		</tr>
		<tr>	
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">State/Country Issued<font color="#FF0000">*</font></font></td>
			<td width="351"><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newdlstate" id="newdlstate">
					<option value="">Select a State</option>
					<option value="">-Other-</option>';
					$sql = "Select Name, Abbrev from State order by Name";
					$state_result = $dbo->prepare($sql);
					$state_result->execute();
					while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
						echo "<option value=".$rows[1].">".$rows[0]."</option>";
					}		
		echo '</select></span></td>
			<td width="25%"></tr>';
		echo '<tr> 
			<td><font size="1">&nbsp;</font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> OR If license issued out of the US, please select the Country</font></td>
			</tr>';
		echo '<tr>
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Country</font></td>
			<td width="351"><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newdlstateother" id="newdlstateother">
				<option value="">Select a Country</option>';	
				$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
				$country_result = $dbo->prepare($sql);
				$country_result->execute();
				while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {		
					echo "<option value=".$rows[0].">".$rows[1]."</option>";
				}		
		echo '</select></span>
		</td>
		</tr>
		<tr>
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Vehicle Make<font color="#FF0000">*</font></font></td>
			<td width="351"><input type="text" size="20" maxlength="40" name="newvmake" id="newvmake"></td>
		</tr>		
		<tr>
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Vehicle Model<font color="#FF0000">*</font></font></td>
			<td width="351"><input type="text" size="20" maxlength="40" name="newvmodel" id="newvmodel"></td>
		</tr>		
		<tr>
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Vehicle Year<font color="#FF0000">*</font></font></td>
			<td width="351"><input type="text" size="20" maxlength="40" name="newvyear" id="newvyear"></td>
		</tr>		
		<tr>
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Vehicle License Plate<font color="#FF0000">*</font></font></td>
			<td width="351"><input type="text" size="20" maxlength="40" name="newvplate" id="newvplate"></td>
		</tr>		
		<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_dmv" VALUE="Save License" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	</table>
	</fieldset>';
	
echo '<table width="763">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="center">
			 <INPUT TYPE="submit" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>';	
	
echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" name=\"Current\" id=\"Current\" VALUE=\"$currentDMV\">
	  <INPUT TYPE=\"hidden\" NAME=\"RecID\" ID=\"RecID\" VALUE=\"$maxRecID\">";

?>
	<div name="DMV_dialog" id="DMV_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgrecid" id="dlgrecid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Driver's License #</font></td>
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
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>	
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">State of Issue</font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
						<select name="dlgDLstate" id="dlgDLstate">
							<option value="">Select a State</option>
							<option value="">-Other-</option>
						<?php
							$sql = "Select Name, Abbrev from State order by Name";
							$state_result = $dbo->prepare($sql);
							$state_result->execute();
							while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
								echo "<option value=".$rows[1].">".$rows[0]."</option>";
							}
						?>			
						</select>
						</font>
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
							<option value="">Select a Country</option>
						<?php			
							$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
							$country_result = $dbo->prepare($sql);
							$country_result->execute();
							while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {		
								echo "<option value=".$rows[0].">".$rows[1]."</option>";
							}
						?>			
						</select></font>
					</td>				
				</tr>
				<tr>
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Vehicle Make</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input type="text" size="20" maxlength="40" name="dlgvmake" id="dlgvmake">
						</font>	
					</td>
				</tr>		
				<tr>
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Vehicle Model</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input type="text" size="20" maxlength="40" name="dlgvmodel" id="dlgvmodel">
						</font>	
					</td>
				</tr>		
				<tr>
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Vehicle Year</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif">	
							<input type="text" size="20" maxlength="40" name="dlgvyear" id="dlgvyear">
						</font>		
					</td>
				</tr>		
				<tr>
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Vehicle License Plate<</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
							<input type="text" size="20" maxlength="40" name="dlgvplate" id="dlgvplate">
						</font>					
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
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#DMV_dialog" ).dialog({ autoOpen: false });

	$( "#add_new_dmv" ).click(function() {	
		var personid = document.getElementById("PersonID").value;		
		if (document.getElementById("newdl").value > '') {
			var DL = document.getElementById("newdl").value;
		} else {		
			document.ALCATEL.newdl.focus();
			alert("Driver's License is required");
			return false;
		}	
		if (document.getElementById("newdle").value > '') {
			if (!isValidEDate('newdle')) {
				$('#newdle').focus();
				alert("Invalid Expiration Date");
				return false;
			} else {					
				var DLE = document.getElementById("newdle").value;
			}	
		} else {		
			document.ALCATEL.newdle.focus();
			alert("Expiration Date is required");
			return false;
		}	
		if (document.getElementById("newdlstate").value == '' && document.getElementById("newdlstateother").value == '' ) {
			document.ALCATEL.newdlstate.focus();
			alert("State or Country of Issue is required");
			return false;
		} else {		
			var DLstate = document.getElementById("newdlstate").value;
			var DLstateother = document.getElementById("newdlstateother").value;
		}	
		if (document.getElementById("newvmake").value > '') {
			var vmake = document.getElementById("newvmake").value;
		} else {		
			document.ALCATEL.newvmake.focus();
			alert("Vehicle Make is required");
			return false;
		}	
		if (document.getElementById("newvmodel").value > '') {
			var vmodel = document.getElementById("newvmodel").value;
		} else {		
			document.ALCATEL.newvmodel.focus();
			alert("Vehicle Model is required");
			return false;
		}	
		if (document.getElementById("newvyear").value > '') {
			var vyear = document.getElementById("newvyear").value;
		} else {		
			document.ALCATEL.newvyear.focus();
			alert("Vehicle Year is required");
			return false;
		}	
		if (document.getElementById("newvplate").value > '') {
			var vplate = document.getElementById("newvplate").value;
		} else {		
			document.ALCATEL.newvplate.focus();
			alert("Vehicle License Plate is required");
			return false;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_dmv.php", 
			data: {personid: personid, DL: DL, DLE: DLE, DLstate: DLstate, DLstateother: DLstateother, vmake: vmake, vmodel: vmodel, vyear: vyear, vplate: vplate},
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
//		alert("In updatedmv");
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
						var VMake = obj2[i].Vehicle_Make;
						var VModel = obj2[i].Vehicle_Model;
						var VYear = obj2[i].Vehicle_Year;
						var VPlate = obj2[i].Vehicle_License_Plate;
			    	}
					document.getElementById("dlgrecid").value = RecID;
					document.getElementById("dlgDL").value = Driver_License;
					document.getElementById("dlgDLE").value = Date_Expires;
					document.getElementById("dlgDLstate").value = Issue_State;
					document.getElementById("dlgDLstateother").value = Issue_StateOther;
					document.getElementById("dlgvmake").value = VMake;
					document.getElementById("dlgvmodel").value = VModel;
					document.getElementById("dlgvyear").value = VYear;
					document.getElementById("dlgvplate").value = VPlate;

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
		if (document.getElementById("dlgvmake").value > '') {
			var vmake = document.getElementById("dlgvmake").value;
		} else {		
			document.ALCATEL.dlgvmake.focus();
			alert("Vehicle Make is required");
			return false;
		}	
		if (document.getElementById("dlgvmodel").value > '') {
			var vmodel = document.getElementById("dlgvmodel").value;
		} else {		
			document.ALCATEL.dlgvmodel.focus();
			alert("Vehicle Model is required");
			return false;
		}	
		if (document.getElementById("dlgvyear").value > '') {
			var vyear = document.getElementById("dlgvyear").value;
		} else {		
			document.ALCATEL.dlgvyear.focus();
			alert("Vehicle Year is required");
			return false;
		}	
		if (document.getElementById("dlgvplate").value > '') {
			var vplate = document.getElementById("dlgvplate").value;
		} else {		
			document.ALCATEL.dlgvplate.focus();
			alert("Vehicle License Plate is required");
			return false;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_dmv.php", 
			data: {personid: personid, recid: recid, DL: DL, DLE: DLE, DLstate: DLstate, DLstateother: DLstateother, vmake: vmake, vmodel: vmodel, vyear: vyear, vplate: vplate},
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
//		alert("In dltdl");
		if (confirm('Are you sure you want to delete this DMV record?')) {
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
	
	$( "#close_dmv" ).click(function() {	
		$( "#DMV_dialog" ).dialog( "close" );
	});
 </script>
 <script language="JavaScript" type="text/javascript">
	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {

		var RecID = document.getElementById("RecID").value;
		if (RecID == 0) {
			document.ALCATEL.newdl.focus();
			alert('You have not entered at least one Driver License');
			return false;
		} else {
			return true;		
		}
	}	
</script>


