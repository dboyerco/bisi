<?php
if(!$testLayout) {
	$noemail = $dbo->query("Select No_Email from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
}
else {
	$noemail = "Y";
}

$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;

if($noemail == 'Y') {
	$FormAction = "index.php?pg=certification&PersonID=" . $PersonID . "&CD=" . $CD; // certification is not on this pass
}
else {
	$FormAction = "index.php?pg=disclosure1&PersonID=" . $PersonID . "&CD=" . $CD;
}

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

if(!$testLayout) {
	$maxAddrID = $dbo->query("Select max(AddrID) from App_Address where PersonID = " . $PersonID . ";")->fetchColumn();

	if($maxAddrID > 0) {
		$selectaddr = "select AddrID, Addr1, Apt, City, State_addr, StateOther, County, ZipCode, FromDate, ToDate, Current_Address from App_Address where PersonID = " . $PersonID . ";";
		$addr_result = $dbo->prepare($selectaddr);
		$addr_result->bindValue(':PersonID', $PersonID);
		$addr_result->execute();

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

			// $fd = new DateTime($fromdate);
			// $td = new DateTime($todate);
			// $diff = $fd->diff($td);
			// echo $diff->y . ' year(s) ' . $diff->m . ' month(s) '. $diff->d . ' day(s)<br />';

			if($fromdate != '' && $todate != '') {
				$datediff = strtotime($todate) - strtotime($fromdate);
				$days = $days + floor($datediff / (60 * 60 * 24));
			}

			echo '	<div class="grid-x margins person-form">';

			if($row[10] == 'Y') {
				$currentaddress = $row[10];
				echo '<div class="cell small-12">
								<h3>Current Address</h3>
							</div>';
			}

			echo '	<div class="cell small-6 sub-heading">
								&nbsp;' . htmlspecialchars($fromdate) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . htmlspecialchars($todate) . '
							</div>
							<div class="cell small-6 right">
								<span onclick="updateaddr(' . $row[0] . ')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Address" title="Edit Address"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deleteaddr(' . $row[0] . ')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Address" title="Delete Address"/></span>
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
							<hr>
							<table width="763" id="addrtbl2" bgcolor="#E4E8E8"></table>';
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
}
else {
	$maxAddrID = 0;
	$days = 2557;

	echo '			<div class="cell small-12">
								<h3>Current Address</h3>
							</div>
							<div class="cell small-6 sub-heading">
								02/03/2007&nbsp;&nbsp;-&nbsp;&nbsp;05/30/2018
							</div>
							<div class="cell small-6 right">
								<span onclick="updateaddr(1)"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Address" title="Edit Address"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deleteaddr(1)"><img class="icon" src="images/deletetrashcan.png" alt="Delete Address" title="Delete Address"/></span>
							</div>
							<div class="cell small-12 medium-3">
								123 My Street &nbsp;&nbsp;&nbsp;&nbsp; Apt 1
							</div>
							<div class="cell small-4 medium-3">
								Loveland
							</div>
							<div class="cell small-2 medium-1">
								CO
							</div>
							<div class="cell small-4 medium-3">
								Larimer
							</div>
							<div class="cell small-2">
								80537
							</div>
							<div class="cell small-12">
								<hr>
							</div>
							<table width="763" id="addrtbl2" bgcolor="#E4E8E8"></table>';
}

if($days >= 2557) {
	echo '			<div class="cell small-12">
								<input class="button float-center" type="submit" value="Next">
							</div>';
}

echo '				<div class="cell small-12">
								<h3>Add Address</h3>
							</div>
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
								<select name="newcurrent" id="newcurrent" onchange="setdate()">
									<option value="Y">Yes</option>
									<option VALUE="N">No</OPTION>
								</select>
							</div>';
}

echo '				<div class="cell medium-6 small-12">
								Street <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="newaddr1" id="newaddr1" size="20" maxlength="100" placeholder="Required">
							</div>
							<div class="cell medium-2 small-4">
								<input type="text" name="newapt" id="newapt" size="5" maxlength="9" value="" placeholder="Apt/Suite">
							</div>
							<div class="cell medium-6 small-12">
								City <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="newcity" id="newcity" size="20" maxlength="40" placeholder="Required">
							</div>';

if($package == 'zinc') {
	echo '			<div class="cell medium-6 small-12">
								Province/Country <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="newstateother" id="newstateother">
									<option value="">Select Province/Country</option>';

	if(!$testLayout) {
		$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
		$country_result = $dbo->prepare($sql);
		$country_result->execute();

		while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
			echo '			<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
		}
	}
	else {
		echo '				<option value="usa">USA</option>';
	}

	echo '				</select>
							</div>';
}
else {
	echo '			<div class="cell medium-6 small-12">
								State <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="newstate" id="newstate" onchange="loadcounties(\'newstate\',\'\')">
									<option value="">Select a State</option>
									<option value="">-Other-</option>';

	if(!$testLayout) {
		$sql = "Select Name, Abbrev from State order by Name";
		$state_result = $dbo->prepare($sql);
		$state_result->execute();

		while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {
			echo '			<option value="' . $rows[1] . '">' . $rows[0] . '</option>';
		}
	}
	else {
		echo '				<option value="co">CO</option>';
	}

	echo '				</select>
							</div>

							<div class="cell small-12">
								OR If address is out of the US, please select the Country
							</div>

							<div class="cell medium-6 small-12">
								Country <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="newstateother" id="newstateother">
									<option value="">Select a Country</option>';

	if(!$testLayout) {
		$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
		$country_result = $dbo->prepare($sql);
		$country_result->execute();

		while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
			echo '			<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
		}
	}
	else {
		echo '				<option value="usa">USA</option>';
	}

	echo '				</select>
							</div>

							<div class="cell medium-6 small-12">
								County <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="newcounty" id="newcounty">
									<option value="">Select a County</option>
								</select>
							</div>';
}

