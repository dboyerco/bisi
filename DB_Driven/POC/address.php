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
$state_options = "";

Class Form {
  public $formTitle;
  public $formSubtitle;
  public $formFields = array();

  public function __construct($formTitle, $formSubtitle, $formFields) {
    $this->formTitle = $formTitle;
    $this->formSubtitle = $formSubtitle;
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
  public $cellSizeMobileForm;
  public $cellSizeDesktopForm;
  public $fieldOrder;
  public $fieldValue;
  public $onCheckboxClick;
  public $subHeading;

  public function __construct($fieldName, $fieldNameCheckbox, $label, $labelCheckbox, $fieldType, $fieldRequired, $cellSizeMobile, $cellSizeDesktop, $cellSizeMobileForm, $cellSizeDesktopForm, $fieldOrder, $fieldValue, $onCheckboxClick, $subHeading) {
    $this->fieldName = $fieldName;
    $this->fieldNameCheckbox = $fieldNameCheckbox;
    $this->label = $label;
    $this->labelCheckbox = $labelCheckbox;
    $this->fieldType = $fieldType;
    $this->fieldRequired = $fieldRequired;
    $this->cellSizeMobile = $cellSizeMobile;
    $this->cellSizeDesktop = $cellSizeDesktop;
    $this->cellSizeMobileForm = $cellSizeMobileForm;
    $this->cellSizeDesktopForm = $cellSizeDesktopForm;
    $this->fieldOrder = $fieldOrder;
    $this->fieldValue = $fieldValue;
    $this->onCheckboxClick = $onCheckboxClick;
    $this->subHeading = $subHeading;
  }
}

$personFormData = new Form(
  "Address Information",
  "Please provide your address information for the past 7 years. starting with your current address.<br />Please be as detailed as possible when providing this information to include a full 7 years.",
  array(
    new Field("Addr1", "", "Street", "", "text", true, 8, 2, 4, 8, 1, "123 My St", "", false),
    new Field("Apt", "", "Apt", "", "text", false, 4, 1, 2, 4, 2, "#20", "", false),
    new Field("City", "", "City", "", "text", true, 4, 3, 6, 12, 3, "Denver", "", false),
    new Field("State_addr", "", "State", "", "stateDropDown", true, 2, 1, 6, 12, 4, "CO", "", false),
    new Field("County", "", "County", "", "text", true, 4, 3, 6, 12, 5, "Larimer", "", false),
    new Field("Zipcode", "", "Zip Code", "", "text", true, 2, 2, 6, 12, 6, "80537", "", false)
  )
);

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
      								<span class="sub-heading">' . $formData->formTitle . '</span><br>
                      ' . $formData->formSubtitle . '<br />&nbsp;
                    </div>

                    <div class="cell small-12">
        							<h3>Current Address</h3>
        						</div>';

  foreach($formData->formFields as $field) {
    switch($field->fieldType) {
      case "text":
        $retVal .= renderTextDisplay($field);
        break;
      case "textWithCheckbox":
        $retVal .= renderTextDisplay($field);
        break;
      case "stateDropDown":
        $retVal .= renderTextDisplay($field);
        break;
      default:
        die("Field Type Not Supported! Contact an Administrator.");
    }
  }

  $retVal .= '    </div>
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
      case "stateDropDown":
        $retVal .= renderStateDropDownField($field);
        break;
      default:
        die("Field Type Not Supported! Contact an Administrator.");
    }
  }

  return $retVal;;
}

function renderDateRangeDisplay($field1, $field2) {
  $retVal = '<div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . ' ' . ($field->subHeading ? 'sub-heading' : '') . '">
              &nbsp;' . htmlspecialchars($field->fieldValue) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . htmlspecialchars($field->fieldValue) . '
            </div>';

  return $retVal;
}

function renderTextDisplay($field) {
  $retVal = '<div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . ' ' . ($field->subHeading ? 'sub-heading' : '') . '">
              ' . htmlspecialchars($field->fieldValue) . '
            </div>';

  return $retVal;
}

function renderTextField($field) {
  $retVal = '<div class="cell small-12 medium-6">
							' . $field->label . ' ' . ($field->fieldRequired ? '<span class="required">*</span>' : '') . '
						</div>
            <div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . '">
							<input type="text" name="' . $field->fieldName . '" id="' . $field->fieldName . '" value="' . htmlspecialchars($field->fieldValue) . '" maxlength="100">
						</div>';

  // $retVal = '<div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . '">
  //             <label>
  //               ' . $field->label . ' ' . ($field->fieldRequired ? '<span class="required">*</span>' : '') . '<br />
  //               <input type="text" name="' . $field->fieldName . '" id="' . $field->fieldName . '" value="' . htmlspecialchars($field->fieldValue) . '" maxlength="40">
  //             </label>
  //           </div>';

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

function renderStateDropDownField($field) {
  global $state_options;

  $retVal = '<div class="cell small-12 medium-6">
              ' . $field->label . ' ' . ($field->fieldRequired ? '<span class="required">*</span>' : '') . '
            </div>
            <div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . '">
              <select name="' . $field->fieldName . '" id="' . $field->fieldName . '" onchange="loadcounties(\'state\',\'\')">
                <option value="">Select a ' . $field->fieldName . '</option>
                <option value="">-Other-</option>
                ' . $state_options . '
              </select>
            </div>';

  // $retVal = '<div class="cell small-' . $field->cellSizeMobile . ' medium-' . $field->cellSizeDesktop . '">
  //             <label>
	// 							' . $field->label . ' ' . ($field->fieldRequired ? '<span class="required">*</span>' : '') . '<br />
  // 							<select name="' . $field->fieldName . '" id="' . $field->fieldName . '" onchange="loadcounties(\'state\',\'\')">
  // 								<option value="">Select a State</option>
  // 								<option value="">-Other-</option>
  // 								' . $state_options . '
  // 							</select>
  //             </label>
	// 					</div>';

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
