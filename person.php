<?php
if(isSet($PersonID)) {
	if($PersonID > '') {
		$selectstmt="Select First_Name, Middle_Name, Last_Name, Date_of_Birth, SSN, Business_Phone, Home_Phone, mobile_Phone, Email, Package, Company_Name, Gender, Emergency_Contact, Emergency_Number, No_Email from App_Person where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);

		if(!$result2->execute()) {

		}
		else {
			$row = $result2->fetch(PDO::FETCH_BOTH);
			$fname = $row[0];
			$mi = $row[1];
			$lname = $row[2];

			if($row[3] == '1900-01-01') {
				$birthdate = "";
				$birthdateYear = "";
				$birthdateMonth = "";
				$birthdateDay = "";
				$masked_birthdate = "";
			}
			else {
				$birthdate = date("m/d/Y", strtotime($row[3]));
				//$masked_birthdate = date("m/d/", strtotime($row[3])) + "XXXX";
				$dateVals = explode("-", $row[3]);
				$birthdateYear = "XXXX";//$dateVals[0];
				$birthdateMonth = $dateVals[1];
				$birthdateDay = $dateVals[2];
				$masked_birthdate = $dateVals[1] . "/" . $dateVals[2] . "/XXXX";
			}

			if($row[4] == '') {
				$ssn = "";
			}
			else {
				$ssn = "XXX-XX-" . substr($row[4], 8);
			}

			$num = $row[4];
			$busphone = $row[5];
			$homephone = $row[6];
			$cellphone = $row[7];
			$email = $row[8];
			$package = $row[9];
			$compname = $row[10];
			$gender = $row[11];
			$emergcontact = $row[12];
			$emergnumber = $row[13];
			$No_Email = $row[14];
			$selectstmt = "Select LastName, Changed from App_Alias where PersonID = :PersonID and AliasType ='M';";
			$result2 = $dbo->prepare($selectstmt);
			$result2->bindValue(':PersonID', $PersonID);
			$result2->execute();
			$namechg = "";
			$maiden = '';
			$row = $result2->fetch(PDO::FETCH_BOTH);

			if($row[0] > '') {
				$maiden = $row[0];

				if($row[1] == '1900-01-01') {
					$namechg = "";
				}
				else {
					$namechg = $row[1];
					$namechgVals = explode("-", $namechg);
					$namechgYear = $namechgVals[0];
					$namechgMonth = $namechgVals[1];
					$namechgDay = $namechgVals[2];
					$namechg = date("m/d/Y", strtotime($namechg));
				}
			}
		}
	}
}

echo '<form method="POST" action="index.php?pg=' . $nextPage . '&PersonID=' . $PersonID . '&CD=' . $CD . '" name="ALCATEL">
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
									<img src="files/horizontal-line.gif" height="3" width="100%">
								</h3>
							</div>

							<div class="cell small-12">
								<span class="sub-heading">Subject Information</span><br>';

if($etype == 'T') {
	echo '<strong>Disclaimer: </strong>All information requested in this application is pertinent and necessary. Failure to fill out all information can delay the tenant process.<br>';
}
else {
	echo '<strong>Disclaimer: </strong>All information requested in this application is pertinent and necessary. Failure to fill out all information can delay the hiring process.<br>';
}

if($No_Email == 'N') {
	echo '				<strong>Note: </strong>You can return to this Application Portal at any time by clicking on the link in the email that was sent to you. All the data you have saved will be displayed when you return.<br>';
}

