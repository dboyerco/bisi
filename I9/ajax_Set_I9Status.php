<?Php
require_once('../pdotriton.php');
$PersonID = $_REQUEST['PersonID'];
$JobID = $_REQUEST['JobID'];
$CustomerID = $_REQUEST['CustomerID'];
$iCIMSID = $dbo->query("select iCIMSID from I9Section1 where PersonID = ".$PersonID." and JobID = ".$JobID." and CustomerID = ".$CustomerID.";")->fetchColumn();
$CodeID = $dbo->query("select CodeID from I9Section1 where PersonID = ".$PersonID." and JobID = ".$JobID." and CustomerID = ".$CustomerID.";")->fetchColumn();
$msg = "";
$sql = "Select * from ICIMSCompany where CompanyID = :CompanyID;";
$result = $dbo->prepare($sql);
$result->bindValue(':CompanyID', $CustomerID);
if (!$result->execute()) {
	$credentials = '';
} else {
	$row=$result->fetch(PDO::FETCH_BOTH);
	$credentials = trim($row["I9_API_User_ID"].':'.$row["I9_Pass"]);
}

$I9URL = "https://proteus.bisi.com/I9/I9Section1view.php?PersonID=".$PersonID."&JobId=".$JobID."&CustomerId=".$CustomerID."&CD=".$CodeID;
		
$headers = array(
	'Content-Type:application/json',
	'Authorization: Basic '. base64_encode("$credentials") 
);
$url = 'https://api.icims.com/customers/'.$CustomerID.'/people/'.$iCIMSID;	
$json = '{"i9nextaction":{"id":"D37002014004","value":"Sign Section 2"},"i9nextactionduedate":"'.date("Y-m-d").'","i9url": "'.$I9URL.'"}';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_HEADER, 0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch,CURLOPT_TIMEOUT, 60);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch,CURLOPT_POSTFIELDS,$json);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch,CURLOPT_COOKIESESSION,TRUE);
curl_setopt($ch,CURLOPT_COOKIEJAR,'ICIMS-SessionId');
curl_setopt($ch,CURLOPT_COOKIEFILE,'/var/www/html/bis/I9/create');
$result = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);			
curl_close($ch);

$Recruitment_Email = $dbo->query("select Recruitment_Email from ICIMSPerson where personId = ".$iCIMSID." and customerId = ".$CustomerID.";")->fetchColumn();
$fname = $dbo->query("select firstName from ICIMSPerson where personId = ".$iCIMSID." and customerId = ".$CustomerID.";")->fetchColumn();
$lname = $dbo->query("select lastName from ICIMSPerson where personId = ".$iCIMSID." and customerId = ".$CustomerID.";")->fetchColumn();
$hellofrom = "I9 Section1 Submission <service@bisi.com>";
$helloto = $Recruitment_Email;
#$helloto = "";
$hellosubject = "Form I-9 Section 1 Completed for $fname $lname";
$hellobody = '<html><body><table style="border:5px solid black; border-radius:10px;">';
$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">'.$fname.'&nbsp;'.$lname.' has completed Section 1 of the Form I-9. Please complete Section 2.</span></td></tr>';
$hellobody .= '<tr><td>&nbsp;</td></tr>';
$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">If you have questions or need assistance, please contact us at <a href="mailto:service@bisi.com">Service@BISI.com</a> or 800-433-6010. We are in the office Monday - Friday, 8:00am - 5:00 pm MT.</span></td></tr>';
$hellobody .= '<tr><td>&nbsp;</td></tr>';
$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">Thank you!</span></td></tr>';
$hellobody .= '<tr><td>&nbsp;</td></tr>';
$hellobody .= '<tr><td>&nbsp;</td></tr>';
$hellobody .= '<tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">Req # '.$JobID.'</span></td></tr>';
$hellobody .= '</table></body></html>';
$header = "Bcc: Dennis Boyer <dennis.boyer@bisi.com>\r\n";
$header .= "From: ".$hellofrom. "\r\n";
$emaildatetime = Date('m/d/Y H:i:s');
#$header .= "Date: ".$emaildatetime."\r\n";
$header .= "MIME-Version: 1.0\r\n";		
$header .= "Content-Type: text/html; charset-ISO-8859-1\r\n";		
mail($helloto, $hellosubject, $hellobody, $header);

$Status = 'I9 Section 1 Completed';
$EntryDate = date("Y-m-d H:i:s");
$Comment = $fname.' '.$lname.' has Completed Section 1 of the I9.';
$sql2 = "insert into App_Log (PersonID, Status, Status_Date, Email, Comment) values(:PersonID, :Status, :Status_Date, :Email, :Comment);";
$save2_result = $dbo->prepare($sql2);
$save2_result->bindValue(':PersonID', $PersonID);
$save2_result->bindValue(':Status', $Status);
$save2_result->bindValue(':Status_Date', $EntryDate, PDO::PARAM_STR);
$save2_result->bindValue(':Email', '');
$save2_result->bindValue(':Comment', $Comment);
if (!$save2_result->execute()) {
	$hellofrom = "Log Update <service@bisi.com>";
	$helloto = "Dennis Boyer <dennis.boyer@bisi.com>";
	$hellosubject = "Section1 Complete Log Update Error";
	$hellobody = "PersonID = ".$PersonID."\nError Inserting into Log. Error Code: ".$save2_result->errorCode();
	mail($helloto, $hellosubject, $hellobody, "From: $hellofrom");
} 

$hellofrom = "I9Section2 Status Update <service@bisi.com>";
$helloto = "Dennis Boyer <dennis.boyer@bisi.com>";
$hellosubject = "I9Section2 Status Update";
$hellobody = "PersonID = ".$iCIMSID."\nCustomerID = ".$CustomerID."\nCurl Result = ".$status_code."\nURL = ".$url."\njson = ".$json;
mail($helloto, $hellosubject, $hellobody, "From: $hellofrom");
$dir="create/";
$fn = "ICIMS_I9Section2_Status.xml";
$filename = $dir.$fn;
$fp = fopen($filename, 'wb');
fwrite($fp, $result);
fclose($fp);

echo json_encode($msg); 
?>