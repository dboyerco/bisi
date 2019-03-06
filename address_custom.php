<?php
require_once('pdobisitest.php');
$CompanyID = 1;
$PackageID = 1;

$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;

Class Form {
  public $formFields = array();

  public function __construct($formFields=null) {
    if($formFields) {
      $this->formFields = $formFields;
    }
  }
}

Class Field {
  public $field_name;
  public $field_label;
	public $visible_to_user;
  public $required_in_form;
  public $required_by_user;

  public function __construct($fieldName, $fieldLabel, $visible_to_user, $required_in_form, $required_by_user) {
    $this->field_name = $fieldName;
    $this->field_label = $fieldLabel;
		$this->visible_to_user = $visible_to_user;
    $this->required_in_form = $required_in_form;
    $this->required_by_user = $required_by_user;
  }
}

$customFormData = new Form();

$addressFieldsSQL = "select
                    	id,
                    	company_id,
                    	field_label,
                    	field_name,
                      visible_to_user,
                      required_in_form,
                      required_by_user
                    from
                    	AddressForm
                    where
                    	(company_id = 0 and package_id = 0 and field_default = 1)
                    		OR (company_id = :CompanyID and package_id = :PackageID and field_default = 0)
                    order by
                    	company_id";
$addressFields = $dboTest->prepare($addressFieldsSQL);
$addressFields->bindValue(':CompanyID', $CompanyID);
$addressFields->bindValue(':PackageID', $PackageID);
$addressFields->execute();

while($row = $addressFields->fetch(PDO::FETCH_BOTH)) {
  $customFormData->formFields[$row['field_name']] = new Field($row['field_name'], $row['field_label'], $row['visible_to_user'], $row['required_in_form'], $row['required_by_user']);
}

//echo "<pre>";
//print_r($customFormData);
//echo "</pre>";

//die("HERE");

echo '<form method="post" action="' . $FormAction . '" name="ALCATEL">
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
							</div>';

$currentaddress = 'N';
$maxAddrID = $dbo->query("Select max(AddrID) from App_Address where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxAddrID > 0) {
	$selectaddr = "select AddrID, Addr1, Apt, City, State_addr, StateOther, County, ZipCode, FromDate, ToDate, Current_Address from App_Address where PersonID = " . $PersonID . ";";
	$addr_result = $dbo->prepare($selectaddr);
	$addr_result->bindValue(':PersonID', $PersonID);
	$addr_result->execute();
	$i = 0;

	while($row = $addr_result->fetch(PDO::FETCH_BOTH)) {
		if($row['FromDate'] == '1900-01-01') {
			$fromdate = '';
		}
		else {
			$fromdate = date("m/d/Y", strtotime($row['FromDate']));
		}

		if($row['ToDate'] == '1900-01-01') {
			$todate = '';
		}
		else {
			$todate = date("m/d/Y", strtotime($row['ToDate']));
		}

		if($fromdate != '' && $todate != '') {
			$datediff = strtotime($todate) - strtotime($fromdate);
			$days = $days + floor($datediff / (60 * 60 * 24));
		}

		if($row['Current_Address'] == 'Y') {
			$addressType = "Current";
			$currentaddress = $row['Current_Address'];
		}
		else {
			$addressType = "Additional";
		}

		echo '	<div class="cell small-12">
							<h3>' . $addressType . ' Address</h3>
						</div>
						<div class="cell small-6 sub-heading">
							&nbsp;' . htmlspecialchars($fromdate) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . htmlspecialchars($todate) . '
						</div>
						<div class="cell small-6 right">
							<span onclick="updateaddr(' . $row['AddrID'] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Address" title="Edit Address"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span onclick="deleteaddr(' . $row['AddrID'] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Address" title="Delete Address"/></span>
						</div>';

		// echo '<pre>';
		// print_r($customFormData->formFields["Addr1"]);
		// echo '</pre>';
		// die();
		if($customFormData->formFields["Addr1"]->visible_to_user) {
			echo '<div class="cell small-12 medium-3">
							' . htmlspecialchars($row['Addr1']) . ' ' . ($row[2] > '' && $customFormData->formFields["Apt"]->visible_to_user ? '&nbsp;&nbsp;&nbsp;Apt:&nbsp;' . htmlspecialchars($row['Apt']) : '') . '
						</div>';
		}

		if($customFormData->formFields["City"]->visible_to_user) {
			echo '<div class="cell small-4 medium-3">
							' . htmlspecialchars($row['City']) . '
						</div>';
		}

		if($customFormData->formFields["StateOther"]->visible_to_user && $row['StateOther'] > '') {
			echo '<div class="cell small-2 medium-1">
							' . htmlspecialchars($row['StateOther']) . '
						</div>';
		}
		else if($customFormData->formFields["State_addr"]->visible_to_user && $row['State_addr'] > '') {
			echo '<div class="cell small-2 medium-1">
							' . htmlspecialchars($row['State_addr']) . '
						</div>';
		}

		if($customFormData->formFields["County"]->visible_to_user) {
			echo '<div class="cell small-4 medium-3">
							' . htmlspecialchars($row['County']) . '
						</div>';
		}

		if($customFormData->formFields["ZipCode"]->visible_to_user) {
			echo '<div class="cell small-2">
							' . htmlspecialchars($row['ZipCode']) . '
						</div>';
		}

		echo '	<div class="cell small-12">
						<hr>
					</div>';

		$i++;
	}

	if($days > 0){
		$YR = floor($days / 365);
		$MO = floor(($days - (floor($days / 365) * 365)) / 30);
		$DY = $days - (($YR * 365) + ($MO * 30));
	}
	else {
		$YR = 0;
		$MO = 0;
		$DY = 0;
	}
}
else {
	$maxAddrID = 0;
}

