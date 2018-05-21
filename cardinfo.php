<? 
require_once('../pdotriton.php');
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$custid = $dbo->query("Select CustID from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$acctid = $dbo->query("Select bisAcct from App_HR_Company where Company_Name = '".$compname."';")->fetchColumn();	
$packageno = $dbo->query("Select PackageNo from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$NYchk = $dbo->query("Select NYchk from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
$maxLandlordID = $dbo->query("Select max(LandlordID) from App_Landlord where PersonID = ".$PersonID.";")->fetchColumn();
$packagecharge = $dbo->query("Select sCharge from services where sCustID = '".$custid."' and sServiceNo = '".$packageno."';")->fetchColumn();
$packagename = $dbo->query("Select sServiceName from services where sCustID = '".$custid."' and sServiceNo = '".$packageno."';")->fetchColumn();

$selectaddr="select City, State_addr, StateOther, County from App_Address where PersonID = :PersonID;";
$addr_result = $dbo->prepare($selectaddr);
$addr_result->bindValue(':PersonID', $PersonID);
$addr_result->execute();
$amount = 0;
$cnt = 0;
echo '<fieldset style="border-radius:10px; border:2px solid #0000FF; color:blue;"><center><h3>Background Check Charges</h3></center>
		<table width="95%">'; 
echo '<tr><td><b>'.$packagename.'</b></td><td align="right">$'.number_format($packagecharge,2).'</td></tr>';

while($addr_row = $addr_result->fetch(PDO::FETCH_BOTH)) {		
	$city = $addr_row[0];
	$state = $addr_row[1];
	if ($addr_row[2] > '') {
		$country = $addr_row[2];
	} else {	
		$country = 'USA';
	}	
	$county = $addr_row[3];
#	echo "State: ".$state." - County: ".$county."<br />";
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
				if ($NYchk == 'Y') {
					$charge = $dbo->query("Select sCharge from services where sCustID = '".$custid."' and sServiceNo = '".$serviceno."';")->fetchColumn();	
				}
				break;
			case 'BAIN_00038':		
				if ($maxLandlordID > 1) {	
					$charge = $dbo->query("Select sCharge from services where sCustID = '".$custid."' and sServiceNo = '".$serviceno."';")->fetchColumn();	
				}	
				break;
			default:
				$charge = $dbo->query("Select sCharge from services where sCustID = '".$custid."' and sServiceNo = '".$serviceno."';")->fetchColumn();	
		}		
		
		$surcharge = $dbo->query("Select Service_Surcharge from Service_Surcharges where ServiceNo = '".$serviceno."' and Service_StateCD = '".$state."' and Service_County = '".$county."' and Service_City = '<All>';")->fetchColumn();	
		if ($surcharge == 0) {
			$surcharge = $dbo->query("Select Service_Surcharge from Service_Surcharges where ServiceNo = '".$serviceno."' and Service_StateCD = '".$state."' and Service_County = '<All>' and Service_City = '<All>';")->fetchColumn();	
		}
#		$amount = $amount + $charge + $surcharge;
		$tcharge = $charge + $surcharge;
		if ($tcharge > 0) {
			echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$service_desc.'</td><td align="right">&nbsp;</td></tr>';
		}
	}		
}
echo "<tr>
		<td>
			<font color='red'>Your credit card will be charged</font></td><td align='right'><font color='red'>$".number_format($packagecharge,2)."</font>
		</td>
	</tr>
	</table></fieldset>"; 

$amount = $packagecharge;
echo "
<INPUT TYPE=\"hidden\" NAME=\"amount\" ID=\"amount\" VALUE=\"$amount\">
<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
<INPUT TYPE=\"hidden\" NAME=\"compname\" ID=\"compname\" VALUE=\"$compname\">
<INPUT TYPE=\"hidden\" NAME=\"acctid\" ID=\"acctid\" VALUE=\"$acctid\">";
		
