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
</head>

<?php
require_once('../pdotriton.php');

$num="";
if (isset($PersonID)) {
	$ipaddress = getenv("REMOTE_ADDR");
	echo "<input type=\"hidden\" name=\"PersonID\" id=\"PersonID\" value=\"$PersonID\">";
	echo "<input type=\"hidden\" name=\"ipaddr\" id=\"ipaddr\" value=\"$ipaddress\">";

	if ($PersonID > '') {
		$selectstmt="Select First_Name, Middle_Name, Last_Name, Date_of_Birth, SSN, Business_Phone, Home_Phone, mobile_Phone, Email, Package, Company_Name, Gender, Guarantor_Name, Guarantor_Email, Guarantor_Phone, No_Email, Pet_Info, Guarantor_Relationship from App_Person where PersonID = :PersonID;";
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
			$package=$row[9];
			$compname=$row[10];
			$gender=$row[11];
			$guarantorname=$row[12];
			$guarantoremail=$row[13];
			$guarantornumber=$row[14];
			$No_Email=$row[15];
			$Pet_Info=$row[16];
			$guarantorrelationship=$row[17];
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
$FormAction = "address.php?PersonID=".$PersonID;
echo "<body bgcolor=\"#E4E8E8\" onload=\"setindexes('".$gender."')\">";
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
		<?php
			if ($No_Email == 'N') {
				echo '<tr>
						<td>	
							<p><font face="Verdana, Arial, Helvetica, sans-serif"><strong>Note: </strong>You can return to this Application Portal at any time by clicking on the link in the email that was sent to you. All the data you have saved will be displayed when you return.</font></p>
						</td>
					</tr>';
			}		
		?>			
	</table>		
<?php		
echo '
<table border="0" width="763" bgcolor="#E4E8E8">
	<tr> 
		<td colspan="2"><b><font color="FF0000">*</font><font size="1" color="FF0000" face="Verdana, Arial, Helvetica, sans-serif"> Required Fields To Continue</font></b></td>
	</tr>
	<tr> 
		<td width="20%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;First Name</font><font color="#FF0000">*</font></b></td>
		<td width="5%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;M.I.</font><font color="#FF0000">*</font></td>
		<td width="10%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;No M.I.</font></b></td>
		<td width="75%"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Last Name</font><font color="#FF0000">*</font></b></td>
	</tr>
	<tr> 
		<td width="15%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="fname" id="fname" readonly value="'.htmlspecialchars($fname).'" size="40" maxlength="40">
			</font>
		</td>	
		<td width="5%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="mi" id="mi" value="'.htmlspecialchars($mi).'" size="1" maxlength="1"></font>
		</td>		
		<td width="10%">		
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="nomi" id="nomi" style="width:25px;height:25px;" onclick="NoMI()">	
		</td>
		<td width="75%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="lname" id="lname" readonly value="'.htmlspecialchars($lname).'" size="40" maxlength="40">
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
				<input name="namechg" id="namechg" size="13" maxlength="10" id="namechg" value="'.htmlspecialchars($namechg).'" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
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
		$selectalias="Select AliasID, FirstName, LastName, Changed from App_Alias where PersonID = :PersonID and AliasType = 'A';";
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
				<input name="newakachange" id="newakachange" size="13" maxlength="10" placeholder="mm/dd/yyyy" value="" onKeyUp="return frmtdate(this,\'up\')">
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
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">SSN</font><font color="#FF0000">*</font></b></td>';
#		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gender</font><font color="#FF0000">*</font></b></td>
echo '</tr>
	<tr> 	
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="birthdate" size="13" maxlength="10" id="birthdate" placeholder="mm/dd/yyyy" value="'.htmlspecialchars($Xbdate).'" onKeyUp="return frmtdate(this,\'up\')">    
			</font>
		</td>
		<td>
			<input type="text" id="ssn" name="ssn" placeholder="###-##-####" maxlength="11" onBlur = "validateSSN()" onKeyUp="return frmtssn(this,\'up\')" 
					onKeyDown="return frmtssn(this,\'down\')" value="'.htmlspecialchars($ssn).'" /> 
		</td>';	
/*		<td>
			<select name="gender" id="gender">
				<option value="">Select a Gender</option>
				<option value="Female">Female</option>
				<option value="Male">Male</option>
			</select>
		</td>		
*/
echo '</tr></table>';
	
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><hr></td>
	</tr></table>';
	if ($package == 'zinc') {
		echo '<table border="0" width="763" bgcolor="#E4E8E8"><tr>';
		echo '<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Mother\'s Maiden Name</font><font color="#FF0000">*</font></b></td>
			<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Father\'s Full Name</font><font color="#FF0000">*</font></b></td>';
		echo '</tr></tr>';	
		echo '<td>
			<input type="text" id="mothermaiden" name="mothermaiden" placeholder="Mother\'s Maiden Name" maxlength="30" 
			value="'.htmlspecialchars($MotherMaiden).'" /> 
			</td>';	
		echo '<td>
				<input type="text" id="fathername" name="fathername" placeholder="Father\'s Name" maxlength="50" 
				value="'.htmlspecialchars($FatherName).'" /> 
			</td>';	
			echo '</tr></table>';
		echo '<table border="0" width="763" bgcolor="#E4E8E8"><tr><td colspan="2"><hr></td></tr></table>';
	}
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
				<input name="busphone" type="text" id="busphone" value="'.htmlspecialchars($busphone).'" size="20" maxlength="40" placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtbphone(this,\'up\')">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="homephone" type="text" id="homephone" value="'.htmlspecialchars($homephone).'" size="20" maxlength="40" placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="cellphone" type="text" id="cellphone" value="'.htmlspecialchars($cellphone).'" size="20" maxlength="40" placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
			</font>
		</td>
	</tr>
</table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><hr></td>
	</tr></table>';
if ($package == 'package1') {	
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Guarantor Information:</font><font color="#FF0000">*</font></b></td>
	</tr>
	<tr>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Name </font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Phone # </font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Email </font></td>
	</tr>
	<tr>		
		<td>
			<font size="1">
				<input name="guarantorname" type="text" id="guarantorname" value="'.htmlspecialchars($guarantorname).'" size="20" maxlength="40">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="guarantornumber" type="text" id="guarantornumber" value="'.htmlspecialchars($guarantornumber).'" size="20" maxlength="40"placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<input name="guarantoremail" type="text" id="guarantoremail" value="'.htmlspecialchars($guarantoremail).'" size="20" maxlength="100">
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relationship </font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
	</tr>
	<tr>		
		<td>
			<font size="1">
				<input name="guarantorrelation" type="text" id="guarantorrelation" value="'.htmlspecialchars($guarantorrelationship).'" size="20" maxlength="40">
			</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
		</td>
		<td>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
		</td>
	</tr>
	
</table>';
}
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><hr></td>
	</tr></table>';
	
echo '<table border="0" width="763" bgcolor="#E4E8E8">	
	<tr>
		<td width="30%">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Enter an E-mail address:</b></font>';
			if ($No_Email == 'N') {
				echo '<font color="#FF0000">*</font>';
			}	
	echo '</td>
		<td width="80%">
			<font size="1">
				<input name="email" type="email" id="email" value="'.htmlspecialchars($email).'" size="30">
			</font>
		</td> 
	</tr>';
if ($package == 'package1') {	
echo '<table border="0" width="763" bgcolor="#E4E8E8">
	<tr>
		<td colspan="2"><hr></td>
	</tr></table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8"><tr>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Pet Information</b></font><font color="#FF0000">*</font></td>
  </tr>  
	<tr>
    	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    		<b>No pets without prior approval-each pet subject to a refundable pet deposit.</b></font>
		</td>
  	</tr></table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">
  	<tr>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Most of the rental units managed by Boulder Property Management do not permit pets. If you have a pet please communicate with the representative about the availability of any housing permitting pets. 
    	Please list all pets to include their type, weight, age, description, if house-broken and how long you have owned them. 
    	Service Dogs: Service dogs are permitted if disclosed prior to move-in and if a tenant demonstrates that his or her dog is in fact a service animal.
    	"Certifications", "registrations", and paraphernalia obtained from online websites are typically fraudulent and will not be accepted as evidence that a dog is a service dog.</font></td>'; 
    echo '<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      	<textarea name="petinfo" id="petinfo" cols="50" rows="10" maxlength="256">'.htmlspecialchars($Pet_Info).'</textarea>
      	</font></td></tr>';	
	
	echo'</table>';
}	
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
	<input type=\"hidden\" name=\"package\" id=\"package\" value=\"$package\">
	<input type=\"hidden\" name=\"noemail\" id=\"noemail\" value=\"$No_Email\">
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
	<div id="nomidialog" name="nomidialog">
		<div>Confirm Middle Initial Optout
			<img onclick="nomidialogclose()" style="cursor:pointer; float:right; position:relative; top:0px; left:0px;" class="close" height="15" width="15" src="images/dialog_close.png" alt="Close" title="Close"/>
			<br/>
			<hr>
			<br />
			<table name="resultInfo" id="resultInfo" cellpadding="0" cellspacing="0" class="db-table" width="100%"> 
				<tbody>
					<tr>
						<td>
							Middle Name is required to ensure maximum possible accuracy.<br /> Are you sure you do not have a middle name?
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>	
							<input name="nomiyes" id="nomiyes" type="button" value="I do not have a middle initial" style="font-size:medium; font-family=Tahoma; color:red; background-color: #fff; border:1px solid #000000; border-radius:6px; padding: 5px 24px;">&nbsp;&nbsp;
							<input name="nomino" id="nomino" type="button" value="I do have a middle initial" style="font-size:medium; font-family=Tahoma; color:blue; background-color: #fff; border:1px solid #000000; border-radius:5px; padding: 5px 24px;">
						</td>
					</tr>		
				</tbody>
			</table>
		</div>
	</div>
		
</FORM>

<script language="JavaScript" type="text/javascript"> 
 	$( "#Alias_dialog" ).dialog({ autoOpen: false });

	function setindexes(gender) {
//		alert('In setindexes - '+gender);
		var gendr = document.getElementById("gender");
		
		for(var x=0;x < gendr.length; x++) {
			if (gender == gendr.options[x].value)
				gendr.selectedIndex = x;
		}
	}
	function NoMI() {
//		alert('In setindexes - '+gender);	
		if (document.getElementById("nomi").checked) { 		
			$("#nomidialog").css("visibility","visible");
		}
	}
    function nomidialogclose() {
		el = document.getElementById("nomidialog");			
		el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";	
	}
	
	$( "#nomiyes" ).click(function() {
		$("#nomidialog").css("visibility","hidden");
	});
	
	$( "#nomino" ).click(function() {
		document.getElementById("nomi").checked = false;
		document.ALCATEL.mi.focus();
		$("#nomidialog").css("visibility","hidden");
	});
	
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
			if (!isValidDate('newakachange')) {
				$('#newakachange').focus();
				alert("Invalid Date Last Used");
				return false;
			} else {				
				var akachange = document.getElementById("newakachange").value;
			}	
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
		alert(personid);
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
			if (!isValidDate('dlgchanged')) {
				$('#dlgchanged').focus();
				alert("Invalid Date Last Used");
				return false;
			} else {					
				var changed = document.getElementById("dlgchanged").value;
			}	
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
		var packagename = document.getElementById("package").value;
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
		if (document.getElementById("nomi").checked) { 
			var mi = '';
		} else {	
			if (document.getElementById("mi").value > '') {
				var mi = document.getElementById("mi").value;	
			} else {	
				var mi = '';	
//				document.ALCATEL.mi.focus();
//				alert("Middle Initial is required");
//				return false;
			}						
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
			if (!isValidDate('namechg')) {
				$('#namechg').focus();
				alert("Invalid Date Maiden Name Changed");
				return false;
			} else {			
				var namechg = document.getElementById("namechg").value;
			}
		}		
		if (document.getElementById("birthdate").value > '') {
			var birthdate = document.getElementById("birthdate").value;
			if (birthdate.indexOf('XXXX') > 0) {
				birthdate = document.getElementById("fbdate").value;
				document.getElementById("birthdate").value = document.getElementById("fbdate").value;				
			}	
		} else {		
			var birthdate = '01/01/1960'; 
//			document.ALCATEL.birthdate.focus();
//			alert("Date of Birth is required");
//			return false;
		}
//		alert(birthdate);
//		if (!isValidDOB('birthdate')) {
//			$('#birthdate').focus();
//			alert("Invalid Date of Birth");
//			return false;
//		}			
		if (packagename == 'zinc') {		
			var ssn = '';
			if (document.getElementById("ins").value > '') {
				var ins = document.getElementById("ins").value;
			} else {		
				$('#ins').focus();
				alert("National Ins # or N/A is required");
				return false;
			}	
			if (document.getElementById("passport").value > '') {
				var passport = document.getElementById("passport").value;
			} else {		
				$('#passport').focus();
				alert("Passport # is required");
				return false;
			}	
			if (document.getElementById("nationality").value > '') {
				var nationality = document.getElementById("nationality").value;
			} else {		
				$('#nationality').focus();
				alert("Nationality is required");
				return false;
			}	
			if (document.getElementById("mothermaiden").value > '') {
				var mothermaiden = document.getElementById("mothermaiden").value;
			} else {		
				$('#mothermaiden').focus();
				alert("Mother's Maiden Name is required");
				return false;
			}	
			if (document.getElementById("fathername").value > '') {
				var fathername = document.getElementById("fathername").value;
			} else {		
				$('#fathername').focus();
				alert("Father's Full Name is required");
				return false;
			}				
		} else {	
			var ins = '';
			var passport = '';
			var nationality = '';
			var mothermaiden = '';
			var fathername = '';
			if (document.getElementById("ssn").value > '') {
				var ssn = document.getElementById("ssn").value;
				if (ssn.length < 11) {
					document.ALCATEL.ssn.focus();
					alert("Invalid SSN - Require format ###-##-####");
					return false;
				} else {
					if (ssn.substring(0,3) == 'XXX') {
						ssn = document.getElementById("num").value;
					}
				}		
			} else {	
				var ssn = '111-11-1111';	
//				document.ALCATEL.ssn.focus();
//				alert("SSN is required");
//				return false;
			}
		}	
		
/*
		if (document.getElementById("gender").value > '') {
			var gender = document.getElementById("gender").value;
		} else {		
			document.ALCATEL.gender.focus();
			alert("Gender is required");
			return false;
		}	
*/		
		var gender = '';
		if (document.getElementById("busphone").value == '' && document.getElementById("homephone").value == '' && 
			document.getElementById("cellphone").value == '') {
			var homephone = '123 456-7890'
			var busphone = '';
			var cellphone = '';
			
//			document.ALCATEL.busphone.focus();
//			alert("Please enter at least one contact phone number");
//			return false;
		} else {		
			var busphone = document.getElementById("busphone").value;
			var homephone = document.getElementById("homephone").value;
			var cellphone = document.getElementById("cellphone").value;
		}	
		if (packagename == 'package1') {
			if (document.getElementById("guarantorname").value > '') {
				var guarantorname = document.getElementById("guarantorname").value;
			} else {		
				var guarantorname = ''
//				document.ALCATEL.guarantorname.focus();
//				alert("Guarantor Name is required");
//				return false;
			}	
			if (document.getElementById("guarantornumber").value > '') {
				var guarantornumber = document.getElementById("guarantornumber").value;
			} else {		
				var guarantornumber = '';
//				document.ALCATEL.guarantornumber.focus();
//				alert("Guarantor Phone Number is required");
//				return false;
			}	
			if (document.getElementById("guarantoremail").value > '') {
				var guarantoremail = document.getElementById("guarantoremail").value;
			} else {		
				var guarantoremail = '';			
//				document.ALCATEL.guarantoremail.focus();
//				alert("Guarantor Email is required");
//				return false;
			}	
			if (document.getElementById("guarantorrelation").value > '') {
				var guarantorrelation = document.getElementById("guarantorrelation").value;
			} else {		
				var guarantorrelation = '';
//				document.ALCATEL.guarantorrelation.focus();
//				alert("Guarantor Relationship is required");
//				return false;
			}	
			if (document.getElementById("petinfo").value > '') {
				var petinfo = document.getElementById("petinfo").value;
			} else {		
				var petinfo = '';
//				document.ALCATEL.guarantoremail.focus();
//				alert("Pet Information is required. Put N/A if you don't have any pets");
//				return false;
			}	
		} else {
			var guarantorname = '';
			var guarantornumber = '';
			var guarantoremail = '';
			var guarantorrelation = '';
			var petinfo = '';
		}
		
		var emergcontact = '';
		var emergnumber =  '';
		
		if (document.getElementById("noemail").value == 'N') {
			if (document.getElementById("email").value > '') {
				var email = document.getElementById("email").value;
			} else {		
				var email = '';
//				document.ALCATEL.email.focus();
//				alert("Email Address is required");
//				return false;
			}	
		} else {
			var email = document.getElementById("email").value;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_person.php", 
			data: {personid: personid, fname: fname, mi: mi, lname: lname, maiden: maiden, namechg: namechg, birthdate: birthdate, ssn: ssn, busphone: busphone, homephone: homephone, cellphone: cellphone, email: email, gender: gender, emergcontact: emergcontact, emergnumber: emergnumber,ipaddress: ipaddress, guarantorname: guarantorname, guarantornumber: guarantornumber, guarantoremail: guarantoremail, petinfo: petinfo, guarantorrelation: guarantorrelation},
			datatype: "JSON",
			success: function(valor) {
//				alert('Valor: '+valor);
				var obj2 = $.parseJSON(valor);
				if (obj2 > '') {
					alert(obj2);
					return false; 
				} else {
					switch(packagename) {
						case 'package1':
			 				window.location ='bank.php?PersonID='+personid;	
							break;
						case 'package2':
		 					window.location ='dmv.php?PersonID='+personid;	
		 					break;
		 				case 'package3':	
		 					window.location ='address.php?PersonID='+personid;	
		 					break;
					
		 			}	

				}	
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); 
				alert('Error: '+errorThrown);
			} 					
		});
	});
	
	$( "#save_person_info" ).click(function() {	
		var packagename = document.getElementById("package").value;

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
		if (packagename == 'zinc') {		
			var ssn = '';
			var ins = document.getElementById("ins").value;
			var passport = document.getElementById("passport").value;
			var nationality = document.getElementById("nationality").value;
			var mothermaiden = document.getElementById("mothermaiden").value;
			var fathername = document.getElementById("fathername").value;
		} else {
			var ins = '';
			var passport = '';
			var nationality = '';
			var mothermaiden = '';
			var fathername = '';
			if (document.getElementById("ssn").value > '') {
				var ssn = document.getElementById("ssn").value;
				if (ssn.substring(0,3) == 'XXX') {
					ssn = document.getElementById("num").value;
				}	
			} else {		
				ssn = '';
			}
		}
		
		var busphone = document.getElementById("busphone").value;
		var homephone = document.getElementById("homephone").value;
		var cellphone = document.getElementById("cellphone").value;	
/*
		var gender = document.getElementById("gender").value;
		var emergcontact = document.getElementById("emergcontact").value;
		var emergnumber = document.getElementById("emergnumber").value;
*/
		if (packagename == 'package1') {
			var guarantorname = document.getElementById("guarantorname").value;
			var guarantornumber = document.getElementById("guarantornumber").value;
			var guarantoremail = document.getElementById("guarantoremail").value;
			var guarantorrelation = document.getElementById("guarantorrelation").value;
			var petinfo = document.getElementById("petinfo").value;
		} else {
			var guarantorname = '';
			var guarantornumber = '';
			var guarantoremail = '';
			var guarantorrelation = '';
			var petinfo = '';		
		}	
		var gender = '';
		var emergcontact = '';
		var emergnumber = '';

		var email = document.getElementById("email").value;
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_person.php", 
			data: {personid: personid, fname: fname, mi: mi, lname: lname, maiden: maiden, namechg: namechg, birthdate: birthdate, ssn: ssn, busphone: busphone, homephone: homephone, cellphone: cellphone, email: email, gender: gender, emergcontact: emergcontact, emergnumber: emergnumber, ipaddress: ipaddress, guarantorname: guarantorname, guarantornumber: guarantornumber, guarantoremail: guarantoremail, petinfo: petinfo, guarantorrelation: guarantorrelation},
			datatype: "JSON",
			success: function(valor) {
//				alert('Valor: '+valor);
				var obj2 = $.parseJSON(valor);
				if (obj2 > '') {
					alert(obj2);
					return false;
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
	function validateSSN() {
   		var patt = new RegExp("\d{3}[\-]\d{2}[\-]\d{4}");
   		var x = document.getElementById("ssn");
   		var res = patt.test(x.value);
   		if(!res){
    		x.value = x.value
        	.match(/\d*/g).join('')
        	.match(/(\d{0,3})(\d{0,2})(\d{0,4})/).slice(1).join('-')
        	.replace(/-*$/g, '');
   		}
	}	
	
</script>
</body>
</html>

