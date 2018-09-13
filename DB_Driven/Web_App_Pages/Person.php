<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script type="text/javascript" src="js/autoFormats.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>
		<script src="jquery-ui/jquery-ui.js"></script>		
</head>

<?php
require_once('../pdotriton.php');
$num="";
$page = 2;
if (isset($PersonID)) {
	$ipaddress = getenv("REMOTE_ADDR");
	echo "<input type=\"hidden\" name=\"PersonID\" id=\"PersonID\" value=\"$PersonID\">";
	echo "<input type=\"hidden\" name=\"ipaddr\" id=\"ipaddr\" value=\"$ipaddress\">";

	if ($PersonID > '') {
		$selectstmt="Select First_Name, Middle_Name, Last_Name, Date_of_Birth, SSN, Business_Phone, Home_Phone, mobile_Phone, Email, Package, Company_Name from App_Person where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		if (!$result2->execute()) {
		} else {	
			$row=$result2->fetch(PDO::FETCH_BOTH);
			$fname=$row[0];
			$mi=$row[1];
			$lname=$row[2];
			if ($row[3] == '1900-01-01') {
				$birthdate="";
				$Xbdate = '';
			} else {	
				$birthdate=$row[3];
				$birthdate = date("m/d/Y", strtotime($birthdate));
				$Xbdate = substr($birthdate,0,6).'XXXX';
			}	
			
			if ($row[4] == '') {
				$ssn="";
			} else {	
				$ssn="XXX-XX-".substr($row[4],7);
			}
			$num=$row[4]; 
			$busphone=$row[5];
			$homephone=$row[6];
			$cellphone=$row[7];
			$email=$row[8];
			$Package=$row[9];
			$compname=$row[10];
			$selectstmt="Select LastName, Changed from App_Alias where PersonID = :PersonID and AliasType ='M';";
			$result2 = $dbo->prepare($selectstmt);
			$result2->bindValue(':PersonID', $PersonID);
			$result2->execute();
			$namechg="";
			$maiden='';
			$row=$result2->fetch(PDO::FETCH_BOTH);
			if ($row[0] > '') {
				$maiden=$row[0];
				if ($row[1] == '1900-01-01') {
					$namechg="";
				} else {	
					$namechg=$row[1];
					$namechg = date("m/d/Y", strtotime($namechg));
				}	
			}	
		}	
	}
}	
$Next_Page = $dbo->query("Select Web_App_Name from WebApp_Web_Pages where Company_Name = '".$compname."' and Package_Name = '".$Package."' and Web_Page_Number = ".$page.";")->fetchColumn();	

$FormAction = $Next_Page.".php?PersonID=".$PersonID."&page=".$page;
$sql = "Select * from WebApp_Person_Flds where Company_Name = :Company_Name and Package_Name = :Package_Name;";
$result3 = $dbo->prepare($sql);
$result3->bindValue(':Company_Name', $compname);
$result3->bindValue(':Package_Name', $Package);
if (!$result3->execute()) {
} else {	

}


echo "<body bgcolor=\"#E4E8E8\">";
echo "<FORM METHOD=\"POST\" action=\"$FormAction\" NAME=\"ALCATEL\">";
?>
<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  		<tbody>
  			<tr>
    			<td></td>
    			<td class="submenu" height="27" width="763">&nbsp;</td>
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
	<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td>
				<p><font face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">Subject Information</font></strong></font></p>
			</td>
		</tr>
		<tr>
			<td>	
				<p><font face="Verdana, Arial, Helvetica, sans-serif"><strong>Disclaimer: </strong>All information requested in this application 
  					is pertinent and necessary. Not filling out all information can delay the hiring process.</font></p>
			</td>
		</tr>
		<tr>
			<td>	
				<p><font face="Verdana, Arial, Helvetica, sans-serif"><strong>Note: </strong>You can return to this Application Portal at any time by clicking on the link in the email that was sent to you. All the data you have saved will be displayed when you return.</font></p>
			</td>
		</tr>
	</table>		
