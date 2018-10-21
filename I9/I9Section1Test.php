<?php
require_once('../pdotriton.php');
$PersonID = 15341;
$JobID = 3067;
$CD = 'j9kNiyGx0T2uDzLd'; 
$CustomerID = 9351;
session_start();
$codeid = $dbo->query("Select CodeID from I9Section1 where PersonID = ".$PersonID." and JobID = ".$JobID." and CustomerID = ".$CustomerID.";")->fetchColumn();
$_SESSION['SESS_JobID'] = $JobID;
$_SESSION['SESS_CustomerID'] = $CustomerID;
$_SESSION['SESS_CodeID'] = $codeid;
if (!isset($CD)) {
	$CD = '';
}	
$cnt = 0;
$cnt = $dbo->query("Select count(*) from I9Section1 where PersonID = ".$PersonID." and JobID = ".$JobID." and CustomerID = ".$CustomerID.";")->fetchColumn();
	$date = date("m/d/Y");
	#$PersonID = 1;
	#$FormAction = "disclosure2.php?PersonID=".$PersonID;
	$lname='';
	$fname='';
	$mname='';
	$oname='';
	$address='';
	$apt='N/A';
	$city='';
	$state='';
	$zip='';
	$dob='';
	$ssn1_1='';
	$ssn1_2='';
	$ssn1_3='';
	$ssn2_1='';
	$ssn2_2='';
	$ssn3_1='';
	$ssn3_2='';
	$ssn3_3='';
	$ssn3_4='';
	$email='';
	$ephone='';
	$citizen='';
	$noncitizen='';
	$legalresident='';
	$regno='';
	$alienexpdate='';
	$aliennumber='';
	$admissionnumber='';
	$foreignpassport='';
	$countryofissuance='';
	$employeesignature='';	
	$employeesignaturedate = date("m/d/Y");
	$ptsignature='';
	$preparersignaturedate = date("m/d/Y");
	$ptlname = '';
	$ptfname = '';
	$ptaddress = '';
	$ptcity = '';				
	$ptstate = '';
	$ptzip = '';

	$I9Cnt = $cnt;
	$selectstmt="Select * from I9Section1 where PersonID = :PersonID and JobID = :JobID and CustomerID = :CustomerID;";
	$result2 = $dbo->prepare($selectstmt);
	$result2->bindValue(':PersonID', $PersonID);
	$result2->bindValue(':JobID', $JobID);
	$result2->bindValue(':CustomerID', $CustomerID);
	if (!$result2->execute()) {
	} else {	
		$row=$result2->fetch(PDO::FETCH_BOTH);
#		$CustomerID = $row['CustomerID'];
		$iCIMSID = $row['iCIMSID'];
		$lname = $row['Last_Name'];
		$fname = $row['First_Name'];
		$mname = substr($row['Middle_Name'],0,1);
		$oname = $row['Other_Last_Name'];
		if ($oname == '') {
			$oname = 'N/A';
		}	
		$address = $row['Address'];
		$apt = $row['Apt'];
		$city = $row['City'];
		$state = $row['State_Code'];
		$zip = $row['Zip_Code'];

		if (trim($apt) == '') {
			$apt = 'N/A';
		}	
		$dob = date('m/d/Y',strtotime($row['Date_of_Birth']));
		$ssn1_1 = substr($row['SSN'],0,1);
		$ssn1_2 = substr($row['SSN'],1,1);
		$ssn1_3 = substr($row['SSN'],2,1);
		$ssn2_1 = substr($row['SSN'],4,1);
		$ssn2_2 = substr($row['SSN'],5,1);
		$ssn3_1 = substr($row['SSN'],7,1);
		$ssn3_2 = substr($row['SSN'],8,1);
		$ssn3_3 = substr($row['SSN'],9,1);
		$ssn3_4 = substr($row['SSN'],10,1);
		$email = $row['Email'];
		$ephone = $row['Phone'];
		$citizen = $row['Citizen'];
		$noncitizen  = $row['NonCitizen'];
		if ($row['Permanent_Resident'] == 'Y') {
			$legalresident = $row['Permanent_Resident'];
			$regno = $row['Registration_USCIS'];
		}	
		if ($row['Alien_Auth_Work'] == 'Y') {	
			$alienexpdate = $row['Auth_Work_Date'];
			$aliennumber = $row['Registration_USCIS'];
			$admissionnumber = $row['I94_Admission'];
			if ($row['Foreign_Passport'] > '') {
				$foreignpassport = $row['Foreign_Passport'];
				$countryofissuance = $row['Foreign_Passport_Country'];
			}
		}		
		$employeesignature = '';
		if ($row['Employee_Signature_Date'] == '1900-01-01') {
			$employeesignaturedate = date("m/d/Y");
		} else {	
			$employeesignaturedate = date('m/d/Y', strtotime($row['Employee_Signature_Date']));	
		}	
 		if ($row['Did_Used_Preparer'] == 'Y') {
			$ptsignature = '';
			if ($row['Preparer_Signature_Date'] == '1900-01-01') {
				$preparersignaturedate = date("m/d/Y");
			} else {	
				$preparersignaturedate = date('m/d/Y', strtotime($row['Preparer_Signature_Date']));	
			}	
			$ptlname = $row['Preparer_Last_Name'];
			$ptfname = $row['Preparer_First_Name'];
			$ptaddress = $row['Preparer_Address'];
			$ptcity = $row['Preparer_City'];
			$ptstate = $row['Preparer_State'];
			$ptzip = $row['Preparer_Zip_Code'];
		}			
		$ReturnURL = $row['ReturnURL']; 
		$IncompleteURL = $row['IncompleteURL']; 
	}
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
#	echo $user_agent."<br />";

	function getOS() { 
	    global $user_agent;
	    $os_platform = "Unknown OS Platform";
	    $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    	foreach ($os_array as $regex => $value)
        	if (preg_match($regex, $user_agent))
            	$os_platform = $value;

    	return $os_platform;
	}

	function getBrowser() {
	    global $user_agent;
    	$browser = "Unknown Browser";
	    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    	foreach ($browser_array as $regex => $value)
        	if (preg_match($regex, $user_agent))
            $browser = $value;

    	return $browser;
	}

	$user_os = getOS();
	$user_browser = getBrowser();
	
	$fndemp = false;
	$fndprep = false;
	$empurl = "Signature/PersonID-".$PersonID."Emp.png";
	if (file_exists($empurl)) {
		$fndemp = true;
	}	
	$prepurl = "Signature/PersonID-".$PersonID."Prep.png";
	if (file_exists($prepurl)) {
		$fndprep = true;
	}	
#	echo substr($user_os,0,6)." - ".$user_browser."<br />";
	echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"JobID\" ID=\"JobID\" VALUE=\"$JobID\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"CustomerID\" ID=\"CustomerID\" VALUE=\"$CustomerID\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"CD\" ID=\"CD\" VALUE=\"$CD\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"ReturnURL\" ID=\"ReturnURL\" VALUE=\"$ReturnURL\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"IncompleteURL\" ID=\"IncompleteURL\" VALUE=\"$IncompleteURL\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"form_clean\" ID=\"form_clean\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"fndemp\" ID=\"fndemp\" value=\"$fndemp\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"fndprep\" ID=\"fndprep\" value=\"$fndprep\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"icimsid\" ID=\"icimsid\" value=\"$iCIMSID\">";
	echo "<INPUT TYPE=\"hidden\" NAME=\"Completed\" ID=\"Completed\" value=\"\">";
	echo "<input TYPE=\"hidden\" name=\"lastname\" id=\"lastname\" value=\"$lname\">";
	echo "<input TYPE=\"hidden\" name=\"firstname\" id=\"firstname\" value=\"$fname\">";
	echo "<input TYPE=\"hidden\" name=\"middlename\" id=\"middlename\" value=\"$mname\">";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online I9 Section 1</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--		<link rel="stylesheet" type="text/css" href="StyleCSS/reset.css"> -->
<?php
		if (substr($user_os,0,3) == 'Mac' && ($user_browser == 'Safari' || $user_browser == 'Chrome')) {
			echo '<link type="text/css" rel="stylesheet" href="StyleCSS/section1Test_style.css" />';
		} else if (substr($user_os,0,3) == 'Mac' && $user_browser == 'Firefox') {
			echo '<link type="text/css" rel="stylesheet" href="StyleCSS/Firefoxsection1_style.css" />';
		} else if (substr($user_os,0,6) == 'Window' && $user_browser == 'Chrome') {
			echo '<link type="text/css" rel="stylesheet" href="StyleCSS/Chromesection1_style.css" />';
		} else if (substr($user_os,0,6) == 'Window' && $user_browser == 'Firefox') {
			echo '<link type="text/css" rel="stylesheet" href="StyleCSS/Firefoxsection1_style.css" />';
		} else if (substr($user_os,0,6) == 'Window' && $user_browser == 'Unknown Browser') {
			echo '<link type="text/css" rel="stylesheet" href="StyleCSS/Windowsection1_style.css" />';
		} else if ($user_os == 'Android' && $user_browser == 'Handheld  Browser') {
			echo '<link type="text/css" rel="stylesheet" href="StyleCSS/section1_style.css" />';
		} else {
			echo '<link rel="stylesheet" type="text/css" href="StyleCSS/section1_style.css">';
		}		
