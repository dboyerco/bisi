<?php
require_once('../../pdotriton.php');

$PersonID = 15341;
$JobID = 3067;
$CD = 'j9kNiyGx0T2uDzLd';
$CustomerID = 9351;

session_start();

$codeid = $dbo->query("Select CodeID from I9Section1 where PersonID = ".$PersonID." and JobID = ".$JobID." and CustomerID = ".$CustomerID.";")->fetchColumn();
$_SESSION['SESS_JobID'] = $JobID;
$_SESSION['SESS_CustomerID'] = $CustomerID;
$_SESSION['SESS_CodeID'] = $codeid;

if(!isset($CD)) {
	$CD = '';
}

$cnt = 0;
$cnt = $dbo->query("Select count(*) from I9Section1 where PersonID = ".$PersonID." and JobID = ".$JobID." and CustomerID = ".$CustomerID.";")->fetchColumn();
$date = date("m/d/Y");
#$PersonID = 1;
#$FormAction = "disclosure2.php?PersonID=".$PersonID;
$lname = '';
$fname = '';
$mname = '';
$oname = '';
$address = '';
$apt = 'N/A';
$city = '';
$state = '';
$zip = '';
$dob = '';
$ssn1_1 = '';
$ssn1_2 = '';
$ssn1_3 = '';
$ssn2_1 = '';
$ssn2_2 = '';
$ssn3_1 = '';
$ssn3_2 = '';
$ssn3_3 = '';
$ssn3_4 = '';
$email = '';
$ephone = '';
$citizen = '';
$noncitizen = '';
$legalresident = '';
$regno = '';
$alienexpdate = '';
$aliennumber = '';
$admissionnumber = '';
$foreignpassport = '';
$countryofissuance = '';
$employeesignature = '';
$employeesignaturedate = date("m/d/Y");
$ptsignature = '';
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