echo '				<div class="cell medium-6 small-12">
								Postal Code <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="newzip" id="newzip" size="10" maxlength="10" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								From Date <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="newfromdate" id="newfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')" >
							</div>

							<div class="cell medium-6 small-12">
								To Date <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="newtodate" id="newtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
							</div>
						</div>

						<div class="grid-x margins">
							<div class="cell small-12 padding-bottom">
								<input id="add_new_address" class="float-center" type="button" value="Save Address">
							</div>
						</div>

						<INPUT TYPE="hidden" NAME="PersonID" ID="PersonID" VALUE="' . $PersonID . '">
					  <INPUT TYPE="hidden" NAME="AddrID" ID="AddrID" VALUE="' . $maxAddrID . '">
					  <INPUT TYPE="hidden" name="Current" id="Current" VALUE="' . $currentaddress . '">
					  <INPUT TYPE="hidden" name="package" id="package" VALUE="' . $package . '">
					  <INPUT TYPE="hidden" NAME="days" ID="days" VALUE="' . $days . '">
					</div>
				</div>

				<div name="Address_dialog" id="Address_dialog" title="Dialog Title">
					<div>
						<br/>
						<input type="hidden" name="dlgaddrid" id="dlgaddrid">
						<table width="100%" align="left" border="0" bgcolor="#eeeeee">
							<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Current Address</font></td>
								<td>
									<select name="dlgcurrent" id="dlgcurrent">
										<option VALUE="N">No</OPTION>
										<option value="Y">Yes</option>
									</select>
								</td>
							</tr>
							<tr>
								<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Street</font></td>
								<td width="351">
									<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<input name="dlgaddr1" id="dlgaddr1" size="20" maxlength="100">
											&nbsp;&nbsp;&nbsp;Apt &nbsp;
										<input name="dlgapt" id="dlgapt" size="5" maxlength="9" value="">
									</font>
								</td>
							</tr>
							<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">City</font></td>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<input name="dlgcity" id="dlgcity" size="20" maxlength="40" >
									</font>
								</td>
							</tr>';

