<?
session_start();
$APP = $_SESSION['SESS_App_Name'];
$htmlredirect = "https://proteus.bisi.com/".$APP."/HR";
                header("Location: ".$htmlredirect);
		exit;
?>
