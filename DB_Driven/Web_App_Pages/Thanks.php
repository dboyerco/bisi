<?php
require_once('../pdotriton.php');

#echo 'PersonID = '.$PersonID;

include "newheader.inc";

$sql = "select * from App_Person where PersonID=".$PersonID.";";
$result=$dbo->prepare($sql);
$result->execute();
$row=$result->fetch(PDO::FETCH_BOTH);
$PersonID=$row[0];
$lname=$row[1];
$fname=$row[2];
$mi=$row[3];
$ssn1=substr($row[4],0,3);
$ssn2=substr($row[4],4,2);
$ssn3=substr($row[4],7);
$birthdate=date("m/d/Y", strtotime($row[5]));
$birthdate2=substr($birthdate,0,6)."XXXX";
$busphone=$row[6];
$homephone=$row[7];
$cellphone=$row[8];
$email=$row[9];
$Entered=date("m/d/Y H:i:s", strtotime($row[10]));
$DB_Entered=$row[10];
$compname=$row[11];
$appname=$row[13];
$package=$row[19];
$packageno=$row[14];
$ipaddress=$row[16];
$CertifiedBy=$row[17];
if ($row[18] == '1900-01-01 00:00:00') {
	$CetifiedDate='';
} else {
	$CetifiedDate=date("m/d/Y H:i:s", strtotime($row[18]));
}

$signature=$row[22];
$signdate=date("m/d/Y", strtotime($row[23]));
$signature2=$row[24];
$signdate2=date("m/d/Y", strtotime($row[25]));
$NYchk=$row[31];
$appcompleted=$row[32];

	if ($NYchk == "Y") {
        $copyplz = "Yes";
	} else {
        $copyplz = "No";
	}
	$hellofrom = $compname." Application Submission <info@bisi.com>";
	$helloto = "";
	$header = "Bcc: Dennis Boyer <dennis.boyer@bisi.com>\r\n";
	$header .= "From: ".$hellofrom. "\r\n";
	$hellosubject = $compname." Application Submission for $fname $lname";
	$hellobody = "
------------------------".$compname." REQUEST DATA------------------------------------------
Recorded: $Entered     ReferenceID: $PersonID	Applicant: $fname $lname
Certified By: $CertifiedBy	$CetifiedDate

SUBJECT INFORMATION
Name:           $fname $mi $lname\n";
$sql = "Select * from App_Alias where PersonID = $PersonID and AliasType = 'M'";
$row=$dbo->prepare($sql);
$row->execute();
if($row->rowCount() > 0) {
	while($alias = $row->fetch()) {
		$lastused = date("m/d/Y", strtotime($alias[5]));
		$hellobody .= "Maiden Name:  $alias[3] $alias[2]  Date changed: $lastused\n"; 
	}
}	
$aka = 0;
$sql = "Select * from App_Alias where PersonID = $PersonID and AliasType = 'A'";
$row=$dbo->prepare($sql);
$row->execute();
while($alias = $row->fetch()) {
	$lastused = date("m/d/Y", strtotime($alias[5]));
if ($cnt == 0) { 
$hellobody .= "\nAka:\n";	
}
$hellobody .= "	
	$alias[3] $alias[2] 	Last used: $lastused\n"; 
	$aka++;
}

$hellobody2 = $hellobody;
$hellobody .= "
Birth:          $birthdate
SSN:            $ssn1 - $ssn2 - $ssn3";
$hellobody2 .= "
Birth:          $birthdate2
SSN:            xxx-xx-$ssn3";
$hellobody3 = "
Business Phone: $busphone
Home Phone: 	$homephone
Cell Phone: 	$cellphone
Email: 		$email";
$sql = "Select * from App_Address where PersonID = $PersonID and Current_Address='Y'";
$row=$dbo->prepare($sql);
$row->execute();
while($addr = $row->fetch()) {
	$street = $addr[2];
    $apt = $addr[4];
    $city = $addr[5];
    $state = $addr[6];
    $stateother = $addr[7];
    $zip = $addr[9];
	$fromdate = date('m/d/Y', strtotime($addr[10]));
	$todate = date('m/d/Y', strtotime($addr[11]));
	
$hellobody3 .= "
Address:	$addr[2] $addr[4]
		$addr[5]	$addr[6] $addr[7] $addr[9]
		From: $fromdate
		To: $todate";
}
$cnt = 0;
$sql = "Select * from App_DMV where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($DMV = $row->fetch()) {
	$date_expires = date('m/d/Y', strtotime($DMV[3]));
if ($cnt == 0) { 
$hellobody3 .= "\n
DRIVERS LICENSE INFORMATION";
$cnt++;
}
$hellobody3 .= "
License Number: $DMV[2]
Expires:	$date_expires
State: 		$DMV[4]	$DMV[5]\n";	
}
$cnt = 0;
$sql = "Select * from App_Address where PersonID = $PersonID and Current_Address = 'N'";
$row=$dbo->prepare($sql);
$row->execute();
while($addr = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($addr[10]));
	$todate = date('m/d/Y', strtotime($addr[11]));