if($package == 'zinc') {
	echo '			<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Province/Country</font></td>
								<td>
									<span style="font-size:small; font-family=Tahoma; color:#000000;">
										<select name="dlgstateother" id="dlgstateother">
											<option value="">Select Province/Country</option>';

	if(!$testLayout) {
		$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
		$country_result = $dbo->prepare($sql);
		$country_result->execute();

		while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
			echo '					<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
		}
	}
	else {
		echo '						<option value="usa">USA</option>';
	}

	echo '						</select>
									</span>
								</td>
							</tr>';
}
else {
	echo '			<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">State</font></td>
								<td>
									<span style="font-size:small; font-family=Tahoma; color:#000000;">
										<select name="dlgstate" id="dlgstate" onchange="loadcounties(\'dlgstate\',\'\')">
											<option value="">Select a State</option>
											<option value="other">-Other-</option>';

	if(!$testLayout) {
		$sql = "Select Name, Abbrev from State order by Name";
		$state_result = $dbo->prepare($sql);
		$state_result->execute();

		while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {
			echo '					<option value="' . $rows[1] . '">' . $rows[0] . '</option>';
		}
	}
	else {
		echo '						<option value="co">CO</option>';
	}

	echo '						</select>
									</span>
								</td>
							</tr>
							<tr>
								<td><font size="2">&nbsp;</font></td>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> OR If address is out of the US, please select the Country</font></td>
							</tr>
							<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Country</font></td>
								<td>
									<span style="font-size:small; font-family=Tahoma; color:#000000;">
										<select name="dlgstateother" id="dlgstateother">
											<option value="">Select a Country</option>';

	if(!$testLayout) {
		$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
		$country_result = $dbo->prepare($sql);
		$country_result->execute();

		while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
			echo '					<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
		}
	}
	else {
		echo '						<option value="usa">USA</option>';
	}

	echo '						</select>
									</span>
								</td>
							</tr>
							<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">County</font></td>
								<td>
									<span style="font-size:small; color:#000000;">
										<select name="dlgcounty" id="dlgcounty">
											<option value="">Select a County</option>
										</select>
									</span>
								</td>
							</tr>';
	}

	echo '			<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Postal Code</font></td>
								<td>
									<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<input name="dlgzip" id="dlgzip" size="10" maxlength="10">
									</font>
								</td>
							</tr>
							<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">From Date</font></td>
								<td nowrap>
									<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<input name="dlgfromdate" id="dlgfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
									</font>
								</td>
							</tr>
							<tr>
								<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">To Date</font></td>
								<td nowrap>
									<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<input name="dlgtodate" id="dlgtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
									</font>
								</td>
							</tr>
						</table>

						<table width="100%" bgcolor="#eeeeee">
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="center">
									<INPUT TYPE="button" id="save_address" VALUE="Save Address">
									<INPUT TYPE="button" id="close_address" VALUE="Close">
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>';
?>

