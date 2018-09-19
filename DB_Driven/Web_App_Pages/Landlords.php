<?php
require_once('../pdotriton.php');
$page++;
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Next_Page = $dbo->query("Select Web_App_Name from WebApp_Web_Pages where Company_Name = '".$compname."' and Package_Name = '".$Package."' and Web_Page_Number = ".$page.";")->fetchColumn();	

$FormAction = $Next_Page.".php?PersonID=".$PersonID."&page=".$page;  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/../App_Ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<script type="text/javascript" src="js/autoFormats.js"></script>
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
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Landlord References</strong> </font></p>
			</td>
		</tr>	
		<tr>
			<td colspan="2"> 
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">List one or more Landlord Reference(s).</font></p>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp</font></p>
				<p>&nbsp;</p>
			</td>
		</tr>
	</table>';
	$currentlandlord = 'N';
	$maxLandlordID = 0;
	$maxLandlordID = $dbo->query("Select max(LandlordID) from App_Landlord where PersonID = ".$PersonID.";")->fetchColumn();
	if ($maxLandlordID > 0) { 
		$selectstmt="select LandlordID, Landlord_Name, Landlord_Phone, Landlord_Email, Current_Landlord from App_Landlord where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();
		while($row=$result2->fetch(PDO::FETCH_BOTH)) {
			echo '<table width="763" bgcolor="#E4E8E8">';
			$currentlandlord = $row[4];
			if ($row[4] == 'Y') {
				echo '<tr><td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br><strong>Current Landlord</strong></font></td>
				</tr>';  
			}
			echo '<tr>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[1]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[3]).'</font></td>
					<td align="center">
						<a http="#" onclick="updatelandlord('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Landlord" title="Edit Landlord"/></a>&nbsp;&nbsp;
				<a http="#" onclick="deletelandlord('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Landlord" title="Delete Reference"/></a>
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
echo '<fieldset><legend><strong>Add Landlord</strong></legend>
	<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>&nbsp;</td>
		</tr>';
	if ($currentlandlord == 'N') {	
		echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Current Landlord<font color="#FF0000">*</font></font></td>
			<td>
				<select name="newcurrent" id="newcurrent" onchange="setdate()">
					<option VALUE="N">No</OPTION>
					<option value="Y">Yes</option>
				</select>
			</td>
		</tr>';
	}		
	echo '<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Landlord Name<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newlandlordname" id="newlandlordname" value="" size="30" maxlength="100">
				</font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Phone<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newlandlordphone" id="newlandlordphone" value="" size="30" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')"></font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Email<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newlandlordemail" id="newlandlordemail" value="" size="30" maxlength="100">
				</font>
			</td>
		</tr>		
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_landlord" VALUE="Save Landlord" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
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
	  <INPUT TYPE=\"hidden\" name=\"Current\" id=\"Current\" VALUE=\"$currentlandlord\">
	  <INPUT TYPE=\"hidden\" NAME=\"LandlordID\" ID=\"LandlordID\" VALUE=\"$maxLandlordID\">";

?>
		<div name="Landlord_dialog" id="Landlord_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlglandlordid" id="dlglandlordid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Current Landlord</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
					    <select name="dlgcurrent" id="dlgcurrent">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
						</font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Landlord Name<font color="FF0000">*</font></font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlglandlordname" id="dlglandlordname" value="" size="30" maxlength="100">
						</font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Contact Phone #<font color="FF0000">*</font></font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlglandlordphone" id="dlglandlordphone" value="" size="30" placeholder="### ### #### #####" 
						onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')"></font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Email<font color="FF0000">*</font></font></td>
					<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlglandlordemail" id="dlglandlordemail" value="" size="30" maxlength="100"></font>
					</td>
				</tr>	
			</table>			
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_landlord" VALUE="Save Landlord">
						<INPUT TYPE="button" id="close_landlord" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#Landlord_dialog" ).dialog({ autoOpen: false });

	$( "#add_new_landlord" ).click(function() {	
		var personid = document.getElementById("PersonID").value;		
		if (document.getElementById("Current").value == 'N') {
			var newcurrentlandlord = document.getElementById("newcurrent").value;
		} else {
			var newcurrentlandlord = 'Y';
		}
		if (document.getElementById("newlandlordname").value > '') {
			var newlandlordname = document.getElementById("newlandlordname").value;
		} else {		
			document.ALCATEL.newlandlordname.focus();
			alert("Landlord Name is required");
			return;
		}	
					
		if (document.getElementById("newlandlordphone").value > '') {
			var newlandlordphone = document.getElementById("newlandlordphone").value;
		} else {		
			document.ALCATEL.newlandlordphone.focus();
			alert("Contact Phone # is required");
			return;
		}	
				
		if (document.getElementById("newlandlordemail").value > '') {
			var newlandlordemail = document.getElementById("newlandlordemail").value;
		} else {		
			document.ALCATEL.newlandlordemail.focus();
			alert("Contact Email is required");
			return;
		}		
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_landlord.php", 
			data: {personid: personid, newlandlordname: newlandlordname, newlandlordphone: newlandlordphone, newlandlordemail: newlandlordemail, newcurrentlandlord: newcurrentlandlord},
					
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EduID = obj2;
					document.getElementById("newlandlordname").value = '';
					document.getElementById("newlandlordphone").value = '';
					document.getElementById("newlandlordemail").value = '';
					location.reload();
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	
 	function updatelandlord(landlordid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_landlord.php", 
			data: {personid: personid, landlordid: landlordid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var CurrentLandlord = obj2[i].Current_Landlord;
						var LandlordID = obj2[i].LandlordID;
						var LandlordName = obj2[i].Landlord_Name;
						var LandlordPhone = obj2[i].Landlord_Phone;
						var LandlordEmail = obj2[i].Landlord_Email;
			    	}
			
					document.getElementById("dlgcurrent").value = CurrentLandlord;
					document.getElementById("dlglandlordid").value = LandlordID;
					document.getElementById("dlglandlordname").value = LandlordName;
					document.getElementById("dlglandlordphone").value = LandlordPhone;
					document.getElementById("dlglandlordemail").value = LandlordEmail;
  
					$( "#Landlord_dialog" ).dialog( "option", "title", "Edit Landlord");	
					$( "#Landlord_dialog" ).dialog( "option", "modal", true );
					$( "#Landlord_dialog" ).dialog( "option", "width", 700 );
					$( "#Landlord_dialog" ).dialog( "open" );
				} else {
					alert('No Landlord Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	}	
	
 	$("#save_landlord").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var landlordid = document.getElementById("dlglandlordid").value;
		var currentlandlord = document.getElementById("dlgcurrent").value;

		if (document.getElementById("dlglandlordname").value > '') {
			var landlordname = document.getElementById("dlglandlordname").value;
		} else {		
			document.ALCATEL.dlglandlordname.focus();
			alert("Landlord Name is required");
			return;
		}	
		
		if (document.getElementById("dlglandlordphone").value > '') {
			var landlordphone = document.getElementById("dlglandlordphone").value;
		} else {		
			document.ALCATEL.dlglandlordphone.focus();
			alert("Contact Phone # is required");
			return;
		}	
		
		if (document.getElementById("dlglandlordemail").value > '') {
			var landlordemail = document.getElementById("dlglandlordemail").value;
		} else {		
			document.ALCATEL.dlglandlordemail.focus();
			alert("Contact Email is required");
			return;
		}			

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_landlord.php", 
			data: {personid: personid, landlordid: landlordid, landlordname: landlordname, landlordphone: landlordphone, landlordemail: landlordemail, currentlandlord: currentlandlord},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Reference_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deletelandlord(LandlordID) {	
		if (confirm('Are you sure you want to delete this Landlord?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_landlord.php", 
				data: {personid: personid, LandlordID: LandlordID},
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
	$( "#close_landlord" ).click(function() {	
		$( "#Landlord_dialog" ).dialog( "close" );
	});
 </script>
 <script language="JavaScript" type="text/javascript">
	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {

		var LandlordID = document.getElementById("LandlordID").value;
		if (LandlordID == 0) {
			document.ALCATEL.newlandlordname.focus();
			alert('You have not entered at least one Landlord');
			return false;
		} else {
			return true;		
		}
	}	
</script>