$orderString = "<font color='red'> Your credit card will be charged $".number_format($amount,2)." for your background check.</font>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title>Card Info</title>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/validation.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
		<style type="text/css">
			#overlay {visibility: hidden; position: absolute; left: 0px; top:25%; width:100%; height:100%; text-align:center; z-index: 1000;}
			#overlay #header {width:400px; height:5px; OVERFLOW: auto; background-color: #eee; position: absolute; right: 248px; top: 5px; border:0px; }
			#overlay div {width:400px; margin: 100px auto; OVERFLOW: auto; background-color: #eee; border:5px solid #696969; border-radius:10px; padding:15px; text-align:left;}
			#valcarddiv {visibility: hidden; position: absolute; left: 0px; top:25%; width:100%; height:100%; text-align:center; z-index: 1000;}
			#valcarddiv div {width:300px; margin: 100px auto; OVERFLOW: auto; background-color: #eee; border:5px solid #696969; border-radius:10px; padding:15px; text-align:left;}
			#processdata {visibility: hidden; position: absolute; left: 0px; top:15%; width:100%; height:100%; text-align:center; z-index: 1000;}
			#processdata div {width:350px; margin: 100px auto; OVERFLOW: auto; background-color: #eee; border:5px solid #696969; border-radius:10px; padding:15px; text-align:left;}
			#contact {visibility: hidden; position: absolute; left: 0px; top:30px; width:100%; height:100%; text-align:center; z-index: 1000;}
			#contact div {width:350px; margin: 100px auto; OVERFLOW: auto; background-color: #FFFFFF; border:10px solid #000000; border-radius:20px; padding:15px; text-align:left;}
			.fieldset-auto-width {display: inline-block;}		
		</style>	
	
		<script language="JavaScript" type="text/javascript">
			$(document).ready(function() {
				$("#processdata").css("visibility","hidden");						
			});
			function overlayclose() {
				el = document.getElementById("overlay");			
				el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";	
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
//				alert('Ready to validate');
			
				$.ajax({
					type: "POST",
					url: "ValidateCard.php", 
					data: {cardnum: cardnum, expdate: expdate, amount: amount, fname: fname, lname: lname, company: company, address: address, city: city, state: state, zip: zip, email: email, ccv: ccv, PersonID: PersonID, desc: desc},
				
					datatype: "JSON",
					success: function(valor) {
						$("#valcarddiv").css("visibility","hidden");
						var obj2 = $.parseJSON(valor);
						if (valor.length > 13) { 	
//							alert(obj2);	
							$("#resultInfo").find("tr").remove();
							var tablerow = "<tr><td><font color='#000000' size='3' face='Verdana, Arial, Helvetica, sans-serif'>"+obj2+"</font></td></tr>"
							$("#resultInfo > tbody:last").append(tablerow);
							$("#valcarddiv").css("visibility","hidden");
							$("#overlay").css("visibility","visible");
							return false;
						} else {
							var tranid = obj2;
//							alert(tranid);
							window.location ='Thanks.php?PersonID='+PersonID;	
						}	
					},	
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert('Status: '+textStatus); alert('Error: '+errorThrown);
						return;
					} 	
				});	
				return false;
			}
		
		</script>
	</head>
<body>
<?php
	echo "<form name=\"payform\" id=\"payform\" action=\"Thanks.php?PersonID=$PersonID\" onsubmit=\"return validateform()\" method=\"POST\">";
