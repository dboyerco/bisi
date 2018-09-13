<?
$PersonID = $_REQUEST['PersonID'];
$page = $_REQUEST['page'];

require_once('../pdotriton.php');
$page++;
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Next_Page = $dbo->query("Select Web_App_Name from WebApp_Web_Pages where Company_Name = '".$compname."' and Package_Name = '".$Package."' and Web_Page_Number = ".$page.";")->fetchColumn();	
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
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
<?php
$sql = "Select * from WebApp_Address_Flds where Company_Name = :Company_Name and Package_Name = :Package_Name;";
$result3 = $dbo->prepare($sql);
$result3->bindValue(':Company_Name', $compname);
$result3->bindValue(':Package_Name', $Package);
if (!$result3->execute()) {
} else {
	$row=$result3->fetch(PDO::FETCH_BOTH);	
	$LimitEntriesBy = $row['LimitEntriesBy'];
	$LimitEntries = $row['LimitEntries'];
	$Display_Msg = $row['Display_Msg'];
	$FLD1 = $row['FLD1'];
	$FLD1_Visible = $row['FLD1_Visible'];
	$FLD1_Required = $row['FLD1_Required'];
	$FLD2 = $row['FLD2'];
	$FLD2_Visible = $row['FLD2_Visible'];
	$FLD2_Required = $row['FLD2_Required'];
	$FLD3 = $row['FLD3'];
	$FLD3_Visible = $row['FLD3_Visible'];
	$FLD3_Required = $row['FLD3_Required'];
	$FLD4 = $row['FLD4'];
	$FLD4_Visible = $row['FLD4_Visible'];
	$FLD4_Required = $row['FLD4_Required'];
	$FLD5 = $row['FLD5'];
	$FLD5_Visible = $row['FLD5_Visible'];
	$FLD5_Required = $row['FLD5_Required'];
	$FLD6 = $row['FLD6'];
	$FLD6_Visible = $row['FLD6_Visible'];
	$FLD6_Required = $row['FLD6_Required'];
	$FLD7 = $row['FLD7'];
	$FLD7_Visible = $row['FLD7_Visible'];
	$FLD7_Required = $row['FLD7_Required'];
	$FLD8 = $row['FLD8'];
	$FLD8_Visible = $row['FLD8_Visible'];
	$FLD8_Required = $row['FLD8_Required'];
	$FLD9 = $row['FLD9'];
	$FLD9_Visible = $row['FLD9_Visible'];
	$FLD9_Required = $row['FLD9_Required'];
}
?>	
<table width="763" bgcolor="#E4E8E8">
	<tr>
		<td>
			<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Address Information</strong> </font></p>
		</td>
	</tr>	
	<tr> 
		<td colspan="2"> 
			<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $Display_Msg; ?></font>
		</td>
	</tr>
	<tr> 
		<td colspan="2"> </td>
	</tr>
</table>	
  
<?php  
	$currentaddress = 'N';

	$maxAddrID = $dbo->query("Select max(AddrID) from App_Address where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxAddrID > 0) { 
		$selectaddr="select AddrID, Addr1, Apt, City, State_addr, StateOther, County, ZipCode, FromDate, ToDate, Current_Address from App_Address where PersonID = :PersonID;";
		$addr_result = $dbo->prepare($selectaddr);
		$addr_result->bindValue(':PersonID', $PersonID);
		$addr_result->execute();
		while($row = $addr_result->fetch(PDO::FETCH_BOTH)) {		
			if ($row[8] == '1900-01-01') {
				$fromdate = '';
			} else {
				$fromdate = date("m/d/Y", strtotime($row[8]));
			}
			if ($row[9] == '1900-01-01') {
				$todate = '';
			} else {
				$todate = date("m/d/Y", strtotime($row[9]));
			}
#			$fd = new DateTime($fromdate);
#			$td = new DateTime($todate);
#			$diff = $fd->diff($td);
#			echo $diff->y . ' year(s) ' . $diff->m . ' month(s) '. $diff->d . ' day(s)<br />';
			if ($fromdate != '' && $todate != '') { 
				$datediff = strtotime($todate) - strtotime($fromdate);
				$days = $days + floor($datediff / (60 * 60 * 24));
			}
			echo '<table width="763" id="addrtbl" bgcolor="#E4E8E8">';

			if ($row[10] == 'Y') {
				$currentaddress = $row[10];
				echo '<tr><td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br><strong>Current Address</strong></font></td>
				</tr>';  
			}
			echo '<tr><td width="140"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.htmlspecialchars($row[1]);
			if ($row[2] > '') {
				echo '&nbsp;&nbsp;&nbsp;Apt:&nbsp;'.htmlspecialchars($row[2]);
			}	
			echo'</font></td>
			<td width="50"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[3]).'</font></td>';
			if ($row[5] > '') {
				echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[5]).'</font></td>';
			} else {	
				echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[4]).'</font></td>';
			}	
			echo '<td width="50"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[6]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[7]).'</font></td>
			<td width="100"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.htmlspecialchars($fromdate).'&nbsp;&nbsp;-&nbsp;&nbsp;'.htmlspecialchars($todate).'</font></td>
			<td width="60">
				<a http="#" onclick="updateaddr('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Address" title="Edit Address"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deleteaddr('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Address" title="Delete Address"/></a>
				</td>
			</tr>
			</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>
			<table width="763" id="addrtbl2" bgcolor="#E4E8E8">';
		}	
		echo '</table>';
		if ($days > 0){
			$YR = floor($days / 365);
			$MO = floor(($days - (floor($days / 365) * 365)) / 30);
			$DY = $days - (($YR * 365) + ($MO * 30));
		} else {
			$YR = 0;
			$MO = 0;
			$DY = 0;
		}	
	}
	
