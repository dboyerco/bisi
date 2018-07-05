<?php
require_once('../pdotriton.php');
$FormAction = "";
#echo 'PersonID = '.$PersonID;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />		
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	</head>
<?php
echo '<body bgcolor="#E4E8E8">';
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";

$sql = "select * from App_Person where PersonID=".$PersonID.";";
$result=$dbo->prepare($sql);
$result->execute();
$row=$result->fetch(PDO::FETCH_BOTH);
$PersonID=$row['PersonID'];
$lname=$row['Last_Name'];
$fname=$row['First_Name'];
$mi=$row['Middle_Name'];
$ssn1=substr($row['SSN'],0,3);
$ssn2=substr($row['SSN'],4,2);
$ssn3=substr($row['SSN'],7);
$birthdate=date("m/d/Y", strtotime($row['Date_of_Birth']));
$birthdate2=substr($birthdate,0,6)."XXXX";
$busphone=$row['Business_Phone'];
$homephone=$row['Home_Phone'];
$cellphone=$row['Mobile_Phone'];
$email=$row['Email'];
$Entered=date("m/d/Y H:i:s", strtotime($row['Entered']));
$DB_Entered=$row['Entered'];
$compname=$row['Company_Name'];
$appname=$row['App_Name'];
$package=$row['Package'];
$packageno = $row['PackageNo'];
$Status = $row['Status'];
$ipaddress=$row['ipAddress'];
$Sponsor=$row['Sponsor'];
$NYchk=$row['NYchk'];
$appcompleted=$row['AppCompleted'];
$custid=$row['CustID'];
$etype=$row['Email_Type'];
$noemail=$row['No_Email'];
$datetime = Date('Y-m-d H:i:s');

$Date = date("m/d/Y");			
$datediff = strtotime($Date) - strtotime($birthdate);
$days = floor($datediff / (60 * 60 * 24));
$parentdocneeded = false;
if ($days < 6570) {	
	$pdoc = $dbo->query("Select count(*) from App_Uploads where PersonID = ".$PersonID." and UploadType = 'Disclosure Authorization Parent';")->fetchColumn();	
	if ($pdoc > 0) {
	} else {
		$parentdocneeded = true;
	}	
}	
$dsignature = $dbo->query("select Signature from App_Signature where PersonID = ".$PersonID." and Signature_Type = 'Disclosure';")->fetchColumn();
$dsigndate = $dbo->query("select Signature_Date from App_Signature where PersonID = ".$PersonID." and Signature_Type = 'Disclosure';")->fetchColumn();
$dsigndate = date("m/d/Y", strtotime($dsigndate));
$asignature = $dbo->query("select Signature from App_Signature where PersonID = ".$PersonID." and Signature_Type = 'Authorization';")->fetchColumn();
$asigndate = $dbo->query("select Signature_Date from App_Signature where PersonID = ".$PersonID." and Signature_Type = 'Authorization';")->fetchColumn();
$asigndate = date("m/d/Y", strtotime($asigndate));

	if ($NYchk == "Y") {
        $copyplz = "Yes";
	} else {
        $copyplz = "No";
	}
	include "newheader.inc";
$hellobody2 = "
------------------------".$compname." REQUEST DATA------------------------------------------
Recorded: $Entered     PersonID: $PersonID		Applicant: $fname $lname

SUBJECT INFORMATION\n
	Name:           $fname $mi $lname\n";
