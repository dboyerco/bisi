<?php
	require_once('../../pdotriton.php');

	//Start session
	session_start();

	if(!isset($_REQUEST['CO'])) {
		$CO = $_SESSION['SESS_BIS_ACCOUT'];
		$CONAME = $_SESSION['SESS_Company_Name'];
		$COTYPE = $_SESSION['SESS_Company_Type'];
		$APP = $_SESSION['SESS_App_Name'];
	}
	else {
		$CO = $_REQUEST['CO'];
		$CONAME = $dbo->query("select Company_Name from App_HR_Company where bisAcct = '" . $CO . "';")->fetchColumn();
		$COTYPE = $dbo->query("select Company_Type from App_HR_Company where bisAcct = '" . $CO . "';")->fetchColumn();
		$PARENT = $dbo->query("Select Parent_Account from App_HR_Company where bisAcct = '" . $CO . "';")->fetchColumn();

		$APP = $_REQUEST['APP'];
		$_SESSION['SESS_BIS_ACCOUT'] = $CO;
		$_SESSION['SESS_Company_Name'] = $CONAME;
		$_SESSION['SESS_Company_Type'] = $COTYPE;
		$_SESSION['SESS_App_Name'] = $APP;
		$_SESSION['SESS_Parent_Account'] = $PARENT;
	}

	//Unset the variables stored in session
	unset($_SESSION['SESS_MEMBER_ID']);
	unset($_SESSION['SESS_UserName']);
	unset($_SESSION['SESS_Email']);
	unset($_SESSION['SESS_FName']);
	unset($_SESSION['SESS_LName']);
	unset($_SESSION['SESS_User_Type']);

	session_write_close();
	header("location: ../../HR/Applicant_Entry.php");
	exit();

?>