echo '				<div class="cell small-12">
								<span class="add-address add-button"><img class="icon" src="images/plus.png" alt="Add Address" title="Add Address" /> Add Address</span>
							</div>
							<div class="cell small-12">
								<hr>
							</div>';

if($days >= 2557) {
	echo '			<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>
							<div class="cell small-6">
								<input class="button float-center" type="submit" value="Next">
							</div>';
}

echo '			</div>

						<div class="grid-x margins person-form" name="Address_dialog" id="Address_dialog" title="Dialog Title">
							<div class="cell medium-6 small-12 required">
								* Required Fields To Continue
							</div>
							<div class="cell medium-6 small-12">
								You have entered ' . $YR . ' years ' . $MO . ' months ' . $DY . ' days
							</div>';

if($currentaddress == 'N') {
	echo '			<div class="cell medium-6 small-12">
								Current Address <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="current" id="current" onchange="setdate()">
									<option value="Y">Yes</option>
									<option value="N">No</OPTION>
								</select>
							</div>';
}
else {
	echo '			<input type="hidden" name="current" id="current" value="Y">';
}

if($customFormData->formFields["Addr1"]->visible_to_user) {
  echo '			<div class="cell medium-6 small-12">
								' . $customFormData->formFields["Addr1"]->field_label . ' <span class="required">*</span>
							</div>
              <div class="cell medium-4 small-8">
								<input type="text" name="addr1" id="addr1" maxlength="100" placeholder="Required">
							</div>
							<div class="cell medium-2 small-4">
								<input type="text" name="apt" id="apt" maxlength="9" value="" placeholder="Apt/Suite">
							</div>';
}

if($customFormData->formFields["City"]->visible_to_user) {
  echo '      <div class="cell medium-6 small-12">
								' . $customFormData->formFields["City"]->field_label . ' <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="city" id="city" maxlength="40" placeholder="Required">
							</div>';
}

