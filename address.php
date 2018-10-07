<?php

$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;

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
		if($row[8] == '1900-01-01') {
			$fromdate = '';
		}
		else {
			$fromdate = date("m/d/Y", strtotime($row[8]));
		}

		if($row[9] == '1900-01-01') {
			$todate = '';
		}
		else {
			$todate = date("m/d/Y", strtotime($row[9]));
		}

		if($fromdate != '' && $todate != '') {
			$datediff = strtotime($todate) - strtotime($fromdate);
			$days = $days + floor($datediff / (60 * 60 * 24));
		}

		if($row[10] == 'Y') {
			$addressType = "Current";
			$currentaddress = $row[10];
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
							<span onclick="updateaddr(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Address" title="Edit Address"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span onclick="deleteaddr(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Address" title="Delete Address"/></span>
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[1]) . ' ' . ($row[2] > '' ? '&nbsp;&nbsp;&nbsp;Apt:&nbsp;' . htmlspecialchars($row[2]) : '') . '
						</div>
						<div class="cell small-4 medium-3">
							' . htmlspecialchars($row[3]) . '
						</div>';

		if($row[5] > '') {
			echo '<div class="cell small-2 medium-1">
							' . htmlspecialchars($row[5]) . '
						</div>';
		}
		else {
			echo '<div class="cell small-2 medium-1">
							' . htmlspecialchars($row[4]) . '
						</div>';
		}

		echo '	<div class="cell small-4 medium-3">
							' . htmlspecialchars($row[6]) . '
						</div>
						<div class="cell small-2">
							' . htmlspecialchars($row[7]) . '
						</div>

						<div class="cell small-12">
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
	echo '			<div class="cell small-12">
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

echo '				<div class="cell medium-6 small-12">
								Street <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="addr1" id="addr1" maxlength="100" placeholder="Required">
							</div>
							<div class="cell medium-2 small-4">
								<input type="text" name="apt" id="apt" maxlength="9" value="" placeholder="Apt/Suite">
							</div>
							<div class="cell medium-6 small-12">
								City <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="city" id="city" maxlength="40" placeholder="Required">
							</div>';

if($package == 'zinc') {
	echo '			<div class="cell medium-6 small-12">
								Province/Country
							</div>
							<div class="cell medium-6 small-12">
								<select name="country" id="country">
									<option value="">Select Province/Country</option>
									' . $country_options . '
								</select>
							</div>';
}
else {
	echo '			<div class="cell medium-6 small-12">
								State <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="state" id="state" onchange="loadcounties(\'state\',\'\')">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</div>

							<div class="cell small-12">
								OR If address is out of the US, please select the Country
							</div>

							<div class="cell medium-6 small-12">
								Country
							</div>
							<div class="cell medium-6 small-12">
								<select name="country" id="country">
									<option value="">Select a Country</option>
									' . $country_options . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								County <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="county" id="county">
									<option value="">Select a County</option>
								</select>
							</div>';
}

echo '				<div class="cell medium-6 small-12">
								Postal Code <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="zip" id="zip" size="10" maxlength="10" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								From Date <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="fromdate" id="fromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" readonly>
							</div>

							<div class="cell medium-6 small-12">
								To Date <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="todate" id="todate" size="10" maxlength="10" placeholder="mm/dd/yyyy" readonly>
							</div>

							<div class="cell small-12 padding-bottom">
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
	if($('#fromdate')[0].type != 'date' ) $('#fromdate').datepicker();
	if($('#todate')[0].type != 'date' ) $('#todate').datepicker();

<?php
	if($days < 2557) {
		echo 'addAddress();';
	}
?>

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
		$("#fromdate").val('');
		$("#todate").val('');

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

		//today = mm + '/' + dd + '/' + yyyy;
		today = yyyy + '-' + mm + '-' + dd;

		return today;
	}

	function setdate() {
		if($("#current").val() == 'Y') {
			var today = getToday();

			$("#todate").placeholder = '';
			$("#todate").val(today);
		}
		else {
			$("#todate").placeholder = 'mm/dd/yyyy';
			$("#todate").val('');
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

		if($("#fromdate").val() > '') {
			var fromdate = $("#fromdate").val();
		}
		else {
			$("#fromdate").focus();
			alert("From Date is required");
			return;
		}

		if($("#todate").val() > '') {
			var todate = $("#todate").val();
		}
		else {
			$("#todate").focus();
			alert("To Date is required");
			return;
		}

		if(!isValidDiff(fromdate, todate)) {
			$('#fromdate').focus();
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
		// alert('In updateaddr');
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
					var fromDate = '';
					var toDate = '';

					if($('#fromdate')[0].type != 'date') {
						fd = obj2.FromDate.split("-");
						fromDate = fd[1] + "/" + fd[2] + "/" + fd[0];
						td = obj2.ToDate.split("-");
						toDate = td[1] + "/" + td[2] + "/" + td[0];
					}
					else {
						fromDate = obj2.FromDate;
						toDate = obj2.ToDate;
					}

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
					$("#fromdate").val(fromDate);
					$("#todate").val(toDate);

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
		var addrid = $("#AddrID").val();
		var nodays = $("#days").val();
		var pname = $("#package").val();

		if(nodays < 2557) {
			alert('You have not entered at least 7 years of address information');
			return false;
		}

		return true;
	}
</script>