echo '					<strong>Please make sure that the first and last name is as it appears on your government issued ID / SSN card etc.</strong>
							</div>
						</div>

						<div class="grid-x margins person-form">
							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell small-12 medium-5">
								<label>
									First Name <span class="required">*</span><br />
									<input type="text" name="fname" id="fname" value="' . htmlspecialchars($fname) . '" maxlength="40">
								</label>
							</div>
							<div class="cell small-6 medium-1">
								<label>
									M.I. <span class="required">*</span>
									<input type="text" name="mi" id="mi" value="' . htmlspecialchars($mi) . '" maxlength="1">
								</label>
							</div>
							<div class="cell small-6 medium-1">
								<label>
									No M.I.<br />
									&nbsp;&nbsp;&nbsp;<input type="checkbox" name="nomi" id="nomi" onclick="NoMI()">
								</label>
							</div>
							<div class="cell small-12 medium-5">
								<label>
									Last Name <span class="required">*</span>
									<input type="text" name="lname" id="lname" value="' . htmlspecialchars($lname) . '" maxlength="40">
								</label>
							</div>

							<div class="cell small-12 medium-5">
								<label>
									Maiden Name
									<input type="text" name="maiden" id="maiden" value="' . htmlspecialchars($maiden) . '" maxlength="40" id="maiden">
								</label>
							</div>
							<div class="cell small-12 medium-5">
								<label>
									Date Maiden Name Changed<br>
									<select id="namechg_month" name="namechg_month" style="width: 35%">
										' . buildMonthsList($namechgMonth) . '
									</select>
									/
									<select id="namechg_day" name="namechg_day" style="width: 25%">
										' . buildDaysList($namechgDay) . '
									</select>
									/
									<select id="namechg_year" name="namechg_year" style="width: 30%">
										' . buildYearsList($namechgYear) . '
									</select>
								</label>
							</div>
							<div class="cell medium-2"></div>

							<div class="cell small-12">
								<strong>AKAs</strong>&nbsp;<small>(Any names used in the past, nicknames, etc.)</small><br />
								<strong>**NOTE: <u>MUST</u> have date last used entered.</strong>
							</div>

							<div class="cell small-12">
								<table>
									<thead>
										<tr>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Last Used</th>
											<th class="center"><span class="add-alias"><img class="icon" src="images/plus.png" alt="Add Alias" title="Add Alias" /></span></th>
										</tr>
									</thead>
									<tbody id="aliasTable">';

$maxAliasID = $dbo->query("select max(AliasID) from App_Alias where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxAliasID > 0) {
	$selectalias = "Select AliasID, FirstName, LastName, Changed from App_Alias where PersonID = :PersonID and AliasType = 'A';";
	$alias_result = $dbo->prepare($selectalias);
	$alias_result->bindValue(':PersonID', $PersonID);
	$alias_result->execute();

	while($Alias = $alias_result->fetch(PDO::FETCH_BOTH)) {
		$dateUsed = date("m/d/Y", strtotime($Alias[3]));

		echo '				<tr id="alias' . $Alias[0] . '">
										<td>' . htmlspecialchars($Alias[1]) . '</td>
										<td>' . htmlspecialchars($Alias[2]) . '</td>
										<td>' . htmlspecialchars($dateUsed) . '</td>
										<td class="center">
											<a http="#" onclick="updateaka(' . $Alias[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;
											<a http="#" onclick="deleteaka(' . $Alias[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a>
										</td>
									</tr>';
	}
}
else {
	$maxAliasID = 0;
}

echo '						</tbody>
								</table>
							</div>
							<div class="cell small-12"><hr></div>

							<div class="cell small-12 medium-5">
								<label>
									Date of Birth <span class="required">*</span><br>
									<select id="birthdate_month" name="birthdate_month" onchange="updateDOB()" style="width: 35%">
										' . buildMonthsList($birthdateMonth) . '
									</select>
									/
									<select id="birthdate_day" name="birthdate_day" onchange="updateDOB()" style="width: 25%">
										' . buildDaysList($birthdateDay) . '
									</select>
									/
									<select id="birthdate_year" name="birthdate_year" onchange="updateDOB()" style="width: 30%">
										' . buildYearsList($birthdateYear, true) . '
									</select>
									<input type="hidden" name="fbdate" id="fbdate" value="' . $birthdate . '">
								</label>
							</div>
							<div class="cell small-12 medium-3">
								<label>
									SSN <span class="required">*</span>
									<input type="tel" id="ssn" name="ssn" placeholder="###-##-####" maxlength="11" onBlur = "validateSSN()" onKeyUp="return frmtssn(this, \'up\')" onKeyDown="return frmtssn(this, \'down\')" value="' . htmlspecialchars($ssn) . '" />
								</label>
							</div>
							<div class="cell medium-4"></div>';

