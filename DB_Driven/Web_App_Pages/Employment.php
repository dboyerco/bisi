<?
require_once('../pdotriton.php');
$page++;
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
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

<body bgcolor="#E4E8E8">

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
				
<?	
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
echo '<table bgcolor="#E4E8E8" width="763">
		<tr>
			<td>
				<p><font size="2"><strong>Employment History</strong> </font></p>
			</td>
		</tr>
	</table>			
	<table width="763" bgcolor="#E4E8E8">';

	if($Package == 'water') {
		echo '<tr><td><font size="2">Please provide your and current and most recent employment for the past 7 years. Please be thorough and include any gaps of employment more than 3 months such as period of non-employment, full-time student, etc.
		</font></td></tr><tr><td>&nbsp;</td></tr>';
	} else {
		echo '<tr><td><font size="2">Please provide your current or most recent employment history.
		</font></td></tr><tr><td>&nbsp;</td></tr></table>';
	}
	
	$currentEmployer = 'N';
	$maxEmpID = $dbo->query("Select max(EmpID) from App_Employment where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxEmpID > 0) { 
		$selectemp="select EmpID, EmpName, EmpCity, EmpState, EmpStateOther, EmpStreet, EmpZip, EmpDateFrom, EmpDateTo, EmpSupervisor, EmpReasonForLeaving, EmpTitle, EmpPhone, EmpSupervisorPhone, EmpSupervisorEmail, EmpMayWeContact, EmpCurrent, EmpDotReg, EmpDotTst from App_Employment where PersonID = :PersonID;";
			
		$emp_result = $dbo->prepare($selectemp);
		$emp_result->bindValue(':PersonID', $PersonID);
		$emp_result->execute();
		while($row = $emp_result->fetch(PDO::FETCH_BOTH)) {		
			if ($row[16] == 'Y') {
				$currentEmployer = 'Y';
				echo'<table width="763" bgcolor="#E4E8E8"><tr>
					<td width="240" valign="top" align="left"><font size="1"><strong> Current Employment. </strong></font></td></tr>
				<tr>			
					<td width="240" valign="top" align="left"><font size="1"><strong> May we contact your current employer?</strong>&nbsp;&nbsp;';
					if ($row[15] == 'Y') {					
						echo '<td>Yes';
					} else {	
						echo '<td>No';
					}	
			echo '</font></td></tr></table>';
			}	
		echo '<table width="763" bgcolor="#E4E8E8">
			<tr valign="top"> 
				<td width="15%"><font size="1">Company Name:&nbsp;</font></td>
				<td width="30%">
					<font size="1"> 
						<input name="empname" value="'.htmlspecialchars($row[1]).'" class="nobord" readonly>
					</font>
				</td>
				<td width="30%">
					<font size="1"> 
						'.htmlspecialchars($row[5]).'<br />
						'.htmlspecialchars($row[2]).',&nbsp;';
					if ($row[4] > '') {
						echo htmlspecialchars($row[4]);	
					} else {
						echo htmlspecialchars($row[3]);
					}
				echo '</font>
				</td>			
			</tr>';
				if ($row[7] == '1900-01-01') {
					$fromdate = '';
				} else {
					$fromdate = date("m/d/Y", strtotime($row[7]));
				}
				if ($row[8] == '1900-01-01') {
					$todate = '';
				} else {
					$todate = date("m/d/Y", strtotime($row[8]));
				}
				if ($fromdate != '' && $todate != '') { 
					$datediff = strtotime($todate) - strtotime($fromdate);
					$days = $days + floor($datediff / (60 * 60 * 24));
				}

		echo '<tr valign="top"> 
			<td><font size="1">Dates:</font></td>
			<td>	
				<font size="1">
					'.htmlspecialchars($fromdate).' - '.htmlspecialchars($todate).' 
				</font>
			</td>
		</tr>
		<tr> 
			<td><font size="1">Position:</font></td>
			<td>
				<font size="1"> 
					<input name="empposition" value="'.htmlspecialchars($row[11]).'" class="nobord" readonly>
				</font>
			</td>
			<td><font size="1">Supervisor:&nbsp;&nbsp;'.htmlspecialchars($row[9]).'</font>
			</td>
		</tr>
		<tr> 
			<td><font size="1">Phone:</font></td>
			<td>
				<font size="1"> 
					<input name="empphone" value="'.htmlspecialchars($row[12]).'" class="nobord" readonly>
				</font>
			</td>
			<td><font size="1">Supervisor Phone:&nbsp;&nbsp;'.htmlspecialchars($row[13]).'</font>
			</td>
		</tr>
		<tr> 
			<td><font size="1">Reason for Leaving:</font></td>
			<td>
				<font size="1"> 
					<input name="empreason" value="'.htmlspecialchars($row[10]).'" class="nobord" readonly>
				</font>
			</td>
			<td><font size="1">Supervisor Email:&nbsp;&nbsp;'.htmlspecialchars($row[14]).'</font>
			</td>
		</tr></table>';
	if ($Package == "mountain") {
		$dotreg = 'No';
		$dottst = 'No';
		if ($row[17] == 'Y') {$dotreg = 'Yes';}
		if ($row[18] == 'Y') {$dottst = 'Yes';}
			
		echo "<table width='763' bgcolor='#E4E8E8'><tr>      
			<td valign='top' width='60%'>
				<font size='1'>FMCSA or PHMSA Safety Regulations:&nbsp;&nbsp;".$dotreg."</font>   
			</td>
			<td valign='top' width='40%'>
				<font size='1'>Designated as safety sensitive: &nbsp;&nbsp;".$dottst." </font>
			</td>
		</tr></table>";
	}
	echo '<table width="763" bgcolor="#E4E8E8"><tr>	
			<td>&nbsp;</td>
			<td align="center">
				<a http="#" onclick="updateemp('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Employment" title="Edit Employment"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deleteemp('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Employment" title="Delete Employment"/></a>
			</td>
			<td>&nbsp;</td>			
		</tr>';

		echo '</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>';
		}
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
echo '<fieldset><legend><strong>Add Employment</strong></legend>
	<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1">* Denotes Required Field</font></td>
			<td>You have entered '.$YR.' years '.$MO.' months '.$DY.' days</td>
		</tr>';
	if ($currentEmployer == 'N') {	
		echo '<tr> 
			<td valign="top"> 
				<font size="1"><strong> Current employer?</strong><strong><font color="FF0000">*</font></strong></font>
			</td>
			<td width="351"> <font size="1"> 
				<select name="newempcurrent" id="newempcurrent">
					<option VALUE="N">No</OPTION>
					<option value="Y">Yes</option>
				</select>
			</td>
		</tr>		
		<tr> 
			<td valign="top" nowrap> 
				<font size="1"><strong> May we contact your current employer?</strong><strong><font color="FF0000">*</font></strong></font>
			</td>
			<td width="351"> <font size="1"> 
				<select name="newempcontact" id="newempcontact">
					<option VALUE="N">No</OPTION>
					<option value="Y">Yes</option>
				</select>
			</td>
		</tr>';
	}
	echo '</table>	
	<table width="100%" bgcolor="#E4E8E8">
	
		<tr valign="top"> 
			<td width="160"><font size="1">Company Name<font color="FF0000">*</font></font></td>
			<td>
				<font size="1"> 
					<input name="newempname" id="newempname" value="" size="25" maxlength="40">
				</font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1">Address<font color="FF0000">*</font></font></td>
			<td><font size="1"> 
				<input name="newempstreet" id="newempstreet" value="" size="20" maxlength="40">&nbsp;&nbsp;</font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1">City<font color="FF0000">*</font></font></td>
			<td><font size="1"> 
				<input name="newempcity" id="newempcity" value="" size="20" maxlength="40"></font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1">State<font color="FF0000">*</font></font></td>
			<td> <font size="1"> 
				<select name="newempstate" id="newempstate">
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
			<td><font size="1"> OR If Employment was out of the US, please select the Country</font></td>
		</tr>';
echo '<tr>
		<td width="160"><font size="1">Country</font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
			<select name="newempstateother" id="newempstateother">
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
		<td width="160"><font size="1">Phone<font color="FF0000">*</font></font></td>
		<td><font size="1"> 
			<input text="number" name="newempphone" id="newempphone" value="" size="30" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')"></font>
		</td>
	</tr>';
echo '<tr valign="top"> 
		<td width="160"><font size="1">From Date<font color="FF0000">*</font></font></td>
		<td>
			<font size="1"> 
				<input name="newempfromdate" id="newempfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
			</font>
		</td>
	</tr>
	<tr> 
		<td width="160"><font size="1">To Date<font color="FF0000">*</font></font></td>
		<td>
			<font size="1"> 
				<input name="newemptodate" id="newemptodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
			</font>
		</td>
	</tr>
	<tr> 
		<td width="160"><font size="1">Position<font color="FF0000">*</font></font></td>
		<td><font size="1"> 
			<input name="newemptitle" id="newemptitle" value="" size="20" maxlength="40"></font>
		</td>
	</tr>
	<tr> 
		<td width="160"><font size="1">Supervisor<font color="FF0000">*</font></font></td>
		<td><font size="1"> 
			<input name="newempsuper" id="newempsuper" value="" size="20" maxlength="40"></font>
		</td>
	</tr>
	<tr> 
		<td><font size="1">Supervisor Phone</font></td>
		<td nowrap>
			<font size="1"> 
				<input text="number" name="newsphone" id="newsphone" size="30" placeholder="### ### #### #####" 
				onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">  
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1">Supervisor Email</font></td>
		<td nowrap>
			<font size="1"> 
				<input name="newsemail" id="newsemail" size="40" maxlength="40">  
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1">Reason for leaving</font></td>
		<td nowrap>
			<font size="1"> 
				<input name="newreason" id="newreason" size="40" maxlength="40">  
			</font>
		</td>
	</tr></table>';
if ($Package == "mountain") {
	echo "<table  width='100%' bgcolor='#E4E8E8'><tr>      
			<td valign='top' width='200'>
				<font size='1'>
					Were you subject to FMCSA or PHMSA Safety Regulations while employed? <strong><font color='FF0000' size='2'>*</font></strong>
				</font>   
			</td>
			<td>
				<font size='1'>
					<select name='newempdotreg' id='newempdotreg'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr>
		<tr>
			<td>
				<font size='1'>
					Was this job designated as a safety sensitive function in any DOT regulated mode and therefore subject to alcohol and controlled substances testing requirements? 
					<strong><font color='FF0000' size='2'>*</font></strong>
				</font>
			</td>
			<td>
				<font size='1'>
					<select name='newempdottst' id='newempdottst'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr></table>";
	}
	echo '<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
				<INPUT TYPE="button" id="add_new_employment" VALUE="Save Employment" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
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
echo "<INPUT TYPE=\"hidden\" name=\"PersonID\" id=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" name=\"EmpID\" id=\"EmpID\" VALUE=\"$maxEmpID\">
	  <INPUT TYPE=\"hidden\" name=\"Current\" id=\"Current\" VALUE=\"$currentEmployer\">
	  <INPUT TYPE=\"hidden\" name=\"Package\" id=\"Package\" VALUE=\"$Package\">";
#	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";

?>
		<div name="Employment_dialog" id="Employment_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgempid" id="dlgempid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr>
					<td><font size="2">Current Employment</font></td>
					<td>
					    <select name="dlgcurrent" id="dlgcurrent">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
					</td>
				</tr>
				<tr> 
					<td width="160" valign="top"> 
						<font size="1"><strong> May we contact your current employer?</strong></font>
					</td>
					<td width="351"> <font size="1"> 
						<select name="dlgcontact" id="dlgcontact">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
					</td>
				</tr>		
				<tr> 
					<td width="160"><font size="2">Company Name</font></td>
					<td width="351">
						<font size="2"> 
							<input type="text" name="dlgempname" id="dlgempname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td width="160"><font size="2">Address</font></td>
					<td width="351">
						<font size="2"> 
							<input type="text" name="dlgaddr" id="dlgaddr" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">City</font></td>
					<td><font size="2"> 
							<input type="text" name="dlgcity" id="dlgcity" size="20" maxlength="40" >
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">State</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgstate" id="dlgstate">
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
					<td><font size="2"> OR If address is out of the US, please select the Country</font></td>
				</tr>
				<tr>
					<td><font size="2">Country</font></td>
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
					<td><font size="2">Phone</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgphone" id="dlgphone" size="30" placeholder="### ### #### #####" 
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')">  
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">From Date</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgfromdate" id="dlgfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>	
				</tr>
				<tr> 
					<td><font size="2">To Date</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgtodate" id="dlgtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">Position</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgtitle" id="dlgtitle" size="40" maxlength="40">  
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">Supervisor</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgsuper" id="dlgsuper" size="40" maxlength="40">  
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">Supervisor Phone</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgsphone" id="dlgsphone" size="30" placeholder="### ### #### #####" 
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')">  
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">Supervisor Email</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgsemail" id="dlgsemail" size="40" maxlength="40">  
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">Reason for leaving</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgreason" id="dlgreason" size="40" maxlength="40">  
						</font>
					</td>
				</tr>
			</table>	
<?php
if ($Package == "mountain") {
	echo "<table width='100%' align='left' border='3' bgcolor='#eeeeee'><tr>      
			<td valign='top' width='200'>
				<font size='1'>
					Were you subject to FMCSA or PHMSA Safety Regulations while employed? <strong><font color='FF0000' size='2'>*</font></strong>
				</font>   
			</td>
			<td>
				<font size='1'>
					<select name='dlgempdotreg' id='dlgempdotreg'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr>
		<tr>
			<td>
				<font size='1'>
					Was this job designated as a safety sensitive function in any DOT regulated mode and therefore subject to alcohol and controlled substances testing requirements? 
					<strong><font color='FF0000' size='2'>*</font></strong>
				</font>
			</td>
			<td>
				<font size='1'>
					<select name='dlgempdottst' id='dlgempdottst'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr></table>";
	}
?>				
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_employment" VALUE="Save Employment">
						<INPUT TYPE="button" id="close_employment" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#Employment_dialog" ).dialog({ autoOpen: false });
 	$( "#add_new_employment" ).click(function() {	
		var personid = document.getElementById("PersonID").value;
		if (document.getElementById("Current").value == 'Y') {
			var empcontact = '';
			var empcurrent = 'N';
		} else {	
			var empcontact = document.getElementById("newempcontact").value;
			var empcurrent = document.getElementById("newempcurrent").value;
		}	
		if (document.getElementById("newempname").value > '') {
			var empname = document.getElementById("newempname").value;
		} else {		
			document.ALCATEL.newempname.focus();
			alert("Company Name is required");
			return;
		}	
			
		if (document.getElementById("newempstreet").value > '') {
			var empstreet = document.getElementById("newempstreet").value;
		} else {		
			document.ALCATEL.newempstreet.focus();
			alert("Address is required");
			return;
		}	
			
		if (document.getElementById("newempcity").value > '') {
			var empcity = document.getElementById("newempcity").value;
		} else {		
			document.ALCATEL.newempcity.focus();
			alert("City is required");
			return;
		}	
			
		if (document.getElementById("newempstate").value == '' && document.getElementById("newempstateother").value == '' ) {
			document.ALCATEL.newempstate.focus();
			alert("State or Country is required");
			return;
		} else {		
			var empstate = document.getElementById("newempstate").value;
			var empstateother = document.getElementById("newempstateother").value;
		}	
				
		if (document.getElementById("newempfromdate").value > '') {
			var empfromdate = document.getElementById("newempfromdate").value;
		} else {		
			document.ALCATEL.newempfromdate.focus();
			alert("From Date is required");
			return;
		}	
		
		if (document.getElementById("newemptodate").value > '') {
			var emptodate = document.getElementById("newemptodate").value;
		} else {		
			document.ALCATEL.newemptodate.focus();
			alert("To Date is required");
			return;
		}	
		if (document.getElementById("newemptitle").value > '') {
			var emptitle = document.getElementById("newemptitle").value;
		} else {		
			document.ALCATEL.newemptitle.focus();
			alert("Position is required");
			return;
		}	
		if (document.getElementById("newempsuper").value > '') {
			var empsuper = document.getElementById("newempsuper").value;
		} else {		
			document.ALCATEL.newempsuper.focus();
			alert("Supervisor is required");
			return;
		}	
		if (document.getElementById("newempphone").value > '') {
			var empphone = document.getElementById("newempphone").value;
		} else {		
			document.ALCATEL.newempphone.focus();
			alert("Phone is required");
			return;
		}	
		
		if (document.getElementById("newsphone").value > '') {
			var empsphone = document.getElementById("newsphone").value;
		} else {		
			var empsphone = '';		
		}	
		if (document.getElementById("newsemail").value > '') {
			var empsemail = document.getElementById("newsemail").value;
		} else {	
			var empsemail = '';		
		}	
		if (document.getElementById("newreason").value > '') {
			var empreason = document.getElementById("newreason").value;
		} else {	
			var empreason = '';		
		}	
		if (document.getElementById("Package").value == "mountain") {
			if (document.getElementById("newempdotreg").value > '') {
				var empdotreg = document.getElementById("newempdotreg").value;
			} else {	
				document.ALCATEL.newempdotreg.focus();
				alert("Subject to FMCS Regulations is required.");
				return;
			}
			if (document.getElementById("newempdottst").value > '') {
				var empdottst = document.getElementById("newempdottst").value;
			} else {	
				document.ALCATEL.newempdottst.focus();
				alert("Designated as safety sensitive is required.");
				return;
			}	
		} else {
			var empdotreg = '';
			var empdottst = '';
		}
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_employment.php", 
			data: {personid: personid, empcontact:empcontact, empcurrent:empcurrent, empname: empname, empstreet: empstreet, empcity: empcity, empstate: empstate, empstateother: empstateother, empfromdate: empfromdate, emptodate: emptodate, emptitle: emptitle, empsuper: empsuper, empphone: empphone, empsphone: empsphone, empsemail: empsemail, empreason: empreason, empdotreg: empdotreg, empdottst: empdottst},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EmpID = obj2;
					if (document.getElementById("Current").value == 'N') {
						document.getElementById("newempcontact").value = 'N';
						document.getElementById("newempcurrent").value = 'N';
					}
					document.getElementById("newempname").value = '';
					document.getElementById("newempstreet").value = '';
					document.getElementById("newempcity").value = '';
					document.getElementById("newempstate").value = '';
					document.getElementById("newempstateother").value = '';
					document.getElementById("newemptitle").value = '';
					document.getElementById("newempfromdate").value = '';
					document.getElementById("newemptodate").value = '';
					document.getElementById("newempsuper").value = '';
					document.getElementById("newempphone").value = '';
					if (document.getElementById("Package").value == "mountain") {
						document.getElementById("newempdotreg").value = '';
						document.getElementById("newempdottst").value = '';
					}	
					window.location.reload();					
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});

 	function updateemp(empid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_employment.php", 
			data: {personid: personid, empid: empid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var EmpID = obj2[i].EmpID;
						var EmpName = obj2[i].EmpName;
						var EmpStreet = obj2[i].EmpStreet;
						var EmpCity = obj2[i].EmpCity;
						var EmpState = obj2[i].EmpState;
						var EmpStateOther = obj2[i].EmpStateOther;
						var fd = obj2[i].EmpDateFrom;
						var EmpDateFrom = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var td = obj2[i].EmpDateTo;
						var EmpDateTo = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
						var EmpSupervisor = obj2[i].EmpSupervisor;
						var EmpReasonForLeaving = obj2[i].EmpReasonForLeaving;
						var EmpTitle = obj2[i].EmpTitle;
						var EmpPhone = obj2[i].EmpPhone;
						var EmpCurrent = obj2[i].EmpCurrent;
						var EmpMayWeContact = obj2[i].EmpMayWeContact;
						var EmpSupervisorPhone = obj2[i].EmpSupervisorPhone;
						var EmpSupervisorEmail = obj2[i].EmpSupervisorEmail;
						var EmpDotReg = obj2[i].EmpDotReg;
						var EmpDotTst = obj2[i].EmpDotTst;
			    	}
					document.getElementById("dlgempid").value = EmpID;
					document.getElementById("dlgcontact").value = EmpMayWeContact;
					document.getElementById("dlgcurrent").value = EmpCurrent;
					document.getElementById("dlgempname").value = EmpName;
					document.getElementById("dlgaddr").value = EmpStreet;
					document.getElementById("dlgcity").value = EmpCity;
					document.getElementById("dlgstate").value = EmpState;
					document.getElementById("dlgstateother").value = EmpStateOther;
					document.getElementById("dlgfromdate").value = EmpDateFrom;
					document.getElementById("dlgtodate").value = EmpDateTo;
					document.getElementById("dlgsuper").value = EmpSupervisor;
					document.getElementById("dlgreason").value = EmpReasonForLeaving;
					document.getElementById("dlgtitle").value = EmpTitle;
					document.getElementById("dlgphone").value = EmpPhone;
					document.getElementById("dlgsphone").value = EmpSupervisorPhone;
					document.getElementById("dlgsemail").value = EmpSupervisorEmail;
					if (document.getElementById("Package").value == 'mountain') {
						document.getElementById("dlgempdotreg").value = EmpDotReg;
						document.getElementById("dlgempdottst").value = EmpDotTst;
					}

					$( "#Employment_dialog" ).dialog( "option", "title", "Edit Employment");	
					$( "#Employment_dialog" ).dialog( "option", "modal", true );
					$( "#Employment_dialog" ).dialog( "option", "width", 700 );
					$( "#Employment_dialog" ).dialog( "open" );
				} else {
					alert('No Employment Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
 	$("#save_employment").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var empid = document.getElementById("dlgempid").value;
		var current_employment = document.getElementById("dlgcurrent").value;

		if (document.getElementById("dlgempname").value > '') {
			var empname = document.getElementById("dlgempname").value;
		} else {		
			document.ALCATEL.dlgempname.focus();
			alert("Company Name is required");
			return;
		}	
		contact = document.getElementById("dlgcontact").value;	
		
		if (document.getElementById("dlgaddr").value > '') {
			var addr = document.getElementById("dlgaddr").value;
		} else {		
			document.ALCATEL.dlgaddr.focus();
			alert("Street is required");
			return;
		}	

		if (document.getElementById("dlgcity").value > '') {
			var city = document.getElementById("dlgcity").value;
		} else {		
			document.ALCATEL.dlgcity.focus();
			alert("City is required");
			return;
		}	
			
		if (document.getElementById("dlgstate").value == '' && document.getElementById("dlgstateother").value == '' ) {
			document.ALCATEL.dlgstate.focus();
			alert("State or Country is required");
			return;
		} else {		
			var state = document.getElementById("dlgstate").value;
			var stateother = document.getElementById("dlgstateother").value;
		}	
		if (document.getElementById("dlgphone").value > '') {
			var phone = document.getElementById("dlgphone").value;
		} else {		
			document.ALCATEL.dlgphone.focus();
			alert("Phone is required");
			return;
		}	
		
		if (document.getElementById("dlgfromdate").value > '') {
			var fromdate = document.getElementById("dlgfromdate").value;
		} else {		
			document.ALCATEL.dlgfromdate.focus();
			alert("From Date is required");
			return;
		}	
		
		if (document.getElementById("dlgtodate").value > '') {
			var todate = document.getElementById("dlgtodate").value;
		} else {		
			document.ALCATEL.dlgtodate.focus();
			alert("To Date is required");
			return;
		}	

		if (document.getElementById("dlgtitle").value > '') {
			var position = document.getElementById("dlgtitle").value;
		} else {		
			document.ALCATEL.dlgtitle.focus();
			alert("Position is required");
			return;
		}	
		
		if (document.getElementById("dlgsuper").value > '') {
			var supervisor = document.getElementById("dlgsuper").value;
		} else {		
			document.ALCATEL.dlgsuper.focus();
			alert("Supervisor is required");
			return;
		}	
		if (document.getElementById("dlgsphone").value > '') {
			var sphone = document.getElementById("dlgsphone").value;
		} else {		
			var sphone = '';		
		}	
		if (document.getElementById("dlgsemail").value > '') {
			var semail = document.getElementById("dlgsemail").value;
		} else {	
			var semail = '';		
		}	
		if (document.getElementById("dlgreason").value > '') {
			var reason = document.getElementById("dlgreason").value;
		} else {	
			var reason = '';		
		}	
		if (document.getElementById("Package").value == "mountain") {
			var empdotreg = document.getElementById("dlgempdotreg").value;
			var empdottst = document.getElementById("dlgempdottst").value;
		} else {
			var empdotreg = '';
			var empdottst = '';
		}
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_employment.php", 
			data: {personid: personid, empid: empid, empname: empname, addr: addr, city: city, state: state, stateother: stateother, phone: phone, fromdate: fromdate, todate: todate, current_employment: current_employment, contact: contact, position: position, supervisor: supervisor, sphone: sphone, semail: semail, reason: reason, empdotreg: empdotreg, empdottst: empdottst},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Employment_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deleteemp(EmpID) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this employment record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_employment.php", 
				data: {personid: personid, EmpID: EmpID},
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
	$( "#close_employment" ).click(function() {	
		$( "#Employment_dialog" ).dialog( "close" );
	});
 
 
 </script>
 <script language="JavaScript" type="text/javascript">
 
 var frmvalidator = new Validator("ALCATEL");
//frmvalidator.setAddnlValidationFunction("DoCustomValidation");
/*
function DoCustomValidation() {
	var frm = document.forms["ALCATEL"];
  	if(frm.companyname2.value.trim() == 'N/A' || frm.companyname2.value.trim() == 'n/a' ) {
  	} else {
  		if(frm.companyname2.value.trim() == '') {
  			document.ALCATEL.companyname2.focus();
			alert('Company Name 2 is required.');
	    	return false;
		} 
  		if(frm.companyaddr2.value.trim() == '') {
  			document.ALCATEL.companyaddr2.focus();
			alert('Please enter the Street for Company 2.');
	    	return false;
		} 
  		if(frm.companycity2.value.trim() == '') {
  			document.ALCATEL.companycity2.focus();
			alert('Please enter the City for Company 2.');
	    	return false;
		} 
  		if(frm.companystate2.value.trim() == '') {
  			document.ALCATEL.companystate2.focus();
			alert('Please enter the State for Company 2.');
	   		return false;
		} 
		if (frm.companystate2.value.trim() == 'XX' && frm.companystateother2.value.trim() == '') {
			document.ALCATEL.companystateother2.focus();
			alert('Please select a Province/Country for Company 2');
	   		return false;
  		} 
  		if(frm.companyposition2.value.trim() == '') {
  			document.ALCATEL.companyposition2.focus();
			alert('Please enter the Position for Company 2.');
	   		return false;
		} 
	  	if(frm.companydatefrommonth2.value.trim() == '' || frm.companydatefromday2.value.trim() == '' || frm.companydatefromyear2.value.trim() == '') {
			if (frm.companydatefrommonth2.value.trim() == '') {
				document.ALCATEL.companydatefrommonth2.focus();
  			} else if (frm.companydatefromday2.value.trim() == '') {	
				document.ALCATEL.companydatefromday2.focus();
  			} else if (frm.companydatefromyear2.value.trim() == '') {	
				document.ALCATEL.companydatefromyear2.focus();
			}
			alert('Please enter the From Date for Company 2.');
	    	return false;
		} 
	  	if(frm.companydatetomonth2.value.trim() == '' || frm.companydatetoday2.value.trim() == '' || frm.companydatetoyear2.value.trim() == '') {
			if (frm.companydatetomonth2.value.trim() == '') {
				document.ALCATEL.companydatetomonth2.focus();
  			} else if (frm.companydatetoday2.value.trim() == '') {	
				document.ALCATEL.companydatetoday2.focus();
  			} else if (frm.companydatetoyear2.value.trim() == '') {	
				document.ALCATEL.companydatetoyear2.focus();
			}
			alert('Please enter the To Date for Company 2');
	    	return false;
		} 
  		if(frm.companysuper2.value.trim() == '') {
  			document.ALCATEL.companysuper2.focus();
			alert('Please enter the Supervisor for Company 2.');
	   		return false;
		} 
  		if(frm.companyphone2.value.trim() == '') {
  			document.ALCATEL.companyphone2.focus();
			alert('Please enter the Phone Number for Company 2.');
	   		return false;
		} 
	
	}

  	if(frm.companyname3.value.trim() == 'N/A' || frm.companyname3.value.trim() == 'n/a' ) {
  	} else {
  		if(frm.companyname3.value.trim() == '') {
  			document.ALCATEL.companyname3.focus();
			alert('Company Name 3 is required.');
	    	return false;
		} 
  		if(frm.companyaddr3.value.trim() == '') {
  			document.ALCATEL.companyaddr3.focus();
			alert('Please enter the Street for Company 3.');
	    	return false;
		} 
  		if(frm.companycity3.value.trim() == '') {
  			document.ALCATEL.companycity3.focus();
			alert('Please enter the City for Company 3.');
	    	return false;
		} 
  		if(frm.companystate3.value.trim() == '') {
  			document.ALCATEL.companystate3.focus();
			alert('Please enter the State for Company 3.');
	   		return false;
		} 
		if (frm.companystate3.value.trim() == 'XX' && frm.companystateother3.value.trim() == '') {
			document.ALCATEL.companystateother3.focus();
			alert('Please select a Province/Country for Company 2');
	   		return false;
  		} 
  		if(frm.companyposition3.value.trim() == '') {
  			document.ALCATEL.companyposition3.focus();
			alert('Please enter the Position for Company 3.');
	   		return false;
		} 
	  	if(frm.companydatefrommonth3.value.trim() == '' || frm.companydatefromday3.value.trim() == '' || frm.companydatefromyear3.value.trim() == '') {
			if (frm.companydatefrommonth3.value.trim() == '') {
				document.ALCATEL.companydatefrommonth3.focus();
  			} else if (frm.companydatefromday3.value.trim() == '') {	
				document.ALCATEL.companydatefromday3.focus();
  			} else if (frm.companydatefromyear3.value.trim() == '') {	
				document.ALCATEL.companydatefromyear3.focus();
			}
			alert('Please enter the From Date for Company 3.');
	    	return false;
		} 
	  	if(frm.companydatetomonth3.value.trim() == '' || frm.companydatetoday3.value.trim() == '' || frm.companydatetoyear3.value.trim() == '') {
			if (frm.companydatetomonth3.value.trim() == '') {
				document.ALCATEL.companydatetomonth3.focus();
  			} else if (frm.companydatetoday3.value.trim() == '') {	
				document.ALCATEL.companydatetoday3.focus();
  			} else if (frm.companydatetoyear3.value.trim() == '') {	
				document.ALCATEL.companydatetoyear3.focus();
			}
			alert('Please enter the To Date for Company 3');
	    	return false;
		} 
  		if(frm.companysuper3.value.trim() == '') {
  			document.ALCATEL.companysuper3.focus();
			alert('Please enter the Supervisor for Company 3.');
	   		return false;
		} 
  		if(frm.companyphone3.value.trim() == '') {
  			document.ALCATEL.companyphone3.focus();
			alert('Please enter the Phone Number for Company 3.');
	   		return false;
		} 
	
	}

	return true;
}
*/
</script>


