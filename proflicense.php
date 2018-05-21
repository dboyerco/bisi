<?php
require_once('../pdotriton.php');
$PersonID = $_REQUEST['PersonID'];
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$maxProfID = $dbo->query("Select max(ProfID) from App_ProfLicenses where PersonID = ".$PersonID.";")->fetchColumn();
	if ($maxProfID == '') {
		$maxProfID = 0;
	}	
	$FormAction = "military.php?PersonID=".$PersonID;
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
		<script language="JavaScript" type="text/javascript">
			$(document).ready(function() {
				var profid = document.getElementById("ProfID").value;
				if (profid > 0) { 
					document.getElementById("ProLic").value = 'Yes';
				}	
				turnonquestions();
			});	

			function turnonquestions() {
				if (document.getElementById("ProLic").value == 'Yes') {
					eldiv = document.getElementById("overlay");
					eldiv.style.visibility = "visible";
					eldiv2 = document.getElementById("overlay2");
					eldiv2.style.visibility = "hidden";
				} else {	
					if (document.getElementById("ProfID").value > 0) {
						alert("If you Don't Have Any Professional License(s)\nPlease Delete the Existing License Record(s)");
						document.getElementById("ProLic").value = 'Yes';
						return;
					} else {
						eldiv = document.getElementById("overlay");
						eldiv.style.visibility = "hidden";
						eldiv2 = document.getElementById("overlay2");
						eldiv2.style.visibility = "visible";
					}	
				};
				return true;
			}	
			
		</script>
		
	</head>

<body>
<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td></td>
				<td class="submenu" height="27" width="763"></td>
			</tr>
		</tbody>
	</table>
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
?>
	
	<table bgcolor="#E4E8E8" width="763">
		<tr> 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr> 
			<td width="425"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Do you had any active professional license(s) in relation to this position?</b></font></td>
			<td align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<select name="ProLic" id="ProLic" onchange="turnonquestions();">
					<option value="No">No</OPTION>
					<option value="Yes">Yes</option>
				</select>
				</font>
			</td>
		</tr>  
		<tr> 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<table bgcolor="#E4E8E8" width="763" border="0">
		<tr> 
			<td>
				<div name="overlay2" id="overlay2" align="center" style="visibility: hidden">
					<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
					<INPUT TYPE="submit" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
				</div>	
			</td>
		</tr>
	</table>		
<?	
echo '<div name="overlay" id="overlay" style="visibility: hidden; width:763px;">';
echo '<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Professional License(s)</strong> </font></p>
			</td>
		</tr>	
		<tr> 
		<td colspan="2"> <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">List all or any professional license(s) in relation to this position.</font></p>
		<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp</font></p>
		<p>&nbsp;</p></td>
	</tr></table>';
	if ($maxProfID > 0) { 
		$selectstmt="select ProfID, ProfLicenseType, ProfState, ProfStateOther, ProfLicenseNumber from App_ProfLicenses where PersonID= :PersonID;"; 	
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();
		while($row=$result2->fetch(PDO::FETCH_BOTH)) {
			echo '<table width="763" bgcolor="#E4E8E8">
				<tr>
					<td width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[1]).'</font></td>
					<td width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>
					<td width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[3]).'</font></td>			
					<td width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[4]).'</font></td>
					<td align="center">
						<a http="#" onclick="updateprof('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit License" title="Edit License"/></a>&nbsp;&nbsp;
						<a http="#" onclick="deleteprof('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete License" title="Delete License"/></a>
					</td>
				</tr>';
		echo '</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>';
		}
	}
	
echo '<fieldset><legend><strong>Add Professional License</strong></legend>
	<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>&nbsp;</td>
		</tr>';
	echo '<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">License Type<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newproflictype" id="newproflictype" value="" size="25" maxlength="40">
				</font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">State<font color="FF0000">*</font></font></td>
			<td> <font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<select name="newprofstate" id="newprofstate">
					<option value="">Select a State</option>
					<option value="">-Other-</option>';
					$sql = "Select Name, Abbrev from State order by Name";
					$state_result = $dbo->prepare($sql);
					$state_result->execute();
					while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
						echo "<option value=".$rows[1].">".$rows[0]."</option>";
					}		
				echo '</select>
			</td>
		</tr>';	
	echo '<tr> 
			<td width="160"><font size="1">&nbsp;</font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> OR If License Issued was out of the US, please select the Country</font></td>
		</tr>';
echo '<tr>
		<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Country</font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
			<select name="newprofstateother" id="newprofstateother">
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
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">License number<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newprofnumber" id="newprofnumber" value="" size="30"></font>
			</td>
		</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_proflicense" VALUE="Save License Info" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
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
</table>	
</div>	';
echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" NAME=\"ProfID\" ID=\"ProfID\" VALUE=\"$maxProfID\">";
#	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";

