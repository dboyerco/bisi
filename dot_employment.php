<?
	require_once('../pdotriton.php');
	$days = 0;
	$YR = 0;
	$MO = 0;
	$DY = 0;
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();  
	if ($Package == 'package2' || $Package == 'package3') {
		$releasefnd = $dbo->query("Select count(*) from App_Uploads where PersonID = ".$PersonID." and UploadType = 'DOT Questionnaire FMCSA-PHMSA Form';")->fetchColumn();	
		$FormAction = "drv_experience.php?PersonID=".$PersonID;
	} else {
		$releasefnd = 1;	
		if ($noemail == 'Y') {
			$FormAction = "certification.php?PersonID=".$PersonID;
		} else {
			$FormAction = "disclosure1.php?PersonID=".$PersonID;
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />		
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
			if ($("#releasefnd").val() > 0) {
		   		el = document.getElementById("submitid");
				el.style.visibility = "visible";
			}	
		});	
	</script>	

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
	if($Package == 'package2') {
		echo '<tr><td><font size="2">Per DOT Regulations, please complete 10 yrs of employment history beginning with your 
				most current.<br />For the past 3 yrs, the DOT form must be completed for each employer.<br /> 
				Click on &quot;Save Employment&quot; or &quot;Download Form&quot; for additional instructions.	 			
				</font></td></tr><tr><td>&nbsp;</td></tr></table>';
	} else {
		echo '<tr><td><font size="2">Per DOT Regulations, please complete 10 yrs of employment history beginning with your 
				most current.<br />For the past 2 yrs, the DOT form must be completed for each employer.<br /> 
				Click on &quot;Save Employment&quot; or &quot;Download Form&quot; for additional instructions.	 			
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
					<td width="240" valign="top" align="left"><font size="1"><strong> May we contact your current employer?</strong>&nbsp;&nbsp;</font>';
					if ($row[15] == 'Y') {					
						echo '<td><font size="1"><b>Yes</b></font>';
					} else {	
						echo '<td><font size="1"><b>No</b></font>';
					}	
			echo '</td></tr></table>';
			}	
		echo '<table width="763" bgcolor="#E4E8E8">
			<tr valign="top"> 
				<td width="15%"><font size="1">Company Name:&nbsp;</font></td>
				<td width="30%">
						<input name="empname" value="'.htmlspecialchars($row[1]).'" class="nobord" readonly style="font-size:8px;">
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
					<input name="empposition" value="'.htmlspecialchars($row[11]).'" class="nobord" readonly style="font-size:8px;">
			</td>
			<td><font size="1">Supervisor:&nbsp;&nbsp;'.htmlspecialchars($row[9]).'</font>
			</td>
		</tr>
		<tr> 
			<td><font size="1">Phone:</font></td>
			<td>
					<input name="empphone" value="'.htmlspecialchars($row[12]).'" class="nobord" readonly style="font-size:8px;">
			</td>
			<td><font size="1">Supervisor Phone:&nbsp;&nbsp;'.htmlspecialchars($row[13]).'</font>
			</td>
		</tr>
		<tr> 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><font size="1">Supervisor Email:&nbsp;&nbsp;'.htmlspecialchars($row[14]).'</font>
			</td>
		</tr>
		</table>';
	if ($Package == "package2" || $Package == "package3") {
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
			<td width="351"><font size="1"> 
				<select name="newempcurrent" id="newempcurrent">
					<option value="Y">Yes</option>
					<option VALUE="N">No</OPTION>
				</select></font>
			</td>
		</tr>		
		<tr> 
			<td valign="top" nowrap> 
				<font size="1"><strong> May we contact your current employer?</strong><strong><font color="FF0000">*</font></strong></font>
			</td>
			<td width="351"><font size="1"> 
				<select name="newempcontact" id="newempcontact">
					<option VALUE="N">No</OPTION>
					<option value="Y">Yes</option>
				</select></font>
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
		<td><span style="font-size:small; color:#000000;">
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
			<input text="number" name="newempphone" id="newempphone" value="" size="30" placeholder="### ### #### #####" onKeyUp="return frmtphone(this,\'up\')"></font>
		</td>
	</tr>';
echo '<tr valign="top"> 
		<td width="160"><font size="1">From Date<font color="FF0000">*</font></font></td>
		<td>
			<font size="1"> 
				<input name="newempfromdate" id="newempfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
			</font>
		</td>
	</tr>
	<tr> 
		<td width="160"><font size="1">To Date<font color="FF0000">*</font></font></td>
		<td>
			<font size="1"> 
				<input name="newemptodate" id="newemptodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
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
		<td><font size="1">Supervisor Phone<font color="FF0000">*</font></font></td>
		<td nowrap>
			<font size="1"> 
				<input text="number" name="newsphone" id="newsphone" size="30" placeholder="### ### #### #####" 
				 onKeyUp="return frmtphone(this,\'up\')">  
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1">Supervisor Email<font color="FF0000">*</font></font></td>
		<td nowrap>
			<font size="1"> 
				<input name="newsemail" id="newsemail" size="40" maxlength="40">  
			</font>
		</td>
	</tr>
	</table>';
if ($Package == "package2" || $Package == "package3") {
	echo "<table  width='100%' bgcolor='#E4E8E8'>
			<tr>      
				<td valign='top' width='275'>
					<font size='1'>
						Were you subject to FMCSA or PHMSA Safety Regulations while employed? <strong><font color='FF0000' size='2'>*</font></strong>
					</font>   
				</td>
				<td align='left' width='300'>
					<font size='1'>
						<select name='newempdotreg' id='newempdotreg'>
							<option value='N'>NO</option>
							<option value='Y'>YES</option>
						</select>
					</font>
				</td>
			</tr>
			<tr>
				<td valign='top' width='275'>
					<font size='1'>
						Was this job designated as a safety sensitive function in any DOT regulated mode and<br />therefore subject to alcohol and controlled substances testing requirements? 
						<strong><font color='FF0000' size='2'>*</font></strong>
					</font>
				</td>
				<td align='left' width='300'>
					<font size='1'>
						<select name='newempdottst' id='newempdottst'>
							<option value='N'>NO</option>
							<option value='Y'>YES</option>
						</select>
					</font>
				</td>
			</tr>
		</table>";
/*
	if ($days < 1095){
		
	echo "<table  width='100%' bgcolor='#E4E8E8'>
			<tr>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td>
					<font size='2'>
						Please download and complete the <a href='#' onclick='downloadForm()'>DOT Questionnaire FMCSA-PHMSA Form<a> for this employer.
						<strong><font color='FF0000'>*</font></strong><input type='button' name='dlform' id='dlform' VALUE='Download Form' onclick='return downloadForm()' style='border-radius:5px; padding: 1px 2px;'>
						<br />
						<strong>Clicking on the 'Download Form' button will open the form in a separate tab.<br />Print the form, Complete and 
						<a href='#openPhotoDialog'>Click Here To Upload The Document.</a><br />
						You will need to complete a form for each employer entered within a 3 year period.<br />
						Your background screening will not be processed until this form is completed and returned to BISI.</strong>						
					</font>
				</td>
			</tr>";						
		echo"</table>";
	}
*/		
}		
		echo '<table width="100%" bgcolor="#E4E8E8">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<INPUT TYPE="button" id="add_new_employment" VALUE="Save Employment" style="font-size:medium; color:green;">
				</td>
			</tr>
		</table>';
echo '</fieldset>';
#echo "Days: ".$days."<br />";
$shownext = false;

if($Package == 'package2' && $releasefnd > 0 && $days > 1095) {
	$shownext = true;
} 
if($Package == 'package3' && $releasefnd > 0 && $days > 730) {
	$shownext = true;
}

if($shownext) {
	echo '<table width="763">
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td align="center">
					 <INPUT TYPE="submit" VALUE="Next" style="font-size:medium; color:green;">
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>';	
} else {
	echo '<table width="763">
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td align="center">
			 		<INPUT TYPE="button" id="requireddoc" VALUE="Download/Upload DOT Questionnaire FMCSA-PHMSA Form" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>';	
}	
	
include ('Upload/UploadDialog.php');

echo "<INPUT TYPE=\"hidden\" name=\"PersonID\" id=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" name=\"EmpID\" id=\"EmpID\" VALUE=\"$maxEmpID\">
	  <INPUT TYPE=\"hidden\" name=\"Current\" id=\"Current\" VALUE=\"$currentEmployer\">
	  <INPUT TYPE=\"hidden\" name=\"Package\" id=\"Package\" VALUE=\"$Package\">
	  <INPUT TYPE=\"hidden\" NAME=\"UploadType\" id=\"UploadType\"VALUE=\"DOT Questionnaire FMCSA-PHMSA Form\">
	  <INPUT TYPE=\"hidden\" NAME=\"releasefnd\" id=\"releasefnd\"VALUE=\"$releasefnd\">
	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";


echo '<div name="reqdoc" id="reqdoc" title="Dialog Title">
		<div>
			<table width="100%" align="left" border="0">
				<tr>
					<td>
						<font size="2">';
					if ($Package == "package2") {	
						echo 'Please download and complete the <a href="#" onclick="downloadForm()">DOT Questionnaire FMCSA-PHMSA Form<a> for this employer.
							<strong><font color="FF0000">*</font></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" name="dlform" id="dlform" VALUE="Download Form" onclick="return downloadForm()" style="border-radius:5px; padding: 1px 2px;"><br /><br />
							Clicking on the "Download Form" button will open the form in a separate tab.<br /><strong>Print the form, complete Section 1, and sign with a handwritten signature.</strong>	 
							<font color="0000FF"><a href="#openPhotoDialog">Click Here To Upload The Document.</a></font><br /><br />
							You will need to complete a form for each employer entered within a 3 year period.<br />
							Your background screening will not be processed until this form is completed and returned to BISI.';
					} else {
						echo 'Please download and complete the <a href="#" onclick="downloadForm()">DOT Questionnaire FMCSA-PHMSA Form<a> for this employer.
							<strong><font color="FF0000">*</font></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" name="dlform" id="dlform" VALUE="Download Form" onclick="return downloadForm()" style="border-radius:5px; padding: 1px 2px;"><br /><br />
							Clicking on the "Download Form" button will open the form in a separate tab.<br /><strong>Print the form, complete Section 1, and sign with a handwritten signature..</strong>	 
							<font color="0000FF"><a href="#openPhotoDialog">Click Here To Upload The Document.</a></font><br /><br />
							You will need to complete a form for each employer entered within a 2 year period.<br />
							Your background screening will not be processed until this form is completed and returned to BISI.';					
					}							
					echo '</font>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="close_reqdoc" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>	
	</div>'; 
?>
	<div name="Employment_dialog" id="Employment_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgempid" id="dlgempid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
				<tr>
					<td><font size="2">Current Employment</font></td>
					<td><font size="2">
					    <select name="dlgcurrent" id="dlgcurrent">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select></font>
					</td>
				</tr>
				<tr> 
					<td width="160" valign="top"> 
						<font size="2"><strong> May we contact your current employer?</strong></font>
					</td>
					<td width="351"> <font size="2"> 
						<select name="dlgcontact" id="dlgcontact">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select></font>
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
					<td><span style="font-size:small; color:#000000;">
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
					<td><span style="font-size:small;color:#000000;">
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
							onKeyUp="return frmtphone(this,'up')">  
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2">From Date</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgfromdate" id="dlgfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
							onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>	
				</tr>
				<tr> 
					<td><font size="2">To Date</font></td>
					<td nowrap>
						<font size="2"> 
							<input type="text" name="dlgtodate" id="dlgtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" 
						 	onKeyUp="return frmtdate(this,'up')">
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
							onKeyUp="return frmtphone(this,'up')">  
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
			</table>	
<?php
if ($Package == "package2" || $Package == "package3") {
	echo "<table width='100%' align='left' border='3' bgcolor='#eeeeee'><tr>      
			<td valign='top' width='200'>
				<font size='2'>
					Were you subject to FMCSA or PHMSA Safety Regulations while employed? <strong><font color='FF0000' size='2'>*</font></strong>
				</font>   
			</td>
			<td>
				<font size='2'>
					<select name='dlgempdotreg' id='dlgempdotreg'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr>
		<tr>
			<td>
				<font size='2'>
					Was this job designated as a safety sensitive function in any DOT regulated mode and therefore subject to alcohol and controlled substances testing requirements? 
					<strong><font color='FF0000' size='2'>*</font></strong>
				</font>
			</td>
			<td>
				<font size='2'>
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
	$( "#reqdoc" ).dialog({ autoOpen: false });
 	
	function downloadForm(){
//   		el = document.getElementById("submitid");
//  		elbutton = document.getElementById("dlform");
//		elbutton.style.visibility = "hidden";
//		el.style.visibility = "visible";
		window.open('https://proteus.bisi.com/pusgcorpoffice/Peak-Utility-Services-Group-DOT-Form-2-2019.pdf');
	}	
	
	$( "#requireddoc" ).click(function() {
		$( "#reqdoc" ).dialog( "option", "title", "Down and Upload DOT Form");	
		$( "#reqdoc" ).dialog( "option", "modal", true );
		$( "#reqdoc" ).dialog( "option", "width", 750 );
		$( "#reqdoc" ).dialog( "open" );
	});

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
			if (!isValidDate('newempfromdate')) {
				$('#newempfromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var empfromdate = document.getElementById("newempfromdate").value;
			}	
		} else {		
			$('#newempfromdate').focus();
			alert("From Date is required");
			return;
		}	
		
		if (document.getElementById("newemptodate").value > '') {
			if (!isValidDate('newemptodate')) {
				$('#newemptodate').focus();
				alert("Invalid To Date");
				return false;
			} else {					
				var emptodate = document.getElementById("newemptodate").value;
			}	
		} else {		
			$('#newemptodate').focus();
			alert("To Date is required");
			return;
		}	
		if (!isValidDiff(empfromdate,emptodate)) {
			$('#newempfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
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
			document.ALCATEL.newsphone.focus();
			alert("Supervisor Phone is required");
			return;
		}	
		if (document.getElementById("newsemail").value > '') {
			var empsemail = document.getElementById("newsemail").value;
		} else {	
			document.ALCATEL.newsemail.focus();
			alert("Supervisor Email is required");
			return;
		}	
		var empreason = '';		
 		if ($("#Package").val() == "package2" || $("#Package").val() == "package3") {
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
			data: {personid: personid, empcontact: empcontact, empcurrent: empcurrent, empname: empname, empstreet: empstreet, empcity: empcity, empstate: empstate, empstateother: empstateother, empfromdate: empfromdate, emptodate: emptodate, emptitle: emptitle, empsuper: empsuper, empphone: empphone, empsphone: empsphone, empsemail: empsemail, empreason: empreason, empdotreg: empdotreg, empdottst: empdottst},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EmpID = obj2;
					if ($("#Current").val() == 'N') {
						$("#newempcontact").val('N');
						$("#newempcurrent").val('N');
					}
					$("#newempname").val('');
					$("#newempstreet").val('');
					$("#newempcity").val('');
					$("#newempstate").val('');
					$("#newempstateother").val('');
					$("#newemptitle").val('');
					$("#newempfromdate").val('');
					$("#newemptodate").val('');
					$("#newempsuper").val('');
					$("#newempphone").val('');
					if ($("#Package").val() == "package2" || $("#Package").val() == "package3") {
						$("#newempdotreg").val('');
						$("#newempdottst").val('');
					}
					var needform = false;
					if ($("#Package").val() == "package2" &&  ($("#releasefnd").val() == 0 || $("#days").val() < 1095)) {
						needform = true;
					} else {
						if($("#releasefnd").val() == 0 || $("#days").val() < 730) {
							needform = true;
						}
					}		
						
					if(needform == true) {
						$( "#reqdoc" ).dialog( "option", "title", "Down and Upload DOT Form");	
						$( "#reqdoc" ).dialog( "option", "modal", true );
						$( "#reqdoc" ).dialog( "option", "width", 750 );
						$( "#reqdoc" ).dialog( "open" );
					} else {
						location.reload(true);
						return;
					}	
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
//						var EmpReasonForLeaving = obj2[i].EmpReasonForLeaving;
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
//					document.getElementById("dlgreason").value = EmpReasonForLeaving;
					document.getElementById("dlgtitle").value = EmpTitle;
					document.getElementById("dlgphone").value = EmpPhone;
					document.getElementById("dlgsphone").value = EmpSupervisorPhone;
					document.getElementById("dlgsemail").value = EmpSupervisorEmail;
					if ($("#Package").val() == 'package2' || $("#Package").val() == "package3") {
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
			$('#dlgempname').focus();
			alert("Company Name is required");
			return;
		}	
		contact = document.getElementById("dlgcontact").value;	
		
		if (document.getElementById("dlgaddr").value > '') {
			var addr = document.getElementById("dlgaddr").value;
		} else {		
			$('#dlgaddr').focus();
			alert("Street is required");
			return;
		}	

		if (document.getElementById("dlgcity").value > '') {
			var city = document.getElementById("dlgcity").value;
		} else {		
			document.ALCATEL.dlgcity.focus();
			$('#dlgcity').focus();
			alert("City is required");
			return;
		}	
			
		if (document.getElementById("dlgstate").value == '' && document.getElementById("dlgstateother").value == '' ) {
			$('#dlgstate').focus();
			alert("State or Country is required");
			return;
		} else {		
			var state = document.getElementById("dlgstate").value;
			var stateother = document.getElementById("dlgstateother").value;
		}	
		if (document.getElementById("dlgphone").value > '') {
			var phone = document.getElementById("dlgphone").value;
		} else {		
			$('#dlgphone').focus();
			alert("Phone is required");
			return;
		}	
		
		if (document.getElementById("dlgfromdate").value > '') {
			if (!isValidDate('dlgfromdate')) {
				$('#dlgfromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var fromdate = document.getElementById("dlgfromdate").value;
			}	
		} else {		
			document.ALCATEL.dlgfromdate.focus();
			alert("From Date is required");
			return;
		}	
		
		if (document.getElementById("dlgtodate").value > '') {
			if (!isValidDate('dlgtodate')) {
				$('#dlgtodate').focus();
				alert("Invalid To Date");
				return false;
			} else {					
				var todate = document.getElementById("dlgtodate").value;
			}	
		} else {		
			document.ALCATEL.dlgtodate.focus();
			$('#dlgtodate').focus();
			alert("To Date is required");
			return;
		}	
		if (!isValidDiff(fromdate,todate)) {
			$('#dlgfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}	

		if (document.getElementById("dlgtitle").value > '') {
			var position = document.getElementById("dlgtitle").value;
		} else {		
			$('#dlgtitle').focus();
			alert("Position is required");
			return;
		}	
		
		if (document.getElementById("dlgsuper").value > '') {
			var supervisor = document.getElementById("dlgsuper").value;
		} else {		
			$('#dlgsuper').focus();
			alert("Supervisor is required");
			return;
		}	
		if (document.getElementById("dlgsphone").value > '') {
			var sphone = document.getElementById("dlgsphone").value;
		} else {		
			$('#dlgsphone').focus();
			alert("Supervisor Phone is required");
			return;
		}	
		if (document.getElementById("dlgsemail").value > '') {
			var semail = document.getElementById("dlgsemail").value;
		} else {	
			$('#dlgsemail').focus();
			alert("Supervisor Email is required");
			return;
		}	
		var reason = '';		
		if ($("#Package").val() == "package2" || $("#Package").val() == "package3") {
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
 	$( "#close_reqdoc" ).click(function() {	
		$( "#reqdoc" ).dialog( "close" );
		location.reload(true);
	});
 
 </script>
 <script language="JavaScript" type="text/javascript">
 	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {

		var empid = $("#EmpID").val();
		var nodays = $("#days").val();
		if(nodays >= 3650) {
			return true;
		}
		else {
			$("#newempname").focus();
			alert('You have not entered 10 years of employment.');
			return false;
		}
	}
</script>
<script src="Upload/Upload.js"></script>