if ($cnt == 0) { 
$hellobody3 .= "
PREVIOUS ADDRESSES:";
$cnt++;
}	
	
$hellobody3 .= "
Address:	$addr[2] $addr[4]
		$addr[5]	$addr[6] $addr[7] $addr[9]
		From: $fromdate
		To: $todate\n";
}
$cnt = 0;
$sql = "Select * from App_Employment where PersonID = $PersonID and EmpCurrent='Y'";
$row=$dbo->prepare($sql);
$row->execute();
while($emp = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($emp[8]));
	$todate = date('m/d/Y', strtotime($emp[9]));
if ($cnt == 0) { 
$hellobody3 .= "
EMPLOYER INFORMATION:";
}	
	
$hellobody3 .= "
Ok to contact current Employer? $emp[16]
Employer Name: 	$emp[3]
		$emp[6]
		$emp[3] $emp[4] $emp[5]
		From: $fromdate
		To: $todate
		Position: $emp[12]
		Supervisor: $emp[10]
		Company Phone Number: $emp[14]\n";
$cnt++;		
}

$sql = "Select * from App_Employment where PersonID = $PersonID and EmpCurrent='N'";
$row=$dbo->prepare($sql);
$row->execute();
while($emp = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($emp[8]));
	$todate = date('m/d/Y', strtotime($emp[9]));
$hellobody3 .= "
Employer Name: 	$emp[3]
		$emp[6]
		$emp[3] $emp[4] $emp[5]
		From: $fromdate
		To: $todate
		Position: $emp[12]
		Supervisor: $emp[10]
		Company Phone Number: $emp[14]\n";
}

$cnt = 0;
$sql = "Select * from App_Education where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($edu = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($edu[7]));
	$todate = date('m/d/Y', strtotime($edu[8]));
if ($cnt == 0) { 
	$hellobody3 .= "	
EDUCATIONAL HISTORY";
}	
$hellobody3 .= "
Institution:	$edu[2]
		Phone: $edu[3]
		Location: $edu[4] $edu[5] $edu[6]
		Degree: $edu[10] 
		Major: $edu[9]\n";
		switch ($edu[11]) {
			case 'C':
				$hellobody3 .= "		Did you Graduate: Currently Enrolled";
				break;
			case 'N':	
				$hellobody3 .= "		Did you Graduate: No";
				break;
			case 'Y':	
				$hellobody3 .= "		Did you Graduate: Yes";
				break;
		}		
$hellobody3 .= "
		Name at Graduation: $edu[13]
		From: $fromdate
		To: $todate\n";
$cnt++;		
		
}
$cnt = 0;
$sql = "Select * from App_ProfLicenses where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($prof = $row->fetch()) {
	if ($cnt == 0) { 
$hellobody3 .= "
PROFESSIONAL LICENSES:";
}  	
$hellobody3 .= "
Type of License:	$prof[2]
State:			$prof[3]	$prof[4]
License Number:		$prof[5]\n";
$cnt++;
}
$cnt = 0;
$sql = "Select * from App_References where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($ref = $row->fetch()) {
	if ($cnt == 0) { 
$hellobody3 .= "
PROFESSIONAL REFERENCES:";
}  	
$fullname = $ref[3].' '.$ref[4];
$hellobody3 .= "
Contact Name:	$fullname
Contact Phone: $ref[13]
Contact Email: $ref[12]
Company Name: $ref[2] 
Job Title: $ref[15]\n";
$cnt++;
}
$cnt = 0;
$sql = "Select * from App_Military where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($mil = $row->fetch()) {
	if ($cnt == 0) { 
$hellobody3 .= "
Military:";
}  	
$hellobody3 .= "
		Branch: $mil[2]
		Rank: $mil[3]
		Years: $mil[4]
		End Date: $mil[5]";
$cnt++;
}

