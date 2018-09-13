<?
#echo 'PersonID is '.$PersonID."<br />";
#echo 'AliasID is '.$AliasID."<br />";
require_once('../pdotriton.php');
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
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
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
	<script src="jquery-ui/jquery-ui.js"></script>
	<script type="text/javascript" src="js/autoFormats.js"></script>
	<style type="text/css">
		.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
	</style>
</head>

<?
#echo "<body bgcolor=\"#E4E8E8\" onload=\"setindexes()\">";
echo '<body bgcolor="#E4E8E8">';
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";

?>

<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
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
			<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Bank Information</strong> </font></p>
		</td>
	</tr>	
	<tr> 
		<td colspan="2"> 
			<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please provide your bank information.</font>
		</td>
	</tr>
	<tr> 
		<td colspan="2"> </td>
	</tr>
</table>
  
<?php  
	$currentaddress = 'N';

	$maxBankID = $dbo->query("Select max(BankID) from App_Bank where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxBankID > 0) { 
		$selectaddr="select BankID, Bank_Name, Bank_Address, Bank_City, Bank_State, Bank_Country, Bank_ZipCode, Account_Type, Account_Number from App_Bank where PersonID = :PersonID;";
		$bank_result = $dbo->prepare($selectaddr);
		$bank_result->bindValue(':PersonID', $PersonID);
		$bank_result->execute();
		while($row = $bank_result->fetch(PDO::FETCH_BOTH)) {		
			$fullaccount = $row[8];
			$displayaccount = 'xxxxxxxxxxxx';
			echo '<table width="763" id="banktbl" bgcolor="#E4E8E8">';
			echo '<tr><td width="100"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.htmlspecialchars($row[1]).'</font></td>';
			echo '<td width="100"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>';
			echo '<td width="50"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[3]).'</font></td>';
			if ($row[5] > '') {
				echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[5]).'</font></td>';
			} else {	
				echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[4]).'</font></td>';
			}	
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[6]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[7]).'</font></td>';
			echo '<td width="60">
				<a http="#" onclick="updatebank('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Bank" title="Edit Bank"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deletebank('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Bank" title="Delete Bank"/></a>
				</td>
			</tr>
			</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>
			<table width="763" id="banktbl2" bgcolor="#E4E8E8">';
		}	
		echo '</table>';
	} 
		
	echo '<fieldset><legend><strong>Add Bank Information</strong></legend>
		<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
		</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank Name<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newbankname" id="newbankname" size="20" maxlength="100" placeholder="Required">
				</font>
			</td>
		</tr>';
	echo '<tr> 
		<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank Street<font color="#FF0000">*</font></font></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newbankaddress" id="newbankaddress" size="20" maxlength="100" placeholder="Required">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank City<font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newbankcity" id="newbankcity" size="20" maxlength="40" placeholder="Required">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank State<font color="#FF0000">*</font></font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newbankstate" id="newbankstate">
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
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> OR If bank is out of the US, please select the Country</font></td>
	</tr>';
echo '<tr>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank Country</font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
			<select name="newbankcountry" id="newbankcountry">
				<option value="">Select a Country</option>';	
				$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
				$country_result = $dbo->prepare($sql);
				$country_result->execute();
				while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {		
					echo "<option value=".$rows[0].">".$rows[1]."</option>";
				}		
		echo '</select></span>
		</td>
	</tr>';
	echo '<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank Zip Code<font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
			<input name="newbankzip" id="newbankzip" size="10" maxlength="10">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Acoount Type<font color="#FF0000">*</font></font></td>
		<td>
			<select name="newaccounttype" id="newaccounttype">
					<option VALUE="Checking">Checking</OPTION>
					<option value="Savings">Savings</option>
				</select>
			</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Account Number<font color="#FF0000">*</font></font></td>
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newaccountnumber" id="newaccountnumber" size="15" maxlength="25" placeholder="requires">
			</font>
		</td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_bank" VALUE="Save Bank" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	</table></fieldset>';
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
	  <INPUT TYPE=\"hidden\" NAME=\"BankID\" ID=\"BankID\" VALUE=\"$maxBankID\">";
