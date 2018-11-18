<style type="text/css">
	#overlay { display: none; position: absolute; left: 0px; top: 90%; width: 100%; height:100%; text-align: center; z-index: 1000; }
	#overlay #header { width: 400px; height: 5px; OVERFLOW: auto; background-color: #eee; position: absolute; right: 248px; top: 5px; border:0px; }
	#overlay div {width:400px; margin: 100px auto; OVERFLOW: auto; background-color: #eee; border:5px solid #696969; border-radius:10px; padding:15px; text-align:left;}
	#valcarddiv {visibility: hidden; position: absolute; left: 0px; top:85%; width:100%; height:100%; text-align:center; z-index: 1000;}
	#valcarddiv div {width:300px; margin: 100px auto; OVERFLOW: auto; background-color: #eee; border:5px solid #696969; border-radius:10px; padding:15px; text-align:left;}
	#processdata {visibility: hidden; position: absolute; left: 0px; top:15%; width:100%; height:100%; text-align:center; z-index: 1000;}
	#processdata div {width:350px; margin: 100px auto; OVERFLOW: auto; background-color: #eee; border:5px solid #696969; border-radius:10px; padding:15px; text-align:left;}
	.fieldset-auto-width {display: inline-block;}
</style>

<?
$cnt = 0;
$custid = $dbo->query("Select CustID from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
$acctid = $dbo->query("Select bisAcct from App_HR_Company where Company_Name = '".$compname."';")->fetchColumn();
$packageno = $dbo->query("Select PackageNo from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
$NYchk = $dbo->query("Select NYchk from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
$maxLandlordID = $dbo->query("Select max(LandlordID) from App_Landlord where PersonID = ".$PersonID.";")->fetchColumn();
$packagecharge = $dbo->query("Select sCharge from services where sCustID = '".$custid."' and sServiceNo = '".$packageno."';")->fetchColumn();
$packagename = $dbo->query("Select sServiceName from services where sCustID = '".$custid."' and sServiceNo = '".$packageno."';")->fetchColumn();

$selectaddr = "select Addr1, Apt, City, State_addr, StateOther, County, ZipCode from App_Address where PersonID = :PersonID; and Current_Address = 'Y'";
$addr_result = $dbo->prepare($selectaddr);
$addr_result->bindValue(':PersonID', $PersonID);
$addr_result->execute();

$addr1 = "";
$apt = "";
$city = "";
$state = "";
$country = "";
$county = "";
$zipCode = "";

while($addr_row = $addr_result->fetch(PDO::FETCH_BOTH)) {
	$addr1 = $addr_row[0];
	$apt = $addr_row[1];
	$city = $addr_row[2];
	$state = $addr_row[3];

	if($addr_row[4] > '') {
		$country = $addr_row[3];
	}
	else {
		$country = 'USA';
	}

	$county = $addr_row[5];
	$zipCode = $addr_row[6];
}

$FormAction = "index.php?pg=" . $pageThanks . "&PersonID=" . $PersonID . "&CD=" . $CD;

echo '<form method="post" action="' . $FormAction . '" name="payform" id="payform" onsubmit="return validateform()">
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
								<span class="sub-heading">Background Check Charges</span>
							</div>

							<div class="cell small-6">
								<strong>' . $packagename . '</strong>
							</div>
							<div class="cell small-6">
								$' . number_format($packagecharge, 2) . '
							</div>';

$sql = "Select sServiceNo, sDescrip from packages where sCustID = :CustID and sPkgSvcNo = :PackageNo;";
$service_result = $dbo->prepare($sql);
$service_result->bindValue(':CustID', $custid);
$service_result->bindValue(':PackageNo', $packageno);
$service_result->execute();

while($service_row = $service_result->fetch(PDO::FETCH_BOTH)) {
	$charge = 0;
	$surcharge = 0;
	$route_cost = 0;
	$route_fee = 0;
	$serviceno = $service_row[0];
	$service_desc = $service_row[1];

	switch($serviceno) {
		case 'BAIN_00126':
			if($NYchk == 'Y') {
				$charge = $dbo->query("Select sCharge from services where sCustID = '{$custid}' and sServiceNo = '{$serviceno}';")->fetchColumn();
			}
			break;
		case 'BAIN_00038':
			if($maxLandlordID > 1) {
				$charge = $dbo->query("Select sCharge from services where sCustID = '{$custid}' and sServiceNo = '{$serviceno}';")->fetchColumn();
			}
			break;
		default:
			$charge = $dbo->query("Select sCharge from services where sCustID = '{$custid}' and sServiceNo = '{$serviceno}';")->fetchColumn();
	}

	$surcharge = $dbo->query("Select Service_Surcharge from Service_Surcharges where ServiceNo = '{$serviceno}' and Service_StateCD = '{$state}' and Service_County = '{$county}' and Service_City = '<All>';")->fetchColumn();

	if($surcharge == 0) {
		$surcharge = $dbo->query("Select Service_Surcharge from Service_Surcharges where ServiceNo = '{$serviceno}' and Service_StateCD = '{$state}' and Service_County = '<All>' and Service_City = '<All>';")->fetchColumn();
	}

	$tcharge = $charge + $surcharge;

	if($tcharge > 0) {
		echo '<div class="cell small-12">
						' . $service_desc . '
					</div>';
	}
}

echo '<div class="cell small-6 required">
				Your credit card will be charged
			</div>
			<div class="cell small-6 required">
				$' . number_format($packagecharge, 2) . '
			</div>

			<input type="hidden" name="amount" id="amount" value="' . $packagecharge . '">
			<input type="hidden" name="PersonID" id="PersonID" value="'. $PersonID . '">
			<input type="hidden" name="compname" id="compname" value="' . $compname . '">
			<input type="hidden" name="acctid" id="acctid" value="' . $acctid . '">

			<div class="cell small-12">
				<hr />
			</div>

			<div class="cell small-12 required">
				* Required Fields To Continue
			</div>

			<div class="cell small-12">
				<br />
				<h3>Billing Information (required)</h3>
			</div>

			<div class="cell small-12 medium-6">
				First Name: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input name="firstName" id="firstName" type="text" placeholder="Required"/>
			</div>

			<div class="cell small-12 medium-6">
				Last Name: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input name="lastName" id="lastName" type="text" placeholder="Required" />
			</div>

			<div class="cell small-12 medium-6">
				Company (optional):
			</div>
			<div class="cell small-12 medium-6">
				<input name="company" id="company" type="text" placeholder="Optional" />
			</div>

			<div class="cell small-12 medium-6">
				Street Address: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input name="address" id="address" type="text" value="' . $addr1 . ' ' . $apt . '" placeholder="Required" />
			</div>

			<div class="cell small-12 medium-6">
				City: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input name="city" id="city" type="text" value="' . $city . '" placeholder="Required" />
			</div>

			<div class="cell small-12 medium-6">
				State/Province: <span class="required">*</span>
			</div>
			<div class="cell medium-6 small-12">
				<select name="state" id="state">
					<option value="">Select a State</option>
					<option value="">-Other-</option>
					' . $state_options . '
				</select>
			</div>

			<div class="cell small-12 medium-6">
				Zip/Postal Code: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input name="zip" id="zip" type="text" value="' . $zipCode . '" placeholder="Required" />
			</div>

			<div class="cell small-12 medium-6">
				Contact Email: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input name="contactEmail" id="contactEmail" type="text" placeholder="Required" />
			</div>

			<div class="cell small-12">
				<br />
				<h3>Credit Card (required)</h3>
			</div>

			<div class="cell small-12 medium-6">
				Credit Card Number: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input type="text" name="number" id="number" placeholder="Required" />
			</div>

			<div class="cell small-12 medium-6">
				Expiration Date: <span class="required">*</span>
			</div>
			<div class="cell small-5 medium-2">
				<input type="text" maxlength="2" name="expmonth" id="expmonth" placeholder="MM" />
			</div>
			<div class="cell small-2 medium-2 center">
				&nbsp;/&nbsp;
			</div>
			<div class="cell small-5 medium-2">
				<input type="text" maxlength="4" name="expyear" id="expyear" placeholder="YYYY" />
			</div>

			<div class="cell small-12 medium-6">
				CCV code: <span class="required">*</span>
			</div>
			<div class="cell small-12 medium-6">
				<input type="text" maxlength="4" name="ccv" id="ccv" />
			</div>

			<div class="cell small-12 center">
				<img alt="" title="" src="images/credit_card_logos_11.gif" width="235" height="35" />
			</div>
			<div class="cell small-12">
				<hr />
			</div>
			<div class="cell small-12 center">
				<input type="button" name="submitid" id="submitid" value="SUBMIT" onclick="return validateform()" />
			</div>

			<div id="overlay" name="overlay" class="cell small-12 center">
				<div>
					<img onclick="overlayclose()" style="cursor:pointer; float:right; position:relative; top:0px; left:0px;" class="close" height="15" width="15" src="https://triton.bisi.com/rms/images/dialog_close.png" alt="Close" title="Close" />
					<br/>
					<table name="resultInfo" id="resultInfo" cellpadding="0" cellspacing="0" class="db-table" width="100%">
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<div name="valcarddiv" id="valcarddiv" class="cell small-12 center">
				<div>
					<strong>Validating Credit Card. Please wait.</strong><br />
				</div>
			</div>

			<div name="processdata" id="processdata" class="cell small-12 center">
				<div>
					<strong>Credit Card Approved, Thank You.<br />Processing data. Please Wait....<br />It should take less than a minute.</strong><br />
				</div>
			</div>

			<div class="cell small-12 center">
				<br />
				&copy; ' . date('Y') . ' All rights reserved.
			</div>
		</form>';
?>

<script language="JavaScript" type="text/javascript">
	$().ready(function() {
		$("#processdata").css("visibility", "hidden");
		$("#state").val("<?php echo $state; ?>");
	});

<?php
echo 'var pageThanks = "' . $pageThanks . '";
			var cd = "' . $CD . '";';
?>

	function overlayclose() {
		$("#overlay").css("display", "none");
	}

	function contact() {
		var el = document.getElementById("contact");
		el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	}

	function validateform(){
		strAlertMsg = "";
		focusField = "";
		x = document.forms[0];

		isEmpty('firstName', 'First Name')
		isEmpty('lastName', 'Last Name')
		isEmpty('address', 'Address')
		isEmpty('city', 'City')
		isEmpty('state', 'State/Province')
		isEmpty('zip', 'Zip/Postal code')
		isEmpty('contactEmail', 'Contact Email')
		isEmpty('number', 'Card Number')
		isEmpty('expmonth', 'Expiration Month')
		isEmpty('expyear', 'Expiration Year')
		isEmpty('ccv', 'CCV Code')

		if(strAlertMsg !=""){
			alert("Please correct the following errors:\n____________________________\n\n" +strAlertMsg);
			eval("x." + focusField + ".focus()");
			return false;
		}
		var amount = document.getElementById("amount").value;
		validateCard(amount);
		return true;
	}

	function validateCard(amount){
		$("#valcarddiv").css("visibility","visible");

		var cardnum = document.getElementById("number").value;
		var expmonth = document.getElementById("expmonth").value;
		var expyear = document.getElementById("expyear").value;
		var expdate = expmonth+expyear;
		var fname = document.getElementById("firstName").value;
		var lname = document.getElementById("lastName").value;
		var company = document.getElementById("company").value;
		var address = document.getElementById("address").value;
		var city = document.getElementById("city").value;
		var state = document.getElementById("state").value;
		var zip = document.getElementById("zip").value;
		var email = document.getElementById("contactEmail").value;
		var ccv = document.getElementById("ccv").value;
		var PersonID = document.getElementById("PersonID").value;
		var AcctID = document.getElementById("acctid").value;
		var CompName = document.getElementById("compname").value;
		var desc = AcctID+' - '+CompName+' background check';
		// alert('Ready to validate');

		$.ajax({
			type: "POST",
			url: "ValidateCard.php",
			data: { cardnum: cardnum, expdate: expdate, amount: amount, fname: fname, lname: lname, company: company, address: address, city: city, state: state, zip: zip, email: email, ccv: ccv, PersonID: PersonID, desc: desc },
			datatype: "JSON",
			success: function(valor) {
				$("#valcarddiv").css("visibility","hidden");

				var obj2 = $.parseJSON(valor);

				if(valor.length > 13) {
					// alert(obj2);
					$("#resultInfo").find("tr").remove();
					var tablerow = "<tr><td><font color='#000000' size='3' face='Verdana, Arial, Helvetica, sans-serif'>" + obj2 + "</font></td></tr>"
					$("#resultInfo > tbody:last").append(tablerow);
					$("#valcarddiv").css("visibility","hidden");
					$("#overlay").css("display", "block");
					return false;
				}
				else {
					var tranid = obj2;
					// alert(tranid);
					window.location ='index.php?pg=' + pageThanks + '&PersonID=' + PersonID + '&CD=' + cd;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
				return;
			}
		});

		return false;
	}
</script>
