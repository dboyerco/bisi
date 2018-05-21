<?php
require_once('../../pdotriton.php');

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
//			$response .= $filename." Added.\n";
		}	
	} else {
		$response .= $dir . $filename." Upload failed\n";
	}	
#	move_uploaded_file($_FILES['file']['tmp_name'], $dir . $_FILES['file']['name']);
}
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