if($package == 'zinc') {
	echo '			<div class="cell small-12"><hr></div>

							<div class="cell small-12 medium-4">
								<label>
									Mother\'s Maiden Name <span class="required">*</span>
									<input type="text" id="mothermaiden" name="mothermaiden" placeholder="Mother\'s Maiden Name" maxlength="30" value="' . htmlspecialchars($MotherMaiden) . '" />
								</label>
							</div>
							<div class="cell small-12 medium-4">
								<label>
									Father\'s Full Name <span class="required">*</span>
									<input type="text" id="fathername" name="fathername" placeholder="Father\'s Name" maxlength="50" value="' . htmlspecialchars($FatherName) . '" />
								</label>
							</div>
							<div class="cell medium-4"></div>';
}

echo '				<div class="cell small-12"><hr></div>

							<div class="cell small-12">
								<label>
									Enter one or more contact phone number: <span class="required">*</span>
								</label>
							</div>

							<div class="cell small-12 medium-3">
								<label>
									Business Phone<br />
									<input type="tel" name="busphone" id="busphone" value="' . htmlspecialchars($busphone) . '" maxlength="40" placeholder="### ### ####" onKeyUp="return frmtbphone(this,\'up\')">
								</label>
							</div>
							<div class="cell small-12 medium-3">
								<label>
									Home Phone<br />
									<input type="tel" name="homephone" id="homephone" value="' . htmlspecialchars($homephone) . '" maxlength="40" placeholder="### ### ####" onKeyUp="return frmtphone(this,\'up\')">
								</label>
							</div>
							<div class="cell small-12 medium-3">
								<label>
									Cell Phone<br />
									<input type="tel" name="cellphone" id="cellphone" value="' . htmlspecialchars($cellphone) . '" maxlength="40" placeholder="### ### ####" onKeyUp="return frmtphone(this,\'up\')">
								</label>
							</div>
							<div class="cell medium-3"></div>

							<div class="cell small-12"><hr></div>

							<div class="cell small-12">
								<label>
									Enter an E-mail address: ' . ($No_Email == 'N' ? '<span class="required">*</span>' : '') . '
									<input type="email" name="email" id="email" value="' . htmlspecialchars($email) . '">
								</label>
							</div>

							<div class="cell small-12"><hr></div>

						</div>
					</div>

					<div class="grid-x margins">
						<div class="cell small-12 medium-6 padding-bottom">
							<input name="save_and_stay" class="float-center" id="save_and_stay" type="button" value="Save the Data You Have Entered">
						</div>
						<div class="cell small-12 medium-6">
							<input name="save_and_go" class="float-center" id="save_and_go" type="button" value="Save Subject Data and Continue">
						</div>

						<input type="hidden" name="AliasID" id="AliasID">
						<input type="hidden" name="num" id="num" value="' . $num . '">
						<input type="hidden" name="package" id="package" value=" ' . $package . '">
						<input type="hidden" name="noemail" id="noemail" value="' . $No_Email . '">
						<input type="hidden" name="compname" id="compname" value="' . $compname . '">
					</div>
				</div>
			</form>

			<div class="grid-x margins person-form" name="Alias_dialog" id="Alias_dialog" title="Dialog Title">
				<input type="hidden" name="aliasid" id="aliasid">

				<div class="cell small-12">
					First Name
				</div>
				<div class="cell small-12">
					<input type="text" name="akafirstname" id="akafirstname" size="20" maxlength="100">
				</div>

				<div class="cell small-12">
					Last Name
				</div>
				<div class="cell small-12">
					<input type="text" name="akalastname" id="akalastname" size="20" maxlength="100">
				</div>

				<div class="cell small-12">
					Date Last Used
				</div>
				<div class="cell small-12">
					<select id="akachanged_month" name="akachanged_month" style="width: 35%">
						' . $months_list . '
					</select>
					/
					<select id="akachanged_day" name="akachanged_day" style="width: 25%">
						' . $days_list . '
					</select>
					/
					<select id="akachanged_year" name="akachanged_year" style="width: 30%">
						' . $years_list . '
					</select>
				</div>

				<div class="cell small-12">
					<input type="button" id="save_alias" value="Save AKA">
				</div>
			</div>

			<div class="grid-x margins person-form" id="NOMI_dialog" name="NOMI_dialog" title="Confirm Middle Initial Optout">
				<div class="cell small-12">
					Middle Name is required to ensure maximum possible accuracy.<br /> Are you sure you do not have a middle name?
				</div>

				<div class="cell small-12 center">
					<input name="nomiyes" id="nomiyes" type="button" value="I do not have a middle initial"><br /><br />
					<input name="nomino" id="nomino" type="button" value="I do have a middle initial">
				</div>
			</div>';
