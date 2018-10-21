<?php
require_once('../../pdotriton.php');
require_once('/usr/share/php/mpdf/mpdf.php');

$PersonID = $_REQUEST['PersonID'];
$UploadType = $_REQUEST['UploadType'];
$dir = '../../bisi_uploads/';
error_reporting(0);
$response = "";

if ( 0 < $_FILES['file']['error'] ) {
    $response .= 'Error: ' . $_FILES['file']['error'] . '<br>';
} else {
	$filename = $_FILES['file']['name'];
	if(move_uploaded_file($_FILES['file']['tmp_name'],$dir . $_FILES['file']['name'])) {   	
		$response .= $filename." was uploaded\n";
		if (substr($filename, strlen($filename) - 3, 3) != 'doc' And substr($filename, strlen(filename) - 3, 3) != 'tif' And 
			substr($filename, strlen($filename) - 4, 4) != 'docx') {
#			echo $filename.'<br />';
			$lastDotPos = strrpos($filename, '.');
			$file =	substr($filename, 0, $lastDotPos);
			$newfilename = $file.".pdf";
			$docpdf = $dir.$file.".pdf";
			if (strpos($filename,'jpg') == true || strpos($filename,'JPG') == true || strpos($filename,'png') == true || strpos($filename,'gif') == true) {
				$image = $dir.$filename;
				$img = new Imagick($image);
				$img->setImageFormat('pdf');
				$success = $img->writeImage($docpdf);
				if ($success) {
					$response .= $filename." converted successfully to ".$newfilename."\n";
					chmod($docpdf,0664);
					$filename = $newfilename;
				}
			} elseif (strpos($filename,'html') == true || strpos($filename,'htm') == true) {
				$htmlcontent = file_get_contents($dir.$filename);
				$mpdf = new mPDF('', 'A4',10,'', 15, 15, 0, 0, 0, 0, 'P');
				$mpdf->SetTopMargin('30%');
				$mpdf->use_kwt = true;
				$mpdf->WriteHTML($htmlcontent);
				$mpdf->Output($docpdf);
				chmod($docpdf,0664);
				$response .= $filename." converted successfully to ".$newfilename."\n";
				$filename = $newfilename;
			} 
		}
		
		$UploadId = $dbo->query("Select max(UploadID) from App_Uploads where PersonID = ".$PersonID.";")->fetchColumn();
		$UploadId++; 

		$sql = "Insert into App_Uploads(PersonID, UploadID, UploadType, Image_URL)";
		$sql .= "Values (:PersonID, :UploadID, :UploadType, :Image_URL);";
		$isrt_result = $dbo->prepare($sql);

		$isrt_result->bindValue(':UploadID', $UploadId);
		$isrt_result->bindValue(':PersonID', $PersonID);
		$isrt_result->bindValue(':UploadType', $UploadType);
		$isrt_result->bindValue(':Image_URL', $filename);
		if (!$isrt_result->execute()) {
			if ($isrt_result->errorCode() == 23000) {
				$response .= "Error Code ".$isrt_result->errorCode()." -PersonId ".$PersonID." UploadID ".$UploadId." - UploadType ".$UploadType." Image ".$filename." Already Exist\n";
			} else {	
				$response .= "Error Insert did not work. Error Code: ".$isrt_result->errorCode()."\n";
			}	
		} else {
			$msg = '';
#			$profno = '';
			$profno = $dbo->query("Select Prof_No from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
			$custid = $dbo->query("Select CustID from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
			$UserName = $dbo->query("Select UserName from userinfo;")->fetchColumn();
			$Password = $dbo->query("Select Password from userinfo;")->fetchColumn();
			$BOID = $dbo->query("Select BOID from userinfo;")->fetchColumn();
			
			include('UploadProfileDocument.php');

			if ($profno > '') {
				$uploadfullname = "/var/www/html/bisi/bisi_uploads/".$filename;
				$msg = UploadProfileDocument($UserName, $Password, $BOID, $custid, $profno, $uploadfullname, $filename, $UploadType);	
			}
			if ($msg > '') {
				$response .= $msg."\n";
			}	
		}	
	} else {
		$response .= $dir . $filename." Upload failed\n";
	}	
#	move_uploaded_file($_FILES['file']['tmp_name'], $dir . $_FILES['file']['name']);
}
/*
$to = "Dennis Boyer <dennis.boyer@bisi.com>";
$hellobody = "Upload to Profile # ".$profno."\nCustID: ".$custid."\nUserName: ".$UserName."\nPWD: ".$Password."\nBOID: ".$BOID."\nPersonID: ".$PersonID."\nResponse: ".$response;
$hellosubject = "Upload to Profile";
$hellofrom = "Upload <info@bisi.com>";
mail($to, $hellosubject, $hellobody, "From: $hellofrom");
*/
echo json_encode($response);

/*

if(!is_dir($dir))
{
#	echo "making dir UPLOAD<br />";
	mkdir($dir,0777);
}
$i=0;
$files = $_FILES['file'];
$response .= "File is ".$files."<br />";
foreach($files['name'] as $file)
{
#	$fileName = md5(uniqid()).'.'.pathinfo($file,PATHINFO_EXTENSION); 
	$fileName = $file; 
	$destination = $dir.$fileName;
#	echo "destination is ".$destination; 
	if(move_uploaded_file($files['tmp_name'][$i],$destination))
	{
		$thumb = createThumbnail($destination,120);
#		$response[] = array('name'=>$destination,'size'=>$files['size'][$i]);
#		$image = "<img src='".$destination."' width=100>";
		$response .= $destination."<br />";
	}
	$i++;
}
if ($i == 0) { 
	$response .= "No File <br />"; 
}
echo json_encode($response);

function createThumbnail($source,$size)
{
	    $img = imagecreatefromjpeg($source);
	    $width = imagesx($img);
        $height = imagesy($img);

        $new_width = $size;
        $new_height = floor($height *($size/$width));

        $tmp_img = imagecreatetruecolor( $new_width, $new_height );
        imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      
		$thumb = UPLOAD.'/thumb_'. pathinfo($source,PATHINFO_BASENAME);
        imagejpeg($tmp_img,$thumb);
		return $thumb;
} */
?>