#if ($maxAddrID == 0) { 
		
	echo '<fieldset><legend><strong>Add Address</strong></legend>
		<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>You have entered '.$YR.' years '.$MO.' months '.$DY.' days</td>
		</tr>';
	if ($currentaddress == 'N') {	
		echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Current Address<font color="#FF0000">*</font></font></td>
			<td>
				<select name="newcurrent" id="newcurrent" onchange="setdate()">
					<option VALUE="N">No</OPTION>
					<option value="Y">Yes</option>
				</select>
			</td>
		</tr>';
	}
	echo '<tr> 
		<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Street<font color="#FF0000">*</font></font></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newaddr1" id="newaddr1" size="20" maxlength="100" placeholder="Required">
					&nbsp;&nbsp;&nbsp;Apt &nbsp; 
				<input name="newapt" id="newapt" size="5" maxlength="9" value="">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">City<font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newcity" id="newcity" size="20" maxlength="40" placeholder="Required">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">State<font color="#FF0000">*</font></font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newstate" id="newstate" onchange="loadcounties(\'newstate\',\'\')">
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
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Country</font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
			<select name="newstateother" id="newstateother">
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
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">County<font color="#FF0000">*</font></font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
			<select name="newcounty" id="newcounty">
				<option value="">Select a County</option>';	
		echo '</select></span>
		</td>
	</tr>';
	echo '<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Zip Code<font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
			<input name="newzip" id="newzip" size="10" maxlength="10">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">From Date<font color="#FF0000">*</font></font></td>
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newfromdate" id="newfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')" >
			</font>
		</td>	
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">To Date<font color="#FF0000">*</font></font></td>
		<td nowrap>
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newtodate" id="newtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
			</font>
		</td>
	</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_address" VALUE="Save Address" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	</table></fieldset>';
#}	
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
	  <INPUT TYPE=\"hidden\" NAME=\"AddrID\" ID=\"AddrID\" VALUE=\"$maxAddrID\">
	  <INPUT TYPE=\"hidden\" name=\"Current\" id=\"Current\" VALUE=\"$currentaddress\">
	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";
?>
	<div name="Address_dialog" id="Address_dialog" title="Dialog Title">
		<div>
			<br/>
			<input type="hidden" name="dlgaddrid" id="dlgaddrid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Current Address</font></td>
					<td>
					    <select name="dlgcurrent" id="dlgcurrent">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
					</td>
				</tr>
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Street</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgaddr1" id="dlgaddr1" size="20" maxlength="100">
								&nbsp;&nbsp;&nbsp;Apt &nbsp; 
							<input name="dlgapt" id="dlgapt" size="5" maxlength="9" value="">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">City</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgcity" id="dlgcity" size="20" maxlength="40" >
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">State</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgstate" id="dlgstate" onchange="loadcounties('dlgstate','')">
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
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> OR If address is out of the US, please select the Country</font></td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Country</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgstateother" id="dlgstateother">
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
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">County</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgcounty" id="dlgcounty">
							<option value="">Select a County</option>
						</select></span>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip Code</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgzip" id="dlgzip" size="10" maxlength="10">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">From Date</font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgfromdate" id="dlgfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
							onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>	
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">To Date</font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgtodate" id="dlgtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
							onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>
				</tr>
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_address" VALUE="Save Address">
						<INPUT TYPE="button" id="close_address" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>