?>

<script language="JavaScript" type="text/javascript">

<?php
	echo 'var maxAliasID = ' . $maxAliasID . ';
				var nextPage = ' . $nextPage . ';';
?>

 	$("#Alias_dialog").dialog({ autoOpen: false });
	$("#NOMI_dialog").dialog({ autoOpen: false });

	$(".add-alias").click(function() {
		addAlias();
	});

	function addAlias() {
		$('#aliasid').val('');
		$("#akafirstname").val('');
		$("#akalastname").val('');
		$("#akachanged_month").val('');
		$("#akachanged_day").val('');
		$("#akachanged_year").val('');

		$("#Alias_dialog").dialog("option", "title", "Edit AKA");
		$("#Alias_dialog").dialog("option", "modal", true);
		$("#Alias_dialog").dialog("option", "width", "100%");
		$("#Alias_dialog").dialog("open");
	}

	$("#close_alias").click(function() {
		$("#Alias_dialog").dialog("close");
	});

	function updateDOB() {
		$("#fbdate").val($("#birthdate_year").val() + '-' + $("#birthdate_month").val() + '-' + $("#birthdate_day").val());
	}

	function NoMI() {
		if($("#nomi").attr('checked')) {
			$("#NOMI_dialog").dialog("option", "title", "No Middle Initial");
			$("#NOMI_dialog").dialog("option", "modal", true);
			$("#NOMI_dialog").dialog("option", "width", "100%");
			$("#NOMI_dialog").dialog("open");
		}
	}

	function nomidialogclose() {
		el = $("#nomidialog");
		el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
	}

	$("#nomiyes").click(function() {
		$("#NOMI_dialog").dialog("close");
	});

	$("#nomino").click(function() {
		$("#nomi").attr('checked', false);
		document.ALCATEL.mi.focus();
		$("#NOMI_dialog").dialog("close");
	});

	function updateaka(aliasid) {
		var personid = $('#PersonID').val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_alias.php",
			data: { personid: personid, aliasid: aliasid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					ad = obj2.Changed.split("-");
					akaDateYear = ad[0];
					akaDateMonth = ad[1];
					akaDateDay = ad[2];

					$("#aliasid").val(obj2.AliasID);
					$("#akafirstname").val(obj2.FirstName);
					$("#akalastname").val(obj2.LastName);
					$("#akachanged_month").val(akaDateMonth);
					$("#akachanged_day").val(akaDateDay);
					$("#akachanged_year").val(akaDateYear);

					$("#Alias_dialog").dialog("option", "title", "Edit AKA");
					$("#Alias_dialog").dialog("option", "modal", true);
					$("#Alias_dialog").dialog("open");
				}
				else {
					alert('No AKA Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_alias").click(function() {
		var personid = $("#PersonID").val();
		var aliasid = $("#aliasid").val();
		var saveLocation = "../App_Ajax_New/ajax_add_alias.php";

		if(aliasid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_alias.php";
		}

		if($("#akafirstname").val() == '' && $("#akalastname").val() == '' ) {
			$("#akafirstname").focus();
			alert("First or Last Name is required");
			return;
		}
		else {
			var firstname = $("#akafirstname").val();
			var lastname = $("#akalastname").val();
		}

		if($("#akachanged_month").val() > '' && $("#akachanged_day").val() > '' && $("#akachanged_year").val() > '') {
			var changed =  $("#akachanged_month").val() + '/' + $("#akachanged_day").val() + '/' + $("#akachanged_year").val();
		}
		else {
			$('#akachanged_day').focus();
			alert("Date Last Used is required");
			return;
		}

		var data = {
		   personid: personid,
		   aliasid: aliasid,
		   firstname: firstname,
		   lastname: lastname,
		   middlename: '',
		   changed: changed
		};

		console.log(data);
		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					$("#Alias_dialog").dialog("close");

					if(aliasid > 0) {
						var rowToUpdate = $('#alias' + aliasid);

						rowToUpdate.html('<td>' + firstname + '</td><td>' + lastname + '</td><td>' + changed + '</td><td class="center"><a http="#" onclick="updateaka(' + aliasid + ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;<a http="#" onclick="deleteaka(' + aliasid + ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a></td>');
					}
					else {
						var maxAliasRow = $('#alias' + maxAliasID);
						var newAliasID = maxAliasID + 1;

						if(maxAliasRow) {
							console.log("1");
							maxAliasRow.after('<tr id="alias' + newAliasID + '"><td>' + firstname + '</td><td>' + lastname + '</td><td>' + changed + '</td><td class="center"><a http="#" onclick="updateaka(' + newAliasID + ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;<a http="#" onclick="deleteaka(' + newAliasID + ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a></td></tr>');
						}
						else {
							console.log("2");
							var aliasTable = $('#aliasTable');
							aliasTable.apped('<tr id="alias' + newAliasID + '"><td>' + firstname + '</td><td>' + lastname + '</td><td>' + changed + '</td><td class="center"><a http="#" onclick="updateaka(' + newAliasID + ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;<a http="#" onclick="deleteaka(' + newAliasID + ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a></td></tr>');
						}
					}
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

	function deleteaka(aliasid) {
		if(confirm('Are you sure you want to delete this AKA record?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_aka.php",
				data: { personid: personid, AliasID: aliasid },
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0, 4) == 'Error') {
						alert(obj2);
						return false;
					}
					else {
						window.location.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#save_and_stay").click(function() {
		save_person_info(false);
	});

	$("#save_and_go").click(function() {
		save_person_info(true);
	});

	function save_person_info(moveOn=false) {
		var packagename = $("#package").val();

		if($("#newaka").val() > '' || $("#newakalast").val() > '' ) {
			alert("Please Save the AKA record before moving on.");
			return false;
		}

		var personid = $("#PersonID").val();
		var cd = $("#CD").val();
		var ipaddress = $("#ipaddr").val();

		if($("#fname").val() > '') {
			var fname = $("#fname").val();
		}
		else {
			$("#fname").focus();
			alert("First Name is required");
			return false;
		}

		if($("#nomi").is(':checked')) {
			var mi = '';
		}
		else {
			if($("#mi").val() > '') {
				var mi = $("#mi").val();
			}
			else {
				$("#mi").focus();
				alert("Middle Initial is required");
				return false;
			}
		}

		if($("#lname").val() > '') {
			var lname = $("#lname").val();
		}
		else {
			$("#lname").focus();
			alert("Last Name is required");
			return false;
		}

		if($("#maiden").val() > '') {
			var maiden = $("#maiden").val();
		}
		else {
			var maiden = '';
		}

		if($("#namechg_day").val() == "" || $("#namechg_month").val() == "" || $("#namechg_year").val() == "") {
			if(maiden > '') {
				$("#namechg_day").focus();
				alert("Date Maiden Name Changed is required");
				return false;
			}
			else {
				$("#namechg_day").val("");
				$("#namechg_month").val("");
				$("#namechg_year").val("");
				var namechg = '1900-01-01';
			}
		}
		else {
			var namechg = $("#namechg_year").val() + '-' + $("#namechg_month").val() + '-' + $("#namechg_day").val();
		}

		if($("#fbdate").val() > '') {
			var birthdate = $("#fbdate").val();
		}
		else {
			$("#birthdate_day").focus();
			alert("Date of Birth is required");
			return false;
		}

		if(packagename == 'zinc') {
			var ssn = '';

			if($("#ins").val() > '') {
				var ins = $("#ins").val();
			}
			else {
				$('#ins').focus();
				alert("National Ins # or N/A is required");
				return false;
			}

			if($("#passport").val() > '') {
				var passport = $("#passport").val();
			}
			else {
				$('#passport').focus();
				alert("Passport # is required");
				return false;
			}

			if($("#nationality").val() > '') {
				var nationality = $("#nationality").val();
			}
			else {
				$('#nationality').focus();
				alert("Nationality is required");
				return false;
			}

			if($("#mothermaiden").val() > '') {
				var mothermaiden = $("#mothermaiden").val();
			}
			else {
				$('#mothermaiden').focus();
				alert("Mother's Maiden Name is required");
				return false;
			}

			if($("#fathername").val() > '') {
				var fathername = $("#fathername").val();
			}
			else {
				$('#fathername').focus();
				alert("Father's Full Name is required");
				return false;
			}
		}
		else {
			var ins = '';
			var passport = '';
			var nationality = '';
			var mothermaiden = '';
			var fathername = '';

			if($("#ssn").val() > '') {
				var ssn = $("#ssn").val();

				if(ssn.length < 11) {
					$("#ssn").focus();
					alert("Invalid SSN - Require format ###-##-####");
					return false;
				}
				else {
					if(ssn.substring(0,3) == 'XXX') {
						ssn = $("#num").val();
					}
				}
			}
			else {
				$("#ssn").focus();
				alert("SSN is required");
				return false;
			}
		}
		var gender = '';

		if($("#busphone").val() == '' && $("#homephone").val() == '' && $("#cellphone").val() == '') {
			$("#busphone").focus();
			alert("Please enter at least one contact phone number");
			return false;
		}
		else {
			var busphone = $("#busphone").val();
			var homephone = $("#homephone").val();
			var cellphone = $("#cellphone").val();
		}

		var emergcontact = '';
		var emergnumber =  '';

		if($("#noemail").val() == 'N') {
			if($("#email").val() > '') {
				var email = $("#email").val();
			}
			else {
				$("#email").focus();
				alert("Email Address is required");
				return false;
			}
		}
		else {
			var email = $("#email").val();
		}

		var data = {
			personid: personid,
			fname: fname,
			mi: mi,
			lname: lname,
			maiden: maiden,
			namechg: namechg,
			birthdate: birthdate,
			ssn: ssn,
			busphone: busphone,
			homephone: homephone,
			cellphone: cellphone,
			email: email,
			gender: gender,
			emergcontact: emergcontact,
			emergnumber: emergnumber,
			ipaddress: ipaddress
		};

		console.log(data);
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_add_person.php",
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '') {
					alert(obj2);
					return false;
				}
				else {
					if(moveOn) {
						window.location = 'index.php?pg=' + nextPage + '&PersonID=' + personid + '&CD=' + cd;
					}
					else {
						alert('Data saved successfully');
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function validateSSN() {
 		var patt = new RegExp("\d{3}[\-]\d{2}[\-]\d{4}");
 		var x = $("#ssn");
 		var res = patt.test(x.value);

 		if(!res) {
  		x.value = x.value.match(/\d*/g).join('').match(/(\d{0,3})(\d{0,2})(\d{0,4})/).slice(1).join('-').replace(/-*$/g, '');
 		}
	}
</script>
