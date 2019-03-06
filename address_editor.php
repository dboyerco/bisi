<?php
require_once('../pdotriton.php');

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

Class Form {
  public $formFields = array();

  public function __construct($formFields) {
    $this->formFields = $formFields;
  }

  public function editField($fieldName) {
    return '<span onclick="editField(\'' . $fieldName . '\')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Field" title="Edit Field"/></span>';
  }
}

Class Field {
  public $fieldName;
	public $fieldVisible;
  public $fieldRequired;
  public $cellSizeMobile;
  public $cellSizeDesktop;
  public $fieldOrder;

  public function __construct($fieldName, $fieldVisible, $fieldRequired, $cellSizeMobile, $cellSizeDesktop, $fieldOrder) {
    $this->fieldName = $fieldName;
		$this->fieldVisible = $fieldVisible;
    $this->fieldRequired = $fieldRequired;
    $this->cellSizeMobile = $cellSizeMobile;
    $this->cellSizeDesktop = $cellSizeDesktop;
    $this->fieldOrder = $fieldOrder;
  }
}

$customFormData = new Form(
  array(
    "Addr1" => new Field("Addr1", true, true, 12, 5, 1),
    "Apt" => new Field("Apt", true, true, 6, 1, 2),
    "City" => new Field("City", true, true, 12, 5, 3),
    "State_addr" => new Field("State_addr", true, true, 12, 6, 4),
		"StateOther" => new Field("StateOther", true, true, 12, 6, 4),
		"County" => new Field("County", true, true, 12, 6, 4),
		"ZipCode" => new Field("ZipCode", true, true, 12, 6, 4),
    "DateRange" => new Field("DateRange", true, true, 12, 6, 4)
  )
);

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

        <body>
  				<div class="general-page">
  					<div class="sub-menu">&nbsp;</div>

  					<div class="sub-page">
  						<div class="grid-x margins person-form">

  							<div class="cell small-12">
  								<h3>
  									' . $compname . ' Web Application Portal<br>
  									<img src="files/horizontal-line.gif" height="3" width="100%">
  								</h3>
  							</div>

  							<div class="cell small-12">
  								<span class="sub-heading">Address Information</span><br>
  								Please provide your address information for the past 7 years. starting with your current address.<br />
  								Please be as detailed as possible when providing this information to include a full 7 years.<br />&nbsp;
  							</div>

		            <div class="cell small-12">
							    <h3>Current Address</h3>
						    </div>
						    <div class="cell small-6 sub-heading">
                  &nbsp;12/31/1980&nbsp;&nbsp;-&nbsp;&nbsp;1/1/2019
                </div>
                <div class="cell small-6 right">
                  <span onclick="updateaddr(1)"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Address" title="Edit Address"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <span onclick="deleteaddr(1)"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Address" title="Delete Address"/></span>
                </div>';

// echo '<pre>';
// print_r($customFormData->formFields["Addr1"]);
// echo '</pre>';
// die();
if($customFormData->formFields["Addr1"]->fieldVisible) {
	echo '        <div class="cell small-12 medium-3">
                  123 My St&nbsp;&nbsp;&nbsp;Apt:&nbsp;246
                </div>';
}

if($customFormData->formFields["City"]->fieldVisible) {
	echo '        <div class="cell small-4 medium-3">
                  Denver
                </div>';
}

if($customFormData->formFields["StateOther"]->fieldVisible && $row[5] > '') {
	echo '        <div class="cell small-2 medium-1">
                  US
                </div>';
}
else if($customFormData->formFields["State_addr"]->fieldVisible && $row[4] > '') {
	echo '        <div class="cell small-2 medium-1">
                  CO
                </div>';
}

if($customFormData->formFields["County"]->fieldVisible) {
	echo '        <div class="cell small-4 medium-3">
                  Larimer
                </div>';
}

if($customFormData->formFields["ZipCode"]->fieldVisible) {
	echo '        <div class="cell small-2">
                  80502
                </div>';
}

echo '	        <div class="cell small-12">
                  <hr>
                </div>

                <div class="cell small-12">
                  <span class="add-address add-button"><img class="icon" src="images/plus.png" alt="Add Address" title="Add Address" /> Add Address</span>
                </div>
							  <div class="cell small-12">
								  <hr>
							  </div>
              </div>

						  <div class="grid-x margins person-form" name="Address_dialog" id="Address_dialog" title="Dialog Title">
                <div class="cell medium-6 small-12 required">
                  * Required Fields To Continue
                </div>
                <div class="cell medium-6 small-12">
                  You have entered 38 year(s), 0 month(s), and 1 day(s)
                </div>

                <div class="cell medium-1">
                  ' . $customFormData->editField("Current") . '
                </div>
                <div class="cell medium-5 small-12">
                  Current Address <span class="required">*</span>
                </div>
                <div class="cell medium-6 small-12">
                  <select name="current" id="current" onchange="setdate()">
                    <option value="Y">Yes</option>
                    <option value="N">No</option>
                  </select>
                </div>';