?>
		<div name="ProfLicense_dialog" id="ProfLicense_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgprofid" id="dlgprofid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">License Type<font color="FF0000">*</font></font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgproflictype" id="dlgproflictype" size="25" maxlength="40">
						</font>
					</td>
				</tr>
				<tr valign="top"> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">State</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgprofstate" id="dlgprofstate">
							<option value="">Select a State</option>
							<option value="other">-Other-</option>
							<?php
								$sql = "Select Name, Abbrev from State order by Name";
								$state_result = $dbo->prepare($sql);
								$state_result->execute();
								while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
									echo "<option value=".$rows[1].">".$rows[0]."</option>";
								}	
							?>	
						</select></span>
					</td>
				</tr>
				<tr> 
					<td><font size="2">&nbsp;</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> OR If License issued out of the US, please select the Country</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Country</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgprofstateother" id="dlgprofstateother">
							<option value="">Select a Country</option>	
						<?php	
							$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
							$country_result = $dbo->prepare($sql);
							$country_result->execute();
							while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {		
								echo "<option value=".$rows[0].">".$rows[1]."</option>";
							}
						?>			
						</select></span>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">License Number<font color="FF0000">*</font></font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgproflicnum" id="dlgproflicnum" size="30"></font>
					</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>			
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_proflicense" VALUE="Save License">
						<INPUT TYPE="button" id="close_proflicense" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#ProfLicense_dialog" ).dialog({ autoOpen: false });

	$( "#add_new_proflicense" ).click(function() {	
		
		var personid = document.getElementById("PersonID").value;		
		
		if (document.getElementById("newproflictype").value > '') {
			var newproflictype = document.getElementById("newproflictype").value;
		} else {		
			document.ALCATEL.newproflictype.focus();
			alert("License Type is required");
			return;
		}	
		
		if (document.getElementById("newprofstate").value == '' && document.getElementById("newprofstateother").value == '' ) {
			document.ALCATEL.newprofstate.focus();
			alert("State or Country is required");
			return;
		} else {		
			var newprofstate = document.getElementById("newprofstate").value;
			var newprofstateother = document.getElementById("newprofstateother").value;
		}	
					
		if (document.getElementById("newprofnumber").value > '') {
			var newprofnumber = document.getElementById("newprofnumber").value;
		} else {		
			document.ALCATEL.newprofnumber.focus();
			alert("License Number is required");
			return;
		}	
								
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_proflicense.php", 
			data: {personid: personid, newproflictype: newproflictype, newprofstate: newprofstate, newprofstateother: newprofstateother, newprofnumber: newprofnumber},
					
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EduID = obj2;
					document.getElementById("newproflictype").value = '';
					document.getElementById("newprofstate").value = '';
					document.getElementById("newprofstateother").value = '';
					document.getElementById("newprofnumber").value = '';
					location.reload();				
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	
 	function updateprof(profid) {
		var personid = document.getElementById("PersonID").value;
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_proflicense.php", 
			data: {personid: personid, profid: profid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var ProfID = obj2[i].ProfID;
						var ProfLicenseType = obj2[i].ProfLicenseType;
						var ProfState = obj2[i].ProfState;
						var ProfStateOther = obj2[i].ProfStateOther;
						var ProfLicenseNumber = obj2[i].ProfLicenseNumber;
			    	}
			
					document.getElementById("dlgprofid").value = ProfID;
					document.getElementById("dlgproflictype").value = ProfLicenseType;
					document.getElementById("dlgprofstate").value = ProfState;
					document.getElementById("dlgprofstateother").value = ProfStateOther;
					document.getElementById("dlgproflicnum").value = ProfLicenseNumber;
  
					$( "#ProfLicense_dialog" ).dialog( "option", "title", "Edit License");	
					$( "#ProfLicense_dialog" ).dialog( "option", "modal", true );
					$( "#ProfLicense_dialog" ).dialog( "option", "width", 700 );
					$( "#ProfLicense_dialog" ).dialog( "open" );
				} else {
					alert('No License Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	}	
	
 	$("#save_proflicense").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var profid = document.getElementById("dlgprofid").value;

		if (document.getElementById("dlgproflictype").value > '') {
			var proflictype = document.getElementById("dlgproflictype").value;
		} else {		
			document.ALCATEL.dlgproflictype.focus();
			alert("License Type is required");
			return;
		}	

		if (document.getElementById("dlgprofstate").value == '' && document.getElementById("dlgprofstateother").value == '' ) {
			document.ALCATEL.dlgprofstate.focus();
			alert("State or Country is required");
			return;
		} else {		
			var profstate = document.getElementById("dlgprofstate").value;
			var profstateother = document.getElementById("dlgprofstateother").value;
		}	
	

		if (document.getElementById("dlgproflicnum").value > '') {
			var proflicnum = document.getElementById("dlgproflicnum").value;
		} else {		
			document.ALCATEL.dlgproflicnum.focus();
			alert("License # is required");
			return;
		}	
		

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_proflicense.php", 
			data: {personid: personid, profid: profid, proflictype: proflictype, profstate: profstate, profstateother: profstateother, proflicnum: proflicnum},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#ProfLicense_dialog" ).dialog( "close" );
					location.reload();				
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deleteprof(profid) {	
//		alert("In dltedu");
		if (confirm('Are you sure you want to delete this License?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_proflicense.php", 
				data: {personid: personid, profid: profid},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false; 
					} else {
						location.reload();				
					}
				},	
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				} 					
			});
		}
	}
	$( "#close_proflicense" ).click(function() {	
		$( "#ProfLicense_dialog" ).dialog( "close" );
	});
 
 </script>