$sql = "Select * from App_Alias where PersonID = $PersonID and AliasType = 'M'";
$row=$dbo->prepare($sql);
$row->execute();
if($row->rowCount() > 0) {
	while($alias = $row->fetch()) {
		$lastused = date("m/d/Y", strtotime($alias[5]));
		$hellobody2 .= "Maiden Name:  $alias[3] $alias[2]  Date changed: $lastused\n"; 
	}
}	
$aka = 0;
$sql = "Select * from App_Alias where PersonID = $PersonID and AliasType = 'A'";
$row=$dbo->prepare($sql);
$row->execute();
while($alias = $row->fetch()) {
	$lastused = date("m/d/Y", strtotime($alias[5]));
if ($aka == 0) { 
$hellobody2 .= "\nAka:\n";	
}
$hellobody2 .= "	
	$alias[3] $alias[2] 	Last used: $lastused\n"; 
	$aka++;
}
$hellobody2 .= "
	Birth:	$birthdate2
	SSN:	xxx-xx-$ssn3";
$hellobody2 .= "
	Business Phone:	$busphone
	Home Phone:		$homephone
	Cell Phone:		$cellphone
	Email:			$email";
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
	
$hellobody2 .= "
Address:	$addr[2]	$addr[4]
			$addr[5]	$addr[6] $addr[7] $addr[9]
	From: 	$fromdate
	To: 	$todate";
}
$cnt = 0;
$sql = "Select * from App_DMV where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($DMV = $row->fetch()) {
	$date_expires = date('m/d/Y', strtotime($DMV[3]));
if ($cnt == 0) { 
$hellobody2 .= "\n
DRIVERS LICENSE INFORMATION";
$cnt++;
}
$hellobody2 .= "
	License Number: $DMV[2]
	Expires:		$date_expires
	State:	 		$DMV[4]	$DMV[5]\n";	
}

$cnt = 0;
$sql = "Select * from App_Bank where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($Bank = $row->fetch()) {
if ($cnt == 0) { 
$hellobody2 .= "\n
BANK INFORMATION";
$cnt++;
}
$hellobody2 .= "
	Bank Name: 		$Bank[2]              
	Bank Address: 	$Bank[3] 
					$Bank[4], $Bank[5] $Bank[7] $Bank[6]               
	Acct. Type: 	$Bank[8]
	Acct. Number: 	xxxxxxxxxx\n";               
}

$cnt = 0;
$sql = "Select * from App_Address where PersonID = $PersonID and Current_Address = 'N'";
$row=$dbo->prepare($sql);
$row->execute();
while($addr = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($addr[10]));
	$todate = date('m/d/Y', strtotime($addr[11]));
if ($cnt == 0) { 
$hellobody2 .= "
PREVIOUS ADDRESSES:";
$cnt++;
}	
	
$hellobody2 .= "
	Address:	$addr[2] $addr[4]
				$addr[5]	$addr[6] $addr[7] $addr[9]
		From: 	$fromdate
		To: 	$todate\n";
}
$cnt = 0;
$sql = "Select * from App_Employment where PersonID = $PersonID and EmpCurrent='Y'";
$row=$dbo->prepare($sql);
$row->execute();
while($emp = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($emp[8]));
	$todate = date('m/d/Y', strtotime($emp[9]));
if ($cnt == 0) { 
$hellobody2 .= "
EMPLOYER INFORMATION:";
}	
	
$hellobody2 .= "
	Ok to contact current Employer? $emp[16]
	Employer Name: 					$emp[2]
									$emp[6]
									$emp[3] $emp[4] $emp[5]
			From: 					$fromdate
			To: 					$todate
			Position: 				$emp[12]
			Supervisor: 			$emp[10]
			Company Phone Number: 	$emp[13]\n";
$cnt++;		
}

$sql = "Select * from App_Employment where PersonID = $PersonID and EmpCurrent='N'";
$row=$dbo->prepare($sql);
$row->execute();
while($emp = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($emp[8]));
	$todate = date('m/d/Y', strtotime($emp[9]));
$hellobody2 .= "
	Employer Name: 					$emp[2]
									$emp[6]
									$emp[3] $emp[4] $emp[5]
			From: 					$fromdate
			To: 					$todate
			Position: 				$emp[12]
			Supervisor: 			$emp[10]
			Company Phone Number: 	$emp[13]\n";
}

