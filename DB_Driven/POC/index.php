<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ipaddress = getenv("REMOTE_ADDR");
$currentPage = 0;
$currentPageString = "person";
$nextPage = 1;
$prevPage = 0;
$compname = "Test Company";
$PersonID = "abcd1234";
$CD = "12345";
$etype = "";
$No_Email = "";

Class Form {
  public $formTitle = "Subject Information";
  public $formFields = array();

  public function __construct($formTitle, $formFields) {
    $this->formTitle = $formTitle;
    $this->formFields = $formFields;
  }
}

Class Field {
  public $fieldName;
  public $fieldNameCheckbox;
  public $label;
  public $labelCheckbox;
  public $fieldType;
  public $fieldRequired;
  public $cellSizeMobile;
  public $cellSizeDesktop;
  public $fieldOrder;
  public $fieldValue;
  public $onCheckboxClick;

  public function __construct($fieldName, $fieldNameCheckbox, $label, $labelCheckbox, $fieldType, $fieldRequired, $cellSizeMobile, $cellSizeDesktop, $fieldOrder, $fieldValue, $onCheckboxClick) {
    $this->fieldName = $fieldName;
    $this->fieldNameCheckbox = $fieldNameCheckbox;
    $this->label = $label;
    $this->labelCheckbox = $labelCheckbox;
    $this->fieldType = $fieldType;
    $this->fieldRequired = $fieldRequired;
    $this->cellSizeMobile = $cellSizeMobile;
    $this->cellSizeDesktop = $cellSizeDesktop;
    $this->fieldOrder = $fieldOrder;
    $this->fieldValue = $fieldValue;
    $this->onCheckboxClick = $onCheckboxClick;
  }
}

$personFormData = new Form("Subject Information", array(
  new Field("First_Name", "", "First Name", "", "text", true, 12, 5, 1, "Michael", ""),
  new Field("Middle_Name", "nomi", "M.I.", "No M.I.", "textWithCheckbox", true, 6, 1, 2, "D", "NoMI"),
  new Field("Last_Name", "", "Last Name", "", "text", true, 12, 5, 3, "Perrotto", "")
));

function renderForm($formData) {
  global $nextPage, $PersonID, $CD, $ipaddress, $compname, $etype, $No_Email;

  $retVal = '<form method="POST" action="index.php?pg=' . $nextPage . '&PersonID=' . $PersonID . '&CD=' . $CD . '" name="ALCATEL">
      				<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
      				<input type="hidden" name="CD" id="CD" value="' . $CD . '">
      				<input type="hidden" name="ipaddr" id="ipaddr" value="' . $ipaddress . '">

      				<div class="general-page">
      					<div class="sub-menu">&nbsp;</div>

      					<div class="sub-page">
      						<div class="grid-x margins">

      							<div class="cell small-12">
      								<h3>
      									' . $compname . ' Web Application Portal<br>
      									<img src="../../files/horizontal-line.gif" height="3" width="100%">
      								</h3>
      							</div>

      							<div class="cell small-12">
      								<span class="sub-heading">' . $formData->formTitle . '</span><br>';

  if($etype == 'T') {
  	$retVal .= '     <strong>Disclaimer: </strong>All information requested in this application is pertinent and necessary. Failure to fill out all information can delay the tenant process.<br>';
  }
  else {
  	$retVal .= '     <strong>Disclaimer: </strong>All information requested in this application is pertinent and necessary. Failure to fill out all information can delay the hiring process.<br>';
  }

  if($No_Email == 'N') {
  	$retVal .= '     <strong>Note: </strong>You can return to this Application Portal at any time by clicking on the link in the email that was sent to you. All the data you have saved will be displayed when you return.<br>';
  }

  $retVal .= '      <strong>Please make sure that the first and last name is as it appears on your government issued ID / SSN card etc.</strong>
                  </div>
                </div>

  						  <div class="grid-x margins person-form">
                  <div class="cell small-12 required">
                    * Required Fields To Continue
                  </div>';

  foreach($formData->formFields as $field) {
    switch($field->fieldType) {
      case "text":
        $retVal .= renderTextField($field);
        break;
      case "textWithCheckbox":
        $retVal .= renderTextFieldWithCheckbox($field);
        break;
      default:
        die("Field Type Not Supported! Contact an Administrator.");
    }
  }

  return $retVal;;
}

function renderTextField($field) {
  $retVal = '<div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . '">
              <label>
                ' . $field->label . ' ' . ($field->fieldRequired ? '<span class="required">*</span>' : '') . '<br />
                <input type="text" name="' . $field->fieldName . '" id="' . $field->fieldName . '" value="' . htmlspecialchars($field->fieldValue) . '" maxlength="40">
              </label>
            </div>';

  return $retVal;
}

function renderTextFieldWithCheckbox($field) {
  $retVal = '<div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . '">
              <label>
                ' . $field->label . ' ' . ($field->fieldRequired ? '<span class="required">*</span>' : '') . '
                <input type="text" name="' . $field->fieldName . '" id="' . $field->fieldName . '" value="' . htmlspecialchars($field->fieldValue) . '" maxlength="1">
              </label>
            </div>
            <div class="cell small-6 medium-1">
              <label>
                ' . $field->labelCheckbox . '<br />
                &nbsp;&nbsp;&nbsp;<input type="checkbox" name="' . $field->fieldNameCheckbox . '" id="' . $field->fieldNameCheckbox . '"' . ($field->onCheckboxClick != "" ? ' onclick="' . $field->onCheckboxClick . '()' : '') . '">
              </label>
            </div>';

  return $retVal;
}

echo '<!DOCTYPE HTML>
      <html>
        <head>
          <title>BIS Online Background Screen Application</title>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
          <meta name="viewport" content="width=device-width, initial-scale=1">
      		<link rel="stylesheet" href="../../jquery-ui/jquery-ui.css">
          <link rel="stylesheet" href="../../css/main.css">
          <link rel="stylesheet" href="../../Upload/Upload.css">
          <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
          <script src="../../jquery-ui/jquery-ui.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../../../App_JS/validate.js"></script>
          <script language="JavaScript" type="text/javascript" src="../../../App_JS/validation.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../../../App_JS/autoTab.js"></script>
      		<script language="JavaScript" type="text/javascript" src="../../js/autoFormats.js"></script>
        </head>

        <body>
          <div class="grid-x">
            <div class="cell medium-1 show-for-medium"></div>
            <div class="cell small-6 medium-5">
              <img src="../../images/bisilogo.gif">
            </div>
            <div class="cell small-6 medium-6 login-display">
              Questions with this App:<br />
              Phone: (303) 442-3960<br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              (800) 433-6010<br />
              Email: <a href="mailto:service@bisi.com">service@bisi.com</a>
            </div>
          </div>';

echo renderForm($personFormData);

echo '  </body>
      </html>';

?>