if($package == 'zinc' && $customFormData->formFields["StateOther"]->visible_to_user) {
	echo '			<div class="cell medium-6 small-12">
								' . $customFormData->formFields["StateOther"]->field_label . '
							</div>
							<div class="cell medium-6 small-12">
								<select name="country" id="country">
									<option value="">Select Province/Country</option>
									' . $country_options . '
								</select>
							</div>';
}
else if($customFormData->formFields["State_addr"]->visible_to_user) {
	echo '			<div class="cell medium-6 small-12">
								' . $customFormData->formFields["State_addr"]->field_label . ' <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="state" id="state" onchange="loadcounties(\'state\',\'\')">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</div>';

  if($customFormData->formFields["StateOther"]->visible_to_user) {
	   echo '   <div class="cell small-12">
								OR If address is out of the US, please select the Country
							</div>

							<div class="cell medium-6 small-12">
								' . $customFormData->formFields["StateOther"]->field_label . '
							</div>
							<div class="cell medium-6 small-12">
								<select name="country" id="country">
									<option value="">Select a Country</option>
									' . $country_options . '
								</select>
							</div>';
  }

  if($customFormData->formFields["County"]->visible_to_user) {
    echo '    <div class="cell medium-6 small-12">
								' . $customFormData->formFields["County"]->field_label . ' <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="county" id="county">
									<option value="">Select a County</option>
								</select>
							</div>';
  }
}

if($customFormData->formFields["ZipCode"]->visible_to_user) {
  echo '			<div class="cell medium-6 small-12">
								' . $customFormData->formFields["ZipCode"]->field_label . ' <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="zip" id="zip" size="10" maxlength="10" placeholder="Required">
							</div>';
}