$hellobody3 .= "\n
BACKGROUND SCREENING DISCLOSURE AND AUTHORIZATION
DISCLOSURE
".$compname." (\"the Company\") may obtain information about you from a 
consumer reporting agency for employment purposes. Thus, you may be the 
subject of a \"consumer report\" and/or an \"investigative consumer report\" 
which may include information about your character, general reputation, 
personal characteristics, and/or mode of living, and which can involve 
personal interviews with sources such as your neighbors, friends or 
associates. These reports may contain information regarding your criminal 
history, credit history, motor vehicle records (\"driving records\"), 
verification of your education or employment history or other background 
checks. You have the right, upon written request made within a reasonable 
time after receipt of this notice, to request disclosure of the nature and 
scope of any investigative consumer report. Please be advised that the 
nature and scope of the most common form of investigative consumer report 
obtained with regard to applicants for employment is an investigation into 
your education and/or employment history conducted by Background Information 
Services, Inc., 1800 30th Street, Suite 204, Boulder, Colorado 80301, 
800/433-6010, http://www.bisi.com, or another outside organization. 
You should carefully consider whether to exercise your right to request 
disclosure of the nature and scope of any investigative consumer report.";
$hellobody4 = "\nELECTRONIC SIGNATURES
Electronic printed signatures (instead of handwritten signatures) are legal
and accepted under the Uniform Commercial Code, as follows: \"Any form of writing,
stamping, or printing of a name, initials, or mark makes the instrument signed.\"
I understand by typing my name and initials it acts as an original signature under
the UCC sections 1-201:717.
By signing below, I acknowledge that I have read and understand the above
Disclosures.
SIGNATURES:
Signed: $signature
Date:   $signdate
IP:     $ipaddress
Requests a copy of report:      $copyplz
\nAUTHORIZATION
I acknowledge receipt of the DISCLOSURE REGARDING BACKGROUND INVESTIGATION and 
A SUMMARY OF YOUR RIGHTS UNDER THE FAIR CREDIT REPORTING ACT and certify that 
I have read and understand both of those documents. I hereby authorize the 
obtaining of \"consumer reports\" and/or \"investigative consumer reports\" by 
".$compname." (\"the Company\") at any time after receipt of this authorization 
and throughout my employment, if applicable. To this end, I hereby authorize, 
without reservation, any law enforcement agency, administrator, state or 
federal agency, institution, school or university (public or private), information 
service bureau, employer, insurance company or other party to furnish any and all 
background information requested by Background Information Services, Inc., 
1800 30th Street, Suite 204, Boulder, Colorado 80301, 800/433-6010, http://www.bisi.com 
(\"the Agency\"), another outside organization acting on 
behalf of the Company, and/or the Company itself.  
I agree that a facsimile (\"fax\") or electronic or photographic copy of this 
Authorization shall be as valid as the original.
I agree that my electronic signature, acknowledgement, acceptance, and/or
authorization are the equivalent of my handwritten signature.
ELECTRONIC SIGNATURES
Electronic printed signatures (instead of handwritten signatures) are legal
and accepted under the Uniform Commercial Code, as follows: \"Any form of
writing, stamping, or printing of a name, initials, or mark makes the instrument
signed.\" I understand by typing my name and initials it acts as an original
signature under the UCC sections
1-201:717.
SIGNATURES:
Signed: $signature2
Date:   $signdate2
IP:     $ipaddress
";
	$hellobody3 .= $hellobody4;
	$hellobody .= $hellobody3;
	$hellobody2 .= $hellobody3;

	mail($helloto, $hellosubject, $hellobody2, $header);
	include '../include/createpdf.php';
	$pdffilename = "disclaimers/PersonID-".$PersonID.".pdf";
	$pdfAuthfilename = "disclaimers/Auth-".$PersonID.".pdf";

	$msg = txt2pdf($hellobody2, $pdffilename);
	$msg = txt2pdf($hellobody4, $pdfAuthfilename);

#echo "<P>Submission Complete and Printed</P>";
/*	$deletesql = "delete from Submissions where RefID = '$RefID'";
	$dlt=$dbo->prepare($deletesql);
	$dlt->execute();

	$result2 = mysql_db_query("bisidev", $deletesql);
	
	$path = "https://proteus.bisi.com/disclaimers/RefID-".$RefID.".pdf";
	$hellosubject = addslashes($hellosubject);
	$compname = addslashes($compname);
	$isrtsql = "Insert into Submissions(RefID,Company,Msg,Path,Submission_Date) Values(':PersonID',':Company',':Msg',':Path',':DB_Entered')";
	$isrt_result = $dbo->prepare($isrtsql);
	$isrt_result->bindValue(':PersonID', $PersonID);
	$isrt_result->bindValue(':Company', $compname);
	$isrt_result->bindValue(':Msg', $hellosubject);
	$isrt_result->bindValue(':Path', $path);
	$isrt_result->bindValue(':DB_Entered', $DB_Entered, PDO::PARAM_STR);
	if (!$save_result->execute()) {
		$msg .= "Error Submissions: \nSQL is ".$sql."\n".$save_result->errorCode();
		$to = "Dennis Boyer <dennis.boyer@bisi.com>";
		$hellobody = "Error inserting data into Submissions table - ".$save_result->errorCode()."\n".$isrtsql;
		$hellosubject = "Submissions DB Insert Error";
		$hellofrom = "submissions DB Error <info@bisi.com>";
		mail($to, $hellosubject, $hellobody, "From: $hellofrom");
	}
*/
	$sql = "update App_Person set AppCompleted = 'Y', Status = 'Authorization Received' where PersonID = :PersonID";
	$updt=$dbo->prepare($sql);
	$updt->bindValue(':PersonID', $PersonID);
	$updt->execute();

/*	if ($packageno > '') {
		$custid = 'BAIN_01710';
		$serviceno = $packageno;
		include "inc/CreateProfile.php";
	}	
*/	
	echo "<p><strong>Thank you for your submission to ".$compname.".</strong></p><br />
	<P>Submission Complete</P>";

?>