?>		
		<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />		
		<link type="text/css" rel="stylesheet" href="StyleCSS/section1Test_table.css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
	  	<script type="text/javascript">
//  			var form_clean = $("form").serialize(); 
     		$(document).ready(function () {
				var bodyheight = $(window).height();
//				alert (bodyheight);
     			var PersonID = document.getElementById("PersonID").value;
     			if (document.getElementById("fndemp").value) {
					imgsig = document.getElementById("sigimg");
					imgsig.src = "Signature/PersonID-"+PersonID+"Emp.png"
   					sig = document.getElementById("sigdiv");
					sig.style.visibility = "visible";		
     			}
     			if (document.getElementById("fndprep").value) {
					imgsig = document.getElementById("psigimg");
					imgsig.src = "Signature/PersonID-"+PersonID+"Prep.png"
   					sig = document.getElementById("psigdiv");
					sig.style.visibility = "visible";		
     			}
//     			chkrequired();
 			});
		</script>
	</head>
<?php
if ($PersonID == '' || $cnt == 0 || $codeid != $CD) {
	if ($cnt == 0) { 
		echo '<html><body><br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:large; font-family=Tahoma; color:red;">Invalid PersonID.</span><br /><span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the I9 Application please use the link in iCIMS.</td></tr><tr><td>&nbsp;</td></table></body></html>';
	} else {
		echo '<html><body><br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the I9 Application please use the link in iCIMS.</span></td></tr><tr><td>&nbsp;</td></table></body></html>';
	}	
} else {
	if (!isset($_SESSION['SESS_CodeID'])) {
		$_SESSION['SESS_CodeID'] = $CD;
	}
	echo '<body bgcolor="#ffffff" onload="setindexes(\''.$state.'\',\''.$ptstate.'\')">
		<form METHOD="POST" NAME="myform" id="myform">
			<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="975">
				<tbody>
					<tr>
						<td><img src="images/Homeland-Security-Icon.png" alt="Homeland Security" heigth="60px" width="60px"></td>
						<td><center><font size="3" face="Arial">
							<b>Employment Eligibility Verification</b><br />
							<b>Department of Homeland Security</b><br />
							U.S. Citizenship and Immigration Services</font></center>
						</td>
						<td><center><font size="3" face="Arial">
							<b>USCIS</b><br />
							<b>Form I-9</b></font><br />
							<font size="2" face="Arial">
								OMB No. 1615-0047<br />
								Expires 08/31/2019</font></center>
						</td>
					</tr>
				</tbody>
			</table>';
	echo '<div id="container">
			<div id="hrline">
				<hr style="height:10px; border:none; color:#000000; background-color:#000000; width:975; margin-left: 1px;" />
			</div>	
			<div id="contentA">
	<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="975">
		<tbody>
			<tr>
				<td>
<p><font size="2" face="Arial">
	<img id="play" src="images/black-triangle-16.png" width="10px" height="10px" alt=""/> 
<strong>START HERE:</strong></font>
<font size="2" face="Arial">Read <a href="I-9InstructionsNew.pdf" target="_blank">instruction</a> carefully before completing this form. 
The instructions must be available, either in paper or electronically, durning completion of this form. Employers are liable
for errors in the completeion of this form.</font></p> 
<p><font size="2" face="Arial"><strong>ANTI-DISCRIMINATION NOTICE:</strong></font>
<font size="2" face="Arial">It is illegal to discriminate against work-athorized individuals.
Employers <b>CANNOT</b> specify which document(s) an employee may present to establish employment authorization and identity.
The refusal to hire or continue to employ an individual because the documentation presented has a future expiration date may
also constitute illegal discrimination.</font></p>
				</td>
			</tr>
		</tbody>
	</table>
	</div>';
	echo '<div id="contentB">
		<table  border="0" cellpadding="0" cellspacing="0" width="975">
		<tr>
			<td>
 				<table bgcolor="LightGrey" border="1" cellpadding="0" cellspacing="0" width="975">
					<tr>
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Section 1. Employee Information
							and Attestation</strong></font>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">(<i>Employees must complete and sign section 1 of Form I9
							no later <br />than the first day of of employment but not before accepting a job offer.</i>)
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>		
 			<td>
 				<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
					<tr>
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name(<i>Family Name</i>)</font><br />
							<input name="lname" id="lname" class="requiredInput" value="'.htmlspecialchars($lname).'" size="36" maxlength="40" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name(<i>Given Name</i>)</font><br />
							<input name="fname" id="fname" class="requiredInput" value="'.htmlspecialchars($fname).'" size="35" maxlength="40" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Middle Initial</font><br />
							<input name="mname" id="mname" class="requiredInput" value="'.htmlspecialchars($mname).'" size="10" maxlength="10" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Other Last Names Used(<i>if any</i>)</font><br />
							<input name="oname" id="oname" class="requiredInput" value="'.htmlspecialchars($oname).'" size="30" maxlength="40" onblur="chkrequired()">
						</td>	
					</tr>
				</table>
			</td>
		</tr>
		<tr>		
 			<td>
 				<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
					<tr>
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Address(<i>Street Numer and Name</i>)</font><br />
							<input name="address" id="address" class="requiredInput" value="'.htmlspecialchars($address).'" size="45" maxlength="45" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Apt. Number</font><br />
							<input name="apt" id="apt" class="requiredInput" value="'.htmlspecialchars($apt).'" size="10" maxlength="15" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">City or Town</font><br />
							<input name="city" id="city" class="requiredInput" value="'.htmlspecialchars($city).'" size="40" maxlength="40" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">State</font><br />
							<select name="state" id="state" class="requiredInput" onblur="chkrequired()">
								<option value=""></option>';
								$sql = "Select Abbrev from State order by Name";
								$state_result = $dbo->prepare($sql);
								$state_result->execute();
								while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
									echo "<option value=".$rows[0].">".$rows[0]."</option>";
								}		
						echo '</select>	
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip Code</font><br />
							<input name="zip" id="zip" class="requiredInput" value="'.htmlspecialchars($zip).'" size="10" maxlength="15" onblur="chkrequired()">
						</td>	
					</tr>
				</table>
			</td>
		</tr>
		<tr>		
 			<td>
 				<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
					<tr>
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Date of Birth(<i>mm/dd/yyyy</i>)</font><br />
							<input name="dob" id="dob" class="requiredInput" value="'.htmlspecialchars($dob).'" size="25" maxlength="25" onblur="chkrequired()">
						</td>	
						<td valign="top">
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">U.S. Social Security Number</font><br />
							<input name="ssn1-1" id="ssn1-1" class="requiredInput" value="'.htmlspecialchars($ssn1_1).'" maxlength="1" onblur="chkrequired()">
							<input name="ssn1-2" id="ssn1-2" class="requiredInput" value="'.htmlspecialchars($ssn1_2).'" maxlength="1" onblur="chkrequired()">
							<input name="ssn1-3" id="ssn1-3" class="requiredInput" value="'.htmlspecialchars($ssn1_3).'" size="1" maxlength="1" onblur="chkrequired()">
							<input name="dash1" id="dash1" value = "-" readonly> 
							<input name="ssn2-1" id="ssn2-1" class="requiredInput" value="'.htmlspecialchars($ssn2_1).'" size="1" maxlength="1" onblur="chkrequired()">
							<input name="ssn2-2" id="ssn2-2" class="requiredInput" value="'.htmlspecialchars($ssn2_2).'" size="1" maxlength="1" onblur="chkrequired()">
							<input name="dash2" id="dash2" value = "-" readonly> 
							<input name="ssn3-1" id="ssn3-1" class="requiredInput" value="'.htmlspecialchars($ssn3_1).'" size="1" maxlength="1" onblur="chkrequired()">
							<input name="ssn3-2" id="ssn3-2" class="requiredInput" value="'.htmlspecialchars($ssn3_2).'" size="1" maxlength="1" onblur="chkrequired()">
							<input name="ssn3-3" id="ssn3-3" class="requiredInput" value="'.htmlspecialchars($ssn3_3).'" size="1" maxlength="1" onblur="chkrequired()">
							<input name="ssn3-4" id="ssn3-4" class="requiredInput" value="'.htmlspecialchars($ssn3_4).'" size="1" maxlength="1" onblur="chkrequired()">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Employee\'s E-mail Address</font><br />
							<input name="email" id="email" value="'.htmlspecialchars($email).'" size="40" maxlength="40">
						</td>	
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Employee\'s Telephone Number</font><br />
							<input name="ephone" id="ephone" value="'.htmlspecialchars($ephone).'" size="30" maxlength="30">
						</td>	
					</tr>
				</table>
			</td>
		</tr>
		<tr>		
 			<td>
 				<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="975">	
					<tr>
						<td>
							<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>I am aware that federal law
							provides for imprisonment and/or fines for false statements or use of false documents in<br />
							connection with the completion of this form.<br /><br />
							I attest, under penalty of perjury, that I am (check on of the following boxes):</strong>
							</font>
						</td>	
					</tr>
				</table>
			</td>
		</tr>
		<tr>		
 			<td>
 				<table id="tblcitizen" bgcolor="#ffffff" class="requiredInput" border="1" cellpadding="0" cellspacing="0" width="975">	
					<tr>
						<td>';
							if ($I9Cnt > 0) {	
								if ($row['Citizen'] == 'Y') {
									echo '<input type="checkbox" name="citizen" id="citizen" checked onclick="setCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" >';
								} else {
									echo '<input type="checkbox" name="citizen" id="citizen" onclick="setCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								}
							} else {
								echo '<input type="checkbox" name="citizen" id="citizen" onclick="setCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
							}	
							echo '&nbsp;1. A citizen of the United States
							</font>
						</td>	
					</tr>
					<tr>
						<td>';
							if ($I9Cnt > 0) {	
								if ($row['NonCitizen'] == 'Y') {
									echo '<input type="checkbox" name="noncitizen" id="noncitizen" checked onclick="setNonCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								} else {
									echo '<input type="checkbox" name="noncitizen" id="noncitizen" onclick="setNonCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								}
							} else {
								echo '<input type="checkbox" name="noncitizen" id="noncitizen" onclick="setNonCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
							}
							echo '&nbsp;2. A noncitizen national of the United States (<i>See instructions</i>)
							</font>
						</td>	
					</tr>
					<tr>
						<td>';
							if ($I9Cnt > 0) {	
								if ($row['Permanent_Resident'] == 'Y') {
									echo '<input type="checkbox" name="legalresident" id="legalresident" checked onclick="setLegalResident()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								} else {
									echo '<input type="checkbox" name="legalresident" id="legalresident" onclick="setLegalResident()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								}
							} else {	
								echo '<input type="checkbox" name="legalresident" id="legalresident" onclick="setLegalResident()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
							}
							echo '&nbsp;3. A lawful permanent resident (Alien Registration Number/USCIS Number):&nbsp;
							<input name="regno" id="regno" value="'.htmlspecialchars($regno).'" size="30" maxlength="30" onblur="setLegalResident()">
							</font>
						</td>	
					</tr>
				</table>
			</td>
		</tr>
		<tr>		
 			<td>
 				<table id="tblalien" bgcolor="#ffffff" class="requiredInput" border="1" cellpadding="0" cellspacing="0" width="725">	
					<tr>
						<td>';
							if ($I9Cnt > 0) {							
								if ($row['Alien_Auth_Work'] == 'Y') {	
									echo '<input type="checkbox" name="alien" id="alien" checked onclick="setAlien()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								} else {
									echo '<input type="checkbox" name="alien" id="alien" onclick="setAlien()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
								}
							} else {				
								echo '<input type="checkbox" name="alien" id="alien" onclick="setAlien()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
							}
							echo '&nbsp;4. An alien authoized to work  until (expiration date, if applicable, mm/dd/yyy):&nbsp;&nbsp;
							<input name="alienexpdate" id="alienexpdate" value="'.htmlspecialchars($alienexpdate).'" size="15" maxlength="15" onblur="setAlien()"><br />
							&nbsp;&nbsp;Some aliens may write "N/A" in the expiration date field (<i>See instructions</i>) <br /><br />
							<i>Aliens authorized to work must provide only one of the following documents numbers to complete
							Form I-9: <br />
							An Alien Registration Number/USCIS Number OR Form I-94 Admission Number OR Foreign Passport Number.
							<br /><br />
							1. Alien Registration Number/USCIS Number:&nbsp;&nbsp;&nbsp;
							<input name="aliennumber" id="aliennumber" value="'.htmlspecialchars($aliennumber).'" size="25" maxlength="25" onblur="setAlien()"><br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>OR</b><br /> 
							2. Form I-94 Admission Number:&nbsp;&nbsp;&nbsp;
							<input name="admissionnumber" id="admissionnumber" value="'.htmlspecialchars($admissionnumber).'" size="35" maxlength="25" onblur="setAlien()"><br />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>OR</b><br /> 
							3. Foreign Passport Number:&nbsp;&nbsp;&nbsp;
							<input name="foreignpassport" id="foreignpassport" value="'.htmlspecialchars($foreignpassport).'" size="35" maxlength="25" onblur="setAlien()"><br />
							&nbsp;&nbsp;&nbsp;Country of Issuance:&nbsp;&nbsp;&nbsp;
							<input name="countryofissuance" id="countryofissuance" value="'.htmlspecialchars($countryofissuance).'" size="40" maxlength="25" onblur="setAlien()">
							</font>
						</td>	
					</tr>
				</table>
			</td>
		</tr>
	</table>';
	echo '<div id="contentCC">		
	 <table bgcolor="#ffffff" id="tblclicksignature" class="requiredInput" border="0" cellpadding="0" cellspacing="0" width="970">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Agreement to Use Electronic Click Signature to Sign Documents</font>
			</td>
		</tr>
		<tr>
			<td>	
				<input type="checkbox" name="clicksign" id="clicksign" onclick="setSignature()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">I, '.htmlspecialchars($fname).' '.htmlspecialchars($mname).' '.htmlspecialchars($lname).'
				, agree to sign these electronic documents using "click" signature technologny. I understand that a record of each document and my signing of it will be stored in electronic code. I intend both 
				the signature I inscribe with the "click" signature technologny and the electronic record of it to be my legal signature to the document. I confirm that the document is "written" or "in writing" 
				and that any accurate record of the document is an original of the document.
			</td>	
		</tr>
	</table>
	</div>';
	echo '<div id="contentC">		
	 <table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Signature of Employee</font><br />
				<input name="employeesignature" id="employeesignature" class="requiredInput" size="55" maxlength="55" readonly><br />		
			</td>	
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Today\'s Date (<i>mm/dd/yyyy</i>)</font><br />
				<input name="empdate" id="empdate" class="requiredInput" value="'.$employeesignaturedate.'" size="35" maxlength="35" onblur="chkrequired()">
			</td>	
		</tr>
	</table>
	</div>';
#		  		&nbsp;&nbsp;<a href="#" onclick="empSign();">Click to sign your name</a>
#		  		&nbsp;&nbsp;<a href="#" onclick="clearempSign('.$PersonID.');">Clear</a>
	
	echo '<div id="contentD">	
	 <table bgcolor="LightGrey" border="1" cellpadding="0" cellspacing="0" width="975">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Preparer and/or Translator Certification (check one):</strong></font><br />';
				if ($I9Cnt > 0) {							
					if ($row['Did_Not_Used_Preparer'] == 'Y') {
						echo '<input type="checkbox" name="nohelp" id="nohelp" checked onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
					} else {
						if ($row['Did_Used_Preparer'] == 'N') {						
							echo '<input type="checkbox" name="nohelp" id="nohelp" checked onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
						} else {
							echo '<input type="checkbox" name="nohelp" id="nohelp" onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
						}
					}
					echo 'I did not use a preparer or translator&nbsp;&nbsp;&nbsp;&nbsp;</font>';
					if ($row['Did_Used_Preparer'] == 'Y') {
						echo '<input type="checkbox" name="needhelp" id="needhelp" checked onclick="setneedhelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
					} else {
						echo '<input type="checkbox" name="needhelp" id="needhelp" onclick="setneedhelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
					} 
				} else {
					echo '<input type="checkbox" name="nohelp" id="nohelp" onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
					echo 'I did not use a preparer or translator&nbsp;&nbsp;&nbsp;&nbsp;</font>';
					echo '<input type="checkbox" name="needhelp" id="needhelp" onclick="setneedhelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
				}
				
				echo 'A preparer(s) and/or translator(s) assisted the empoyee in completing Section 1.</font><br />
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				(<i>Fields below must be completed and signed when preparers and/or translors assist an employee in completing
				Section 1.</i>)</font> 
			</td>	
		</tr>
	</table>
	</div>';
	echo '<div id="contentE">
 	<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="975">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
				I attest, under penalty of perjury, that I assisted in the completion of Section 1 of this form and that to the
				best of my knowledge the information is true and correct.</strong> 
				</font>
			</td>	
		</tr>
	</table>
	</div>';
	echo '<div id="contentF">
 	<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Signature of Preparer or Translator</font><br />
				<input name="ptsignature" id="ptsignature" class="" value="'.htmlspecialchars($ptsignature).'" size="55" maxlength="55" readonly><br />
				&nbsp;&nbsp;<a href="#" onclick="prepSign();">Click to sign your name</a>&nbsp;&nbsp;<a href="#" onclick="clearprepSign('.$PersonID.');">Clear</a>

			</td>	
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Today\'s Date (<i>mm/dd/yyyy</i>)</font><br />
				<input name="ptdate" id="ptdate" class="" value="'.$preparersignaturedate.'" size="35" maxlength="35" onblur="chkrequired()">
			</td>	
		</tr>
	</table>
	</div>';
	echo '<div id="contentG">	
 	<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name(<i>Family Name</i>)</font><br />
				<input name="ptlname" id="ptlname" value="'.htmlspecialchars($ptlname).'" size="50" maxlength="50" onblur="chkrequired()">
			</td>	
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name(<i>Given Name</i>)</font><br />
				<input name="ptfname" id="ptfname" value="'.htmlspecialchars($ptfname).'" size="50" maxlength="50" onblur="chkrequired()">
			</td>	
		</tr>	
	</table>
	</div>';
	echo '<div id="contentH">		
 	<table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="975">	
		<tr>
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Address(<i>Street Numer and Name</i>)</font><br />
				<input name="ptaddress" id="ptaddress" value="'.htmlspecialchars($ptaddress).'" size="55" maxlength="55" onblur="chkrequired()">
			</td>	
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">City or Town</font><br />
				<input name="ptcity" id="ptcity" value="'.htmlspecialchars($ptcity).'" size="40" maxlength="40" onblur="chkrequired()">
			</td>	
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">State</font><br />
				<select name="ptstate" id="ptstate" onblur="chkrequired()">
					<option value=""></option>';
					$sql = "Select Abbrev from State order by Name";
					$state_result = $dbo->prepare($sql);
					$state_result->execute();
					while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
						echo "<option value=".$rows[0].">".$rows[0]."</option>";
					}		
		echo '</select>				
			</td>	
			<td>
				<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip Code</font><br />
				<input name="ptzip" id="ptzip" value="'.htmlspecialchars($ptzip).'" size="15" maxlength="15" onblur="chkrequired()">
			</td>	
		</tr>
	</table>';
/*    	<td>
    		<p>
    			<br><INPUT TYPE="button" name="savesign" id="savesign" VALUE="Save Data" style="font-size:medium; font-family=Tahoma; color:green; border-radius:10px; padding: 5px 24px;">
    		</p>
    	</td>
*/
echo '<table>
	<tr>
    	<td>
    		<p>
    			<br><INPUT TYPE="button" name="icimsreturn" id="icimsreturn" VALUE="Submit Form & Return to iCIMS" style="font-size:medium; font-family=Tahoma; color:green; border-radius:10px; padding: 5px 24px;">
    		</p>
    	</td>
  	</tr>
</table>
</div>
	<div id="contentI">
		<span id="status" style="color:red; font-size: 16px;">Status:</span><br />
		<input name="statusmsg" id="statusmsg" style="color:red; font-size: 16px; border-top: #000000 0px solid; border-bottom: #000000 0px solid; border-right: #000000 0px solid; border-left: #000000 0px solid;" readonly>
	</div>
</div>';
/*
	if ($fndemp) {
		if ((substr($user_os,0,3) == 'Mac' || substr($user_os,0,6) == 'Window') && $user_browser == 'Firefox') {
			echo '<div name="sigdiv" id="sigdiv" style="visibility: hidden; style="position:absolute;">
  				<font face="Verdana, Arial, Helvetica, sans-serif">
  				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id="sigimg" src="Signature/PersonID-'.$PersonID.'Emp.png" alt="Click on the link to sign you name" width="200px" height="30px" style="position:absolute;top:730px;left:1%;"/>
				</font>
			</div>';
		} else {
			echo '<div name="sigdiv" id="sigdiv" style="visibility: hidden; style="position:absolute;">
  				<font face="Verdana, Arial, Helvetica, sans-serif">
  				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id="sigimg" src="Signature/PersonID-'.$PersonID.'Emp.png" alt="Click on the link to sign you name" width="200px" height="30px" style="position:absolute;top:680px;left:1%;"/>
				</font>
				</div>';
		}		
	}
	
	if ($fndprep) {
		if ((substr($user_os,0,3) == 'Mac' || substr($user_os,0,6) == 'Window') && $user_browser == 'Firefox') {
			echo '<div name="psigdiv" id="psigdiv" style="visibility: hidden; style="position:absolute;">
  				<font face="Verdana, Arial, Helvetica, sans-serif">
  					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id="psigimg" src="Signature/PersonID-'.$PersonID.'Prep.png" alt="" width="200px" height="30px" style="position:absolute;top:880px;left:1%;"/>
				</font>
			</div>';
		} else {
			echo '<div name="psigdiv" id="psigdiv" style="visibility: hidden; style="position:absolute;">
  				<font face="Verdana, Arial, Helvetica, sans-serif">
  					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id="psigimg" src="Signature/PersonID-'.$PersonID.'Prep.png" alt="" width="200px" height="30px" style="position:absolute;top:828px;left:1%;"/>
				</font>
			</div>';
		}		
	}
*/	
}

	include ('Upload/UploadDialog.php');

?>
 </form>
</body>
</html> 
<script language="JavaScript" type="text/javascript">

	function setindexes(StateCode, PTState) {
//		alert('In setindexes - StateCode = '+StateCode);
		var S1 = document.getElementById("state");
		var S2 = document.getElementById("ptstate");
		
		for(var x=0;x < S1.length; x++) {
			if (StateCode.toUpperCase() == S1.options[x].value) {
				S1.selectedIndex = x;
				$('#state').removeClass('requiredInput');
  				$('#state').addClass('goodInput');
  			}	
		}

		for(var x=0;x < S2.length; x++) {
			if (PTState.toUpperCase() == S2.options[x].value)
				S2.selectedIndex = x;
		}
		chkrequired();
		document.getElementById("form_clean").value = $("form").serialize();		
	}		
	$( "#icimsreturn" ).click(function() {
//		chkCompleted();
		var form_dirty = $("form").serialize(); 
		var form_clean = document.getElementById("form_clean").value;
		if (form_clean != form_dirty) {
			updateI9Section1();
//			if (confirm('You have unsaved changes. If you would like to save the changes click Cancel and click the Save Data button or click OK to return to iCIMS.')) {
			ReturnToiCIMS();
//			}
    	} else {		
			ReturnToiCIMS();
		}	
	});

	function chkCompleted() {
//	alert("In chkCompleted");
		var complete = true;
		if (document.getElementById("lname").value == '') {
			complete = false;
		}	
		if (document.getElementById("fname").value == '') {
			complete = false;
		}	
		if (document.getElementById("lname").value == 'Unknown' && document.getElementById("fname").value == 'Unknown') {
			complete = false;
		}	
		if (document.getElementById("mname").value == '') {
			complete = false;
		}	
		if (document.getElementById("oname").value == '') {
			complete = false;
		}	
		if (document.getElementById("address").value == '') {
			complete = false;
		}	
		if (document.getElementById("apt").value == '') {
			complete = false;
		}	
		if (document.getElementById("city").value == '') {
			complete = false;
		}	
		
		if (document.getElementById("state").value == '') {
			complete = false;
		}	
		if (document.getElementById("zip").value == '') {
			complete = false;
		}	
		if (document.getElementById("dob").value == '') {
			complete = false;
		}			
		if (document.getElementById("ssn1-1").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn1-2").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn1-3").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn2-1").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn2-2").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn3-1").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn3-2").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn3-3").value == '') {
			complete = false;
		}	
		if (document.getElementById("ssn3-4").value == '') {
			complete = false;
		}	
	
		if (document.getElementById("citizen").checked == false && document.getElementById("noncitizen").checked == false && 
			document.getElementById("legalresident").checked == false && document.getElementById("alien").checked == false) {
			complete = false;
		}	
		if (document.getElementById("legalresident").checked == true) {
			if (document.getElementById("regno").value == '') {
				complete = false;
			}
		}		
		if (document.getElementById("alien").checked == true) {
			if (document.getElementById("alienexpdate").value == '') {
				complete = false;
			}
			if (document.getElementById("aliennumber").value == '' && document.getElementById("admissionnumber").value == '' &&
				document.getElementById("foreignpassport").value == '' && document.getElementById("countryofissuance").value == '') {
				complete = false;
			}
		} 
		if (document.getElementById("fndemp").value == false) {
			complete = false;
		}	
