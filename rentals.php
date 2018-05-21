<?php
require_once('../pdotriton.php');
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();  
if ($noemail == 'Y') {
	$FormAction = "certification.php?PersonID=".$PersonID;
} else {
	$FormAction = "disclosure1.php?PersonID=".$PersonID;
}
  
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
		<script type="text/javascript" src="https://ajax.googleapis.com/../App_Ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<style type="text/css">
			.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
		</style>
		<script language="JavaScript" type="text/javascript">
		 	$(document).ready(function() {
				if (document.getElementById("Current").value != 'Y') {
					var today = new Date();
					var dd = today.getDate();
					var mm = today.getMonth()+1;
					var yyyy = today.getFullYear();
					if(dd<10) {dd='0'+dd}; 
					if(mm<10) {mm='0'+mm}; 
					today = mm+'/'+dd+'/'+yyyy;	
					document.getElementById("newmoveoutdate").placeholder = '';	
					document.getElementById("newmoveoutdate").value = today;
			}	
		});	
	</script>	
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
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Rental References</strong> </font></p>
			</td>
		</tr>	
		<tr>
			<td colspan="2"> 
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">List one or more Rental Reference(s).</font></p>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp</font></p>
				<p>&nbsp;</p>
			</td>
		</tr>
	</table>';
	$currentRental = 'N';
	$maxRentalID = 0;
	$maxRentalID = $dbo->query("Select max(RentalID) from App_Rentals where PersonID = ".$PersonID.";")->fetchColumn();
	if ($maxRentalID > 0) { 
		$selectstmt="select RentalID, Landlord_Name, Landlord_Phone, Landlord_Email, Current_Rental, Rental_Address, Rental_City, Rental_State, Rental_ZipCode, Rental_Type, Rental_Payment, Rental_MoveIn_Date, Rental_MoveOut_Date from App_Rentals where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();
		while($row=$result2->fetch(PDO::FETCH_BOTH)) {
			echo '<table width="763" bgcolor="#E4E8E8">';
			if ($row[4] == 'Y') {
				$currentRental = $row[4];
				echo '<tr><td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br><strong>Current Rental</strong></font></td>
				</tr>';  
			}
			if ($row[11] == '1900-01-01') {
				$fromdate = '';
			} else {
				$fromdate = date("m/d/Y", strtotime($row[10]));
			}
			if ($row[12] == '1900-01-01') {
				$todate = '';
			} else {
				$todate = date("m/d/Y", strtotime($row[11]));
			}
			
			echo '<tr>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[1]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[3]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[5]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[6]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[7]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[8]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[9]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[10]).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($fromdate).'</font></td>
					<td width="25%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($todate).'</font></td>
					<td align="center">
						<a http="#" onclick="updateRental('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Rental" title="Edit Rental"/></a>&nbsp;&nbsp;
				<a http="#" onclick="deleteRental('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Rental" title="Delete Reference"/></a>
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
echo '<fieldset><legend><strong>Add Rental</strong></legend>
	<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>&nbsp;</td>
		</tr>';
	if ($currentRental == 'N') {	
		echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Current Rental<font color="#FF0000">*</font></font></td>
			<td>
				<select name="newcurrent" id="newcurrent" onchange="setdate()">
					<option value="Y">Yes</option>
					<option VALUE="N">No</OPTION>
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
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Rental Address<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newrentaladdress" id="newrentaladdress" value="" size="30" maxlength="100">
				</font>
			</td>
		</tr>	
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Rental City<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newrentalcity" id="newrentalcity" value="" size="30" maxlength="100">
				</font>
			</td>
		</tr>	
		<tr> 
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Rental State<font color="#FF0000">*</font></font></td>
			<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newrentalstate" id="newrentalstate" onchange="loadcounties(\'newstate\',\'\')">
					<option value="">Select a State</option>
					<option value="">-Other-</option>';
					$sql = "Select Name, Abbrev from State order by Name";
					$state_result = $dbo->prepare($sql);
					$state_result->execute();
					while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
						echo "<option value=".$rows[1].">".$rows[0]."</option>";
					}		
			echo '</select></span>
			</td>
		</tr>';	
		echo '<tr> 
			<td><font size="1">&nbsp;</font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> OR If address is out of the US, please select the Country</font></td>
		</tr>';
		echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Rental Country</font></td>
			<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newrentalstateother" id="newrentalstateother">
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
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Postal Code<font color="#FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newrentalzip" id="newrentalzip" size="10" maxlength="10">
				</font>
			</td>
		</tr>	
		<tr> 
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Type<font color="#FF0000">*</font></font></td>
			<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newrentaltype" id="newrentaltype">
					<option value="">Select a Type</option>
					<option value="Rent">Rent</option>
					<option value="Lease">Lease</option>
					<option value="Own">Own</option>
					<option value="Other">Other</option>
				</select></span>
			</td>
		</tr>';	
	echo '<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Rental Payment<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newrentalpayment" id="newrentalpayment" placeholder="0.00" value="" size="30" maxlength="100">
				</font>
			</td>
		</tr>	
		<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Move In Date<font color="#FF0000">*</font></font></td>
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newmoveindate" id="newmoveindate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')" >
			</font>
		</td>	
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Move Out Date<font color="#FF0000">*</font></font></td>
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newmoveouttodate" id="newmoveoutdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
			</font>
		</td>
	</tr>	
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_Rental" VALUE="Save Rental" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
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
	  <INPUT TYPE=\"hidden\" name=\"Current\" id=\"Current\" VALUE=\"$currentRental\">
	  <INPUT TYPE=\"hidden\" NAME=\"RentalID\" ID=\"RentalID\" VALUE=\"$maxRentalID\">";

?>
	<div name="Rental_dialog" id="Rental_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgrentalid" id="dlgrentalid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Current Rental</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
					    <select name="dlgcurrent" id="dlgcurrent">
							<option value="Y">Yes</option>
							<option VALUE="N">No</OPTION>
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
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Contact Email<font color="FF0000">*</font></font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlglandlordemail" id="dlglandlordemail" value="" size="30" maxlength="100"></font>
					</td>
				</tr>	
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rental Address<font color="FF0000">*</font></font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgrentaladdress" id="dlgrentaladdress" value="" size="30" maxlength="100">
						</font>
					</td>
				</tr>	
				<tr valign="top"> 
				<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rental City<font color="FF0000">*</font></font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgrentalcity" id="dlgrentalcity" value="" size="30" maxlength="100">
						</font>
					</td>
				</tr>	
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rental State<font color="#FF0000">*</font></font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgrentalstate" id="dlgrentalstate">
							<option value="">Select a State</option>
							<option value="">-Other-</option>
						<?	
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
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> OR If address is out of the US, please select the Country</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rental Country</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgrentalstateother" id="dlgrentalstateother">
							<option value="">Select a Country</option>
					<?		
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
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rental Postal Code</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgrentalzipcode" id="dlgrentalzipcode" size="10" maxlength="10">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Type<font color="#FF0000">*</font></font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgrentaltype" id="dlgrentaltype">
							<option value="">Select a Type</option>
							<option value="Rent">Rent</option>
							<option value="Lease">Lease</option>
							<option value="Own">Own</option>
							<option value="Other">Other</option>
						</select></span>
					</td>
				</tr>	
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rental Payment<font color="FF0000">*</font></font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgrentalpayment" id="dlgrentalpayment" placeholder="0.00" value="" size="30" maxlength="100">
						</font>
					</td>
				</tr>	
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Move In Date<font color="#FF0000">*</font></font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgmoveindate" id="dlgmoveindate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')" >
						</font>
					</td>	
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Move Out Date<font color="#FF0000">*</font></font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgmoveouttodate" id="dlgmoveoutdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
						</font>
					</td>
				</tr>				
			</table>		
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_rental" VALUE="Save Rental Info">
						<INPUT TYPE="button" id="close_rental" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#Rental_dialog" ).dialog({ autoOpen: false });

	$( "#add_new_Rental" ).click(function() {	
		var personid = document.getElementById("PersonID").value;		
		if (document.getElementById("Current").value == 'N') {
			var newcurrentRental = document.getElementById("newcurrent").value;
		} else {
			var newcurrentRental = 'N';
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
		if (document.getElementById("newrentaladdress").value > '') {
			var newrentaladdress = document.getElementById("newrentaladdress").value;
		} else {		
			document.ALCATEL.newrentaladdress.focus();
			alert("Rental Address is required");
			return;
		}		
		if (document.getElementById("newrentalcity").value > '') {
			var newrentalcity = document.getElementById("newrentalcity").value;
		} else {		
			document.ALCATEL.newrentalcity.focus();
			alert("Rental City is required");
			return;
		}		

		if (document.getElementById("newrentalstate").value == '' && document.getElementById("newrentalstateother").value == '' ) {
			document.ALCATEL.newrentalstate.focus();
			alert("Rental State or Country is required");
			return;
		} else {		
			var newrentalstate = document.getElementById("newrentalstate").value;
			var newrentalstateother = document.getElementById("newrentalstateother").value;
		}	
		if (document.getElementById("newrentalzip").value > '') {
			var newrentalzipcode = document.getElementById("newrentalzip").value;
		} else {		
			document.ALCATEL.newrentalzip.focus();
			alert("Rental Postal Code is required");
			return;
		}	

		if (document.getElementById("newrentaltype").value > '') {
			var newrentaltype = document.getElementById("newrentaltype").value;
		} else {		
			document.ALCATEL.newrentaltype.focus();
			alert("Type is required");
			return;
		}		

		if (document.getElementById("newrentalpayment").value > '') {
			var newrentalpayment = document.getElementById("newrentalpayment").value;
		} else {		
			document.ALCATEL.newrentalpayment.focus();
			alert("Rental Payment is required");
			return;
		}		

		if (document.getElementById("newmoveindate").value > '') {
			if (!isValidDate('newmoveindate')) {
				$('#newmoveindate').focus();
				alert("Invalid Move In Date");
				return false;
			} else {					
				var newmoveindate = document.getElementById("newmoveindate").value;
			}	
		} else {		
			document.ALCATEL.newmoveindate.focus();
			alert("Move In Date is required");
			return;
		}	
		
		if (document.getElementById("newmoveoutdate").value > '') {
			if (!isValidDate('newmoveoutdate')) {
				$('#newmoveoutdate').focus();
				alert("Invalid Move Out Date");
				return false;
			} else {					
				var newmoveoutdate = document.getElementById("newmoveoutdate").value;
			}	
		} else {		
			document.ALCATEL.newmoveoutdate.focus();
			alert("Move Out Date is required");
			return;
		}
		
		if (!isValidDiff(newmoveindate,newmoveoutdate)) {
			$('#newmoveindate').focus();
			alert("Move In Date can not be greater than Move Out Date");
			return false;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_rental.php", 
			data: {personid: personid, newlandlordname: newlandlordname, newlandlordphone: newlandlordphone, newlandlordemail: newlandlordemail, newcurrentRental: newcurrentRental, 
					newrentaladdress: newrentaladdress, newrentalcity: newrentalcity, newrentalstate: newrentalstate, newrentalstateother: newrentalstateother, 
					newrentaltype: newrentaltype, newrentalpayment: newrentalpayment, newmoveindate: newmoveindate, newmoveoutdate: newmoveoutdate, newrentalzipcode: newrentalzipcode },
					
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var RentalID = obj2;
					document.getElementById("newlandlordname").value = '';
					document.getElementById("newlandlordphone").value = '';
					document.getElementById("newlandlordemail").value = '';
					document.getElementById("newrentaladdress").value = '';
					document.getElementById("newrentalcity").value = '';
					document.getElementById("newrentalstate").value = '';
					document.getElementById("newrentalstateother").value = '';
					document.getElementById("newrentalzip").value = '';
					document.getElementById("newrentaltype").value = '';
					document.getElementById("newrentalpayment").value = '';
					document.getElementById("newmoveindate").value = '';
					document.getElementById("newmoveoutdate").value = '';
					location.reload();
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	function setdate() {
		if (document.getElementById("newcurrent").value == 'Y') {
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1;
			var yyyy = today.getFullYear();
			if(dd<10) {dd='0'+dd}; 
			if(mm<10) {mm='0'+mm}; 
			today = mm+'/'+dd+'/'+yyyy;	
			document.getElementById("newmoveoutdate").placeholder = '';	
			document.getElementById("newmoveoutdate").value = today;
		} else {
			document.getElementById("newmoveoutdate").placeholder = 'mm/dd/yyyy';	
			document.getElementById("newmoveoutdate").value = '';
		}		
	}
	
 	function updateRental(rentalid) {
		var personid = document.getElementById("PersonID").value;
// 		alert('PersonID: '+personid+' - RentalID: '+rentalid);
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_rental.php", 
			data: {personid: personid, rentalid: rentalid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var CurrentRental = obj2[i].Current_Rental;
						var RentalID = obj2[i].RentalID;
						var LandlordName = obj2[i].Landlord_Name;
						var LandlordPhone = obj2[i].Landlord_Phone;
						var LandlordEmail = obj2[i].Landlord_Email;
						var	Rental_Address = obj2[i].Rental_Address;
 						var Rental_City = obj2[i].Rental_City;
						var Rental_State = obj2[i].Rental_State;
						var Rental_Country = obj2[i].Rental_Country;
						var Rental_ZipCode = obj2[i].Rental_ZipCode;
						var Rental_Type = obj2[i].Rental_Type;
						var Rental_Payment = obj2[i].Rental_Payment;
						var movein = obj2[i].Rental_MoveIn_Date;
						var moveout = obj2[i].Rental_MoveOut_Date;
						var Rental_MoveIn_Date = movein.substr(5,2)+"/"+movein.substr(8)+"/"+movein.substr(0,4);
						var Rental_MoveOut_Date = moveout.substr(5,2)+"/"+moveout.substr(8)+"/"+moveout.substr(0,4);
			    	}
 					document.getElementById("dlgcurrent").value = CurrentRental;
					document.getElementById("dlgrentalid").value = RentalID;
					document.getElementById("dlglandlordname").value = LandlordName;
					document.getElementById("dlglandlordphone").value = LandlordPhone;
					document.getElementById("dlglandlordemail").value = LandlordEmail;
					document.getElementById("dlgrentaladdress").value = Rental_Address;
					document.getElementById("dlgrentalcity").value = Rental_City;
					document.getElementById("dlgrentalstate").value = Rental_State;
					document.getElementById("dlgrentalstateother").value = Rental_Country;
					document.getElementById("dlgrentalzipcode").value = Rental_ZipCode;
					document.getElementById("dlgrentaltype").value = Rental_Type;
					document.getElementById("dlgrentalpayment").value = Rental_Payment;
					document.getElementById("dlgmoveindate").value = Rental_MoveIn_Date;
					document.getElementById("dlgmoveoutdate").value = Rental_MoveOut_Date;
  
					$( "#Rental_dialog" ).dialog( "option", "title", "Edit Rental");	
					$( "#Rental_dialog" ).dialog( "option", "modal", true );
					$( "#Rental_dialog" ).dialog( "option", "width", 700 );
					$( "#Rental_dialog" ).dialog( "open" );
				} else {
					alert('No Rental Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	}	
	
 	$("#save_rental").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var rentalid = document.getElementById("dlgrentalid").value;
		var currentRental = document.getElementById("dlgcurrent").value;

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
		if (document.getElementById("dlgrentaladdress").value > '') {
			var dlgrentaladdress = document.getElementById("dlgrentaladdress").value;
		} else {		
			document.ALCATEL.dlgrentaladdress.focus();
			alert("Rental Address is required");
			return;
		}		
		if (document.getElementById("dlgrentalcity").value > '') {
			var dlgrentalcity = document.getElementById("dlgrentalcity").value;
		} else {		
			document.ALCATEL.dlgrentalcity.focus();
			alert("Rental City is required");
			return;
		}		

		if (document.getElementById("dlgrentalstate").value == '' && document.getElementById("dlgrentalstateother").value == '' ) {
			document.ALCATEL.dlgrentalstate.focus();
			alert("Rental State or Country is required");
			return;
		} else {		
			var dlgrentalstate = document.getElementById("dlgrentalstate").value;
			var dlgrentalstateother = document.getElementById("dlgrentalstateother").value;
		}	
 		if (document.getElementById("dlgrentalzipcode").value > '') {
			var dlgrentalzipcode = document.getElementById("dlgrentalzipcode").value;
		} else {		
			document.ALCATEL.dlgrentalzipcode.focus();
			alert("Rental Postal Code is required");
			return;
		}	
 		if (document.getElementById("dlgrentaltype").value > '') {
			var dlgrentaltype = document.getElementById("dlgrentaltype").value;
		} else {		
			document.ALCATEL.dlgrentaltype.focus();
			alert("Type is required");
			return;
		}		
		if (document.getElementById("dlgrentalpayment").value > '') {
			var dlgrentalpayment = document.getElementById("dlgrentalpayment").value;
		} else {		
			document.ALCATEL.dlgrentalpayment.focus();
			alert("Rental Payment is required");
			return;
		}		
		if (document.getElementById("dlgmoveindate").value > '') {
			if (!isValidDate('dlgmoveindate')) {
				$('#dlgmoveindate').focus();
				alert("Invalid Move In Date");
				return false;
			} else {					
				var dlgmoveindate = document.getElementById("dlgmoveindate").value;
			}	
		} else {		
			document.ALCATEL.dlgmoveindate.focus();
			alert("Move In Date is required");
			return;
		}	
		if (document.getElementById("dlgmoveoutdate").value > '') {
			if (!isValidDate('dlgmoveoutdate')) {
				$('#dlgmoveoutdate').focus();
				alert("Invalid Move Out Date");
				return false;
			} else {					
				var dlgmoveoutdate = document.getElementById("dlgmoveoutdate").value;
			}	
		} else {		
			document.ALCATEL.dlgmoveoutdate.focus();
			alert("Move Out Date is required");
			return;
		}
		if (!isValidDiff(dlgmoveindate,dlgmoveoutdate)) {
			$('#dlgmoveindate').focus();
			alert("Move In Date can not be greater than Move Out Date");
			return false;
		}	
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_rental.php", 
			data: {personid: personid, rentalid: rentalid, landlordname: landlordname, landlordphone: landlordphone, landlordemail: landlordemail, 
			currentRental: currentRental, dlgrentaladdress: dlgrentaladdress, dlgrentalcity: dlgrentalcity, dlgrentalstate: dlgrentalstate, 
			dlgrentalstateother: dlgrentalstateother, dlgrentaltype: dlgrentaltype, dlgrentalpayment: dlgrentalpayment, dlgmoveindate: dlgmoveindate, 
			dlgmoveoutdate: dlgmoveoutdate, dlgrentalzipcode: dlgrentalzipcode},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Rental_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deleteRental(rentalid) {	
		if (confirm('Are you sure you want to delete this Rental?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_rental.php", 
				data: {personid: personid, rentalid: rentalid},
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
	$( "#close_rental" ).click(function() {	
		$( "#Rental_dialog" ).dialog( "close" );
	});
 </script>
 <script language="JavaScript" type="text/javascript">
	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {

		var RentalID = document.getElementById("RentalID").value;
		if (RentalID == 0) {
			document.ALCATEL.newlandlordname.focus();
			alert('You have not entered at least one Rental');
			return false;
		} else {
			return true;		
		}
	}	
</script>