$cnt = 0;
$sql = "Select * from App_Education where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($edu = $row->fetch()) {
	$fromdate = date('m/d/Y', strtotime($edu[7]));
	$todate = date('m/d/Y', strtotime($edu[8]));
if ($cnt == 0) { 
	$hellobody2 .= "	
EDUCATIONAL HISTORY";
}	
$hellobody2 .= "
	Institution:		$edu[2]
	Phone: 				$edu[3]
	Location: 			$edu[4] $edu[5] $edu[6]
	Degree: 			$edu[10] 
	Major: 				$edu[9]\n";
		switch ($edu[12]) {
			case 'C':
				$hellobody2 .= "		Did you Graduate: Currently Enrolled";
				break;
			case 'N':	
				$hellobody2 .= "		Did you Graduate: No";
				break;
			case 'Y':	
				$hellobody2 .= "		Did you Graduate: Yes";
				break;
		}		
$hellobody2 .= "
	Name at Graduation: $edu[14]
	From: 				$fromdate
	To: 				$todate\n";
$cnt++;		
		
}
$cnt = 0;
$sql = "Select * from App_ProfLicenses where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($prof = $row->fetch()) {
	if ($cnt == 0) { 
$hellobody2 .= "
PROFESSIONAL LICENSES:";
}  	
$hellobody2 .= "
	Type of License:	$prof[2]
	State:				$prof[3]	$prof[4]
	License Number:		$prof[5]\n";
$cnt++;
}
$cnt = 0;
$sql = "Select * from App_References where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($ref = $row->fetch()) {
	if ($cnt == 0) { 
$hellobody2 .= "
PROFESSIONAL REFERENCES:";
}  	
$fullname = $ref[3].' '.$ref[4];
$hellobody2 .= "
	Contact Name:	$fullname
	Contact Phone: 	$ref[14]
	Contact Email: 	$ref[12]
	Company Name: 	$ref[2] 
	Job Title: 		$ref[15]\n";
