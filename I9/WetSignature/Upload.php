<?php
$dir = '../csvuploads/';
#$UploadType = 'CSV';
error_reporting(0);
$response = "";

if ( 0 < $_FILES['file']['error'] ) {
    $response .= 'Error: ' . $_FILES['file']['error'] . '<br>';
} else {
	$info = pathinfo($_FILES['file']['name']);
	if ($info['extension'] == 'csv'){
		$filename = $_FILES['file']['name'];
		if(move_uploaded_file($_FILES['file']['tmp_name'],$dir . $_FILES['file']['name'])) {   	
			$response .= $filename." was uploaded\n";
		} else {
			$response .= $dir . $filename." Upload failed\n";
		}
	} else {
		$response .= "File ".$filename." is not a CSV file. - Upload failed\n";
	}	
}
echo json_encode($response);

?>