<?php		
echo '
<table border="0" width="763" bgcolor="#E4E8E8">
	<tr> 
		<td colspan="2"><b><font color="FF0000">*</font><font size="1" color="FF0000" face="Verdana, Arial, Helvetica, sans-serif"> Required Fields To Continue</font></b></td>
	</tr>
	<tr> 
		<td width="20%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;First Name</font><font color="#FF0000">*</font></b></td>
		<td width="5%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;M.I.</font></b></td>
		<td width="75%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Last Name</font><font color="#FF0000">*</font></b></td>
	</tr>
	<tr> 
		<td width="15%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="fname" id="fname" autocorrect="off" autocapitalize="words" value="'.htmlspecialchars($fname).'" size="40" maxlength="40">
			</font>
		</td>	
		<td width="5%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="mi" id="mi" value="'.htmlspecialchars($mi).'" size="1" maxlength="1">
			</font>
		</td>
		<td width="75%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="lname" id="lname" value="'.htmlspecialchars($lname).'" size="40" maxlength="40">
			</font>
		</td>
	</tr>
</table>
<table border="0" width="763" bgcolor="#E4E8E8">
	<tr> 
		<td width="25%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Maiden Name</font></b></td>
		<td width="75%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Date Maiden Name Changed</font></b></td>
	</tr>
	<tr>
		<td width="25%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="maiden" id="maiden" value="'.htmlspecialchars($maiden).'" size="40" maxlength="40" id="maiden">
			</font>
		</td>
		<td width="75%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="namechg" id="namechg" size="10" maxlength="10" id="namechg" value="'.htmlspecialchars($namechg).'" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
			</font>
		</td>
	</tr>
</table>
<table border="0" width="763" bgcolor="#E4E8E8">
   <tr>
		<td valign="top" colspan="4"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
			<b>AKAs</b>&nbsp;(Any names used in the past, nicknames, etc.)<br /><b>**NOTE: <u>MUST</u> have date last used entered.<br /></b>
		</td>
	</tr>
</table>	
<table border="0" width="763" id="Aliastbl" bgcolor="#E4E8E8">
	<tr>
		<th align="left" width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;First Name</font></th>
		<th align="left" width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;Last Name</font></th>
		<th align="left" width="20%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Date Last Used</font></th>
		<th align="left" width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></th>		
	</tr>';
	$maxAliasID = $dbo->query("select max(AliasID) from App_Alias where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxAliasID > 0) { 
		$selectalias="Select AliasID, FirstName, LastName, Changed from App_Alias where PersonID= :PersonID and Alias_Type = 'A';";
		$alias_result = $dbo->prepare($selectalias);
		$alias_result->bindValue(':PersonID', $PersonID);
		$alias_result->execute();
		while($Alias = $alias_result->fetch(PDO::FETCH_BOTH)) {		
			$dateUsed = date("m/d/Y", strtotime($Alias[3]));
			echo '<tr>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($Alias[1]).'</font> </td>
					<td width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($Alias[2]).'</font></td>
					<td width="20%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($dateUsed).'</font></td>
					<td width="25%">
						<a http="#" onclick="updateaka('.$Alias[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit AKA" title="Edit AKA"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a http="#" onclick="dltaka('.$Alias[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete AKA" title="Delete AKA"/></a>
					</td>
				</tr>';
		}
		echo '</table>';
	} 