if($customFormData->formFields["FromDate"]->visible_to_user && $customFormData->formFields["ToDate"]->visible_to_user) {
  echo '      <div class="cell medium-6 small-12">
								' . $customFormData->formFields["FromDate"]->field_label . ' <span class="required">*</span>
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

							<div class="cell medium-6 small-12">
								' . $customFormData->formFields["ToDate"]->field_label . ' <span class="required">*</span>
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

echo '        <div class="cell small-12 padding-bottom">
								<input id="save_address" class="float-center" type="button" value="Save Address">
							</div>
						</div>

						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
					  <input type="hidden" name="addrid" id="addrid" value="' . $maxAddrID . '">
					  <input type="hidden" name="package" id="package" value="' . $package . '">
					  <input type="hidden" name="days" id="days" value="' . $days . '">
					</div>
				</div>
			</form>';
?>

<script>
	$("#Address_dialog").dialog({ autoOpen: false });

<?php
	if($days < 2557) {
		echo 'addAddress();';
	}
?>

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	$(".add-address").click(function() {
		addAddress();
	});

	function addAddress() {
		$("#current").val('');
		$("#addrid").val('');
		$("#addr1").val('');
		$("#apt").val('');
		$("#city").val('');

		if($("#package").val() != 'zinc') {
			$("#state").val('');
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

 	$("#save_address").click(function() {
		var personid = $("#PersonID").val();
		var pname = $("#package").val();
		var addrid = $("#addrid").val();
		var current_address = $("#current").val();
		var saveLocation = "../App_Ajax_New/ajax_add_address.php";

		if(addrid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_address.php";
		}

		if($("#addr1").val() > '') {
			var addr1 = $("#addr1").val();
		}
		else {
			$("#addr1").focus();
			alert("Street is required");
			return;
		}

		if($("#apt").val() > '') {
			var apt = $("#apt").val();
		}
		else {
			var apt = '';
		}

		if($("#city").val() > '') {
			var city = $("#city").val();
		}
		else {
			$("#city").focus();
			alert("City is required");
			return;
		}

		if(pname == 'zinc') {
			var state = '';
			var county = '';

			if($("#country").val() == '' ) {
				$('#country').focus();
				alert("Province/Country is required");
				return;
			}
			else {
				var stateother = $("#country").val();
			}
		}
		else {
			if($("#state").val() == '' && $("#country").val() == '' ) {
				$("#state").focus();
				alert("State or Country is required");
				return;
			}
			else {
				var state = $("#state").val();
				var stateother = $("#country").val();
			}

			if($("#state").val() != '') {
				if($("#county").val() > '') {
					var county = $("#county").val();
				}
				else {
					$("#county").focus();
					alert("County is required");
					return;
				}
			}
		}

		if($("#zip").val() > '') {
			var zipcode = $("#zip").val();
		}
		else {
			$("#zip").focus();
			alert("Postal Code is required");
			return;
		}

		if($("#fromdate_day").val() > '' && $("#fromdate_month").val() > '' && $("#fromdate_year").val() > '') {
			var fromdate = $("#fromdate_year").val() + '-' + $("#fromdate_month").val() + '-' + $("#fromdate_day").val();
		}
		else {
			$("#fromdate_day").focus();
			alert("From Date is required");
			return;
		}

		if($("#todate_day").val() > '' && $("#todate_month").val() > '' && $("#todate_year").val() > '') {
			var todate = $("#todate_year").val() + '-' + $("#todate_month").val() + '-' + $("#todate_day").val();
		}
		else {
			$("#todate_day").focus();
			alert("To Date is required");
			return;
		}

		if(!isValidDiff(fromdate, todate)) {
			$('#fromdate_day').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		var data = {
			personid: personid,
			addrid: addrid,
			addr1: addr1,
			apt: apt,
			city: city,
			state: state,
			stateother: stateother,
			zipcode: zipcode,
			fromdate: fromdate,
			todate: todate,
			current_address: current_address,
			county: county
		};

		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2.length > 30) {
					alert(obj2);
				}
				else {
					var AddrID = obj2;

					if($("#current").val() == 'N') {
						$("#current").val('N');
					}

					location.reload();
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

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
		var personid = $("#PersonID").val();
		var pname = $("#package").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_address.php",
			data: { personid: personid, addrid: addrid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					var fd = obj2.FromDate.split("-");
					var fromDateDay = fd[2];
					var fromDateMonth = fd[1];
					var fromDateYear = fd[0];
					var td = obj2.ToDate.split("-");
					var toDateDay = td[2];
					var toDateMonth = td[1];
					var toDateYear = td[0];

					$("#current").val(obj2.Current_Address);
					$("#addrid").val(obj2.AddrID);
					$("#addr1").val(obj2.Addr1);
					$("#apt").val(obj2.Apt);
					$("#city").val(obj2.City);

					if(pname != 'zinc') {
						$("#state").val(obj2.State_addr);
						loadcounties("state", obj2.County);
						$("#county").val(obj2.County);
					}

					$("#country").val(obj2.StateOther);
					$("#zip").val(obj2.ZipCode);
					$("#fromdate_day").val(fromDateDay);
					$("#fromdate_month").val(fromDateMonth);
					$("#fromdate_year").val(fromDateYear);
					$("#todate_day").val(toDateDay);
					$("#todate_month").val(toDateMonth);
					$("#todate_year").val(toDateYear);

					$("#Address_dialog").dialog("option", "title", "Edit Address");
					$("#Address_dialog").dialog("option", "modal", true);
					$("#Address_dialog").dialog("option", "width", "100%");
					$("#Address_dialog").dialog("open");
				}
				else {
					alert('No Address Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function deleteaddr(AddrID) {
		if(confirm('Are you sure you want to delete this address?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_address.php",
				data: { personid: personid, AddrID: AddrID },
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0, 4) == 'Error') {
						alert(obj2);
						return false;
					}
					else {
						location.reload();
						return;
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_address").click(function() {
		$("#Address_dialog").dialog("close");
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var nodays = $("#days").val();

		if(nodays < 2557) {
			alert('You have not entered at least 7 years of address information');
			return false;
		}

		return true;
	}
</script>
