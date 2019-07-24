<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$testLayout = false;

$ipaddress = getenv("REMOTE_ADDR");
$currentPage = 0;
$currentPageString = "person";
$dob = "";
$nextPage = 1;
$prevPage = 0;

//require_once('pdobisitest.php');
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
  $sql = "SELECT
						c.Company_Name,
            c.App_Name,
            c.bisAcct,
						p.Package,
						p.CodeID,
						p.No_Email,
            p.Email_Type,
            p.Date_of_Birth
					FROM
						App_Person p
              INNER JOIN App_HR_Company c
                ON p.Company_Name = c.Company_Name
					WHERE
						p.PersonID = :PersonID";
	$result = $dbo->prepare($sql);
	$result->bindValue(':PersonID', $PersonID);
	$result->execute();
  $row = $result->fetch(PDO::FETCH_BOTH);

  // echo "<pre>";
  // print_r($row);
  // echo "</pre>";
  // die("HERE");

  $compname = $row['Company_Name'];
  $appname = $row['App_Name'];
  $cocode = $row['bisAcct'];
  $package = $row['Package'];
  //$package = 'package4';
  $codeid = $row['CodeID'];
  $noemail = $row['No_Email'];
  $etype = $row['Email_Type'];
  $dob = $row['Date_of_Birth'];

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

  $sql = "SELECT
						available.Page_ID,
						available.Page_Value,
						available.Page_Name,
						assigned.Page_Order,
            assigned.Address_Years,
            assigned.Capture_MI,
            assigned.Capture_SSN,
						assigned.Num_References,
            assigned.Require_Prof_License
					FROM
						WebAppAvailablePages available
						 	INNER JOIN WebAppAssignedPages assigned
								ON assigned.Company_No = :Company_No and assigned.Package_Value = :Package_Value
					WHERE
						available.Page_ID = assigned.Page_ID
					ORDER BY
						assigned.Page_Order";
	$row = $dbo->prepare($sql);
	$row->bindValue(':Company_No', $cocode);
	$row->bindValue(':Package_Value', $package);
	$row->execute();

  $pageOrder = array();
  $addressYears = 0;
  $captureMI = "";
  $captureSSN = "";
  $numReferences = 0;

	while($result = $row->fetch(PDO::FETCH_BOTH)) {
    if(strpos($result['Page_Value'], ',') === true) {
      $values = explode(',', $result['Page_Value']);

      for($v = 0; $v < count($values); $v++) {
        array_push($pageOrder, $values[$v]);
      }
    }
    else {
      if($result['Page_Value'] == 'person') {
        $captureMI = $result['Capture_MI'] == "Y" ? true : false;
        $captureSSN = $result['Capture_SSN'] == "Y" ? true : false;
      }
      else if($result['Page_Value'] == 'address') {
        $addressYears = $result['Address_Years'];
      }
      else if($result['Page_Value'] == 'references') {
        $numReferences = $result['Num_References'];
      }
      else if($result['Page_Value'] == 'proflicense') {
        $profLicense = $result['Require_Prof_License'];
      }

      array_push($pageOrder, $result['Page_Value']);
    }
	}

  function isUnder18($d) {
    $DOB = date("m/d/Y", strtotime($d));
  	$date = date("m/d/Y");
  	$datediff = strtotime($date) - strtotime($DOB);
  	$days = floor($datediff / (60 * 60 * 24));

    if($days < 6570) {
      return true;
    }

    return false;
  }

  if(isSet($noemail) && $noemail != "") {
    unset($pageOrder[count($pageOrder) - 3]);
    array_splice($pageOrder, count($pageOrder) - 2, 0, 'certification');
    // skips disclosure, authorization & credit card forms
  }
  else if(isSet($dob) && $dob != "" && isUnder18($dob)) {
    array_splice($pageOrder, count($pageOrder) - 2, 0, 'under18release');
  }

  $pageUnder18 = count($pageOrder) - 3;
  $pageCardInfo = count($pageOrder) - 2;
  $pageThanks = count($pageOrder) - 1;
  $thisYear = date("Y");
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
  $exp_years_list = '<option>Year</option>';

  for($yr = $thisYear; $yr >= 1900; $yr--) {
    $years_list .= '<option value="' . $yr . '">' . $yr . '</option>';
  }

  for($eyr = $thisYear; $eyr <= ($thisYear + 5); $eyr++) {
    $exp_years_list .= '<option value="' . $eyr . '">' . $eyr . '</option>';
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

    // echo "<pre>";
    // print_r($pageOrder);
    // echo "</pre>";
    // die();
    echo "<br>CURRENT PAGE:" . $currentPageString . "<br>";
    include_once("{$currentPageString}.php");
	}
}

echo '  </body>
      </html>';

?>