echo '<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td width="25%"> 
				<input type="text" size="20" maxlength="40" name="newaka" id="newaka"> 
			</td>
			<td width="30%"> 
				<input type="text" size="20" maxlength="40" name="newakalast" id="newakalast">
			</td>
			<td width="20%">
				<input name="newakachange" id="newakachange" size="10" maxlength="10" placeholder="mm/dd/yyyy" value="" onKeyUp="return frmtdate(this,\'up\')">
			</td>
			<td width="25%">
				<input type="button" name="btnnewaka" id="btnnewaka" value="Save">
			</td>
		</tr>
	</table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">
		<tr>
			<td colspan="4"><hr></td>
		</tr>
	</table>
	<table border="0" width="763" bgcolor="#E4E8E8">
	<tr> 
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Date of Birth</font><font color="#FF0000">*</font></b></td>
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">SSN</font><font color="#FF0000">*</font></b></td>
	</tr>
	<tr> 	
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="birthdate" size="10" maxlength="10" id="birthdate" placeholder="mm/dd/yyyy" value="'.htmlspecialchars($Xbdate).'" onKeyUp="return frmtdate(this,\'up\')">    
			</font>
		</td>
		<td>
			<input type="text" id="ssn" name="ssn" placeholder="###-##-####" maxlength="11" onKeyUp="return frmtssn(this,\'up\')" 
			onKeyDown="return frmtssn(this,\'down\')" value="'.htmlspecialchars($ssn).'" /> 
		</td>		
	</tr></table>';	
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><hr></td>
	</tr></table>';
	
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Enter one or more contact phone number:</font><font color="#FF0000">*</font></b></td>
	</tr>
	<tr>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business Phone </font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Home Phone </font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Cell Phone</font></td>
	</tr>
	<tr>		
		<td>
			<font size="1">
				<input name="busphone" type="text" id="busphone" value="'.htmlspecialchars($busphone).'" size="20" maxlength="40">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="homephone" type="text" id="homephone" value="'.htmlspecialchars($homephone).'" size="20" maxlength="40">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="cellphone" type="text" id="cellphone" value="'.htmlspecialchars($cellphone).'" size="20" maxlength="40">
			</font>
		</td>
	</tr>
</table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><hr></td>
	</tr></table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">	
	<tr>
		<td width="20%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Enter an E-mail address:</b></font><font color="#FF0000">*</font>
		</td>
		<td width="80%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="email" type="email" id="email" value="'.htmlspecialchars($email).'" size="30">
			</font>
		</td> 
	</tr>
</table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">		
	<tr>
        <td colspan="2"><hr></td>
    </tr>
</table>
<font face="Verdana, Arial, Helvetica, sans-serif">
<div align="center">';
echo "<br /><INPUT name=\"save_person_info\" id=\"save_person_info\" TYPE=\"button\" VALUE=\"Save the Data You Have Entered\" style=\"font-size:medium; font-family=Tahoma; color:green; border-radius:6px; padding: 5px 24px;\">&nbsp;&nbsp;<INPUT name=\"add_person_info\" id=\"add_person_info\" TYPE=\"button\" VALUE=\"Save Subject Data and Continue\" style=\"font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;\">
	<input type=\"hidden\" name=\"AliasID\" id=\"AliasID\">
	<input type=\"hidden\" name=\"num\" id=\"num\" value=\"$num\">
	<input type=\"hidden\" name=\"fbdate\" id=\"fbdate\" value=\"$birthdate\">
	<input type=\"hidden\" name=\"nextpage\" id=\"nextpage\" value=\"$Next_Page\">
	<input type=\"hidden\" name=\"pageno\" id=\"pageno\" value=\"$page\">
	</div>	";
echo "<input type=\"hidden\" name=\"compname\" id=\"compname\" value=\"$compname\">";
	
?>
	<div name="Alias_dialog" id="Alias_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgaliasid" id="dlgaliasid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input type="text" name="dlgfistname" id="dlgfirstname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input type="text" name="dlglastname" id="dlglastname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Date Last Used</font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input type="text" name="dlgchanged" id="dlgchanged" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>	
				</tr>
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_alias" VALUE="Save AKA">
						<INPUT TYPE="button" id="close_alias" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>		
</FORM>

