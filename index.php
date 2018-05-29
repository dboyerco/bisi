<?php
//require_once('../pdotriton.php');

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
      <html>
        <head>
          <title>Background Screen Application</title>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="css/main.css">
        </head>

        <body>';


if(!isSet($PersonID)) {
  echo '<br />
        <div class="grid-x">
          <div class="cell medium-2 show-for-medium">Med</div>
          <div class="cell small-12 medium-8 not-logged-in">
            To access the Background Screen Application please use the link in the email that was sent to you.
          </div>
          <div class="cell medium-2 show-for-medium">Med</div>
        </div>';
}
else {
	//$compname = $dbo->query("Select Company_Name from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
	//$codeid = $dbo->query("Select CodeID from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();

	if(!isset($CD)) {
		$CD = '';
	}

	$cnt = 0;
	$end = strrpos($_SERVER['REQUEST_URI'], '/');
	$appname = substr($_SERVER['REQUEST_URI'], 1, $end - 1);
	//$cnt = $dbo->query("Select count(*) from App_Person where PersonID = " . $PersonID . " and App_Name = '" . $appname . "';")->fetchColumn();

	if($PersonID == '' || $cnt == 0 || $codeid != $CD) {
		if($cnt == 0) {
			echo '<br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:large; font-family=Tahoma; color:red;">Invalid PersonID.</span><br /><span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the Background Screen Application please use the link in the email that was sent to you.</td></tr><tr><td>&nbsp;</td></table>';
		}
    else {
      echo '<br />
            <div class="row">
              <div class="columns medium-2"></div>
              <div class="columns small-12 medium-8 not-logged-in">
                To access the Background Screen Application please use the link in the email that was sent to you.
              </div>
              <div class="columns medium-2"></div>
            </div>';
		}
	}
  else {
    echo '<frameset rows="100,*" cols="*" frameborder="NO" border="0" framespacing="0">
            <frame src="heading.html" name="topFrame" scrolling="NO" noresize>
            <frameset bgcolor="#E5EAE4" rows="*" cols="80,790,*" bgcolor="#E5EAE4" framespacing="0" frameborder="NO" border="0">
              <frame rows="*" cols="40,*" src="nav.html" name="leftFrame" scrolling="NO" >
              <frame rows="*" cols="763,*" src="person.php?PersonID=' . $PersonID . '" name="mainFrame">
              <frame rows="*" cols="*,*" src="nav.html" name="leftFrame" scrolling="NO">
            </frameset>
          </frameset>
          <noframes>
            <body></body>
          </noframes>';
	}
}
?>