<script>
	$().ready(function() {
		if($("#Current").val() != 'Y') {
			var today = getToday();

			$("#newtodate").placeholder = '';
			$("#newtodate").val(today);
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

		today = mm + '/' + dd + '/' + yyyy;

		return today;
	}

	function setdate() {
		if($("#newcurrent").val() == 'Y') {
			var today = getToday();

			$("#newtodate").placeholder = '';
			$("#newtodate").val(today);
		}
		else {
			$("#newtodate").placeholder = 'mm/dd/yyyy';
			$("#newtodate").val() = '';
		}
	}

	$("#Address_dialog").dialog({ autoOpen: false });

 	$("#add_new_address").click(function() {
		var personid = $("#PersonID").val();
		var pname = $("#package").val();

		if($("#Current").val() == 'N') {
			var current_address = $("#newcurrent").val();
		}
		else {
			var current_address = 'N';
		}

		if($("#newaddr1").val() > '') {
			var addr1 = $("#newaddr1").val();
		}
		else {
			document.ALCATEL.newaddr1.focus();
			alert("Street is required");
			return;
		}

		if($("#newapt").val() > '') {
			var apt = $("#newapt").val();
		}
		else {
			var apt = '';
		}

		if($("#newcity").val() > '') {
			var city = $("#newcity").val();
		}
		else {
			document.ALCATEL.newcity.focus();
			alert("City is required");
			return;
		}

		if(pname == 'zinc') {
			var state = '';
			var county = '';

			if($("#newstateother").val() == '' ) {
				$('#newstateother').focus();
				alert("Province/Country is required");
				return;
			}
			else {
				var stateother = $("#newstateother").val();
			}
		}
		else {
			if($("#newstate").val() == '' && $("#newstateother").val() == '' ) {
				document.ALCATEL.newstate.focus();
				alert("State or Country is required");
				return;
			}
			else {
				var state = $("#newstate").val();
				var stateother = $("#newstateother").val();
			}

			if($("#newstate").val() != '') {
				if($("#newcounty").val() > '') {
					var county = $("#newcounty").val();
				}
				else {
					document.ALCATEL.newcounty.focus();
					alert("County is required");
					return;
				}
			}
		}

		if($("#newzip").val() > '') {
			var zipcode = $("#newzip").val();
		}
		else {
			document.ALCATEL.newzip.focus();
			alert("Postal Code is required");
			return;
		}

		if($("#newfromdate").val() > '') {
			if(!isValidDate('newfromdate')) {
				$('#newfromdate').focus();
				alert("Invalid From Date");
				return false;
			}
			else {
				var fromdate = $("#newfromdate").val();
			}
		}
		else {
			document.ALCATEL.newfromdate.focus();
			alert("From Date is required");
			return;
		}

		if($("#newtodate").val() > '') {
			if(!isValidDate('newtodate')) {
				$('#newtodate').focus();
				alert("Invalid To Date");
				return false;
			}
			else {
				var todate = $("#newtodate").val();
			}
		}
		else {
			document.ALCATEL.newtodate.focus();
			alert("To Date is required");
			return;
		}

		if(!isValidDiff(fromdate, todate)) {
			$('#newfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_address.php",
			data: {personid: personid, addr1: addr1, apt: apt, city: city, state: state, stateother: stateother, zipcode: zipcode, fromdate: fromdate, todate: todate, current_address: current_address, county: county},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if(obj2.length > 30) {
					alert(obj2);
				}
				else {
					var AddrID = obj2;

					if($("#Current").val() == 'N') {
						$("#newcurrent").val() = 'N';
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
		if(ddl == 'newstate') {
			st = $("#newstate").val();
		}
		else {
			st = $("#dlgstate").val();
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_load_counties.php",
			data: {st: st},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(valor.length > 0) {
					if(ddl == 'newstate') {
						$('#newcounty').find('option').remove();
						$('#newcounty').append('<option value="">Select a County</option>');
					}
					else {
						$('#dlgcounty').find('option').remove();
						$('#dlgcounty').append('<option value="">Select a County</option>');
					}

					for(var i = 0; i < obj2.length; i++) {
						var County_Name = obj2[i].County_Name;

						if(ddl == 'newstate') {
							$('#newcounty').append('<option value="' + County_Name + '">' + County_Name + '</option>');
						}
						else {
							$('#dlgcounty').append('<option value="' + County_Name + '">' + County_Name + '</option>');
						}
					}

					$("#dlgcounty").val() = county;
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
			url: "../App_Ajax/ajax_find_address.php",
			data: { personid: personid, addrid: addrid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(valor.length > 0) {
					for(var i = 0; i < obj2.length; i++) {
						var AddrID = obj2[i].AddrID;
						var Current_Address = obj2[i].Current_Address;
						var Addr1 = obj2[i].Addr1;
						var Apt = obj2[i].Apt;
						var City = obj2[i].City;
						var State = obj2[i].State_addr;
						var StateOther = obj2[i].StateOther;
						var County = obj2[i].County;
						var ZipCode = obj2[i].ZipCode;
						var fd = obj2[i].FromDate;
						var DateFrom = fd.substr(5,2) + "/" + fd.substr(8) + "/" + fd.substr(0,4);
						var td = obj2[i].ToDate;
						var DateTo = td.substr(5,2) + "/" + td.substr(8) + "/" + td.substr(0,4);
			    }

					$("#dlgcurrent").val(Current_Address);
					$("#dlgaddrid").val(AddrID);
					$("#dlgaddr1").val(Addr1);
					$("#dlgapt").val(Apt);
					$("#dlgcity").val(City);

					if(pname != 'zinc') {
						$("#dlgstate").val(State);
						loadcounties("dlgstate", County);
						$("#dlgcounty").val(County);
					}

					$("#dlgstateother").val(StateOther);
					$("#dlgzip").val(ZipCode);
					$("#dlgfromdate").val(DateFrom);
					$("#dlgtodate").val(DateTo);

					$("#Address_dialog").dialog("option", "title", "Edit Address");
					$("#Address_dialog").dialog("option", "modal", true);
					$("#Address_dialog").dialog("option", "width", 700);
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

 	$("#save_address").click(function() {
		// alert('In save_address');

		var pname = $("#package").val();
		var personid = $("#PersonID").val();
		var addrid = $("#dlgaddrid").val();
		var current_address = $("#dlgcurrent").val();

		if($("#dlgaddr1").val() > '') {
			var addr1 = $("#dlgaddr1").val();
		}
		else {
			document.ALCATEL.dlgaddr1.focus();
			alert("Street is required");
			return;
		}

		if($("#dlgapt").val() > '') {
			var apt = $("#dlgapt").val();
		}
		else {
			var apt = '';
		}

		if($("#dlgcity").val() > '') {
			var city = $("#dlgcity").val();
		}
		else {
			document.ALCATEL.dlgcity.focus();
			alert("City is required");
			return;
		}

		if(pname == 'zinc') {
			var state = '';
			var county = '';

			if($("#dlgstateother").val() == '' ) {
				$('#dlgstateother').focus();
				alert("Province/Country is required");
				return;
			}
			else {
				var stateother = $("#dlgstateother").val();
			}
		}
		else {
			if($("#dlgstate").val() == '' && $("#dlgstateother").val() == '' ) {
				document.ALCATEL.dlgstate.focus();
				alert("State or Country is required");
				return;
			}
			else {
				var state = $("#dlgstate").val();
				var stateother = $("#dlgstateother").val();
			}

			if($("#dlgstate").val() != '') {
				if($("#dlgcounty").val() > '') {
					var county = $("dlgcounty").val();
				}
				else {
					document.ALCATEL.dlgcounty.focus();
					alert("County is required");
					return;
				}
			}
		}

		if($("#dlgzip").val() > '') {
			var zipcode = $("#dlgzip").val();
		}
		else {
			document.ALCATEL.dlgzip.focus();
			alert("Postal Code is required");
			return;
		}

		if($("#dlgfromdate").val() > '') {
			if(!isValidDate('dlgfromdate')) {
				$('#dlgfromdate').focus();
				alert("Invalid From Date");
				return false;
			}
			else {
				var fromdate = $("#dlgfromdate").val();
			}
		}
		else {
			document.ALCATEL.dlgfromdate.focus();
			alert("From Date is required");
			return;
		}

		if($("#dlgtodate").val() > '') {
			if(!isValidDate('dlgtodate')) {
				$('##dlgtodate').focus();
				alert("Invalid To Date");
				return false;
			}
			else {
				var todate = $("#dlgtodate").val();
			}
		}
		else {
			document.ALCATEL.dlgtodate.focus();
			alert("To Date is required");
			return;
		}

		if(!isValidDiff(fromdate,todate)) {
			$('#dlgfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_address.php",
			data: { personid: personid, addrid: addrid, addr1: addr1, apt: apt, city: city, state: state, stateother: stateother, zipcode: zipcode, fromdate: fromdate, todate: todate, current_address: current_address, county: county },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					$("#Address_dialog").dialog("close");
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

	function deleteaddr(AddrID) {
		alert("In dltaka");

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
		$("#Address_dialog").dialog( "close" );
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var addrid = $("#AddrID").val();
		var nodays = $("#days").val();
		var pname = $("#package").val();

		alert('Number of Days: ' + nodays);

		if(nodays < 2557) {
			alert('You have not entered at least 7 years of address information');
			return false;
		}

		return true;
	}
</script>