$cnt++;
}
$cnt = 0;
$sql = "Select * from App_Military where PersonID = $PersonID";
$row=$dbo->prepare($sql);
$row->execute();
while($mil = $row->fetch()) {
	if ($cnt == 0) { 
$hellobody2 .= "
Military:";
}  	
$fromdate = date('m/d/Y', strtotime($mil[6]));
$todate = date('m/d/Y', strtotime($mil[7]));
$hellobody2 .= "
	Branch:	 	$mil[2]
	Rank: 		$mil[3]
	Years: 		$mil[5]
	$fromdate - $todate";
$cnt++;
}
if ($etype == 'T') {
$hellobody2 .= "\n
BACKGROUND SCREENING DISCLOSURE AND AUTHORIZATION DISCLOSURE
".$compname." (\"the Company\") may obtain information about you from a 
consumer reporting agency for tenant purposes. Thus, you may be the 
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
disclosure of the nature and scope of any investigative consumer report.
\nELECTRONIC SIGNATURES
Electronic printed signatures (instead of handwritten signatures) are legal
and accepted under the Uniform Commercial Code, as follows: \"Any form of writing,
stamping, or printing of a name, initials, or mark makes the instrument signed.\"
I understand by typing my name and initials it acts as an original signature under
the UCC sections 1-201:717.
By signing below, I acknowledge that I have read and understand the above
Disclosures.
\nSIGNATURES:
Signed: $dsignature
Date:   $dsigndate
IP:     $ipaddress
Requests a copy of report:      $copyplz
\nAUTHORIZATION
I acknowledge receipt of the DISCLOSURE REGARDING BACKGROUND INVESTIGATION and 
A SUMMARY OF YOUR RIGHTS UNDER THE FAIR CREDIT REPORTING ACT and certify that 
I have read and understand both of those documents. I hereby authorize the 
obtaining of “consumer reports” and/or “investigative consumer reports” by 
".$compname." (\"the Company\") at any time after receipt of this authorization 
and throughout my occupancy, if applicable. To this end, I hereby authorize, 
without reservation, any law enforcement agency, administrator, state or 
federal agency, institution, school or university (public or private), information 
service bureau, employer, insurance company or other party to furnish any and all 
background information requested by Background Information Services, Inc., 
1800 30th Street, Suite 204, Boulder, Colorado 80301, 800/433-6010, http://www.bisi.com 
(\"the Agency\"), another outside organization acting on behalf of the Company, 
and/or the Company itself. I agree that a facsimile (“fax”) or electronic or 
photographic copy of this Authorization shall be as valid as the original. 
I agree that my electronic signature, acknowledgement, acceptance, and/or 
authorization are the equivalent of my handwritten signature.\n 
ELECTRONIC SIGNATURES
Electronic printed signatures (instead of handwritten signatures) are legal
and accepted under the Uniform Commercial Code, as follows: \"Any form of
writing, stamping, or printing of a name, initials, or mark makes the instrument
signed.\" I understand by typing my name and initials it acts as an original
signature under the UCC sections 1-201:717.
\nSIGNATURES:
Signed: $asignature
Date:   $asigndate
IP:     $ipaddress
";	
} else {	
$hellobody2 .= "\n
BACKGROUND SCREENING DISCLOSURE AND AUTHORIZATION DISCLOSURE
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
disclosure of the nature and scope of any investigative consumer report.\n
ELECTRONIC SIGNATURES
Electronic printed signatures (instead of handwritten signatures) are legal
and accepted under the Uniform Commercial Code, as follows: \"Any form of writing,
stamping, or printing of a name, initials, or mark makes the instrument signed.\"
I understand by typing my name and initials it acts as an original signature under
the UCC sections 1-201:717.
By signing below, I acknowledge that I have read and understand the above
Disclosures.
\nSIGNATURES:
Signed: $dsignature
Date:   $dsigndate
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
\nELECTRONIC SIGNATURES
Electronic printed signatures (instead of handwritten signatures) are legal
and accepted under the Uniform Commercial Code, as follows: \"Any form of
writing, stamping, or printing of a name, initials, or mark makes the instrument
signed.\" I understand by typing my name and initials it acts as an original
signature under the UCC sections 1-201:717.
\nSIGNATURES:
Signed: $asignature
Date:   $asigndate
IP:     $ipaddress
";
}
#	$hellobody2 .= $hellobody4;
#	$hellobody .= $hellobody3;
#	$hellobody2 .= $hellobody3;

	include '../include/createpdf.php';
	$pdffilename = "disclaimers/PersonID-".$PersonID.".pdf";
#	$pdfAuthfilename = "../disclaimers/Auth-".$PersonID.".pdf";

	$msg = txt2pdf($hellobody2, $pdffilename);
#	$msg = txt2pdf($hellobody4, $pdfAuthfilename);
	$msg = '';
	$Comment = '';
if ($noemail == 'N' || $noemail == 'S') {
	$Semail = '';
	$Semail = $dbo->query("select emailnotification from ReportLinkCustids where custid = '".$custid."';")->fetchColumn();
	$cname = str_replace(',','',$compname);
	$hellofrom = $cname." Application Submission <service@bisi.com>";
	$helloto = $Semail;
	$helloto = "";
	$hellosubject = $compname." Application Submission for $fname $lname";
	$hellobody = '<html><body><table style="border:5px solid black; border-radius:10px;">';
	if ($parentdocneeded) {	
		$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">'.$fname.'&nbsp;'.$lname.' has completed the authorization for their background check but neededs the Parent Release form. You can <a href="https://proteus.bisi.com/'.$appname.'/HR">Click Here</a> to view applicants data.</span></td></tr>';
	} else {
		$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">'.$fname.'&nbsp;'.$lname.' has completed the authorization for their background check and is Ready to Screen. Please <a href="https://proteus.bisi.com/'.$appname.'/HR">Click Here</a> to submit the order to BIS.</span></td></tr>';
	}
	$hellobody .= '<tr><td>&nbsp;</td></tr>';
#	$hellobody .= '<tr><td><a href="https://proteus.bisi.com/disclaimers/PersonID-'.$PersonID.'.pdf">Click Here</a> to view applicants submission</td></tr>';
#	$hellobody .= '<tr><td>&nbsp;</td></tr>';
	$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">If you have questions or need assistance, please contact us at <a href="mailto:service@bisi.com">Service@BISI.com</a> or 800-433-6010. We are in the office Monday - Friday, 8:00am - 5:00 pm MT.</span></td></tr>';
	$hellobody .= '<tr><td>&nbsp;</td></tr>';
	$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">Thank you!</span></td></tr>';
	$hellobody .= '</table></body></html>';
	$header = "Bcc: Dennis Boyer <dennis.boyer@bisi.com>\r\n";
	$header .= "From: ".$hellofrom. "\r\n";
	$emaildatetime = Date('m/d/Y H:i:s');
	$header .= "Date: ".$emaildatetime."\r\n";
	$header .= "MIME-Version: 1.0\r\n";		
	$header .= "Content-Type: text/html; charset-ISO-8859-1\r\n";		
	mail($helloto, $hellosubject, $hellobody, $header);

	if ($Status != 'In Progress' && $Status != 'Completed') { 
		if ($parentdocneeded) {	
			$Status = 'Parent Release Needed';
			$AppCompleted = 'N';
			$Comment = $fname.' '.$lname.' has completed the online web app but Parent Release is needed.';
		} else {
			$Status = 'Ready to Screen';
			$AppCompleted = 'Y';
			$Comment = $fname.' '.$lname.' has completed the online web app and is ready to be submitted to BIS';
		}
		$sql = "update App_Person set AppCompleted = :AppCompleted, Status = :Status where PersonID = :PersonID";
		$updt=$dbo->prepare($sql);
		$updt->bindValue(':PersonID', $PersonID);
		$updt->bindValue(':AppCompleted', $AppCompleted);
		$updt->bindValue(':Status', $Status);
		$updt->execute();

#		$Status = 'Ready to Screen';
#		$Comment = $fname.' '.$lname.' has completed the online web app and is ready to be submitted to BIS';
	}
	if ($Status == 'Ready to Screen') {
		echo "<p><strong>Thank you for your submission to ".$compname.".</strong></p><br />
		<P>Submission Complete</P>";
	} else {	
		echo "<p><strong>Thank you for your submission to ".$compname.".</strong></p><br />
		<P><strong>Parent Release form is still needed.&nbsp;&nbsp;<a href='#openPhotoDialog'>Upload the Document.</a></strong></P>";
	}
	if ($noemail == 'S') {
		echo '<br /><br /><INPUT TYPE="button" name="return" id="return" onclick="redir()" VALUE="Close" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">';
	}
	
} else {	
	$OKSend = false;
	if ($parentdocneeded) {	
		$Status = 'Parent Release Needed';
		$Comment = $fname.' '.$lname.' has completed the online web app but Parent Release is needed.';
		$sql = "update App_Person set AppCompleted = 'N', Submitted = 'N', CertifiedBy = :CertifiedBy, CertifiedDate = :CertifiedDate, Status = :Status, Status_Date = :Status_Date where PersonID = :PersonID";
	} else {
		$OKSend = true;
		$Status = 'In Progress';
		$Comment = $fname.' '.$lname.' was submitted to BIS for a background check';
		$sql = "update App_Person set AppCompleted = 'Y', Submitted = 'Y', CertifiedBy = :CertifiedBy, CertifiedDate = :CertifiedDate, Status = :Status, Status_Date = :Status_Date where PersonID = :PersonID";
	}
	$updt=$dbo->prepare($sql);
	$updt->bindValue(':PersonID', $PersonID);
	$updt->bindValue(':CertifiedBy', $Sponsor);
	$updt->bindValue(':CertifiedDate', $datetime);
	$updt->bindValue(':Status', $Status);
	$updt->bindValue(':Status_Date', $datetime);
	if (!$updt->execute()) {
		$msg .= "Error Updating App_Person: \nSQL is ".$sql."\n".$update->errorCode()."\n";
	}

#		$Status = 'In Progress';
#		$Comment = $fname.' '.$lname.' was submitted to BIS for a background check';
	if ($OKSend) {
		$sql = "insert into App_Ready_Send (PersonID) Values(:PersonID);"; 
		$isrt=$dbo->prepare($sql);
		$isrt->bindValue(':PersonID', $PersonID);
		$isrt->execute();
		if (!$isrt->execute()) {
			$msg .= "Error Submitting Applicant to BIS: <br />SQL is ".$sql."\n".$isrt->errorCode()."\n";
		}
	
		echo "<p><strong>Thank you for your submission.<br />".$fname." ".$lname." submitted to BIS </strong></p><br />
		<P>Submission Complete</P>";
	} else {
		echo "<p><strong>Thank you for your submission.<br />Parent Release form is still needed.&nbsp;&nbsp;<a href='#openPhotoDialog'>Upload the Document.</a></strong></p><br />";	
	}
}
$entered = date("Y-m-d H:i:s");
$sql2 = "insert into App_Log (PersonID, Status, Status_Date, Email, Comment) values(:PersonID, :Status, :Status_Date, :Email, :Comment);";
$save2_result = $dbo->prepare($sql2);
$save2_result->bindValue(':PersonID', $PersonID);
	$save2_result->bindValue(':Status', $Status);
	$save2_result->bindValue(':Status_Date', $entered, PDO::PARAM_STR);
	$save2_result->bindValue(':Email', $email);
	$save2_result->bindValue(':Comment', $Comment);
	if (!$save2_result->execute()) {
		$msg .= "Error Inserting into Log. Error Code: ".$save2_result->errorCode()."\n";
	} 
	$sql = "Insert into App_HR_Log (UserName, Log_Date, PersonID, Company_Name, Comment) values(:UserName, :Log_Date, :PersonID, :Company_Name, :Comment);";
	
	$save_result = $dbo->prepare($sql);
	$save_result->bindValue(':UserName', $fname.' '.$lname);
	$save_result->bindValue(':Log_Date', $entered, PDO::PARAM_STR);
	$save_result->bindValue(':PersonID', $PersonID);
	$save_result->bindValue(':Company_Name', $compname);
	$save_result->bindValue(':Comment', $Comment);
    if (!$save_result->execute()) {
		$msg .= "Error inserting App_HR_Log - Person: ".$PersonID."\nSQL is ".$sql."\n".$save_result->errorCode();
	}

if ($msg > '') { 
	$to = "Dennis Boyer <dennis.boyer@bisi.com>"; 
	$hellobody = "Error(s):\n ".$compname."\n".$fname." ".$lname."\n".$msg."\n";
	$hellosubject = "Thanks Errors";
	$hellofrom = "Thanks Errors <info@bisi.com>";
	mail($to, $hellosubject, $hellobody, "From: $hellofrom"); 	
} 
include ('Upload/UploadDialog.php');
echo	"<INPUT TYPE=\"hidden\" NAME=\"PersonID\" id=\"PersonID\" VALUE=\"$PersonID\">
		<INPUT TYPE=\"hidden\" NAME=\"UploadType\" id=\"UploadType\"VALUE=\"Disclosure Authorization Parent\">";

?>
</FORM>
</body>
</html>

<script language="JavaScript" type="text/javascript">
	function redir() {
//		alert('Bye');
		window.top.close();

	}
</script>
<script src="Upload/Upload.js"></script>
