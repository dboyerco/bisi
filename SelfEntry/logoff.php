<?
session_start();

$APP = $_SESSION['SESS_App_Name'];
$htmlredirect = "https://proteus.bisi.com/miketest/SelfEntry/?APP=" . $APP;
header("Location: " . $htmlredirect);
exit;
?>
