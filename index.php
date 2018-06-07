<?php
//require_once('../pdotriton.php');
$PersonID = "6444";
$CD = "BnzfFtZQs4Jw6VLX";

//<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
echo '<!DOCTYPE HTML>
      <html>
        <head>
          <title>BIS Online Background Screen Application</title>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
          <meta name="viewport" content="width=device-width, initial-scale=1">
      		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
          <link rel="stylesheet" href="css/main.css">
          <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      		<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
      		<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
      		<script language="JavaScript" type="text/javascript" src="js/autoFormats.js"></script>
      		<script src="jquery-ui/jquery-ui.js"></script>
        </head>

        <body>';


if(!isSet($PersonID)) {
  echo '<br />
        <div class="grid-x">
          <div class="cell medium-2 show-for-medium"></div>
          <div class="cell small-12 medium-8 not-logged-in">
            To access the Background Screen Application please use the link in the email that was sent to you.
          </div>
          <div class="cell medium-2 show-for-medium"></div>
        </div>';
}
else {
	//$compname = $dbo->query("Select Company_Name from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
  $compname = "Mike Test";
	//$codeid = $dbo->query("Select CodeID from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
  $codeid = 'BnzfFtZQs4Jw6VLX';

	if(!isset($CD)) {
		$CD = '';
	}

	$cnt = 1;
	$end = strrpos($_SERVER['REQUEST_URI'], '/');
	$appname = substr($_SERVER['REQUEST_URI'], 1, $end - 1);
	//$cnt = $dbo->query("Select count(*) from App_Person where PersonID = " . $PersonID . " and App_Name = '" . $appname . "';")->fetchColumn();

	if($PersonID == '' || $cnt == 0 || $codeid != $CD) {
		if($cnt == 0) {
			echo '<br />
            <div class="grid-x">
              <div class="cell medium-2 show-for-medium"></div>
              <div class="cell small-12 medium-8 not-logged-in">
                <div class="error">Invalid PersonID.</div>
                To access the Background Screen Application please use the link in the email that was sent to you.
              </div>
              <div class="cell medium-2 show-for-medium"></div>
            </div>';
		}
    else {
      echo '<br />
            <div class="grid-x">
              <div class="cell medium-2 show-for-medium"></div>
              <div class="cell small-12 medium-8 not-logged-in">
                To access the Background Screen Application please use the link in the email that was sent to you.
              </div>
              <div class="cell medium-2 show-for-medium"></div>
            </div>';
		}
	}
  else {
    echo '<div class="grid-x">
            <div class="cell medium-1 show-for-medium"></div>
            <div class="cell small-6 medium-5">
              <img src="images/bisilogo.gif">
            </div>
            <div class="cell small-6 medium-6 login-display">
              Questions with this App:<br />
              Phone: (303) 442-3960<br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              (800) 433-6010<br />
              Email: <a href="mailto:service@bisi.com">service@bisi.com</a>
            </div>
          </div>';

    include_once('person.php');
    // echo '<frameset rows="100,*" cols="*" frameborder="NO" border="0" framespacing="0">
    //         <frame src="heading.html" name="topFrame" scrolling="NO" noresize>
    //         <frameset bgcolor="#E5EAE4" rows="*" cols="80,790,*" bgcolor="#E5EAE4" framespacing="0" frameborder="NO" border="0">
    //           <frame rows="*" cols="40,*" src="nav.html" name="leftFrame" scrolling="NO" >
    //           <frame rows="*" cols="763,*" src="person.php?PersonID=' . $PersonID . '" name="mainFrame">
    //           <frame rows="*" cols="*,*" src="nav.html" name="leftFrame" scrolling="NO">
    //         </frameset>
    //       </frameset>
    //       <noframes>
    //         <body></body>
    //       </noframes>';
	}
}
?>