<script language="JavaScript" type="text/javascript">
	$( "#Address_dialog" ).dialog({ autoOpen: false });

 	$( "#add_new_address" ).click(function() {	
		var personid = document.getElementById("PersonID").value;
//		alert('Current: '+document.getElementById("Current").value);
		if (document.getElementById("Current").value == 'N') {
			var current_address = document.getElementById("newcurrent").value;
		} else {
			var current_address = 'N';
		}
		if (document.getElementById("newaddr1").value > '') {
			var addr1 = document.getElementById("newaddr1").value;
		} else {		
			$("#newaddr1").focus();
			alert("Street is required");
			return;
		}	
			
		if (document.getElementById("newapt").value > '') {
			var apt = document.getElementById("newapt").value;
		} else {		
			var apt = '';
		}						

		if (document.getElementById("newcity").value > '') {
			var city = document.getElementById("newcity").value;
		} else {		
			$("#newcity").focus();
			alert("City is required");
			return;
		}	
			
		if (document.getElementById("newstate").value == '' && document.getElementById("newstateother").value == '' ) {
			$("#newstate").focus();
			alert("State or Country is required");
			return;
		} else {		
			var state = document.getElementById("newstate").value;
			var stateother = document.getElementById("newstateother").value;
		}	

		if (document.getElementById("newstate").value != '') {
			if (document.getElementById("newcounty").value > '') {
				var county = document.getElementById("newcounty").value;
			} else {		
				$("#newcounty").focus();
				alert("County is required");
				return;
			}	
		}
		
		if (document.getElementById("newzip").value > '') {
			var zipcode = document.getElementById("newzip").value;
		} else {		
			$("#newzip").focus();
			alert("Postal Code is required");
			return;
		}	
		
		if (document.getElementById("newfromdate").value > '') {
			var fromdate = document.getElementById("newfromdate").value;
		} else {		
			$("#newfromdate").focus();
			alert("From Date is required");
			return;
		}	
		
		if (document.getElementById("newtodate").value > '') {
			var todate = document.getElementById("newtodate").value;
		} else {		
			$("#newtodate").focus();
			alert("To Date is required");
			return;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_address.php", 
			data: {personid: personid, addr1: addr1, apt: apt, city: city, state: state, stateother: stateother, zipcode: zipcode, fromdate: fromdate, todate: todate, current_address: current_address, county: county},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var AddrID = obj2;
					if (document.getElementById("Current").value == 'N') {
						document.getElementById("newcurrent").value = 'N';
					}
					document.getElementById("newaddr1").value = '';
					document.getElementById("newapt").value = '';
					document.getElementById("newcity").value = '';
					document.getElementById("newstate").value = '';
					document.getElementById("newstateother").value = '';
					document.getElementById("newcounty").value = '';
					document.getElementById("newzip").value = '';
					document.getElementById("newfromdate").value = '';
					document.getElementById("newtodate").value = '';
					location.reload();
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	function loadcounties(ddl,county) {
		if (ddl == 'newstate') {
			st = document.getElementById("newstate").value;
		} else {	
			st = document.getElementById("dlgstate").value;
		}
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_load_counties.php", 
			data: {st: st},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 
					if (ddl == 'newstate') {
						$('#newcounty').find('option').remove();
						$('#newcounty').append('<option value="">Select a County</option>');
					} else {			
						$('#dlgcounty').find('option').remove();
						$('#dlgcounty').append('<option value="">Select a County</option>');
					}
					for (var i = 0; i < obj2.length; i++) {	
						var County_Name = obj2[i].County_Name;
						if (ddl == 'newstate') {
							$('#newcounty').append('<option value="' + County_Name + '">' + County_Name + '</option>');	
						} else {	
							$('#dlgcounty').append('<option value="' + County_Name + '">' + County_Name + '</option>');	
						}
					}	
					document.getElementById("dlgcounty").value = county;
				} else {
					alert('No Counties Data Found for State Selected');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 		
	}	
	function setdate() {
		if (document.getElementById("newcurrent").value == 'Y') {
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1;
			var yyyy = today.getFullYear();
			if(dd<10) {dd='0'+dd}; 
			if(mm<10) {mm='0'+mm}; 
			today = mm+'/'+dd+'/'+yyyy;	
			document.getElementById("newtodate").placeholder = '';	
			document.getElementById("newtodate").value = today;
		}			
	}
	function updateaddr(addrid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_address.php", 
			data: {personid: personid, addrid: addrid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 
					for (var i = 0; i < obj2.length; i++) {	
						var AddrID = obj2[i].AddrID;
						var Current_Address = obj2[i].Current_Address;
						var Addr1 = obj2[i].Addr1;
						var Apt = obj2[i].Apt;
						var City = obj2[i].City;
						var State = obj2[i].State_addr;
						var StateOther = obj2[i].StateOther;
						var County = obj2[i].County;
						var ZipCode = obj2[i].ZipCode;
						var fd = obj2[i].FromDate;
						var DateFrom = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var td = obj2[i].ToDate;
						var DateTo = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
			    	}
					document.getElementById("dlgcurrent").value = Current_Address;
					document.getElementById("dlgaddrid").value = AddrID;
					document.getElementById("dlgaddr1").value = Addr1;
					document.getElementById("dlgapt").value = Apt;
					document.getElementById("dlgcity").value = City;
					document.getElementById("dlgstate").value = State;
					document.getElementById("dlgstateother").value = StateOther;
					document.getElementById("dlgzip").value = ZipCode;
					document.getElementById("dlgfromdate").value = DateFrom;
					document.getElementById("dlgtodate").value = DateTo;
					loadcounties("dlgstate",County);
//					document.getElementById("dlgcounty").value = County;

					$( "#Address_dialog" ).dialog( "option", "title", "Edit Address");	
					$( "#Address_dialog" ).dialog( "option", "modal", true );
					$( "#Address_dialog" ).dialog( "option", "width", 700 );
					$( "#Address_dialog" ).dialog( "open" );
				} else {
					alert('No Address Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
 	$("#save_address").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var addrid = document.getElementById("dlgaddrid").value;
		var current_address = document.getElementById("dlgcurrent").value;
	
		if (document.getElementById("dlgaddr1").value > '') {
			var addr1 = document.getElementById("dlgaddr1").value;
		} else {		
			$("#dlgaddr1").focus();
			alert("Street is required");
			return;
		}	
		if (document.getElementById("dlgapt").value > '') {
			var apt = document.getElementById("dlgapt").value;
		} else {		
			var apt = '';
		}						

		if (document.getElementById("dlgcity").value > '') {
			var city = document.getElementById("dlgcity").value;
		} else {		
			$("#dlgcity").focus();
			alert("City is required");
			return;
		}	
			
		if (document.getElementById("dlgstate").value == '' && document.getElementById("dlgstateother").value == '' ) {
			$("#dlgstate").focus();
			alert("State or Country is required");
			return;
		} else {		
			var state = document.getElementById("dlgstate").value;
			var stateother = document.getElementById("dlgstateother").value;
		}	
		if (document.getElementById("dlgcounty").value > '') {
			var county = document.getElementById("dlgcounty").value;
		} else {		
			alert("County is required");
			$("#dlgcounty").focus();
			return;
		}	
		
		if (document.getElementById("dlgzip").value > '') {
			var zipcode = document.getElementById("dlgzip").value;
		} else {		
			$("#dlgzip").focus();
			alert("Postal Code is required");
			return;
		}	
		
		if (document.getElementById("dlgfromdate").value > '') {
			var fromdate = document.getElementById("dlgfromdate").value;
		} else {		
			$("#dlgfromdate").focus();
			alert("From Date is required");
			return;
		}	
		
		if (document.getElementById("dlgtodate").value > '') {
			var todate = document.getElementById("dlgtodate").value;
		} else {		
			$("#dlgtodate").focus();
			alert("To Date is required");
			return;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_address.php", 
			data: {personid: personid, addrid: addrid, addr1: addr1, apt: apt, city: city, state: state, stateother: stateother, zipcode: zipcode, fromdate: fromdate, todate: todate, current_address: current_address, county: county},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Address_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deleteaddr(AddrID) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this address?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_address.php", 
				data: {personid: personid, AddrID: AddrID},
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
	
	$( "#close_address" ).click(function() {	
		$( "#Address_dialog" ).dialog( "close" );
	});
</script>	

<script language="JavaScript" type="text/javascript">
var frmvalidator = new Validator("ALCATEL");
frmvalidator.setAddnlValidationFunction("DoCustomValidation");

function DoCustomValidation() {

	var addrid = document.getElementById("AddrID").value;
	var nodays = document.getElementById("days").value;
// 7 years	if (nodays < 2555) {	
// 10 yesrs	if (nodays < 3650) {	
	
	
//	alert('Number of Days: '+nodays);
	if (addrid == 0) {
//		alert('You have not entered at least 7 years of address information');
		alert('You have not entered current address information');
		return false;
	} else {
		return true;		
	}
}	
</script>