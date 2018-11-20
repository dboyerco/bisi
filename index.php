<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$testLayout = false;

$ipaddress = getenv("REMOTE_ADDR");
$currentPage = 0;
$currentPageString = "person";
$nextPage = 1;
$prevPage = 0;

require_once('../pdotriton.php');

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
          <script src="jquery-ui/jquery-ui.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
          <script language="JavaScript" type="text/javascript" src="../App_JS/validation.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
      		<script language="JavaScript" type="text/javascript" src="js/autoFormats.js"></script>
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
  $compname = $dbo->query("Select Company_Name from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
  $package = $dbo->query("Select Package from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
  $codeid = $dbo->query("Select CodeID from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
  $noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
  $etype = $dbo->query("Select Email_Type from App_Person where PersonID = ".$PersonID.";")->fetchColumn();

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

  //                     0          1          2         3               4                 5             6            7        8        9           10            11           12             13             14               15         16
  $pageOrder = Array('person', 'additional', 'bank', 'business', 'dmv_with_vehicle', 'proflicense', 'references', 'rentals', 'dmv', 'address', 'employment', 'education', 'disclosure1', 'disclosure2', 'under18release', 'cardinfo', 'Thanks');
  $pageUnder18 = 14;
  $pageCardInfo = 15;
  $pageThanks = 16;
  $days_list = '<option>Day</option>';
  $months_list = '<option value="">Month</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>';
  $years_list = '<option>Year</option>';

  for($yr = date("Y"); $yr >= 1900; $yr--) {
    $years_list .= '<option value="' . $yr . '">' . $yr . '</option>';
  }

  for($day = 1; $day <= 31; $day++) {
    $day_string = ($day < 10 ? "0" . $day : $day);
    $days_list .= '<option value="' . $day_string . '">' . $day_string . '</option>';
  }

  function buildMonthsList($selectedMonth) {
    $retVal = '<option value="" ' . ($selectedMonth == "" ? "selected" : "") . '>Month</option>
              <option value="01" ' . ($selectedMonth == "01" ? "selected" : "") . '>January</option>
              <option value="02" ' . ($selectedMonth == "02" ? "selected" : "") . '>February</option>
              <option value="03" ' . ($selectedMonth == "03" ? "selected" : "") . '>March</option>
              <option value="04" ' . ($selectedMonth == "04" ? "selected" : "") . '>April</option>
              <option value="05" ' . ($selectedMonth == "05" ? "selected" : "") . '>May</option>
              <option value="06" ' . ($selectedMonth == "06" ? "selected" : "") . '>June</option>
              <option value="07" ' . ($selectedMonth == "07" ? "selected" : "") . '>July</option>
              <option value="08" ' . ($selectedMonth == "08" ? "selected" : "") . '>August</option>
              <option value="09" ' . ($selectedMonth == "09" ? "selected" : "") . '>September</option>
              <option value="10" ' . ($selectedMonth == "10" ? "selected" : "") . '>October</option>
              <option value="11" ' . ($selectedMonth == "11" ? "selected" : "") . '>November</option>
              <option value="12" ' . ($selectedMonth == "12" ? "selected" : "") . '>December</option>';

    return $retVal;
  }

  function buildDaysList($selectedDay) {
    $retVal = '<option value="" ' . ($selectedDay == "" ? "selected" : "") . '>Day</option>';

    for($day = 1; $day <= 31; $day++) {
      $day_string = ($day < 10 ? "0" . $day : $day);
      $retVal .= '<option value="' . $day_string . '" ' . ($selectedDay == $day_string ? "selected" : "") . '>' . $day_string . '</option>';
    }

    return $retVal;
  }

  function buildYearsList($selectedYear, $includeXXXX=false) {
    $retVal = '<option>Year</option>';

    if($includeXXXX) {
      $retVal .= '<option value="XXXX" selected>XXXX</option>';
    }

    for($yr = date("Y"); $yr >= 1900; $yr--) {
      $retVal .= '<option value="' . $yr . '" ' . ($selectedYear == $yr ? "selected" : "") . '>' . $yr . '</option>';
    }

    return $retVal;
  }

  function assignPage($p) {
    global $pageOrder, $currentPage, $nextPage, $prevPage, $currentPageString;

    $currentPage = $p;
    $lenPages = count($pageOrder);

    if($currentPage + 1 > $lenPages) {
      $nextPage = $lenPages;
    }
    else {
      $nextPage = $currentPage + 1;
    }

    if($currentPage - 1 <= 0) {
      $prevPage = 0;
    }
    else {
      $prevPage = $currentPage - 1;
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

	if(!isset($CD)) {
		$CD = '';
	}

  echo '<script>
          var nextPage = "' . $nextPage . '";
          var cd = "' . $CD . '";
          var prevAction = "index.php?pg=' . $prevPage . '&PersonID=' . $PersonID . '&CD=' . $CD . '";
        </script>';

	$cnt = 1;
	$end = strrpos($_SERVER['REQUEST_URI'], '/');
	$appname = substr($_SERVER['REQUEST_URI'], 1, $end - 1);
  $cnt = $dbo->query("Select count(*) from App_Person where PersonID = " . $PersonID . " and App_Name = '" . $appname . "';")->fetchColumn();

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

    $FormAction = "index.php?pg={$nextPage}&PersonID=" . $PersonID . "&CD=" . $CD;

    include_once("{$currentPageString}.php");
	}
}

echo '  </body>
      </html>';

?>