if($customFormData->formFields["Addr1"]->fieldVisible) {
  echo '			  <div class="cell medium-1">
                  ' . $customFormData->editField("Addr1") . '
                </div>
                <div class="cell medium-5 small-12">
								  Street <span class="required">*</span>
  							</div>
                <div class="cell medium-4 small-8">
  								<input type="text" name="addr1" id="addr1" maxlength="100" placeholder="Required">
  							</div>
  							<div class="cell medium-2 small-4">
  								<input type="text" name="apt" id="apt" maxlength="9" value="" placeholder="Apt/Suite">
  							</div>';
}

if($customFormData->formFields["City"]->fieldVisible) {
  echo '			  <div class="cell medium-1">
                  ' . $customFormData->editField("City") . '
                </div>
                <div class="cell medium-5 small-12">
  								City <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="city" id="city" maxlength="40" placeholder="Required">
  							</div>';
}

if($package == 'zinc' && $customFormData->formFields["StateOther"]->fieldVisible) {
	echo '			  <div class="cell medium-1">
                  ' . $customFormData->editField("StateOther") . '
                </div>
                <div class="cell medium-5 small-12">
  								Province/Country
  							</div>
  							<div class="cell medium-6 small-12">
  								<select name="country" id="country">
  									<option value="">Select Province/Country</option>
  									' . $country_options . '
  								</select>
  							</div>';
}
else if($customFormData->formFields["State_addr"]->fieldVisible) {
	echo '			  <div class="cell medium-1">
                  ' . $customFormData->editField("State_addr") . '
                </div>
                <div class="cell medium-5 small-12">
  								State <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<select name="state" id="state" onchange="loadcounties(\'state\',\'\')">
  									<option value="">Select a State</option>
  									<option value="">-Other-</option>
  									' . $state_options . '
  								</select>
  							</div>';

  if($customFormData->formFields["StateOther"]->fieldVisible) {
	   echo '     <div class="cell small-12">
  								OR If address is out of the US, please select the Country
  							</div>

                <div class="cell medium-1">
                  ' . $customFormData->editField("StateOther") . '
                </div>
                <div class="cell medium-5 small-12">
  								Country
  							</div>
  							<div class="cell medium-6 small-12">
  								<select name="country" id="country">
  									<option value="">Select a Country</option>
  									' . $country_options . '
  								</select>
  							</div>';
  }

  if($customFormData->formFields["County"]->fieldVisible) {
    echo '      <div class="cell medium-1">
                  ' . $customFormData->editField("County") . '
                </div>
                <div class="cell medium-5 small-12">
  								County <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<select name="county" id="county">
  									<option value="">Select a County</option>
  								</select>
  							</div>';
  }
}

if($customFormData->formFields["ZipCode"]->fieldVisible) {
  echo '			  <div class="cell medium-1">
                  ' . $customFormData->editField("ZipCode") . '
                </div>
                <div class="cell medium-5 small-12">
  								Postal Code <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="zip" id="zip" size="10" maxlength="10" placeholder="Required">
  							</div>';
}

if($customFormData->formFields["DateRange"]->fieldVisible) {
  echo '        <div class="cell medium-1">
                  ' . $customFormData->editField("DateRange") . '
                </div>
                <div class="cell medium-5 small-12">
  								From Date <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<select id="fromdate_month" name="fromdate_month" style="width: 30%">
  									' . $months_list . '
  								</select>
  								/
  								<select id="fromdate_day" name="fromdate_day" style="width: 30%">
  									' . $days_list . '
  								</select>
  								/
  								<select id="fromdate_year" name="fromdate_year" style="width: 30%">
  									' . $years_list . '
  								</select>
  							</div>

                <div class="cell medium-1">
                  ' . $customFormData->editField("DateRange") . '
                </div>
                <div class="cell medium-5 small-12">
  								To Date <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<select id="todate_month" name="todate_month" style="width: 30%">
  									' . $months_list . '
  								</select>
  								/
  								<select id="todate_day" name="todate_day" style="width: 30%">
  									' . $days_list . '
  								</select>
  								/
  								<select id="todate_year" name="todate_year" style="width: 30%">
  									' . $years_list . '
  								</select>
  							</div>';
}