?>
<center>
<div id="container" class="clearfix">
  <div id="content">
    <div class="paymentformbox">
	  <div class="paymentform">
		<table bgcolor="#ffffff" width="500">
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
						<tr>
							<td height="25" colspan="2">&nbsp;</td>
						</tr>	
						<tr bgcolor="#102e82">
							<td height="25" colspan="2" style="border-radius:10px; border:2px solid #000;"><font color="#ffffff" size="4"><strong>&nbsp;Billing Information (required)</strong></font>
							</td>
						</tr>
						<tr>
							<td nowrap>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;First Name:</td>
							<td><input name="firstName" id="firstName" type="text" size="50" placeholder="Required"/></td>
						</tr>
						<tr>
							<td>&nbsp;Last Name:</td>
							<td><input name="lastName" id="lastName" type="text" size="50" placeholder="Required"/></td>
						</tr>
						<tr>
							<td>&nbsp;Company (optional):</td>
							<td align="left"><input name="company" id="company" type="text" size="50" placeholder="Optional"/></td>
						</tr>
						<tr>
							<td>&nbsp;Street Address:</td>
							<td><input name="address" id="address" type="text" size="50" placeholder="Required"/></td>
						</tr>
						<tr>
							<td>&nbsp;City:</td>
							<td><input name="city" id="city" type="text" size="40" placeholder="Required"/></td>
						</tr>
						<tr>
							<td>&nbsp;State/Province:</td>
							<td>
								<input name="state" id="state" type="text" size="40" placeholder="Required"/>
							</td>
						<tr>
							<td>&nbsp;Zip/Postal Code:</td>
							<td><input name="zip" id="zip" type="text" size="15" placeholder="Required"/></td>
						</tr>
						<tr>
							<td>&nbsp;Contact Email:</td>
							<td><input name="contactEmail" id="contactEmail" type="text" size="50" placeholder="Required"/></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr bgcolor="#102e82">
							<td height="25" colspan="2" style="border-radius:10px; border:2px solid #000;"><font color="#ffffff" size="4"> <strong>&nbsp;Credit Card (required)</strong></td>
						</tr>
						<tr>
							<td nowrap>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td nowrap>Credit Card Number:</td>
							<td><input type="text" size="20" name="number" id="number" placeholder="Required"/></td>
						</tr>
						<tr>
							<td nowrap>Expiration Date:</td>
							<td><font color="#31266e">
								<input type="text" size="3" maxlength="2" name="expmonth" id="expmonth" placeholder="MM"/>
								<span> / </span>
								<input type="text" size="5" maxlength="4" name="expyear" id="expyear" placeholder="YYYY"/>
							</td>
						</tr>
						<tr>
							<td nowrap>CCV code:</td>
							<td><input type="text" size="4" maxlength="4" name="ccv" id="ccv"/></td>
						</tr>
						<tr>
							<td nowrap>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><img alt="" title="" src="images/credit_card_logos_11.gif" width="235" height="35"/></td>
						</tr>
						<tr>
							<td colspan="2"><hr></td>
						</tr>
						<tr>
							<td nowrap>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td nowrap>&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" name="submitid" id="submitid" style="width:100px; font-weight:bold; font-size:small; border-radius:5px; border:2px solid #000; color:blue;" value="SUBMIT" onclick="return validateform()"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<div id="overlay" name="overlay">
			<div>
				<img onclick="overlayclose()" style="cursor:pointer; float:right; position:relative; top:0px; left:0px;" class="close" height="15" width="15" src="https://triton.bisi.com/rms/images/dialog_close.png" alt="Close" title="Close"/>
				<br/>
				<table name="resultInfo" id="resultInfo" cellpadding="0" cellspacing="0" class="db-table" width="100%"> 
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div name="valcarddiv" id="valcarddiv">
			<div>
				<strong>Validating Credit Card. Please wait.</strong><br />
			</div>
		</div>
		<div name="processdata" id="processdata">
			<div>
				<strong>Credit Card Approved, Thank You.<br />Processing data. Please Wait....<br />It should take less than a minute.</strong><br />
			</div>
		</div>
		<br />
		<div class="contactinfo"><center><font color="black" size="2" face='Verdana, Arial, Helvetica, sans-serif'>&copy; <? echo date('Y'); ?> All rights reserved.</font></center>
		</div>
    </div>
  </div>
</div>
</center>
</form>
</body>
</html>