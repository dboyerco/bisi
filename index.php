<?php
$testLayout = true;

//                    0         1       2           3             4             5             6               7               8           9
$pageOrder = Array('person', 'dmv', 'address', 'employment', 'education', 'disclosure1', 'disclosure2', 'under18release', 'cardinfo', 'Thanks');
$ipaddress = getenv("REMOTE_ADDR");
$currentPage = 0;
$currentPageString = "person";
$nextPage = 1;
$pageUnder18 = 7;
$pageCardInfo = 8;
$pageThanks = 9;

function assignPage($p) {
  global $pageOrder, $currentPage, $nextPage, $currentPageString;

  $currentPage = $p;
  $lenPages = count($pageOrder);

  if($currentPage + 1 > $lenPages) {
    $nextPage = $lenPages;
  }
  else {
    $nextPage = $currentPage + 1;
  }

  $currentPageString = $pageOrder[$currentPage];
}

if(isSet($pg)) {
  assignPage($pg);
}
else if(isSet($_GET['pg'])) {
  $pg = $_GET['pg'];
  assignPage($pg);
}
else {
  assignPage(0);
}

if(!$testLayout) {
  require_once('../pdotriton.php');
}
else {
  $PersonID = "6444";
  $CD = "BnzfFtZQs4Jw6VLX";
}

echo '<!DOCTYPE HTML>
      <html>
        <head>
          <title>BIS Online Background Screen Application</title>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
          <meta name="viewport" content="width=device-width, initial-scale=1">
      		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
          <link rel="stylesheet" href="css/main.css">
          <link rel="stylesheet" href="Upload/Upload.css">
          <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
          <script language="JavaScript" type="text/javascript" src="../App_JS/validation.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
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
  if(!$testLayout) {
    $compname = $dbo->query("Select Company_Name from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
    $package = $dbo->query("Select Package from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
    $codeid = $dbo->query("Select CodeID from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
    $noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();

    // states & countries (almost all forms use this)
  	$state_result = $dbo->prepare("Select Name, Abbrev from State order by Name");
  	$state_result->execute();
    $state_options = "";

		while($state_rows = $state_result->fetch(PDO::FETCH_BOTH)) {
			$state_options .= '<option value="' . $state_rows[1] . '">' . $state_rows[0] . '</option>';
		}

  	$country_result = $dbo->prepare("Select Alpha2Code, FullName from isocountrycodes Order By FullName;");
  	$country_result->execute();
    $country_options = "";

  	while($country_rows = $country_result->fetch(PDO::FETCH_BOTH)) {
  		$country_options .= '<option value="' . $country_rows[0] . '">' . $country_rows[1] . '</option>';
  	}
  }
	else {
    $compname = "Mike Test";
    $codeid = "BnzfFtZQs4Jw6VLX";
    $package = "";
    $noemail = 'Y';

    $state_options = '<option value="co">CO</option>';
    $country_options = '<option value="usa">USA</option>';
  }

	if(!isset($CD)) {
		$CD = '';
	}

	$cnt = 1;
	$end = strrpos($_SERVER['REQUEST_URI'], '/');
	$appname = substr($_SERVER['REQUEST_URI'], 1, $end - 1);

  if(!$testLayout) {
    $cnt = $dbo->query("Select count(*) from App_Person where PersonID = " . $PersonID . " and App_Name = '" . $appname . "';")->fetchColumn();
  }

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

    include_once("{$currentPageString}.php");
	}
}
?>