echo '          <div class="cell small-12 padding-bottom">
  								<input id="save_address" class="float-center" type="button" value="Save Address">
  							</div>
  						</div>

  					  <input type="hidden" name="addrid" id="addrid" value="1">

              <div class="grid-x margins person-form" name="EditField_dialog" id="EditField_dialog" title="Edit Field">
                <div class="cell medium-4 small-12">
                  Field Name
                </div>
                <div class="cell medium-2 small-12 center">
                  Visible
                </div>
                <div class="cell medium-2 small-12 center">
                  Required
                </div>
                <div class="cell medium-2 small-12 center">
                  Mobile Cell Size
                </div>
                <div class="cell medium-2 small-12 center">
                  Desktop Cell Size
                </div>

                <div class="cell medium-4 small-12">
                  Addr1
                </div>
                <div class="cell medium-2 small-12 center">
                  <input type="checkbox" name="fieldVisible" id="fieldVisible">
                </div>
                <div class="cell medium-2 small-12 center">
                  <input type="checkbox" name="fieldRequired" id="fieldRequired">
                </div>
                <div class="cell medium-2 small-12 center">
                  <input type="text" name="mobileCellSize" id="mobileCellSize">
                </div>
                <div class="cell medium-2 small-12 center">
                  <input type="text" name="desktopCellSize" id="desktopCellSize">
                </div>

                <div class="cell small-12 padding-bottom">
  								<input id="save_field" class="float-center" type="button" value="Save Field">
  							</div>
              </div>
  					</div>
  				</div>
  			</form>';
?>

<script>
	$("#Address_dialog").dialog({ autoOpen: false });
  $("#EditField_dialog").dialog({ autoOpen: false });

	$(".add-address").click(function() {
		addAddress();
	});

	function addAddress() {
		$("#current").val('');
		$("#addrid").val('');
		$("#addr1").val('');
		$("#apt").val('');
		$("#city").val('');
    $("#state").val('');

		if($("#package").val() != 'zinc') {
			loadcounties("state", '');
			$("#county").val('');
		}

		$("#country").val('');
		$("#zip").val('');
		$("#fromdate_day").val('');
		$("#fromdate_month").val('');
		$("#fromdate_year").val('');
		$("#todate_day").val('');
		$("#todate_month").val('');
		$("#todate_year").val('');

		$("#Address_dialog").dialog("option", "title", "Add Address");
		$("#Address_dialog").dialog("option", "modal", true);
		$("#Address_dialog").dialog("option", "width", "100%");
		$("#Address_dialog").dialog("open");
	}

	$().ready(function() {
		if($("#current").val() != 'Y') {
			var today = getToday();

			$("#todate").placeholder = '';
			$("#todate").val(today);
		}
	});

	function getToday() {
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1;
		var yyyy = today.getFullYear();

		if(dd < 10)
			dd = '0' + dd;

		if(mm < 10)
			mm = '0' + mm;

		var today_str = yyyy + '-' + mm + '-' + dd;

		return [today_str, yyyy, mm, dd];
	}

	function setdate() {
		if($("#current").val() == 'Y') {
			var today = getToday();

			$("#todate_day").val(today[3]);
			$("#todate_month").val(today[2]);
			$("#todate_year").val(today[1]);
		}
		else {
			$("#todate_day").val('');
			$("#todate_month").val('');
			$("#todate_year").val('');
		}
	}

	function loadcounties(ddl, county) {
		st = $("#state").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_load_counties.php",
			data: {st: st},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(valor.length > 0) {
					$('#county').find('option').remove();
					$('#county').append('<option value="">Select a County</option>');

					for(var i = 0; i < obj2.length; i++) {
						var County_Name = obj2[i].County_Name;

						$('#county').append('<option value="' + County_Name + '">' + County_Name + '</option>');
					}

					$("#county").val(county);
				}
				else {
					alert('No Counties Data Found for State Selected');
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function updateaddr(addrid) {
		//$("#current").val(obj2.Current_Address);
		$("#addrid").val("1");
		$("#addr1").val("123 My Street");
		$("#apt").val("#246");
		$("#city").val("Denver");
		$("#state").val("CO");
		loadcounties("state", "Larimer");
		$("#county").val("Larimer");
		$("#country").val("US");
		$("#zip").val("80502");
		$("#fromdate_day").val("31");
		$("#fromdate_month").val("12");
		$("#fromdate_year").val("1980");
		$("#todate_day").val("1");
		$("#todate_month").val("1");
		$("#todate_year").val("2019");

		$("#Address_dialog").dialog("option", "title", "Edit Address");
		$("#Address_dialog").dialog("option", "modal", true);
		$("#Address_dialog").dialog("option", "width", "100%");
		$("#Address_dialog").dialog("open");
	}

	$("#close_address").click(function() {
		$("#Address_dialog").dialog("close");
	});

  function editField(fieldName) {
    $("#EditField_dialog").dialog("option", "title", "Edit " + fieldName + " Field");
		$("#EditField_dialog").dialog("option", "modal", true);
		$("#EditField_dialog").dialog("option", "width", "75%");
		$("#EditField_dialog").dialog("open");
  }
</script>

<?php
echo '  </body>
      </html>';
?>
