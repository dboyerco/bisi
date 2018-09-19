<? 
	require_once('../pdotriton.php');
	$cardnum = $_REQUEST['cardnum'];
	if ($cardnum == '4111111111111111' || $cardnum == '370000000000002' || $cardnum == '6011000000000012' || $cardnum == '4007000000027' || 
		$cardnum == '4012888818888') {
		require_once '../anet_php_sand_sdk/AuthorizeNet.php';
	} else {
		require_once '../anet_php_sdk/AuthorizeNet.php';
	}	
	$expdate = $_REQUEST['expdate'];
	$ccv = $_REQUEST['ccv'];
	$amount = $_REQUEST['amount'];
	$fname = $_REQUEST['fname'];
	$lname = $_REQUEST['lname'];
    $company = $_REQUEST['company'];
    $address = $_REQUEST['address'];
    $city = $_REQUEST['city'];
    $state = $_REQUEST['state'];
    $zip = $_REQUEST['zip'];
    $country = '';
    $phone = '';
    $email = $_REQUEST['email'];
    $PersonID = $_REQUEST['PersonID'];
	$description = $_REQUEST['desc'];
/*	
	$cardnum = '6011000000000012';
	$expdate = '082017';
	$ccv = '999';
	$amount = '37.00';
	$fname = 'Dennis';
	$lname = 'Boyer';
    $company = '';
    $address = '1942 cindy Ct.';
    $city = 'Loveland';
    $state = 'CO';
    $zip = '80537';
    $country = '';
    $phone = '';
    $email = 'dennis.boyer@bisi.com';
    $PersonID = 9;
	$description = '3471 - Tartuga Properties background check';

					$to = "Dennis Boyer <dennis.boyer@bisi.com>";
					$hellobody = "In ValidateCard";
					$hellosubject = "ValidateCard";
					$hellofrom = "ValidateCard <info@bisi.com>";
					mail($to, $hellosubject, $hellobody, "From: $hellofrom");
*/
	$data = '';	
	$custid = '';
 	$sale = new AuthorizeNetAIM;
 	$sale->setFields(
    array(
    	'amount' => $amount,
 	    'card_num' => $cardnum,
    	'exp_date' => $expdate,
        'description' => $description,
    	'cust_id' => $custid, 
		'first_name' => $fname,
    	'last_name' => $lname,
	    'company' => $company,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'country' => $country,
        'phone' => $phone,
        'email' => $email
    	)
  	);
	$data1 = "Card for PersonID: ".$PersonID." - ".$fname.' '.$lname." - ";
#	$response = $sale->authorizeAndCapture();
  	$response = $sale->authorizeOnly();
#  	var_dump($response);
 	if ($response->approved) {
 		switch ($response->card_code_response) {
 			case 'M':
 				$data = 'The Card Code does not match';
				$logdata = $data."\n";
 				break;
#			case 'P':
#				$data = 'The Card Code was not processed';
# 				break;
			case 'S':
				$data = 'The Card Code was not indicated';
				$logdata = $data."\n";
			case 'U':
				$data = 'Card Code is not supported by the card issuer';
				$logdata = $data."\n";
 				break;
 			default: 
				$tranid = $response->transaction_id;
				$sql = "delete from App_Transaction Where PersonID = :PersonID";
				$delete=$dbo->prepare($sql);
				$delete->bindValue(':PersonID', $PersonID);
				if (!$delete->execute()) {
					$data = "Error Deleting TransactionID: \nSQL is ".$sql."\n".$save_result->errorCode();
					$logdata .= $data."\n";	
					$to = "Dennis Boyer <dennis.boyer@bisi.com>";
					$hellobody = "Error deleting TransactionID\n".$logdata;
					$hellosubject = "Error deleting TransactionID";
					$hellofrom = "TransactionID <info@bisi.com>";
					mail($to, $hellosubject, $hellobody, "From: $hellofrom");
				}			
				$sql = "Insert into App_Transaction(PersonID, TransactionID) Values(:PersonID, :TransactionID);";
				$save_result = $dbo->prepare($sql);
				$save_result->bindValue(':PersonID', $PersonID);
				$save_result->bindValue(':TransactionID', $tranid);
				if (!$save_result->execute()) {
					$data = "Error Adding TransactionID: \nSQL is ".$sql."\n".$save_result->errorCode();
					$logdata .= $data."\n";	
					$to = "Dennis Boyer <dennis.boyer@bisi.com>";
					$hellobody = "Error saving TransactionID\n".$logdata;
					$hellosubject = "Error saving TransactionID";
					$hellofrom = "TransactionID <info@bisi.com>";
					mail($to, $hellosubject, $hellobody, "From: $hellofrom");
				} else {	
					$data = $response->transaction_id;
					$logdata = $response->response_reason_text." The amount of ".$amount." Transaction Id is ".$response->transaction_id."\n";
				}
		}		
    } else {
# 		$data = $response->error_message;
 		$data = $response->response_reason_text;
		$logdata = $data."\n";		
	}
	$logdata = $data1.$logdata;
	$dir="../anet_log/";
	$date=date("mdY");
	$logfile=$dir.$date."_Tartuga_Log.txt";
	$fp = fopen($logfile, 'a');
	fwrite($fp, $logdata);
	fclose($fp);

	print json_encode(trim($data));
#	echo 'Data is - '.$data;
?>