<script language="JavaScript" type="text/javascript"> 
 	$( "#Alias_dialog" ).dialog({ autoOpen: false });

	$( "#btnnewaka" ).click(function() {
//		alert('In btnnewaka');
		var personid = document.getElementById("PersonID").value;
		if (document.getElementById("newaka").value == '' && document.getElementById("newakalast").value == '' ) {
			document.ALCATEL.newaka.focus();
			alert("AKA's First Name and/or AKA's Last Name must be emtered to add an AKA");
			return false;
		} else {		
			var aka = document.getElementById("newaka").value;
			var akalast = document.getElementById("newakalast").value
		}	
		
		if (document.getElementById("newakachange").value > '') {
			var akachange = document.getElementById("newakachange").value;
		} else {		
			document.ALCATEL.newakachange.focus();
			alert("Date Last Used is required");
			return false;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_alias.php", 
			data: {personid: personid, aka: aka, akalast: akalast, akachange: akachange},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '') {
					alert(obj2);
					return false; 
				} else {
					var rows = $("#Aliastbl tr").length;	
					if (rows == 0) {
						rows++;
					}	
		
					aliastblrow = '<tr><td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'+aka+'</font></td><td width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'+akalast+'</font></td><td width="20%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'+akachange+'</font></td><td width="25%"><a http="#" onclick="updateaka('+rows+')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a http="#" onclick="dltaka('+rows+')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a></td></tr>';

					$("#Aliastbl").append(aliastblrow);
					document.getElementById("newaka").value = '';
					document.getElementById("newakalast").value = '';
					document.getElementById("newakachange").value = '';					
					return;
				}
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});

	function updateaka(aliasid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_alias.php", 
			data: {personid: personid, aliasid: aliasid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var AliasID = obj2[i].AliasID;
						var LastName = obj2[i].LastName;
						var FirstName = obj2[i].FirstName;
						var MidlleName = obj2[i].MiddleName;
						var cd = obj2[i].Changed;
						var Changed = cd.substr(5,2)+"/"+cd.substr(8)+"/"+cd.substr(0,4);
			    	}
			
					document.getElementById("dlgaliasid").value = AliasID;
					document.getElementById("dlglastname").value = LastName;
					document.getElementById("dlgfirstname").value = FirstName;
					document.getElementById("dlgchanged").value = Changed;

					$( "#Alias_dialog" ).dialog( "option", "title", "Edit AKA");	
					$( "#Alias_dialog" ).dialog( "option", "modal", true );
					$( "#Alias_dialog" ).dialog( "option", "width", 500 );
					$( "#Alias_dialog" ).dialog( "open" );
				} else {
					alert('No AKA Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
 	$("#save_alias").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var aliasid = document.getElementById("dlgaliasid").value;
		if (document.getElementById("dlgfirstname").value == '' && document.getElementById("dlglastname").value == '' ) {
			document.ALCATEL.dlgfirstname.focus();
			alert("First or Last Name is required");
			return;
		} else {		
			var firstname = document.getElementById("dlgfirstname").value;
			var lastname = document.getElementById("dlglastname").value;
		}	

		if (document.getElementById("dlgchanged").value > '') {
			var changed = document.getElementById("dlgchanged").value;
		} else {		
			document.ALCATEL.dlgchanged.focus();
			alert("Date Last Used is required");
			return;
		}	
		var middlename = '';
	
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_alias.php", 
			data: {personid: personid, aliasid: aliasid, firstname: firstname, lastname: lastname, middlename: middlename, changed: changed},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Alias_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function dltaka(AliasID) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this AKA record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_aka.php", 
				data: {personid: personid, AliasID: AliasID},
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
	
	$( "#close_alias" ).click(function() {	
		$( "#Alias_dialog" ).dialog( "close" );
	});
	
</script>	
<script language="JavaScript" type="text/javascript"> 
	$( "#add_person_info" ).click(function() {	
//		alert("In add_person_info");
		if (document.getElementById("newaka").value > '' || document.getElementById("newakalast").value > '' ) {
			alert("Please Save the AKA record before moving on.");
			return false;
		}	

		var personid = document.getElementById("PersonID").value;
		var ipaddress = document.getElementById("ipaddr").value;

		if (document.getElementById("fname").value > '') {
			var fname = document.getElementById("fname").value;
		} else {		
			document.ALCATEL.fname.focus();
			alert("First Name is required");
			return false;
		}	
		if (document.getElementById("mi").value > '') {
			var mi = document.getElementById("mi").value;
		} else {		
			var mi = '';
		}						
		if (document.getElementById("lname").value > '') {
			var lname = document.getElementById("lname").value;
		} else {		
			document.ALCATEL.lname.focus();
			alert("Last Name is required");
			return false;
		}	
		if (document.getElementById("maiden").value > '') {
			var maiden = document.getElementById("maiden").value;
		} else {		
			var maiden = '';
		}	
		if (document.getElementById("namechg").value == '') {
			if (maiden > '') {
				document.ALCATEL.namechg.focus();
				alert("Date Maiden Name Changed is required");
				return false;
			} else {
				var namechg = '1900-01-01';
			}	
		} else {		
			var namechg = document.getElementById("namechg").value;
		}	
		if (document.getElementById("birthdate").value > '') {
			var birthdate = document.getElementById("birthdate").value;
			if (birthdate.indexOf('XXXX') > 0) {
				birthdate = document.getElementById("fbdate").value;
			}	
		} else {		
			document.ALCATEL.birthdate.focus();
			alert("Date of Birth is required");
			return false;
		}	
		if (document.getElementById("ssn").value > '') {
			var ssn = document.getElementById("ssn").value;
			if (ssn.substring(0,3) == 'XXX') {
				ssn = document.getElementById("num").value;
			}	
		} else {		
			document.ALCATEL.ssn.focus();
			alert("SSN is required");
			return false;
		}	
		if (document.getElementById("busphone").value == '' && document.getElementById("homephone").value == '' && 
			document.getElementById("cellphone").value == '') {
			document.ALCATEL.busphone.focus();
			alert("Please enter at least one contact phone number");
			return false;
		} else {		
			var busphone = document.getElementById("busphone").value;
			var homephone = document.getElementById("homephone").value;
			var cellphone = document.getElementById("cellphone").value;
		}	
		if (document.getElementById("email").value > '') {
			var email = document.getElementById("email").value;
		} else {		
			document.ALCATEL.email.focus();
			alert("Email Address is required");
			return false;
		}	
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_person.php", 
			data: {personid: personid, fname: fname, mi: mi, lname: lname, maiden: maiden, namechg: namechg, birthdate: birthdate, ssn: ssn, busphone: busphone, homephone: homephone, cellphone: cellphone, email: email, ipaddress: ipaddress},
			datatype: "JSON",
			success: function(valor) {
//				alert('Valor: '+valor);
				var obj2 = $.parseJSON(valor);
				if (obj2 > '') {
					alert(obj2);
					return false; 
				} else {
					var nextpage = document.getElementById("nextpage").value;
					var page = document.getElementById("pageno").value;
		 			window.location = nextpage+'.php?PersonID='+personid+"&page="+page;	
				}	
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); 
				alert('Error: '+errorThrown);
			} 					
		});
	});
	
	$( "#save_person_info" ).click(function() {	
//		alert("In save_person_info");
		if (document.getElementById("newaka").value > '' || document.getElementById("newakalast").value > '' ) {
			alert("Please Save the AKA record before moving on.");
			return false;
		}	
		var personid = document.getElementById("PersonID").value;
		var ipaddress = document.getElementById("ipaddr").value;

		var fname = document.getElementById("fname").value;
		var mi = document.getElementById("mi").value;
		var lname = document.getElementById("lname").value;
		var maiden = document.getElementById("maiden").value;
		if (document.getElementById("namechg").value == '') {
			var namechg = '1900-01-01';
		} else {		
			var namechg = document.getElementById("namechg").value;
		}	
		if (document.getElementById("birthdate").value > '') {
			var birthdate = document.getElementById("birthdate").value;
			if (birthdate.indexOf('XXXX') > 0) {
				birthdate = document.getElementById("fbdate").value;
			}	
		} else {		
			birthdate = '1900-01-01';
		}	
		if (document.getElementById("ssn").value > '') {
			var ssn = document.getElementById("ssn").value;
			if (ssn.substring(0,3) == 'XXX') {
				ssn = document.getElementById("num").value;
			}	
		} else {		
			ssn = '';
		}	
		var busphone = document.getElementById("busphone").value;
		var homephone = document.getElementById("homephone").value;
		var cellphone = document.getElementById("cellphone").value;	
		var email = document.getElementById("email").value;
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_person.php", 
			data: {personid: personid, fname: fname, mi: mi, lname: lname, maiden: maiden, namechg: namechg, birthdate: birthdate, ssn: ssn, busphone: busphone, homephone: homephone, cellphone: cellphone, email: email, ipaddress: ipaddress},
			datatype: "JSON",
			success: function(valor) {
//				alert('Valor: '+valor);
				var obj2 = $.parseJSON(valor);
				if (obj2 > '') {
					alert(obj2);
				} else { 	
					alert('Data saved successfully');
				}
				return false; 
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); 
				alert('Error: '+errorThrown);
			} 					
		});
	});
	
</script>
</body>
</html>