//		var PersonID = document.getElementById("PersonID").value;
//    	var	url = 'http://proteus.bisi.com/I9/Signature/PersonID-'+PersonID+'Emp.png';
//		$.get(url)
//   		.done(function() { 
//        		alert("File Fnd"); 
//    		}).fail(function() { 
//        		alert("File Not Fnd"); 
//  				complete = false;
//    		});
    	
		if (document.getElementById("empdate").value == '') {
			complete = false;
		}
		if (document.getElementById("needhelp").checked == false && document.getElementById("nohelp").checked == false) {
			complete = false;
		}	
				
		if (document.getElementById("needhelp").checked == true) {
			if (document.getElementById("fndprep").value == false) {
				complete = false;
			}	
/*		
	    	var	url = 'http://proteus.bisi.com/I9/Signature/PersonID-'+PersonID+'Prep.png';
			$.get(url)
    			.done(function() { 
//        			alert("File Fnd"); 
    			}).fail(function() { 
//        			alert("File Not Fnd"); 
  					complete = false;
    			});
*/
			if (document.getElementById("ptdate").value == '') {
				complete = false;
			}	
					
			if (document.getElementById("ptlname").value == '') {
				complete = false;
			}	
			if (document.getElementById("ptfname").value == '') {
				complete = false;
			}	
			if (document.getElementById("ptaddress").value == '') {
				complete = false;
			}	
			if (document.getElementById("ptcity").value == '') {			
				complete = false;
			}	
			if (document.getElementById("ptstate").value == '') {
				complete = false;
			}	
 			if (document.getElementById("ptzip").value == '') {
				complete = false;
			}	
		} 
		if (complete) {
//		alert("Completed");
			document.getElementById("Completed").value = 'Y';
			document.getElementById("statusmsg").value = 'I9 Form completed';
		} else {
//		alert("Not Completed");	
			document.getElementById("Completed").value = 'N';
			document.getElementById("statusmsg").value = 'I9 Form not completed';
		}
	}	
	function ReturnToiCIMS() {
//		alert("In ReturnToiCIMS - Completed:"+document.getElementById("Completed").value);
		var completed = document.getElementById("Completed").value;
		var ReturnURL = document.getElementById("ReturnURL").value;
		var IncompleteURL = document.getElementById("IncompleteURL").value;
		if (completed == 'Y') {
//			alert('Complete = true');
			var PersonID = document.getElementById("PersonID").value;
			var JobID = document.getElementById("JobID").value;
			var CustomerID = document.getElementById("CustomerID").value;
			$.ajax({
				type: "POST",
				url: "ajax_Set_I9Status.php", 
				data: {PersonID: PersonID, JobID: JobID, CustomerID: CustomerID},
 				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2 > '' ) {
						alert(obj2);
					} else {
						alert('The I9 Section 1 is completed. Thank You');
						window.location = ReturnURL;
					}				 
					return;
				},	
				error: function(XMLHttpRequest, textStatus, errorThrown) {
//					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				} 					
			}); 
		} else {
//			alert('Completed = false');
			if (confirm('The I9 form in not complete. If you would like to complete the form now click Cancel otherwise click OK and rerturn later to finish.')) {
				window.location = IncompleteURL;
			}
		}			
	};
	$( "#savesign" ).click(function() {
//		alert("In save");
		var PersonID = document.getElementById("PersonID").value;
		var JobID = document.getElementById("JobID").value;
		var CustomerID = document.getElementById("CustomerID").value;
		var Last_Name = document.getElementById("lname").value;
		var First_Name = document.getElementById("fname").value;
		if (Last_Name == 'Unknown' && First_Name == 'Unknown') {
			$('#lname').focus();
			alert("Both First Name and Last Name can not be Uknown");
			return false;
		}	
		if (document.getElementById("mname").value == '') {
			var Middle_Name = 'N/A';
		} else {	
			var Middle_Name = document.getElementById("mname").value;
		}
		if (document.getElementById("oname").value == '') {
			var Other_Last_Name = 'N/A';
		} else {	
			var Other_Last_Name = document.getElementById("oname").value;
		}	
		var Address = document.getElementById("address").value;
		if (document.getElementById("apt").value == '') {
			var Apt = 'N/A';
		} else {	
			var Apt = document.getElementById("apt").value;
		}	
		var City = document.getElementById("city").value;
		var State_Code = document.getElementById("state").value;
		var Zip_Code = document.getElementById("zip").value;
		var Date_of_Birth = document.getElementById("dob").value;
		var SSN = document.getElementById("ssn1-1").value + document.getElementById("ssn1-2").value + document.getElementById("ssn1-3").value;
		SSN = SSN + '-' + document.getElementById("ssn2-1").value + document.getElementById("ssn2-2").value;
		SSN = SSN + '-' + document.getElementById("ssn3-1").value + document.getElementById("ssn3-2").value + document.getElementById("ssn3-3").value + document.getElementById("ssn3-4").value;
		var Email = document.getElementById("email").value
		var Phone = document.getElementById("ephone").value
		if (document.getElementById("citizen").checked == true) {
			var Citizen = 'Y';
		} else {
			var Citizen = 'N';
		}			
		if (document.getElementById("noncitizen").checked == true) {
			var NonCitizen = 'Y';
		} else {
			var NonCitizen = 'N';
		}
		if (document.getElementById("legalresident").checked == true) {
			var Permanent_Resident = 'Y';
			var Registration1_USCIS = document.getElementById("regno").value;
		} else {
			var Permanent_Resident = 'N';
			var Registration1_USCIS = '';
		}
		if (document.getElementById("alien").checked == true) {
			var Alien_Auth_Work = 'Y';
			var Auth_Work_Date = document.getElementById("alienexpdate").value;
			var Registration2_USCIS = document.getElementById("aliennumber").value;
			var I94_Admission = document.getElementById("admissionnumber").value;
			var Foreign_Passport = document.getElementById("foreignpassport").value;
			var Foreign_Passport_Country = document.getElementById("countryofissuance").value;
		} else {	
			var Alien_Auth_Work = 'N';
			var Auth_Work_Date = '';
			var Registration2_USCIS = '';
			var I94_Admission = '';
			var Foreign_Passport = '';
			var Foreign_Passport_Country = '';
		}
		if (Registration1_USCIS > '') {
			Registration_USCIS = Registration1_USCIS;
		}
		if (Registration2_USCIS > '') {
			Registration_USCIS = Registration2_USCIS;
		}
		
		
		
		
		if (document.getElementById("clicksign").checked) {
			var Digital_Employee_Signature = 'Y';
		} else {	
			var Digital_Employee_Signature = 'N';
		}
        var Employee_Signature = document.getElementById("employeesignature").value;
		if (Employee_Signature > '') {
			var Employee_Signature_Date = document.getElementById("empdate").value;
		} else {  	
			var Employee_Signature_Date = '';
		}	
		if (document.getElementById("needhelp").checked == true) {
			var Did_Used_Preparer = 'Y';
			var Did_Not_Used_Preparer = 'N';
		
			if (document.getElementById("preparerclicksign").checked) {
				var Digital_Preparer_Signature = 'Y';
			} else {	
				var Digital_Preparer_Signature = 'N';
			}
			var Preparer_Signature_Date = document.getElementById("ptdate").value;
			var Preparer_Last_Name = document.getElementById("ptlname").value;
			var Preparer_First_Name = document.getElementById("ptfname").value;
			var Preparer_Address = document.getElementById("ptaddress").value;
			var Preparer_City = document.getElementById("ptcity").value;			
			var Preparer_State = document.getElementById("ptstate").value;
 			var Preparer_Zip_Code = document.getElementById("ptzip").value;
		} else {
			if (document.getElementById("nohelp").checked == true) {
				var Did_Not_Used_Preparer = 'Y';
			} else {
			 	var Did_Not_Used_Preparer = 'N';
			}
			var Did_Used_Preparer = 'N';
			var Preparer_Signature = '';
			var Preparer_Signature_Date = '';
			var Preparer_Last_Name = '';
			var Preparer_First_Name = '';
			var Preparer_Address = '';
			var Preparer_City = '';			
			var Preparer_State = '';
			var Preparer_Zip_Code = '';
		}	 		
		var CD = document.getElementById("CD").value;

		$.ajax({
			type: "POST",
			url: "ajax_add_I9Section1.php", 
			data: {PersonID: PersonID, JobID: JobID, CustomerID: CustomerID, Last_Name: Last_Name, First_Name: First_Name, Middle_Name: Middle_Name, Other_Last_Name: Other_Last_Name
					, SSN: SSN, Date_of_Birth: Date_of_Birth, Phone: Phone, Email: Email, Address: Address, Apt: Apt, City: City
					, State_Code: State_Code, Zip_Code: Zip_Code, Citizen: Citizen, NonCitizen: NonCitizen, Permanent_Resident: Permanent_Resident
					, Registration_USCIS: Registration_USCIS, Alien_Auth_Work: Alien_Auth_Work, Auth_Work_Date: Auth_Work_Date
					, I94_Admission: I94_Admission, Foreign_Passport: Foreign_Passport, Foreign_Passport_Country: Foreign_Passport_Country
					, Employee_Signature: Employee_Signature, Employee_Signature_Date: Employee_Signature_Date
					, Did_Used_Preparer: Did_Used_Preparer, Did_Not_Used_Preparer: Did_Not_Used_Preparer
					, Preparer_Signature: Preparer_Signature, Preparer_Signature_Date: Preparer_Signature_Date
					, Preparer_Last_Name: Preparer_Last_Name, Preparer_First_Name: Preparer_First_Name, Preparer_Address: Preparer_Address
					, Preparer_City: Preparer_City, Preparer_State: Preparer_State, Preparer_Zip_Code: Preparer_Zip_Code, CD: CD},
 			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {
					alert('Data saved successfully');
					document.getElementById("form_clean").value = $("form").serialize();	
				}				 
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
		
	});
	function updateI9Section1() {
//		alert("In update");
		var PersonID = document.getElementById("PersonID").value;
		var JobID = document.getElementById("JobID").value;
		var CustomerID = document.getElementById("CustomerID").value;
		var Last_Name = document.getElementById("lname").value;
		var First_Name = document.getElementById("fname").value;
		if (Last_Name == 'Unknown' && First_Name == 'Unknown') {
			$('#lname').focus();
			alert("Both First Name and Last Name can not be Unknown");
			return false;
		}	
		if (document.getElementById("mname").value == '') {
			var Middle_Name = 'N/A';
		} else {	
			var Middle_Name = document.getElementById("mname").value;
		}
		if (document.getElementById("oname").value == '') {
			var Other_Last_Name = 'N/A';
		} else {	
			var Other_Last_Name = document.getElementById("oname").value;
		}	
		var Address = document.getElementById("address").value;
		if (document.getElementById("apt").value == '') {
			var Apt = 'N/A';
		} else {	
			var Apt = document.getElementById("apt").value;
		}	
		var City = document.getElementById("city").value;
		var State_Code = document.getElementById("state").value;
		var Zip_Code = document.getElementById("zip").value;
		var Date_of_Birth = document.getElementById("dob").value;
		var SSN = document.getElementById("ssn1-1").value + document.getElementById("ssn1-2").value + document.getElementById("ssn1-3").value;
		SSN = SSN + '-' + document.getElementById("ssn2-1").value + document.getElementById("ssn2-2").value;
		SSN = SSN + '-' + document.getElementById("ssn3-1").value + document.getElementById("ssn3-2").value + document.getElementById("ssn3-3").value + document.getElementById("ssn3-4").value;
		var Email = document.getElementById("email").value
		var Phone = document.getElementById("ephone").value
		if (document.getElementById("citizen").checked == true) {
			var Citizen = 'Y';
		} else {
			var Citizen = 'N';
		}			
		if (document.getElementById("noncitizen").checked == true) {
			var NonCitizen = 'Y';
		} else {
			var NonCitizen = 'N';
		}
		if (document.getElementById("legalresident").checked == true) {
			var Permanent_Resident = 'Y';
			var Registration1_USCIS = document.getElementById("regno").value;
		} else {
			var Permanent_Resident = 'N';
			var Registration1_USCIS = '';
		}
		if (document.getElementById("alien").checked == true) {
			var Alien_Auth_Work = 'Y';
			var Auth_Work_Date = document.getElementById("alienexpdate").value;
			var Registration2_USCIS = document.getElementById("aliennumber").value;
			var I94_Admission = document.getElementById("admissionnumber").value;
			var Foreign_Passport = document.getElementById("foreignpassport").value;
			var Foreign_Passport_Country = document.getElementById("countryofissuance").value;
		} else {	
			var Alien_Auth_Work = 'N';
			var Auth_Work_Date = '';
			var Registration2_USCIS = '';
			var I94_Admission = '';
			var Foreign_Passport = '';
			var Foreign_Passport_Country = '';
		}
		var Registration_USCIS = '';
		if (Registration1_USCIS > '') {
			Registration_USCIS = Registration1_USCIS;
		}
		if (Registration2_USCIS > '') {
			Registration_USCIS = Registration2_USCIS;
		}
		
//		var url = 'Signature/PersonID-'+PersonID+'Emp.png';
		if (document.getElementById("clicksign").checked) {
			var Digital_Employee_Signature = 'Y';
		} else {	
			var Digital_Employee_Signature = 'N';
		}
        var Employee_Signature = document.getElementById("employeesignature").value;
		if (Employee_Signature > '') {
			var Employee_Signature_Date = document.getElementById("empdate").value;
		} else {  	
			var Employee_Signature_Date = '';
		}	
		if (document.getElementById("needhelp").checked == true) {
			var Did_Used_Preparer = 'Y';
			var Did_Not_Used_Preparer = 'N';
			if (document.getElementById("preparerclicksign").checked) {
				var Digital_Preparer_Signature = 'Y';
			} else {	
				var Digital_Preparer_Signature = 'N';
			}
			var Preparer_Signature = '';
			var Preparer_Signature_Date = document.getElementById("ptdate").value;
			var Preparer_Last_Name = document.getElementById("ptlname").value;
			var Preparer_First_Name = document.getElementById("ptfname").value;
			var Preparer_Address = document.getElementById("ptaddress").value;
			var Preparer_City = document.getElementById("ptcity").value;			
			var Preparer_State = document.getElementById("ptstate").value;
 			var Preparer_Zip_Code = document.getElementById("ptzip").value;
		} else {
			if (document.getElementById("nohelp").checked == true) {
				var Did_Not_Used_Preparer = 'Y';
			} else {
			 	var Did_Not_Used_Preparer = 'N';
			}
			var Digital_Preparer_Signature = 'N';
			var Did_Used_Preparer = 'N';
			var Preparer_Signature = '';
			var Preparer_Signature_Date = '';
			var Preparer_Last_Name = '';
			var Preparer_First_Name = '';
			var Preparer_Address = '';
			var Preparer_City = '';			
			var Preparer_State = '';
			var Preparer_Zip_Code = '';
		}	 		
		var CD = document.getElementById("CD").value;
		$.ajax({
			type: "POST",
			url: "ajax_add_I9Section1.php", 
			data: {PersonID: PersonID, JobID: JobID, CustomerID: CustomerID, Last_Name: Last_Name, First_Name: First_Name, Middle_Name: Middle_Name, Other_Last_Name: Other_Last_Name
					, SSN: SSN, Date_of_Birth: Date_of_Birth, Phone: Phone, Email: Email, Address: Address, Apt: Apt, City: City
					, State_Code: State_Code, Zip_Code: Zip_Code, Citizen: Citizen, NonCitizen: NonCitizen, Permanent_Resident: Permanent_Resident
					, Registration_USCIS: Registration_USCIS, Alien_Auth_Work: Alien_Auth_Work, Auth_Work_Date: Auth_Work_Date
					, I94_Admission: I94_Admission, Foreign_Passport: Foreign_Passport, Foreign_Passport_Country: Foreign_Passport_Country
					, Employee_Signature: Employee_Signature, Employee_Signature_Date: Employee_Signature_Date
					, Did_Used_Preparer: Did_Used_Preparer, Did_Not_Used_Preparer: Did_Not_Used_Preparer, Preparer_Signature: Preparer_Signature
					, Preparer_Signature_Date: Preparer_Signature_Date, Preparer_Last_Name: Preparer_Last_Name, Preparer_First_Name: Preparer_First_Name
					, Preparer_Address: Preparer_Address, Preparer_City: Preparer_City, Preparer_State: Preparer_State, Preparer_Zip_Code: Preparer_Zip_Code
					, CD: CD, Digital_Employee_Signature: Digital_Employee_Signature, Digital_Preparer_Signature: Digital_Preparer_Signature},
 			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {
					chkrequired();	
					document.getElementById("form_clean").value = $("form").serialize();	
				}				 
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
//				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 	
	}
</script>
<script language="JavaScript" type="text/javascript">
	function empSign() {
		var personid = document.getElementById("PersonID").value;
		var CD = document.getElementById("CD").value;
		var JobID = document.getElementById("JobID").value;
		var CustomerID = document.getElementById("CustomerID").value;
//		alert("CD: "+CD);
		window.location = 'wetsignature1.php?PersonID='+personid+'&JobId='+JobID+'&CustomerId='+CustomerID+'&SignType=Emp&CD='+CD;
	}
	function prepSign() {
		var personid = document.getElementById("PersonID").value;
		var CD = document.getElementById("CD").value;
		var JobID = document.getElementById("JobID").value;
		var CustomerID = document.getElementById("CustomerID").value;
		window.location = 'wetsignature1.php?PersonID='+personid+'&JobId='+JobID+'&CustomerId='+CustomerID+'&SignType=Emp&CD='+CD;
	}
	function getsig(PersonID) {
//		alert('In getsig');
		imgsig = document.getElementById("sigimg");
		imgsig.src = "Signature/PersonID-"+PersonID+"Emp.png"
   		sig = document.getElementById("sigdiv");
		sig.style.visibility = "visible";		
	}
	function getpsig(PersonID) {
//		alert('In getsig');
		imgsig = document.getElementById("psigimg");
		imgsig.src = "Signature/PersonID-"+PersonID+"Prep.png"
   		sig = document.getElementById("psigdiv");
		sig.style.visibility = "visible";		
	}

	function clearempSign(PersonID) {
//		alert('In clearempSign');
		var filename = "Signature/PersonID-"+PersonID+"Emp.png";
		imgsig = document.getElementById("sigimg");
		imgsig.src = ""
   		sig = document.getElementById("sigdiv");
		sig.style.visibility = "hidden";	
		$.ajax({
			type: "POST",
			url: "ajax_delete_signature.php", 
			data: {filename: filename},
 			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} 
				document.getElementById("fndemp").value = false;
				$('#employeesignature').removeClass('goodInput');
 				$('#employeesignature').addClass('requiredInput');
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 			
	}
	function clearprepSign(PersonID) {
//		alert('In getsig');
		var filename = "Signature/PersonID-"+PersonID+"Prep.png" 
		imgsig = document.getElementById("psigimg");
		imgsig.src = ""
   		sig = document.getElementById("psigdiv");
		sig.style.visibility = "hidden";	
		$.ajax({
			type: "POST",
			url: "ajax_delete_signature.php", 
			data: {filename: filename},
 			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} 
				document.getElementById("fndprep").value = false;
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 			
	}

	function setesig() {
		sig = document.getElementById("signature");
		esig = document.getElementById("esig");
		esig.value = sig.value;
		return sig.value;
	}
	function chkrequired() {
		if (/\S/.test(document.getElementById("lname").value)) {
			$('#lname').removeClass('requiredInput');
  			$('#lname').addClass('goodInput');
		} else {
			$('#lname').removeClass('goodInput');
  			$('#lname').addClass('requiredInput');
		}
		if (/\S/.test(document.getElementById("fname").value)) {
			$('#fname').removeClass('requiredInput');
  			$('#fname').addClass('goodInput');
		} else {
			$('#fname').removeClass('goodInput');
  			$('#fname').addClass('requiredInput');
		}
		if (/\S/.test(document.getElementById("mname").value)) {
			$('#mname').removeClass('requiredInput');
  			$('#mname').addClass('goodInput');
		} else {
			$('#mname').removeClass('goodInput');
  			$('#mname').addClass('requiredInput');
		}
		if (/\S/.test(document.getElementById("oname").value)) {
			$('#oname').removeClass('requiredInput');
  			$('#oname').addClass('goodInput');
		} else {
			$('#oname').removeClass('goodInput');
  			$('#oname').addClass('requiredInput');
		}
		if (/\S/.test(document.getElementById("address").value)) {
			$('#address').removeClass('requiredInput');
  			$('#address').addClass('goodInput');
		} else {
			$('#address').removeClass('goodInput');
  			$('#address').addClass('requiredInput');
		}
		if (/\S/.test(document.getElementById("apt").value)) {
			$('#apt').removeClass('requiredInput');
  			$('#apt').addClass('goodInput');
		} else {
			$('#apt').removeClass('goodInput');
  			$('#apt').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("city").value)) {
			$('#city').removeClass('requiredInput');
  			$('#city').addClass('goodInput');
		} else {
			$('#city').removeClass('goodInput');
  			$('#city').addClass('requiredInput');
		}	
		
		if (document.getElementById("state").value > '') {
			$('#state').removeClass('requiredInput');
  			$('#state').addClass('goodInput');
		} else {
			$('#state').removeClass('goodInput');
  			$('#state').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("zip").value)) {
			$('#zip').removeClass('requiredInput');
  			$('#zip').addClass('goodInput');
		} else {
			$('#zip').removeClass('goodInput');
  			$('#zip').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("dob").value)) {
			$('#dob').removeClass('requiredInput');
  			$('#dob').addClass('goodInput');
		} else {
			$('#dob').removeClass('goodInput');
  			$('#dob').addClass('requiredInput');
		}			
		if (/\S/.test(document.getElementById("ssn1-1").value)) {
			$('#ssn1-1').removeClass('requiredInput');
  			$('#ssn1-1').addClass('goodInput');
		} else {
			$('#ssn1-1').removeClass('goodInput');
  			$('#ssn1-1').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn1-2").value)) {
			$('#ssn1-2').removeClass('requiredInput');
  			$('#ssn1-2').addClass('goodInput');
		} else {
			$('#ssn1-2').removeClass('goodInput');
  			$('#ssn1-2').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn1-3").value)) {
			$('#ssn1-3').removeClass('requiredInput');
  			$('#ssn1-3').addClass('goodInput');
		} else {
			$('#ssn1-3').removeClass('goodInput');
  			$('#ssn1-3').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn2-1").value)) {
			$('#ssn2-1').removeClass('requiredInput');
  			$('#ssn2-1').addClass('goodInput');
		} else {
			$('#ssn2-1').removeClass('goodInput');
  			$('#ssn2-1').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn2-2").value)) {
			$('#ssn2-2').removeClass('requiredInput');
  			$('#ssn2-2').addClass('goodInput');
		} else {
			$('#ssn2-2').removeClass('goodInput');
  			$('#ssn2-2').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn3-1").value)) {
			$('#ssn3-1').removeClass('requiredInput');
  			$('#ssn3-1').addClass('goodInput');
		} else {
			$('#ssn3-1').removeClass('goodInput');
  			$('#ssn3-1').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn3-2").value)) {
			$('#ssn3-2').removeClass('requiredInput');
  			$('#ssn3-2').addClass('goodInput');
		} else {
			$('#ssn3-2').removeClass('goodInput');
  			$('#ssn3-2').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn3-3").value)) {
			$('#ssn3-3').removeClass('requiredInput');
  			$('#ssn3-3').addClass('goodInput');
		} else {
			$('#ssn3-3').removeClass('goodInput');
  			$('#ssn3-3').addClass('requiredInput');
		}	
		if (/\S/.test(document.getElementById("ssn3-4").value)) {
			$('#ssn3-4').removeClass('requiredInput');
  			$('#ssn3-4').addClass('goodInput');
		} else {
			$('#ssn3-4').removeClass('goodInput');
  			$('#ssn3-4').addClass('requiredInput');
		}	
		if (document.getElementById("citizen").checked || document.getElementById("noncitizen").checked || 
			document.getElementById("legalresident").checked || document.getElementById("alien").checked) {
			$('#tblcitizen').removeClass('requiredInput');
  			$('#tblcitizen').addClass('goodInput');
			$('#tblalien').removeClass('requiredInput');
  			$('#tblalien').addClass('goodInput');
		} else {
			$('#tblcitizen').removeClass('goodInput');
  			$('#tblcitizen').addClass('requiredInput');
			$('#tblalien').removeClass('goodInput');
  			$('#tblalien').addClass('requiredInput');
 		}	
		if (document.getElementById("legalresident").checked) {
			if (/\S/.test(document.getElementById("regno").value)) {
				$('#tblcitizen').removeClass('requiredInput');
  				$('#tblcitizen').addClass('goodInput');
				$('#tblalien').removeClass('requiredInput');
  				$('#tblalien').addClass('goodInput');
			} else {
				$('#tblcitizen').removeClass('goodInput');
  				$('#tblcitizen').addClass('requiredInput');
				$('#tblalien').removeClass('goodInput');
  				$('#tblalien').addClass('requiredInput');
 			}	
		}		
		if (document.getElementById("alien").checked) {
			if (document.getElementById("alienexpdate").value == '') {
				$('#tblcitizen').removeClass('goodInput');
  				$('#tblcitizen').addClass('requiredInput');
				$('#tblalien').removeClass('goodInput');
  				$('#tblalien').addClass('requiredInput');
			} else {
				$('#tblcitizen').removeClass('requiredInput');
  				$('#tblcitizen').addClass('goodInput');
				$('#tblalien').removeClass('requiredInput');
  				$('#tblalien').addClass('goodInput');
 			}	
			if (/\S/.test(document.getElementById("aliennumber").value) || 
				/\S/.test(document.getElementById("admissionnumber").value) || 
				(/\S/.test(document.getElementById("foreignpassport").value) && 
				/\S/.test(document.getElementById("countryofissuance").value))) {
				$('#tblcitizen').removeClass('requiredInput');
  				$('#tblcitizen').addClass('goodInput');
				$('#tblalien').removeClass('requiredInput');
  				$('#tblalien').addClass('goodInput');
			} else {
				$('#tblcitizen').removeClass('goodInput');
 				$('#tblcitizen').addClass('requiredInput');
				$('#tblalien').removeClass('goodInput');
  				$('#tblalien').addClass('requiredInput');
 			}	
		}
		if (document.getElementById("clicksign").checked) {
			$('#clicksignature"').removeClass('requiredInput');
  			$('#clicksignature"').addClass('goodInput');		
		} else {
  			$('#clicksignature"').addClass('requiredInput');		
			$('#clicksignature"').removeClass('goodInput');
		}
		
		if (/\S/.test(document.getElementById("employeesignature").value)) {
			$('#employeesignature').removeClass('requiredInput');
  			$('#employeesignature').addClass('goodInput');
		} else {
			$('#employeesignature').removeClass('goodInput');
 			$('#employeesignature').addClass('requiredInput');
 		}	
 
 		if (/\S/.test(document.getElementById("empdate").value)) {
			$('#empdate').removeClass('requiredInput');
  			$('#empdate').addClass('goodInput');
		} else {
			$('#empdate').removeClass('goodInput');
 			$('#empdate').addClass('requiredInput');
 		}	
		if (document.getElementById("needhelp").checked) {
			if (document.getElementById("fndprep").value) {
				$('#ptsignature').removeClass('requiredInput');
  				$('#ptsignature').addClass('goodInput');
			} else {
				$('#ptsignature').removeClass('goodInput');
 				$('#ptsignature').addClass('requiredInput');
 			}	
	 		if (/\S/.test(document.getElementById("ptdate").value)) {
				$('#ptdate').removeClass('requiredInput');
  				$('#ptdate').addClass('goodInput');
			} else {
				$('#ptdate').removeClass('goodInput');
 				$('#ptdate').addClass('requiredInput');
 			}	
			if (/\S/.test(document.getElementById("ptlname").value)) {
				$('#ptlname').removeClass('requiredInput');
  				$('#ptlname').addClass('goodInput');
			} else {
				$('#ptlname').removeClass('goodInput');
 				$('#ptlname').addClass('requiredInput');
 			}	
			if (/\S/.test(document.getElementById("ptfname").value)) {
				$('#ptfname').removeClass('requiredInput');
  				$('#ptfname').addClass('goodInput');
			} else {
				$('#ptfname').removeClass('goodInput');
 				$('#ptfname').addClass('requiredInput');
 			}	
			if (/\S/.test(document.getElementById("ptaddress").value)) {
				$('#ptaddress').removeClass('requiredInput');
  				$('#ptaddress').addClass('goodInput');
			} else {
				$('#ptaddress').removeClass('goodInput');
 				$('#ptaddress').addClass('requiredInput');
 			}	
			if (/\S/.test(document.getElementById("ptcity").value)) {			
				$('#ptcity').removeClass('requiredInput');
  				$('#ptcity').addClass('goodInput');
			} else {
				$('#ptcity').removeClass('goodInput');
 				$('#ptcity').addClass('requiredInput');
 			}	
			if (document.getElementById("ptstate").value > '') {
				$('#ptstate').removeClass('requiredInput');
  				$('#ptstate').addClass('goodInput');
			} else {
				$('#ptstate').removeClass('goodInput');
 				$('#ptstate').addClass('requiredInput');
 			}	
 			if (/\S/.test(document.getElementById("ptzip").value)) {
				$('#ptzip').removeClass('requiredInput');
  				$('#ptzip').addClass('goodInput');
			} else {
				$('#ptzip').removeClass('goodInput');
 				$('#ptzip').addClass('requiredInput');
 			}	
		} 

		if (document.getElementById("nohelp").checked) {
			$('#ptsignature').removeClass('requiredInput');
			$('#ptsignature').removeClass('goodInput');

	 		document.getElementById("ptdate").value = ''; 
			$('#ptdate').removeClass('requiredInput');
			$('#ptdate').removeClass('goodInput');
			
			document.getElementById("ptlname").value = '';
			$('#ptlname').removeClass('requiredInput');
			$('#ptlname').removeClass('goodInput');
 
 			document.getElementById("ptfname").value = '';
			$('#ptfname').removeClass('requiredInput');
			$('#ptfname').removeClass('goodInput');

			document.getElementById("ptaddress").value = '';
			$('#ptaddress').removeClass('requiredInput');
			$('#ptaddress').removeClass('goodInput');

			document.getElementById("ptcity").value = '';		
			$('#ptcity').removeClass('requiredInput');
			$('#ptcity').removeClass('goodInput');

			document.getElementById("ptstate").value > '';
			$('#ptstate').removeClass('requiredInput');
			$('#ptstate').removeClass('goodInput');

 			document.getElementById("ptzip").value = '';
			$('#ptzip').removeClass('requiredInput');
			$('#ptzip').removeClass('goodInput');			

		} 
//	alert("In chkrequired calling chkCompleted");

		chkCompleted();	
		if (document.getElementById("nohelp").checked) {
			var PersonID = document.getElementById("PersonID").value;
			clearprepSign(PersonID);
		}				
	}	
	function setCitizen() {
		if (document.getElementById("citizen").checked) {
			document.getElementById("noncitizen").checked = false;
			document.getElementById("legalresident").checked = false;
			document.getElementById("alien").checked = false;
			document.getElementById("regno").value = '';	
			document.getElementById("alienexpdate").value = '';
			document.getElementById("aliennumber").value = '';
			document.getElementById("admissionnumber").value = '';
			document.getElementById("foreignpassport").value = '';
			document.getElementById("countryofissuance").value = '';
		}	
		updateI9Section1();				
	}
	function setNonCitizen() {
		if (document.getElementById("noncitizen").checked) {
			document.getElementById("citizen").checked = false;
			document.getElementById("legalresident").checked = false;
			document.getElementById("alien").checked = false;
			document.getElementById("regno").value = '';	
			document.getElementById("alienexpdate").value = '';
			document.getElementById("aliennumber").value = '';
			document.getElementById("admissionnumber").value = '';
			document.getElementById("foreignpassport").value = '';
			document.getElementById("countryofissuance").value = '';
		}	
		updateI9Section1();					
	}
	function setLegalResident() {
		if (document.getElementById("legalresident").checked) {
			document.getElementById("citizen").checked = false;
			document.getElementById("noncitizen").checked = false;
			document.getElementById("alien").checked = false;
			document.getElementById("alienexpdate").value = '';
			document.getElementById("aliennumber").value = '';
			document.getElementById("admissionnumber").value = '';
			document.getElementById("foreignpassport").value = '';
			document.getElementById("countryofissuance").value = '';
		}	
		updateI9Section1();						
	}
	function setAlien() {
		if (document.getElementById("alien").checked) {
			document.getElementById("citizen").checked = false;
			document.getElementById("noncitizen").checked = false;
			document.getElementById("legalresident").checked = false;
			document.getElementById("regno").value = '';				
		}	
		updateI9Section1();						
	}
	function setnohelp() {
		if (document.getElementById("nohelp").checked) {
			document.getElementById("needhelp").checked = false;
		}
		updateI9Section1();						
	}	
	function setneedhelp() {
		if (document.getElementById("needhelp").checked) {
			document.getElementById("nohelp").checked = false;
		}	
		updateI9Section1();						
	}	
	function setSignature() {
		if (document.getElementById("clicksign").checked) {
			var fname = document.getElementById("firstname").value;
			var lname = document.getElementById("lastname").value;
			var mname = document.getElementById("middlename").value;
			var d = new Date();
			var strDate = (d.getMonth()+1) + "/" + d.getDate() + "/" +d.getFullYear();
			document.getElementById("employeesignature").value = "Digitally signed by: "+fname+' '+mname+' '+lname+' on '+strDate;
		} else {	
			document.getElementById("employeesignature").value = "";
		}	
		updateI9Section1();
	}
</script>
<script src="WetSignature/Signature.js"></script>
<script src="Upload/Upload.js"></script>
