<?php
/*********************************************************************************************************
 *	function to upload profile document                                                                  *
 *  Parameters passed in:                                                                                *
 *      $username        User Name                                                                       *
 *      $userpwd         Password                                                                        *
 *      $boid            Business Owner ID                                                               *
 *      $custid          Customer ID                                                                     *
 *      $profno          Profile Number                                                                  *
 *      $uploadname      name of the file to be uploaded                                                 *
 *  	$filedesc        Description of the file                                                         *
 *          	                                                                                         *
 *  uploadParams	                                                                                     *
 *		sUserName => $username        User Name                                                          *
 *   	sPassword => $userpwd         Password                                                           *
 *   	iBOID  => $boid               Business Owner ID                                                  * 
 *   	sCustID => $custid            Customer ID                                                        *
 *   	iFileID => 0                  0 - New File or get FileID from GetOrderDocuments call             *                         
 *   	sProfNo => $profno            Profile Number                                                     *                         
 *   	sFileType => 'Order'                                                                             *
 *   	sContentType =>               MIME Type "text/plain", "application/pdf"                          *
 *   	iSecurityLevel => 1           defines who can view the document                                  *
 *		                              1 - No restiction: BO's, Cumstomers                                *
 *		                              2 - BO only can view (internal)                                    *
 *   	sDescription =>               Description of the file                                            *
 *   	sFileName => $uploadname      File Name - Must include extension.                                *
 *   	baFileBytes => $uploaddata    Contents of the file in Byte Array                                 *
 *		                                                                                                 *
 *  Returns:                                                                                             *
 *		$errmsg                                                                                          *
 *********************************************************************************************************/	 
function UploadProfileDocument($username, $userpwd, $boid, $custid, $profno, $uploadfullname, $uploadname, $filedesc) {
	$dir = "../create/";
	$uploaddata = file_get_contents($uploadfullname);
#	var_dump($uploaddata);
	$uploadParams = array(
    	'sUserName' => $username,
    	'sPassword' => $userpwd,
    	'iBOID'  => $boid,   
    	'sCustID' => $custid,
	    'iFileID' => 0, 
	    'sProfNo' => $profno,
   		'sFileType' => 'Profile',
   		'sContentType' => 'multipart/form-data',
   		'iSecurityLevel' => 1,
   		'sDescription' => $filedesc,
   		'sFileName' => $uploadname,
   		'baFileBytes' => $uploaddata
	);
	include('../../include/xmlfunctions.php');
#	print_r($uploadParams);
	$client = new SoapClient('https://gateway.clearstar.net/CSGWDocument/Document.wsdl');
	try {
		$response = $client->UploadProfileDocument($uploadParams);
		$xml = $response->UploadProfileDocumentResult->any;
		preg_match('/\<ErrorStatus\>(.*?)\<\/ErrorStatus\>/s', $xml, $ErrStatus);
	    $errstatus = $ErrStatus[0];
    	preg_match('/\<Code\>(.*?)\<\/Code\>/s', $errstatus, $ErrCode);
		$xml = formatxml($xml);
		$fn = $profno.".log";
		$filename = $dir.$fn;
		$fp = fopen($filename, 'ab');
		fwrite($fp, $xml);
		fclose($fp);
		if ($ErrCode[1] == 0) { 
			$msg = "Document $uploadname successfully uploaded for Profile $profno\r\n";
		} else {
			preg_match('/\<Message\>(.*?)\<\/Message\>/s', $errstatus, $ErrMsg);
			$msg = "Error Status:\r\n Error Code = ".$ErrCode[1]."\r\n Error Message = ".$ErrMsg[1]."\r\n";
		}
	}
	catch(Exception $e) {
		$msg = 'Exception Message: ' .$e->getMessage()."\r\n";
	}	
			$to = "Dennis Boyer <dennis.boyer@bisi.com>";
			$hellobody = "Upload to Profile # ".$profno."\nCustID: ".$custid."\nUserName: ".$username."\nPWD: ".$userpwd."\nBOID: ".$boid."\nmsg: ".$msg;
			$hellosubject = "Upload to Profile";
			$hellofrom = "Upload <info@bisi.com>";
			mail($to, $hellosubject, $hellobody, "From: $hellofrom");
		
	return $msg;
}
?>