if(!$result2->execute()) {

}
else {
	$row = $result2->fetch(PDO::FETCH_BOTH);
#	$CustomerID = $row['CustomerID'];
	$iCIMSID = $row['iCIMSID'];
	$lname = $row['Last_Name'];
	$fname = $row['First_Name'];
	$mname = substr($row['Middle_Name'],0,1);
	$oname = $row['Other_Last_Name'];

	if($oname == '') {
		$oname = 'N/A';
	}

	$address = $row['Address'];
	$apt = $row['Apt'];
	$city = $row['City'];
	$state = $row['State_Code'];
	$zip = $row['Zip_Code'];

	if(trim($apt) == '') {
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

	if($row['Permanent_Resident'] == 'Y') {
		$legalresident = $row['Permanent_Resident'];
		$regno = $row['Registration_USCIS'];
	}

	if($row['Alien_Auth_Work'] == 'Y') {
		$alienexpdate = $row['Auth_Work_Date'];
		$aliennumber = $row['Registration_USCIS'];
		$admissionnumber = $row['I94_Admission'];

		if($row['Foreign_Passport'] > '') {
			$foreignpassport = $row['Foreign_Passport'];
			$countryofissuance = $row['Foreign_Passport_Country'];
		}
	}

	$employeesignature = '';

	if($row['Employee_Signature_Date'] == '1900-01-01') {
		$employeesignaturedate = date("m/d/Y");
	}
	else {
		$employeesignaturedate = date('m/d/Y', strtotime($row['Employee_Signature_Date']));
	}

	if($row['Did_Used_Preparer'] == 'Y') {
		$ptsignature = '';

		if($row['Preparer_Signature_Date'] == '1900-01-01') {
			$preparersignaturedate = date("m/d/Y");
		}
		else {
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
  $os_array = array('/windows nt 10/i'      =>  'Windows 10',
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
                    '/webos/i'              =>  'Mobile');

	foreach($os_array as $regex => $value) {
		if(preg_match($regex, $user_agent))
			$os_platform = $value;
	}

	return $os_platform;
}

function getBrowser() {
  global $user_agent;
	$browser = "Unknown Browser";
  $browser_array = array('/msie/i'      => 'Internet Explorer',
												'/firefox/i'   => 'Firefox',
                        '/safari/i'    => 'Safari',
                        '/chrome/i'    => 'Chrome',
                        '/edge/i'      => 'Edge',
                        '/opera/i'     => 'Opera',
                        '/netscape/i'  => 'Netscape',
                        '/maxthon/i'   => 'Maxthon',
                        '/konqueror/i' => 'Konqueror',
                        '/mobile/i'    => 'Handheld Browser');

	foreach ($browser_array as $regex => $value) {
		if(preg_match($regex, $user_agent))
			$browser = $value;
	}

	return $browser;
}

$user_os = getOS();
$user_browser = getBrowser();

$fndemp = false;
$fndprep = false;
$empurl = "Signature/PersonID-".$PersonID."Emp.png";

if(file_exists($empurl)) {
	$fndemp = true;
}

$prepurl = "Signature/PersonID-".$PersonID."Prep.png";

if(file_exists($prepurl)) {
	$fndprep = true;
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>BIS Online I9 Section 1</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/main.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="../jquery-ui/jquery-ui.js"></script>
		<script language="JavaScript" type="text/javascript" src="../../App_JS/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="../../App_JS/validation.js"></script>
		<script language="JavaScript" type="text/javascript" src="../../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../js/autoFormats.js"></script>
		<link rel="stylesheet" type="text/css" href="StyleCSS/section1_style.css">
		<link type="text/css" rel="stylesheet" href="StyleCSS/section1Test_table.css" />
  	<script type="text/javascript">
//  		var form_clean = $("form").serialize();
   		$(document).ready(function() {
				var bodyheight = $(window).height();
//				alert (bodyheight);
   			var PersonID = $("#PersonID").val();

   			if($("#fndemp").val()) {
					imgsig = $("#sigimg");
					imgsig.src = "Signature/PersonID-" + PersonID + "Emp.png"
   				sig = $("#sigdiv");
					sig.css("visibility", "visible");
   			}

   			if($("#fndprep").val()) {
					imgsig = $("#psigimg");
					imgsig.src = "Signature/PersonID-" + PersonID + "Prep.png"
   				sig = $("#psigdiv");
					sig.css("visibility", "visible");
   			}
//     		chkrequired();
			});
		</script>
	</head>

<?php
if($PersonID == '' || $cnt == 0 || $codeid != $CD) {
	echo '<body>
					<br />
					<table style="border:5px solid black; border-radius:10px;">
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>';

	if($cnt == 0) {
		echo '			<span style="font-size:large; font-family=Tahoma; color:red;">Invalid PersonID.</span>';
	}

	echo '				<br />
								<span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the I9 Application please use the link in iCIMS.</span>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>';
}
else {
	if(!isset($_SESSION['SESS_CodeID'])) {
		$_SESSION['SESS_CodeID'] = $CD;
	}

	echo '<body bgcolor="#ffffff" onload="setindexes(\'' . $state . '\', \'' . $ptstate . '\')">
					<form METHOD="POST" NAME="myform" id="myform">
						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
						<input type="hidden" name="JobID" id="JobID" value="' . $JobID . '">
						<input type="hidden" name="CustomerID" id="CustomerID" value="' . $CustomerID . '">
						<input type="hidden" name="CD" id="CD" value="' . $CD . '">
						<input type="hidden" name="ReturnURL" id="ReturnURL" value="' . $ReturnURL . '">
						<input type="hidden" name="IncompleteURL" id="IncompleteURL" value="' . $IncompleteURL . '">
						<input type="hidden" name="form_clean" id="form_clean">
						<input type="hidden" name="fndemp" id="fndemp" value="' . $fndemp . '">
						<input type="hidden" name="fndprep" id="fndprep" value="' . $fndprep . '">
						<input type="hidden" name="icimsid" id="icimsid" value="' . $iCIMSID . '">
						<input type="hidden" name="Completed" id="Completed" value="">
						<input type="hidden" name="lastname" id="lastname" value="' . $lname . '">
						<input type="hidden" name="firstname" id="firstname" value="' . $fname . '">
						<input type="hidden" name="middlename" id="middlename" value="' . $mname . '">

						<div class="grid-x">
	            <div class="cell small-2">
	              <img src="images/Homeland-Security-Icon.png" alt="Homeland Security" heigth="60px" width="60px">
	            </div>
	            <div class="cell small-5">
								<center>
									<font size="3" face="Arial">
										<b>Employment Eligibility Verification</b><br />
										<b>Department of Homeland Security</b><br />
										U.S. Citizenship and Immigration Services
									</font>
								</center>
	            </div>
							<div class="cell small-5">
								<center>
									<font size="3" face="Arial">
										<b>USCIS</b><br />
										<b>Form I-9</b>
									</font>
									<br />
									<font size="2" face="Arial">
										OMB No. 1615-0047<br />
										Expires 08/31/2019
									</font>
								</center>
							</div>
	          </div>

						<div class="grid-x">
							<div class="cell small-12">
								<hr />
							</div>

							<div class="cell small-12">
								<img id="play" src="images/black-triangle-16.png" width="10px" height="10px" alt="" /><strong>START HERE:</strong> Read <a href="I-9InstructionsNew.pdf" target="_blank">instruction</a> carefully before completing this form. The instructions must be available, either in paper or electronically, durning completion of this form. Employers are liable for errors in the completeion of this form.
							</div>

							<div class="cell small-12">
								<strong>ANTI-DISCRIMINATION NOTICE:</strong> It is illegal to discriminate against work-athorized individuals. Employers <b>CANNOT</b> specify which document(s) an employee may present to establish employment authorization and identity. The refusal to hire or continue to employ an individual because the documentation presented has a future expiration date may also constitute illegal discrimination.
							</div>

							<div class="cell small-12" style="background-color: LightGrey">
								<div class="grid-x">
									<div class="cell small-12">
										<strong>Section 1. Employee Information and Attestation</strong> (<i>Employees must complete and sign section 1 of Form I9 no later <br />than the first day of of employment but not before accepting a job offer.</i>)
									</div>

									<div class="cell small-12 medium-4">
										<label>Last Name(<i>Family Name</i>)</label>
										<input name="lname" id="lname" class="requiredInput" value="' . htmlspecialchars($lname) . '" maxlength="40" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-3">
										<label>First Name(<i>Given Name</i>)</label>
										<input name="fname" id="fname" class="requiredInput" value="' . htmlspecialchars($fname) . '" maxlength="40" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-1">
										<label>Middle Initial</label>
										<input name="mname" id="mname" class="requiredInput" value="' . htmlspecialchars($mname) . '" maxlength="10" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-4">
										<label>Other Last Names Used(<i>if any</i>)</label>
										<input name="oname" id="oname" class="requiredInput" value="' . htmlspecialchars($oname) . '" maxlength="40" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-5">
										<label>Address(<i>Street Numer and Name</i>)</label><br />
										<input name="address" id="address" class="requiredInput" value="' . htmlspecialchars($address) . '" size="45" maxlength="45" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-1">
										<label>Apt. Number</label><br />
										<input name="apt" id="apt" class="requiredInput" value="' . htmlspecialchars($apt) . '" size="10" maxlength="15" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-4">
										<font size="2" face="Verdana, Arial, Helvetica, sans-serif">City or Town</font><br />
										<input name="city" id="city" class="requiredInput" value="' . htmlspecialchars($city) . '" size="40" maxlength="40" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-1">
										<label>State</label><br />
										<select name="state" id="state" class="requiredInput" onblur="chkrequired()">
											<option value=""></option>';

											$sql = "Select Abbrev from State order by Name";
											$state_result = $dbo->prepare($sql);
											$state_result->execute();

											while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {
												echo "<option value=".$rows[0].">".$rows[0]."</option>";
											}

echo '							</select>
									</div>

									<div class="cell small-12 medium-1">
										<label>Zip Code</label><br />
										<input name="zip" id="zip" class="requiredInput" value="' . htmlspecialchars($zip) . '" maxlength="15" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-3">
										<label>Date of Birth(<i>mm/dd/yyyy</i>)</label>
										<input name="dob" id="dob" class="requiredInput" value="' . htmlspecialchars($dob) . '" maxlength="25" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-3">
										<label>U.S. Social Security Number</label>
										<input name="ssn1-1" id="ssn1-1" class="requiredInput" value="' . htmlspecialchars($ssn1_1) . '" maxlength="1" onblur="chkrequired()">
										<input name="ssn1-2" id="ssn1-2" class="requiredInput" value="' . htmlspecialchars($ssn1_2) . '" maxlength="1" onblur="chkrequired()">
										<input name="ssn1-3" id="ssn1-3" class="requiredInput" value="' . htmlspecialchars($ssn1_3) . '" size="1" maxlength="1" onblur="chkrequired()">
										<input name="dash1" id="dash1" value="-" readonly>
										<input name="ssn2-1" id="ssn2-1" class="requiredInput" value="' . htmlspecialchars($ssn2_1) . '" size="1" maxlength="1" onblur="chkrequired()">
										<input name="ssn2-2" id="ssn2-2" class="requiredInput" value="' . htmlspecialchars($ssn2_2) . '" size="1" maxlength="1" onblur="chkrequired()">
										<input name="dash2" id="dash2" value="-" readonly>
										<input name="ssn3-1" id="ssn3-1" class="requiredInput" value="' . htmlspecialchars($ssn3_1) . '" size="1" maxlength="1" onblur="chkrequired()">
										<input name="ssn3-2" id="ssn3-2" class="requiredInput" value="' . htmlspecialchars($ssn3_2) . '" size="1" maxlength="1" onblur="chkrequired()">
										<input name="ssn3-3" id="ssn3-3" class="requiredInput" value="' . htmlspecialchars($ssn3_3) . '" size="1" maxlength="1" onblur="chkrequired()">
										<input name="ssn3-4" id="ssn3-4" class="requiredInput" value="' . htmlspecialchars($ssn3_4) . '" size="1" maxlength="1" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-3">
										<label>Employee\'s E-mail Address</label>
										<input name="email" id="email" value="' . htmlspecialchars($email) . '" maxlength="40">
									</div>

									<div class="cell small-12 medium-3">
										<label>Employee\'s Telephone Number</label>
										<input name="ephone" id="ephone" value="' . htmlspecialchars($ephone) . '" maxlength="30">
									</div>

									<div class="cell small-12">
										<strong>I am aware that federal law provides for imprisonment and/or fines for false statements or use of false documents in<br />
										connection with the completion of this form.<br /><br />
										I attest, under penalty of perjury, that I am (check on of the following boxes):</strong>
									</div>

									<div class="cell small-12">';

if($I9Cnt > 0) {
	if($row['Citizen'] == 'Y') {
		echo '					<input type="checkbox" name="citizen" id="citizen" checked onclick="setCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" >';
	}
	else {
		echo '					<input type="checkbox" name="citizen" id="citizen" onclick="setCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
}
else {
	echo '						<input type="checkbox" name="citizen" id="citizen" onclick="setCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
}

echo '							&nbsp;1. A citizen of the United States
									</div>

									<div class="cell small-12">';

if($I9Cnt > 0) {
	if($row['NonCitizen'] == 'Y') {
		echo '					<input type="checkbox" name="noncitizen" id="noncitizen" checked onclick="setNonCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
	else {
		echo '					<input type="checkbox" name="noncitizen" id="noncitizen" onclick="setNonCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
}
else {
	echo '						<input type="checkbox" name="noncitizen" id="noncitizen" onclick="setNonCitizen()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
}

echo '							&nbsp;2. A noncitizen national of the United States (<i>See instructions</i>)
									</div>

									<div class="cell small-12">';

if($I9Cnt > 0) {
	if($row['Permanent_Resident'] == 'Y') {
		echo '					<input type="checkbox" name="legalresident" id="legalresident" checked onclick="setLegalResident()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
	else {
		echo '					<input type="checkbox" name="legalresident" id="legalresident" onclick="setLegalResident()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
}
else {
	echo '						<input type="checkbox" name="legalresident" id="legalresident" onclick="setLegalResident()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
}

echo '							&nbsp;3. A lawful permanent resident (Alien Registration Number/USCIS Number):&nbsp;
										<input name="regno" id="regno" value="'.htmlspecialchars($regno).'" size="30" maxlength="30" onblur="setLegalResident()">
									</div>

									<div class="cell small-12">';

if($I9Cnt > 0) {
	if($row['Alien_Auth_Work'] == 'Y') {
		echo '					<input type="checkbox" name="alien" id="alien" checked onclick="setAlien()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
	else {
		echo '					<input type="checkbox" name="alien" id="alien" onclick="setAlien()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
	}
}
else {
	echo '						<input type="checkbox" name="alien" id="alien" onclick="setAlien()"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">';
}

echo '							&nbsp;4. An alien authoized to work until (expiration date, if applicable, mm/dd/yyy):&nbsp;&nbsp;
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
									</div>

									<div class="cell small-12">
										<label>Agreement to Use Electronic Click Signature to Sign Documents</label>
										<input type="checkbox" name="clicksign" id="clicksign" onclick="setSignature()">I, ' . htmlspecialchars($fname) . ' ' . htmlspecialchars($mname) . ' ' . htmlspecialchars($lname) . ', agree to sign these electronic documents using "click" signature technology. I understand that a record of each document and my signing of it will be stored in electronic code. I intend both the signature I inscribe with the "click" signature technology and the electronic record of it to be my legal signature to the document. I confirm that the document is "written" or "in writing" and that any accurate record of the document is an original of the document.
									</div>

									<div class="cell small-12 medium-6">
										<label>Signature of Employee</label>
										<input name="employeesignature" id="employeesignature" class="requiredInput" maxlength="55" readonly>
									</div>

									<div class="cell small-12 medium-6">
										<label>Today\'s Date (<i>mm/dd/yyyy</i>)</label>
										<input name="empdate" id="empdate" class="requiredInput" value="' . $employeesignaturedate . '" maxlength="35" onblur="chkrequired()">
									</div>

									<div class="cell small-12">
										<label><strong>Preparer and/or Translator Certification (check one):</strong></label>';

if($I9Cnt > 0) {
	if($row['Did_Not_Used_Preparer'] == 'Y') {
		echo '					<input type="checkbox" name="nohelp" id="nohelp" checked onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
	}
	else {
		if($row['Did_Used_Preparer'] == 'N') {
			echo '				<input type="checkbox" name="nohelp" id="nohelp" checked onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
		}
		else {
			echo '				<input type="checkbox" name="nohelp" id="nohelp" onclick="setnohelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
		}
	}

	echo '						I did not use a preparer or translator&nbsp;&nbsp;&nbsp;&nbsp;';

	if($row['Did_Used_Preparer'] == 'Y') {
		echo '					<input type="checkbox" name="needhelp" id="needhelp" checked onclick="setneedhelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
	}
	else {
		echo '					<input type="checkbox" name="needhelp" id="needhelp" onclick="setneedhelp()"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">';
	}
	}
	else {
	echo '						<input type="checkbox" name="nohelp" id="nohelp" onclick="setnohelp()">
										I did not use a preparer or translator&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="needhelp" id="needhelp" onclick="setneedhelp()">';
	}

	echo '						A preparer(s) and/or translator(s) assisted the empoyee in completing Section 1.<br />
										(<i>Fields below must be completed and signed when preparers and/or translors assist an employee in completing
										Section 1.</i>)
									</div>

									<div class="cell small-12">
										<strong>I attest, under penalty of perjury, that I assisted in the completion of Section 1 of this form and that to the best of my knowledge the information is true and correct.</strong>
									</div>

									<div class="cell small-12 medium-6">
										<label>Signature of Preparer or Translator</label>
										<input name="ptsignature" id="ptsignature" class="" value="' . htmlspecialchars($ptsignature) . '" maxlength="55" readonly><br />
										&nbsp;&nbsp;<a href="#" onclick="prepSign();">Click to sign your name</a>&nbsp;&nbsp;<a href="#" onclick="clearprepSign('.$PersonID.');">Clear</a>
									</div>

									<div class="cell small-12 medium-6">
										<label>Today\'s Date (<i>mm/dd/yyyy</i>)</label>
										<input name="ptdate" id="ptdate" class="" value="' . $preparersignaturedate . '" maxlength="35" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-6">
										<label>Last Name(<i>Family Name</i>)</label>
										<input name="ptlname" id="ptlname" value="' . htmlspecialchars($ptlname) . '" maxlength="50" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-6">
										<label>First Name(<i>Given Name</i>)</label>
										<input name="ptfname" id="ptfname" value="' . htmlspecialchars($ptfname) . '" maxlength="50" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-6">
										<label>Address(<i>Street Numer and Name</i>)</label>
										<input name="ptaddress" id="ptaddress" value="' . htmlspecialchars($ptaddress) . '" maxlength="55" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-4">
										<label>City or Town</label>
										<input name="ptcity" id="ptcity" value="' . htmlspecialchars($ptcity) . '" maxlength="40" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-1">
										<label>State</label>
										<select name="ptstate" id="ptstate" onblur="chkrequired()">
											<option value=""></option>';

$sql = "Select Abbrev from State order by Name";
$state_result = $dbo->prepare($sql);
$state_result->execute();

while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {
	echo "							<option value=" . $rows[0] . ">" . $rows[0] . "</option>";
}

echo '							</select>
									</div>

									<div class="cell small-12 medium-1">
										<label>Zip Code</label>
										<input name="ptzip" id="ptzip" value="' . htmlspecialchars($ptzip) . '" maxlength="15" onblur="chkrequired()">
									</div>

									<div class="cell small-12 medium-4">
										<input type="button" name="icimsreturn" id="icimsreturn" value="Submit Form & Return to iCIMS" style="font-size:medium; font-family=Tahoma; color:green; border-radius:10px; padding: 5px 24px;">
									</div>

									<div class="cell small-12 medium-8">
										<span id="status" style="color:red; font-size: 16px;">Status:</span><br />
										<input name="statusmsg" id="statusmsg" style="color:red; font-size: 16px; border-top: #000000 0px solid; border-bottom: #000000 0px solid; border-right: #000000 0px solid; border-left: #000000 0px solid;" readonly>
									</div>

								</div>
							</div>
						</div>';
}

include('Upload/UploadDialog.php');
?>

 		</form>
	</body>
</html>

<script language="JavaScript" type="text/javascript">
	function setindexes(StateCode, PTState) {
//	alert('In setindexes - StateCode = '+StateCode);
		var S1 = $("#state");
		var S2 = $("#ptstate");

		for(var x = 0; x < S1.length; x++) {
			if(StateCode.toUpperCase() == S1.options[x].value) {
				S1.selectedIndex = x;

				$('#state').removeClass('requiredInput');
  				$('#state').addClass('goodInput');
			}
		}

		for(var x = 0; x < S2.length; x++) {
			if(PTState.toUpperCase() == S2.options[x].value)
				S2.selectedIndex = x;
		}

		chkrequired();
		$("#form_clean").val() = $("form").serialize();
	}

	$("#icimsreturn").click(function() {
//	chkCompleted();
		var form_dirty = $("form").serialize();
		var form_clean = $("#form_clean").val();

		if(form_clean != form_dirty) {
			updateI9Section1();
//			if (confirm('You have unsaved changes. If you would like to save the changes click Cancel and click the Save Data button or click OK to return to iCIMS.')) {
			ReturnToiCIMS();
//			}
    }
		else {
			ReturnToiCIMS();
		}
	});

	function chkCompleted() {
//	alert("In chkCompleted");
		var complete = true;

		if($("#lname").val() == '') {
			complete = false;
		}

		if($("#fname").val() == '') {
			complete = false;
		}

		if($("#lname").val() == 'Unknown' && $("#fname").val() == 'Unknown') {
			complete = false;
		}

		if($("#mname").val() == '') {
			complete = false;
		}

		if($("#oname").val() == '') {
			complete = false;
		}

		if($("#address").val() == '') {
			complete = false;
		}

		if($("#apt").val() == '') {
			complete = false;
		}

		if($("#city").val() == '') {
			complete = false;
		}

		if($("#state").val() == '') {
			complete = false;
		}

		if($("#zip").val() == '') {
			complete = false;
		}

		if($("#dob").val() == '') {
			complete = false;
		}

		if($("#ssn1-1").val() == '') {
			complete = false;
		}

		if($("#ssn1-2").val() == '') {
			complete = false;
		}

		if($("#ssn1-3").val() == '') {
			complete = false;
		}

		if($("#ssn2-1").val() == '') {
			complete = false;
		}

		if($("#ssn2-2").val() == '') {
			complete = false;
		}

		if($("#ssn3-1").val() == '') {
			complete = false;
		}

		if($("#ssn3-2").val() == '') {
			complete = false;
		}

		if($("#ssn3-3").val() == '') {
			complete = false;
		}

		if($("#ssn3-4").val() == '') {
			complete = false;
		}

		if($("#citizen").checked == false && $("#noncitizen").checked == false &&
			$("#legalresident").checked == false && $("#alien").checked == false) {
			complete = false;
		}

		if($("#legalresident").checked == true) {
			if($("#regno").val() == '') {
				complete = false;
			}
		}

		if($("#alien").checked == true) {
			if($("#alienexpdate").val() == '') {
				complete = false;
			}

			if($("#aliennumber").val() == '' && $("#admissionnumber").val() == '' &&
				$("#foreignpassport").val() == '' && $("#countryofissuance").val() == '') {
				complete = false;
			}
		}

		if($("#fndemp").val() == false) {
			complete = false;
		}
//		var PersonID = $("#PersonID").val();
//    	var	url = 'http://proteus.bisi.com/I9/Signature/PersonID-'+PersonID+'Emp.png';
//		$.get(url)
//   		.done(function() {
//        		alert("File Fnd");
//    		}).fail(function() {
//        		alert("File Not Fnd");
//  				complete = false;
//    		});
		if($("#empdate").val() == '') {
			complete = false;
		}

		if($("#needhelp").checked == false && $("#nohelp").checked == false) {
			complete = false;
		}

		if($("#needhelp").checked == true) {
			if($("#fndprep").val() == false) {
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
			if($("#ptdate").val() == '') {
				complete = false;
			}

			if($("#ptlname").val() == '') {
				complete = false;
			}

			if($("#ptfname").val() == '') {
				complete = false;
			}

			if($("#ptaddress").val() == '') {
				complete = false;
			}

			if($("#ptcity").val() == '') {
				complete = false;
			}

			if($("#ptstate").val() == '') {
				complete = false;
			}

 			if($("#ptzip").val() == '') {
				complete = false;
			}
		}

		if(complete) {
//		alert("Completed");
			$("#Completed").val() = 'Y';
			$("#statusmsg").val() = 'I9 Form completed';
		}
		else {
//		alert("Not Completed");
			$("#Completed").val() = 'N';
			$("#statusmsg").val() = 'I9 Form not completed';
		}
	}

	function ReturnToiCIMS() {
//		alert("In ReturnToiCIMS - Completed:"+$("#Completed").val());
		var completed = $("#Completed").val();
		var ReturnURL = $("#ReturnURL").val();
		var IncompleteURL = $("#IncompleteURL").val();

		if(completed == 'Y') {
//		alert('Complete = true');
			var PersonID = $("#PersonID").val();
			var JobID = $("#JobID").val();
			var CustomerID = $("#CustomerID").val();

			$.ajax({
				type: "POST",
				url: "ajax_Set_I9Status.php",
				data: {PersonID: PersonID, JobID: JobID, CustomerID: CustomerID},
 				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2 > '' ) {
						alert(obj2);
					}
					else {
						alert('The I9 Section 1 is completed. Thank You');
						window.location = ReturnURL;
					}

					return;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
//					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				}
			});
		}
		else {
//			alert('Completed = false');
			if(confirm('The I9 form in not complete. If you would like to complete the form now click Cancel otherwise click OK and rerturn later to finish.')) {
				window.location = IncompleteURL;
			}
		}
	};

	$("#savesign").click(function() {
//		alert("In save");
		var PersonID = $("#PersonID").val();
		var JobID = $("#JobID").val();
		var CustomerID = $("#CustomerID").val();
		var Last_Name = $("#lname").val();
		var First_Name = $("#fname").val();

		if(Last_Name == 'Unknown' && First_Name == 'Unknown') {
			$('#lname').focus();
			alert("Both First Name and Last Name can not be Uknown");
			return false;
		}

		if($("#mname").val() == '') {
			var Middle_Name = 'N/A';
		}
		else {
			var Middle_Name = $("#mname").val();
		}

		if($("#oname").val() == '') {
			var Other_Last_Name = 'N/A';
		}
		else {
			var Other_Last_Name = $("#oname").val();
		}

		var Address = $("#address").val();

		if($("#apt").val() == '') {
			var Apt = 'N/A';
		}
		else {
			var Apt = $("#apt").val();
		}

		var City = $("#city").val();
		var State_Code = $("#state").val();
		var Zip_Code = $("#zip").val();
		var Date_of_Birth = $("#dob").val();
		var SSN = $("#ssn1-1").val() + $("#ssn1-2").val() + $("#ssn1-3").val();
		SSN = SSN + '-' + $("#ssn2-1").val() + $("#ssn2-2").val();
		SSN = SSN + '-' + $("#ssn3-1").val() + $("#ssn3-2").val() + $("#ssn3-3").val() + $("#ssn3-4").val();
		var Email = $("#email").val()
		var Phone = $("#ephone").val()

		if($("#citizen").checked == true) {
			var Citizen = 'Y';
		}
		else {
			var Citizen = 'N';
		}

		if($("#noncitizen").checked == true) {
			var NonCitizen = 'Y';
		}
		else {
			var NonCitizen = 'N';
		}

		if($("#legalresident").checked == true) {
			var Permanent_Resident = 'Y';
			var Registration1_USCIS = $("#regno").val();
		}
		else {
			var Permanent_Resident = 'N';
			var Registration1_USCIS = '';
		}

		if($("#alien").checked == true) {
			var Alien_Auth_Work = 'Y';
			var Auth_Work_Date = $("#alienexpdate").val();
			var Registration2_USCIS = $("#aliennumber").val();
			var I94_Admission = $("#admissionnumber").val();
			var Foreign_Passport = $("#foreignpassport").val();
			var Foreign_Passport_Country = $("#countryofissuance").val();
		}
		else {
			var Alien_Auth_Work = 'N';
			var Auth_Work_Date = '';
			var Registration2_USCIS = '';
			var I94_Admission = '';
			var Foreign_Passport = '';
			var Foreign_Passport_Country = '';
		}

		if(Registration1_USCIS > '') {
			Registration_USCIS = Registration1_USCIS;
		}

		if(Registration2_USCIS > '') {
			Registration_USCIS = Registration2_USCIS;
		}

		if($("#clicksign").checked) {
			var Digital_Employee_Signature = 'Y';
		}
		else {
			var Digital_Employee_Signature = 'N';
		}

		var Employee_Signature = $("#employeesignature").val();

		if(Employee_Signature > '') {
			var Employee_Signature_Date = $("#empdate").val();
		}
		else {
			var Employee_Signature_Date = '';
		}

		if($("#needhelp").checked == true) {
			var Did_Used_Preparer = 'Y';
			var Did_Not_Used_Preparer = 'N';

			if($("#preparerclicksign").checked) {
				var Digital_Preparer_Signature = 'Y';
			}
			else {
				var Digital_Preparer_Signature = 'N';
			}

			var Preparer_Signature_Date = $("#ptdate").val();
			var Preparer_Last_Name = $("#ptlname").val();
			var Preparer_First_Name = $("#ptfname").val();
			var Preparer_Address = $("#ptaddress").val();
			var Preparer_City = $("#ptcity").val();
			var Preparer_State = $("#ptstate").val();
 			var Preparer_Zip_Code = $("#ptzip").val();
		}
		else {
			if($("#nohelp").checked == true) {
				var Did_Not_Used_Preparer = 'Y';
			}
			else {
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

		var CD = $("#CD").val();

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

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					alert('Data saved successfully');
					$("#form_clean").val() = $("form").serialize();
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

	function updateI9Section1() {
//		alert("In update");
		var PersonID = $("#PersonID").val();
		var JobID = $("#JobID").val();
		var CustomerID = $("#CustomerID").val();
		var Last_Name = $("#lname").val();
		var First_Name = $("#fname").val();

		if(Last_Name == 'Unknown' && First_Name == 'Unknown') {
			$('#lname').focus();
			alert("Both First Name and Last Name can not be Unknown");
			return false;
		}

		if($("#mname").val() == '') {
			var Middle_Name = 'N/A';
		}
		else {
			var Middle_Name = $("#mname").val();
		}

		if($("#oname").val() == '') {
			var Other_Last_Name = 'N/A';
		}
		else {
			var Other_Last_Name = $("#oname").val();
		}

		var Address = $("#address").val();

		if($("#apt").val() == '') {
			var Apt = 'N/A';
		}
		else {
			var Apt = $("#apt").val();
		}

		var City = $("#city").val();
		var State_Code = $("#state").val();
		var Zip_Code = $("#zip").val();
		var Date_of_Birth = $("#dob").val();
		var SSN = $("#ssn1-1").val() + $("#ssn1-2").val() + $("#ssn1-3").val();
		SSN = SSN + '-' + $("#ssn2-1").val() + $("#ssn2-2").val();
		SSN = SSN + '-' + $("#ssn3-1").val() + $("#ssn3-2").val() + $("#ssn3-3").val() + $("#ssn3-4").val();
		var Email = $("#email").val()
		var Phone = $("#ephone").val()

		if($("#citizen").checked == true) {
			var Citizen = 'Y';
		}
		else {
			var Citizen = 'N';
		}

		if($("#noncitizen").checked == true) {
			var NonCitizen = 'Y';
		}
		else {
			var NonCitizen = 'N';
		}

		if($("#legalresident").checked == true) {
			var Permanent_Resident = 'Y';
			var Registration1_USCIS = $("#regno").val();
		}
		else {
			var Permanent_Resident = 'N';
			var Registration1_USCIS = '';
		}

		if($("#alien").checked == true) {
			var Alien_Auth_Work = 'Y';
			var Auth_Work_Date = $("#alienexpdate").val();
			var Registration2_USCIS = $("#aliennumber").val();
			var I94_Admission = $("#admissionnumber").val();
			var Foreign_Passport = $("#foreignpassport").val();
			var Foreign_Passport_Country = $("#countryofissuance").val();
		}
		else {
			var Alien_Auth_Work = 'N';
			var Auth_Work_Date = '';
			var Registration2_USCIS = '';
			var I94_Admission = '';
			var Foreign_Passport = '';
			var Foreign_Passport_Country = '';
		}

		var Registration_USCIS = '';

		if(Registration1_USCIS > '') {
			Registration_USCIS = Registration1_USCIS;
		}

		if(Registration2_USCIS > '') {
			Registration_USCIS = Registration2_USCIS;
		}
//		var url = 'Signature/PersonID-'+PersonID+'Emp.png';
		if($("#clicksign").checked) {
			var Digital_Employee_Signature = 'Y';
		}
		else {
			var Digital_Employee_Signature = 'N';
		}

		var Employee_Signature = $("#employeesignature").val();

		if(Employee_Signature > '') {
			var Employee_Signature_Date = $("#empdate").val();
		}
		else {
			var Employee_Signature_Date = '';
		}

		if($("#needhelp").checked == true) {
			var Did_Used_Preparer = 'Y';
			var Did_Not_Used_Preparer = 'N';

			if($("#preparerclicksign").checked) {
				var Digital_Preparer_Signature = 'Y';
			}
			else {
				var Digital_Preparer_Signature = 'N';
			}

			var Preparer_Signature = '';
			var Preparer_Signature_Date = $("#ptdate").val();
			var Preparer_Last_Name = $("#ptlname").val();
			var Preparer_First_Name = $("#ptfname").val();
			var Preparer_Address = $("#ptaddress").val();
			var Preparer_City = $("#ptcity").val();
			var Preparer_State = $("#ptstate").val();
 			var Preparer_Zip_Code = $("#ptzip").val();
		}
		else {
			if($("#nohelp").checked == true) {
				var Did_Not_Used_Preparer = 'Y';
			}
			else {
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

		var CD = $("#CD").val();

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

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					chkrequired();
					$("#form_clean").val() = $("form").serialize();
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
//				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	}

	function empSign() {
		var personid = $("#PersonID").val();
		var CD = $("#CD").val();
		var JobID = $("#JobID").val();
		var CustomerID = $("#CustomerID").val();
//		alert("CD: "+CD);
		window.location = 'wetsignature1.php?PersonID='+personid+'&JobId='+JobID+'&CustomerId='+CustomerID+'&SignType=Emp&CD='+CD;
	}

	function prepSign() {
		var personid = $("#PersonID").val();
		var CD = $("#CD").val();
		var JobID = $("#JobID").val();
		var CustomerID = $("#CustomerID").val();
		window.location = 'wetsignature1.php?PersonID='+personid+'&JobId='+JobID+'&CustomerId='+CustomerID+'&SignType=Emp&CD='+CD;
	}

	function getsig(PersonID) {
//		alert('In getsig');
		imgsig = $("#sigimg");
		imgsig.src = "Signature/PersonID-" + PersonID + "Emp.png"
   	sig = $("#sigdiv");
		sig.css("visibility", "visible");
	}

	function getpsig(PersonID) {
//		alert('In getsig');
		imgsig = $("#psigimg");
		imgsig.src = "Signature/PersonID-" + PersonID + "Prep.png"
   	sig = $("#psigdiv");
		sig.css("visibility", "visible");
	}

	function clearempSign(PersonID) {
//		alert('In clearempSign');
		var filename = "Signature/PersonID-"+PersonID+"Emp.png";
		imgsig = $("#sigimg");
		imgsig.src = ""
   	sig = $("#sigdiv");
		sig.style.visibility = "hidden";

		$.ajax({
			type: "POST",
			url: "ajax_delete_signature.php",
			data: {filename: filename},
 			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}

				$("#fndemp").val() = false;
				$('#employeesignature').removeClass('goodInput');
 				$('#employeesignature').addClass('requiredInput');
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function clearprepSign(PersonID) {
//		alert('In getsig');
		var filename = "Signature/PersonID-"+PersonID+"Prep.png"
		imgsig = $("#psigimg");
		imgsig.src = ""
   	sig = $("#psigdiv");
		sig.style.visibility = "hidden";

		$.ajax({
			type: "POST",
			url: "ajax_delete_signature.php",
			data: {filename: filename},
 			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}

				$("#fndprep").val() = false;
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function setesig() {
		sig = $("#signature");
		esig = $("#esig");
		esig.value = sig.value;
		return sig.value;
	}

	function chkrequired() {
		if(/\S/.test($("#lname").val())) {
			$('#lname').removeClass('requiredInput');
  		$('#lname').addClass('goodInput');
		}
		else {
			$('#lname').removeClass('goodInput');
  		$('#lname').addClass('requiredInput');
		}

		if(/\S/.test($("#fname").val())) {
			$('#fname').removeClass('requiredInput');
  		$('#fname').addClass('goodInput');
		}
		else {
			$('#fname').removeClass('goodInput');
  		$('#fname').addClass('requiredInput');
		}

		if(/\S/.test($("#mname").val())) {
			$('#mname').removeClass('requiredInput');
  		$('#mname').addClass('goodInput');
		}
		else {
			$('#mname').removeClass('goodInput');
  		$('#mname').addClass('requiredInput');
		}

		if(/\S/.test($("#oname").val())) {
			$('#oname').removeClass('requiredInput');
  		$('#oname').addClass('goodInput');
		}
		else {
			$('#oname').removeClass('goodInput');
  		$('#oname').addClass('requiredInput');
		}

		if(/\S/.test($("#address").val())) {
			$('#address').removeClass('requiredInput');
  		$('#address').addClass('goodInput');
		}
		else {
			$('#address').removeClass('goodInput');
  		$('#address').addClass('requiredInput');
		}

		if (/\S/.test($("#apt").val())) {
			$('#apt').removeClass('requiredInput');
  		$('#apt').addClass('goodInput');
		}
		else {
			$('#apt').removeClass('goodInput');
  		$('#apt').addClass('requiredInput');
		}

		if(/\S/.test($("#city").val())) {
			$('#city').removeClass('requiredInput');
  		$('#city').addClass('goodInput');
		}
		else {
			$('#city').removeClass('goodInput');
  		$('#city').addClass('requiredInput');
		}

		if($("#state").val() > '') {
			$('#state').removeClass('requiredInput');
  		$('#state').addClass('goodInput');
		}
		else {
			$('#state').removeClass('goodInput');
  		$('#state').addClass('requiredInput');
		}

		if(/\S/.test($("#zip").val())) {
			$('#zip').removeClass('requiredInput');
  		$('#zip').addClass('goodInput');
		}
		else {
			$('#zip').removeClass('goodInput');
  		$('#zip').addClass('requiredInput');
		}

		if(/\S/.test($("#dob").val())) {
			$('#dob').removeClass('requiredInput');
  		$('#dob').addClass('goodInput');
		}
		else {
			$('#dob').removeClass('goodInput');
  		$('#dob').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn1-1").val())) {
			$('#ssn1-1').removeClass('requiredInput');
  		$('#ssn1-1').addClass('goodInput');
		}
		else {
			$('#ssn1-1').removeClass('goodInput');
  		$('#ssn1-1').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn1-2").val())) {
			$('#ssn1-2').removeClass('requiredInput');
  		$('#ssn1-2').addClass('goodInput');
		}
		else {
			$('#ssn1-2').removeClass('goodInput');
  		$('#ssn1-2').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn1-3").val())) {
			$('#ssn1-3').removeClass('requiredInput');
  		$('#ssn1-3').addClass('goodInput');
		}
		else {
			$('#ssn1-3').removeClass('goodInput');
  		$('#ssn1-3').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn2-1").val())) {
			$('#ssn2-1').removeClass('requiredInput');
  		$('#ssn2-1').addClass('goodInput');
		}
		else {
			$('#ssn2-1').removeClass('goodInput');
  		$('#ssn2-1').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn2-2").val())) {
			$('#ssn2-2').removeClass('requiredInput');
  		$('#ssn2-2').addClass('goodInput');
		}
		else {
			$('#ssn2-2').removeClass('goodInput');
  		$('#ssn2-2').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn3-1").val())) {
			$('#ssn3-1').removeClass('requiredInput');
  		$('#ssn3-1').addClass('goodInput');
		}
		else {
			$('#ssn3-1').removeClass('goodInput');
  		$('#ssn3-1').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn3-2").val())) {
			$('#ssn3-2').removeClass('requiredInput');
  		$('#ssn3-2').addClass('goodInput');
		}
		else {
			$('#ssn3-2').removeClass('goodInput');
  		$('#ssn3-2').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn3-3").val())) {
			$('#ssn3-3').removeClass('requiredInput');
  		$('#ssn3-3').addClass('goodInput');
		}
		else {
			$('#ssn3-3').removeClass('goodInput');
  		$('#ssn3-3').addClass('requiredInput');
		}

		if(/\S/.test($("#ssn3-4").val())) {
			$('#ssn3-4').removeClass('requiredInput');
  		$('#ssn3-4').addClass('goodInput');
		}
		else {
			$('#ssn3-4').removeClass('goodInput');
  		$('#ssn3-4').addClass('requiredInput');
		}

		if($("#citizen").checked || $("#noncitizen").checked || $("#legalresident").checked || $("#alien").checked) {
			$('#tblcitizen').removeClass('requiredInput');
  		$('#tblcitizen').addClass('goodInput');
			$('#tblalien').removeClass('requiredInput');
  		$('#tblalien').addClass('goodInput');
		}
		else {
			$('#tblcitizen').removeClass('goodInput');
  		$('#tblcitizen').addClass('requiredInput');
			$('#tblalien').removeClass('goodInput');
  		$('#tblalien').addClass('requiredInput');
 		}

		if($("#legalresident").checked) {
			if(/\S/.test($("#regno").val())) {
				$('#tblcitizen').removeClass('requiredInput');
  			$('#tblcitizen').addClass('goodInput');
				$('#tblalien').removeClass('requiredInput');
  			$('#tblalien').addClass('goodInput');
			}
			else {
				$('#tblcitizen').removeClass('goodInput');
  			$('#tblcitizen').addClass('requiredInput');
				$('#tblalien').removeClass('goodInput');
  			$('#tblalien').addClass('requiredInput');
 			}
		}

		if($("#alien").checked) {
			if($("#alienexpdate").val() == '') {
				$('#tblcitizen').removeClass('goodInput');
  			$('#tblcitizen').addClass('requiredInput');
				$('#tblalien').removeClass('goodInput');
  			$('#tblalien').addClass('requiredInput');
			}
			else {
				$('#tblcitizen').removeClass('requiredInput');
  			$('#tblcitizen').addClass('goodInput');
				$('#tblalien').removeClass('requiredInput');
  			$('#tblalien').addClass('goodInput');
 			}

			if(/\S/.test($("#aliennumber").val()) || /\S/.test($("#admissionnumber").val()) || (/\S/.test($("#foreignpassport").val()) && /\S/.test($("#countryofissuance").val()))) {
				$('#tblcitizen').removeClass('requiredInput');
  			$('#tblcitizen').addClass('goodInput');
				$('#tblalien').removeClass('requiredInput');
  			$('#tblalien').addClass('goodInput');
			}
			else {
				$('#tblcitizen').removeClass('goodInput');
 				$('#tblcitizen').addClass('requiredInput');
				$('#tblalien').removeClass('goodInput');
  			$('#tblalien').addClass('requiredInput');
 			}
		}

		if($("#clicksign").checked) {
			$('#clicksignature"').removeClass('requiredInput');
  		$('#clicksignature"').addClass('goodInput');
		}
		else {
  		$('#clicksignature"').addClass('requiredInput');
			$('#clicksignature"').removeClass('goodInput');
		}

		if(/\S/.test($("#employeesignature").val())) {
			$('#employeesignature').removeClass('requiredInput');
  		$('#employeesignature').addClass('goodInput');
		}
		else {
			$('#employeesignature').removeClass('goodInput');
 			$('#employeesignature').addClass('requiredInput');
 		}

 		if(/\S/.test($("#empdate").val())) {
			$('#empdate').removeClass('requiredInput');
  		$('#empdate').addClass('goodInput');
		}
		else {
			$('#empdate').removeClass('goodInput');
 			$('#empdate').addClass('requiredInput');
 		}

		if($("#needhelp").checked) {
			if($("#fndprep").val()) {
				$('#ptsignature').removeClass('requiredInput');
  			$('#ptsignature').addClass('goodInput');
			}
			else {
				$('#ptsignature').removeClass('goodInput');
 				$('#ptsignature').addClass('requiredInput');
 			}

	 		if(/\S/.test($("#ptdate").val())) {
				$('#ptdate').removeClass('requiredInput');
  			$('#ptdate').addClass('goodInput');
			}
			else {
				$('#ptdate').removeClass('goodInput');
 				$('#ptdate').addClass('requiredInput');
 			}

			if(/\S/.test($("#ptlname").val())) {
				$('#ptlname').removeClass('requiredInput');
  			$('#ptlname').addClass('goodInput');
			}
			else {
				$('#ptlname').removeClass('goodInput');
 				$('#ptlname').addClass('requiredInput');
 			}

			if(/\S/.test($("#ptfname").val())) {
				$('#ptfname').removeClass('requiredInput');
  			$('#ptfname').addClass('goodInput');
			}
			else {
				$('#ptfname').removeClass('goodInput');
 				$('#ptfname').addClass('requiredInput');
 			}

			if(/\S/.test($("#ptaddress").val())) {
				$('#ptaddress').removeClass('requiredInput');
  			$('#ptaddress').addClass('goodInput');
			}
			else {
				$('#ptaddress').removeClass('goodInput');
 				$('#ptaddress').addClass('requiredInput');
 			}

			if(/\S/.test($("#ptcity").val())) {
				$('#ptcity').removeClass('requiredInput');
  			$('#ptcity').addClass('goodInput');
			}
			else {
				$('#ptcity').removeClass('goodInput');
 				$('#ptcity').addClass('requiredInput');
 			}

			if($("#ptstate").val() > '') {
				$('#ptstate').removeClass('requiredInput');
  			$('#ptstate').addClass('goodInput');
			}
			else {
				$('#ptstate').removeClass('goodInput');
 				$('#ptstate').addClass('requiredInput');
 			}

 			if(/\S/.test($("#ptzip").val())) {
				$('#ptzip').removeClass('requiredInput');
  			$('#ptzip').addClass('goodInput');
			}
			else {
				$('#ptzip').removeClass('goodInput');
 				$('#ptzip').addClass('requiredInput');
 			}
		}

		if($("#nohelp").checked) {
			$('#ptsignature').removeClass('requiredInput');
			$('#ptsignature').removeClass('goodInput');

	 		$("#ptdate").val() = '';
			$('#ptdate').removeClass('requiredInput');
			$('#ptdate').removeClass('goodInput');

			$("#ptlname").val() = '';
			$('#ptlname').removeClass('requiredInput');
			$('#ptlname').removeClass('goodInput');

 			$("#ptfname").val() = '';
			$('#ptfname').removeClass('requiredInput');
			$('#ptfname').removeClass('goodInput');

			$("#ptaddress").val() = '';
			$('#ptaddress').removeClass('requiredInput');
			$('#ptaddress').removeClass('goodInput');

			$("#ptcity").val() = '';
			$('#ptcity').removeClass('requiredInput');
			$('#ptcity').removeClass('goodInput');

			$("#ptstate").val() > '';
			$('#ptstate').removeClass('requiredInput');
			$('#ptstate').removeClass('goodInput');

 			$("#ptzip").val() = '';
			$('#ptzip').removeClass('requiredInput');
			$('#ptzip').removeClass('goodInput');
		}
//	alert("In chkrequired calling chkCompleted");

		chkCompleted();

		if($("#nohelp").checked) {
			var PersonID = $("#PersonID").val();
			clearprepSign(PersonID);
		}
	}

	function setCitizen() {
		if($("#citizen").checked) {
			$("#noncitizen").checked = false;
			$("#legalresident").checked = false;
			$("#alien").checked = false;
			$("#regno").val() = '';
			$("#alienexpdate").val() = '';
			$("#aliennumber").val() = '';
			$("#admissionnumber").val() = '';
			$("#foreignpassport").val() = '';
			$("#countryofissuance").val() = '';
		}

		updateI9Section1();
	}

	function setNonCitizen() {
		if($("#noncitizen").checked) {
			$("#citizen").checked = false;
			$("#legalresident").checked = false;
			$("#alien").checked = false;
			$("#regno").val() = '';
			$("#alienexpdate").val() = '';
			$("#aliennumber").val() = '';
			$("#admissionnumber").val() = '';
			$("#foreignpassport").val() = '';
			$("#countryofissuance").val() = '';
		}

		updateI9Section1();
	}

	function setLegalResident() {
		if($("#legalresident").checked) {
			$("#citizen").checked = false;
			$("#noncitizen").checked = false;
			$("#alien").checked = false;
			$("#alienexpdate").val() = '';
			$("#aliennumber").val() = '';
			$("#admissionnumber").val() = '';
			$("#foreignpassport").val() = '';
			$("#countryofissuance").val() = '';
		}

		updateI9Section1();
	}

	function setAlien() {
		if($("#alien").checked) {
			$("#citizen").checked = false;
			$("#noncitizen").checked = false;
			$("#legalresident").checked = false;
			$("#regno").val() = '';
		}

		updateI9Section1();
	}

	function setnohelp() {
		if($("#nohelp").checked) {
			$("#needhelp").checked = false;
		}

		updateI9Section1();
	}

	function setneedhelp() {
		if($("#needhelp").checked) {
			$("#nohelp").checked = false;
		}

		updateI9Section1();
	}

	function setSignature() {
		if($("#clicksign").checked) {
			var fname = $("#firstname").val();
			var lname = $("#lastname").val();
			var mname = $("#middlename").val();
			var d = new Date();
			var strDate = (d.getMonth()+1) + "/" + d.getDate() + "/" +d.getFullYear();
			$("#employeesignature").val() = "Digitally signed by: "+fname+' '+mname+' '+lname+' on '+strDate;
		}
		else {
			$("#employeesignature").val() = "";
		}

		updateI9Section1();
	}
</script>
<script src="WetSignature/Signature.js"></script>
<script src="Upload/Upload.js"></script>