?>
	<div name="Bank_dialog" id="Bank_dialog" title="Dialog Title">
		<div>
			<br/>
			<input type="hidden" name="dlgbankid" id="dlgbankid">
			<input type="hidden" name="dlgaccounthidden" id="dlgaccounthidden">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank Name</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbankname" id="dlgbankname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank Street</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbankaddress" id="dlgbankaddress" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank City</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbankcity" id="dlgbankcity" size="20" maxlength="40" >
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank State</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgbankstate" id="dlgbankstate">
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
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> OR If bank is out of the US, please select the Country</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank Country</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgbankcountry" id="dlgbankcountry">
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
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank Zip Code</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbankzip" id="dlgbankzip" size="10" maxlength="10">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Account Type</font></td>
					<td>
					    <select name="dlgaccounttype" id="dlgaccounttype">
							<option VALUE="Checking">Checking</OPTION>
							<option value="Savings">Savings</option>
						</select>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Account Number</font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgaccountnumber" id="dlgaccountnumber" size="20" maxlength="50">
						</font>
					</td>
				</tr>
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_bank" VALUE="Save Bank">
						<INPUT TYPE="button" id="close_bank" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>
<script language="JavaScript" type="text/javascript">
	$( "#Bank_dialog" ).dialog({ autoOpen: false });

 	$( "#add_new_bank" ).click(function() {	
		var personid = document.getElementById("PersonID").value;
		if (document.getElementById("newbankname").value > '') {
			var bankname = document.getElementById("newbankname").value;
		} else {		
			document.ALCATEL.newbankname.focus();
			alert("Bank name is required");
			return;
		}	
		if (document.getElementById("newbankaddress").value > '') {
			var bankaddress = document.getElementById("newbankaddress").value;
		} else {		
			document.ALCATEL.newbankaddress.focus();
			alert("Bank street is required");
			return;
		}	
			
		if (document.getElementById("newbankcity").value > '') {
			var bankcity = document.getElementById("newbankcity").value;
		} else {		
			document.ALCATEL.newbankcity.focus();
			alert("Bank city is required");
			return;
		}	
			
		if (document.getElementById("newbankstate").value == '' && document.getElementById("newbankcountry").value == '' ) {
			document.ALCATEL.newbankstate.focus();
			alert("Bank State or Country is required");
			return;
		} else {		
			var bankstate = document.getElementById("newbankstate").value;
			var bankcountry = document.getElementById("newbankcountry").value;
		}	
			
		if (document.getElementById("newbankzip").value > '') {
			var bankzipcode = document.getElementById("newbankzip").value;
		} else {		
			document.ALCATEL.newbankzip.focus();
			alert("Bank Zip Code is required");
			return;
		}	
		
		var accounttype = document.getElementById("newaccounttype").value;
		
		if (document.getElementById("newaccountnumber").value > '') {
			var accountnumber = document.getElementById("newaccountnumber").value;
		} else {		
			document.ALCATEL.newaccountnumber.focus();
			alert("Account number is required");
			return;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_bank.php", 
			data: {personid: personid, bankname: bankname, bankaddress: bankaddress, bankcity: bankcity, bankstate: bankstate, bankcountry: bankcountry, bankzipcode: bankzipcode, accounttype: accounttype, accountnumber: accountnumber},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
//					var BankID = obj2;
					document.getElementById("newaccounttype").value = 'Checking';
					document.getElementById("newbankaddress").value = '';
					document.getElementById("newbankname").value = '';
					document.getElementById("newbankcity").value = '';
					document.getElementById("newbankstate").value = '';
					document.getElementById("newbankcountry").value = '';
					document.getElementById("newbankzip").value = '';
					document.getElementById("newaccountnumber").value = '';
					location.reload();
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	function updatebank(bankid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_bank.php", 
			data: {personid: personid, bankid: bankid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 
					for (var i = 0; i < obj2.length; i++) {	
						var BankID = obj2[i].BankID;
						var Account_Type = obj2[i].Account_Type;
						var Bank_Address = obj2[i].Bank_Address;
						var Bank_Name = obj2[i].Bank_Name;
						var Bank_City = obj2[i].Bank_City;
						var Bank_State = obj2[i].Bank_State;
						var Bank_Country = obj2[i].Bank_Country;
						var Bank_ZipCode = obj2[i].Bank_ZipCode;
						var Account_Number = obj2[i].Account_Number;
			    	}
					document.getElementById("dlgaccounttype").value = Account_Type;
					document.getElementById("dlgbankid").value = BankID;
					document.getElementById("dlgbankaddress").value = Bank_Address;
					document.getElementById("dlgbankname").value = Bank_Name;
					document.getElementById("dlgbankcity").value = Bank_City;
					document.getElementById("dlgbankstate").value = Bank_State;
					document.getElementById("dlgbankcountry").value = Bank_Country;
					document.getElementById("dlgbankzip").value = Bank_ZipCode;
					document.getElementById("dlgaccountnumber").value = 'xxxxxxxxxxxx';
					document.getElementById("dlgaccounthidden").value = Account_Number;

					$( "#Bank_dialog" ).dialog( "option", "title", "Edit Bank Information");	
					$( "#Bank_dialog" ).dialog( "option", "modal", true );
					$( "#Bank_dialog" ).dialog( "option", "width", 700 );
					$( "#Bank_dialog" ).dialog( "open" );
				} else {
					alert('No Bank Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
 	$("#save_bank").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var bankid = document.getElementById("dlgbankid").value;		
 		if (document.getElementById("dlgbankname").value > '') {
			var bankname = document.getElementById("dlgbankname").value;
		} else {		
			document.ALCATEL.dlgbankname.focus();
			alert("Bank name is required");
			return;
		}	
		if (document.getElementById("dlgbankaddress").value > '') {
			var bankaddress = document.getElementById("dlgbankaddress").value;
		} else {		
			document.ALCATEL.dlgbankaddress.focus();
			alert("Bank street is required");
			return;
		}	
			
		if (document.getElementById("dlgbankcity").value > '') {
			var bankcity = document.getElementById("dlgbankcity").value;
		} else {		
			document.ALCATEL.dlgbankcity.focus();
			alert("Bank city is required");
			return;
		}	
			
		if (document.getElementById("dlgbankstate").value == '' && document.getElementById("dlgbankcountry").value == '' ) {
			document.ALCATEL.dlgbankstate.focus();
			alert("Bank State or Country is required");
			return;
		} else {		
			var bankstate = document.getElementById("dlgbankstate").value;
			var bankcountry = document.getElementById("dlgbankcountry").value;
		}	
			
		if (document.getElementById("dlgbankzip").value > '') {
			var bankzipcode = document.getElementById("dlgbankzip").value;
		} else {		
			document.ALCATEL.dlgbankzip.focus();
			alert("Bank Zip Code is required");
			return;
		}	
		
		var accounttype = document.getElementById("dlgaccounttype").value;
		var accounthidden = document.getElementById("dlgaccounthidden").value;
		var accountnumber = document.getElementById("dlgaccountnumber").value;
		
		if (accountnumber.indexOf('x') > -1 || accountnumber == '') {
			var accountnumber = accounthidden;
		} 
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_bank.php", 
			data: {personid: personid, bankid: bankid, bankname: bankname, bankaddress: bankaddress, bankcity: bankcity, bankstate: bankstate, bankcountry: bankcountry, bankzipcode: bankzipcode, accounttype: accounttype, accountnumber: accountnumber},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Bank_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deletebank(bankid) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this bank?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_bank.php", 
				data: {personid: personid, bankid: bankid},
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
	
	$( "#close_bank" ).click(function() {	
		$( "#Bank_dialog" ).dialog( "close" );
	});
</script>	

<script language="JavaScript" type="text/javascript">
var frmvalidator = new Validator("ALCATEL");
frmvalidator.setAddnlValidationFunction("DoCustomValidation");

function DoCustomValidation() {
	var bankid = document.getElementById("BankID").value;
//	alert('Number of Days: '+nodays);
	if (bankid == 0) {
		document.ALCATEL.newbankname.focus();
		alert('You have not entered at least one bank account');
		return false;
	} else {
		return true;		
	}
}	
</script>