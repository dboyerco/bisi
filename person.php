<?php
$num = "";
$fname = "Mike";
$mi = "D";
$lname = "Perrotto";
$birthdate = "1980-05-04";
$ssn = "123-123-1234";
$busphone = "";
$homephone = "3031231234";
$cellphone = "";
$email = "test@test.com";
$package = "";
$gender = "M";
$emergcontact = "";
$emergnumber = "";
$No_Email = "";
$namechg = "";
$maiden = '';

if(isSet($PersonID)) {
	if(!$testLayout) {
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
					$masked_birthdate = "";
				}
				else {
					$birthdate = date("m/d/Y", strtotime($row[3]));
					//$masked_birthdate = date("m/d/", strtotime($row[3])) + "XXXX";
					$dateVals = split("-", $row[3]);
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
						$namechg = date("m/d/Y", strtotime($namechg));
					}
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
								<span class="sub-heading">Subject Information</span><br>
								<strong>Disclaimer: </strong>All information requested in this application is pertinent and necessary. Failure to fill out all information can delay the hiring process.<br>';

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
							<div class="cell small-12 medium-3">
								<label>
									Date Maiden Name Changed
									<input type="text" name="namechg" id="namechg" size="13" maxlength="10" id="namechg" value="' . htmlspecialchars($namechg) . '" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
								</label>
							</div>
							<div class="cell medium-4"></div>

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

if(!$testLayout) {
	$maxAliasID = $dbo->query("select max(AliasID) from App_Alias where PersonID = " . $PersonID . ";")->fetchColumn();
}
else {
	$maxAliasID = 1;
}

if($maxAliasID > 0) {
	$selectalias = "Select AliasID, FirstName, LastName, Changed from App_Alias where PersonID = :PersonID and AliasType = 'A';";

	if(!$testLayout) {
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
		echo '					<tr id="alias1">
											<td>Mikey</td>
											<td>Pelirojo</td>
											<td>01/01/2018</td>
											<td class="center">
												<a http="#" onclick="updateaka(1)"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;
												<a http="#" onclick="deleteaka(1)"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a>
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

							<div class="cell small-6 medium-3">
								<label>
									Date of Birth <span class="required">*</span>
									' . ($birthdate != "" ? '<input type="text" name="birthdate" id="birthdate" placeholder="mm/dd/yyyy" value="' . $masked_birthdate . '">' : '<input type="date" name="birthdate" id="birthdate" placeholder="mm/dd/yyyy" value="">') . '
									<input type="hidden" name="fbdate" id="fbdate" value="' . $birthdate . '">
								</label>
							</div>
							<div class="cell small-6 medium-3">
								<label>
									SSN <span class="required">*</span>
									<input type="tel" id="ssn" name="ssn" placeholder="###-##-####" maxlength="11" onBlur = "validateSSN()" onKeyUp="return frmtssn(this,\'up\')" onKeyDown="return frmtssn(this,\'down\')" value="' . htmlspecialchars($ssn) . '" />
								</label>
							</div>
							<div class="cell medium-3"></div>
							<div class="cell medium-3"></div>';

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
									<input type="tel" name="busphone" id="busphone" value="' . htmlspecialchars($busphone) . '" maxlength="40" placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtbphone(this,\'up\')">
								</label>
							</div>
							<div class="cell small-12 medium-3">
								<label>
									Home Phone<br />
									<input type="tel" name="homephone" id="homephone" value="' . htmlspecialchars($homephone) . '" maxlength="40" placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
								</label>
							</div>
							<div class="cell small-12 medium-3">
								<label>
									Cell Phone<br />
									<input type="tel" name="cellphone" id="cellphone" value="' . htmlspecialchars($cellphone) . '" maxlength="40" placeholder="### ### ####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
								</label>
							</div>
							<div class="cell medium-3"></div>

							<div class="cell small-12"><hr></div>

							<div class="cell small-12">
								<label>
									Enter an E-mail address: ' . ($No_Email == 'N' ? '<span class="required">*</span>' : '') . '
									<input type="email" name="email" id="email" value="' . htmlspecialchars($email) . '">
							</div>

							<div class="cell small-12"><hr></div>

						</div>
					</div>

					<div class="grid-x margins">
						<div class="cell small-12 medium-6 padding-bottom">
							<input name="save_person_info" class="float-center" id="save_person_info" type="button" value="Save the Data You Have Entered">
						</div>
						<div class="cell small-12 medium-6">
							<input name="add_person_info" class="float-center" id="add_person_info" type="button" value="Save Subject Data and Continue">
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
					<input type="date" name="akachanged" id="akachanged" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
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
	if($('#akachanged')[0].type != 'date' ) $('#akachanged').datepicker();
	if($('#birthdate')[0].type != 'date' ) $('#birthdate').datepicker();

	$(".add-alias").click(function() {
		addAlias();
	});

	function addAlias() {
		$('#aliasid').val('');
		$("#akafirstname").val('');
		$("#akalastname").val('');
		$("#akachanged").val('');

		$("#Alias_dialog").dialog("option", "title", "Edit AKA");
		$("#Alias_dialog").dialog("option", "modal", true);
		$("#Alias_dialog").dialog("option", "width", "100%");
		$("#Alias_dialog").dialog("open");
	}

	$("#close_alias").click(function() {
		$("#Alias_dialog").dialog("close");
	});

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
					var akaDate = '';

					if($('#akachanged')[0].type != 'date') {
						ad = obj2.Changed.split("-");
						akaDate = ad[1] + "/" + ad[2] + "/" + ad[0];
					}
					else {
						akaDate = obj2.Changed;
					}

					$("#aliasid").val(obj2.AliasID);
					$("#akafirstname").val(obj2.FirstName);
					$("#akalastname").val(obj2.LastName);
					$("#akachanged").val(akaDate);

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
		console.log(saveLocation);
		if($("#akafirstname").val() == '' && $("#akalastname").val() == '' ) {
			$("#akafirstname").focus();
			alert("First or Last Name is required");
			return;
		}
		else {
			var firstname = $("#akafirstname").val();
			var lastname = $("#akalastname").val();
		}

		if($("#akachanged").val() > '') {
			var myDate = $("#akachanged").val();
			var altDate = myDate.split("-");
			var changed = myDate;
		}
		else {
			$('#akachanged').focus();
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

					if(aliasid > 0) { // updating
						var rowToUpdate = $('#alias' + aliasid);

						rowToUpdate.html('<td>' + firstname + '</td><td>' + lastname + '</td><td>' + changed + '</td><td class="center"><a http="#" onclick="updateaka(' + aliasid + ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;<a http="#" onclick="deleteaka(' + aliasid + ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a></td>');
					}
					else {
						var maxAliasRow = $('#alias' + maxAliasID);
						var newAliasID = maxAliasID + 1;

						if(maxAliasRow) {
							maxAliasRow.after('<tr id="alias' + newAliasID + '"><td>' + firstname + '</td><td>' + lastname + '</td><td>' + changed + '</td><td class="center"><a http="#" onclick="updateaka(' + newAliasID + ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Alias" title="Edit Alias"/></a>&nbsp;&nbsp;&nbsp;<a http="#" onclick="deleteaka(' + newAliasID + ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Alias" title="Delete Alias"/></a></td></tr>');
						}
						else {
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
						var rowToDelete = $('#alias' + aliasid);

						rowToDelete.remove();

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

	$("#add_person_info").click(function() {
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
			document.ALCATEL.fname.focus();
			alert("First Name is required");
			return false;
		}

		if($("#nomi").checked) {
			var mi = '';
		}
		else {
			if($("#mi").val() > '') {
				var mi = $("#mi").val();
			}
			else {
				document.ALCATEL.mi.focus();
				alert("Middle Initial is required");
				return false;
			}
		}

		if($("#lname").val() > '') {
			var lname = $("#lname").val();
		}
		else {
			document.ALCATEL.lname.focus();
			alert("Last Name is required");
			return false;
		}

		if($("#maiden").val() > '') {
			var maiden = $("#maiden").val();
		}
		else {
			var maiden = '';
		}

		if($("#namechg").val() == '') {
			if(maiden > '') {
				document.ALCATEL.namechg.focus();
				alert("Date Maiden Name Changed is required");
				return false;
			}
			else {
				var namechg = '1900-01-01';
			}
		}
		else {
			if(!isValidDate('namechg')) {
				$('#namechg').focus();
				alert("Invalid Date Maiden Name Changed");
				return false;
			}
			else {
				var namechg = $("#namechg").val();
			}
		}

		if($("#birthdate").val() > '') {
			var birthdate = $("#birthdate").val();
		}
		else {
			$("#birthdate").focus();
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
					document.ALCATEL.ssn.focus();
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
				document.ALCATEL.ssn.focus();
				alert("SSN is required");
				return false;
			}
		}

		var gender = '';

		if($("#busphone").val() == '' && $("#homephone").val() == '' && $("#cellphone").val() == '') {
			document.ALCATEL.busphone.focus();
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
				document.ALCATEL.email.focus();
				alert("Email Address is required");
				return false;
			}
		}
		else {
			var email = $("#email").val();
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_add_person.php",
			data: { personid: personid, fname: fname, mi: mi, lname: lname, maiden: maiden, namechg: namechg, birthdate: birthdate, ssn: ssn, busphone: busphone, homephone: homephone, cellphone: cellphone, email: email, gender: gender, emergcontact: emergcontact, emergnumber: emergnumber,ipaddress: ipaddress },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '') {
					alert(obj2);
					return false;
				}
				else {
		 			window.location = 'index.php?pg=' + nextPage + '&PersonID=' + personid + '&CD=' + cd;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

	$("#save_person_info").click(function() {
		var packagename = $("#package").val();

		if($("#newaka").val() > '' || $("#newakalast").val() > '' ) {
			alert("Please Save the AKA record before moving on.");
			return false;
		}

		var personid = $("#PersonID").val();
		var ipaddress = $("#ipaddr").val();

		var fname = $("#fname").val();
		var mi = $("#mi").val();
		var lname = $("#lname").val();
		var maiden = $("#maiden").val();

		if($("#namechg").val() == '') {
			var namechg = '1900-01-01';
		}
		else {
			var namechg = $("#namechg").val();
		}

		if($("#birthdate").val() > '') {
			var birthdate = $("#birthdate").val();

			if(birthdate.indexOf('XXXX') > 0) {
				birthdate = $("#fbdate").val();
			}
		}
		else {
			birthdate = '1900-01-01';
		}

		if(packagename == 'zinc') {
			var ssn = '';
			var ins = $("#ins").val();
			var passport = $("#passport").val();
			var nationality = $("#nationality").val();
			var mothermaiden = $("#mothermaiden").val();
			var fathername = $("#fathername").val();
		}
		else {
			var ins = '';
			var passport = '';
			var nationality = '';
			var mothermaiden = '';
			var fathername = '';

			if($("#ssn").val() > '') {
				var ssn = $("#ssn").val();

				if(ssn.substring(0,3) == 'XXX') {
					ssn = $("#num").val();
				}
			}
			else {
				ssn = '';
			}
		}

		var busphone = $("#busphone").val();
		var homephone = $("#homephone").val();
		var cellphone = $("#cellphone").val();
		var gender = '';
		var emergcontact = '';
		var emergnumber = '';

		var email = $("#email").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_add_person.php",
			data: { personid: personid, fname: fname, mi: mi, lname: lname, maiden: maiden, namechg: namechg, birthdate: birthdate, ssn: ssn, busphone: busphone, homephone: homephone, cellphone: cellphone, email: email, gender: gender, emergcontact: emergcontact, emergnumber: emergnumber, ipaddress: ipaddress },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '') {
					alert(obj2);
					return false;
				}
				else {
					alert('Data saved successfully');
				}

				return false;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});

	function validateSSN() {
   		var patt = new RegExp("\d{3}[\-]\d{2}[\-]\d{4}");
   		var x = $("#ssn");
   		var res = patt.test(x.value);

   		if(!res){
    		x.value = x.value
        	.match(/\d*/g).join('')
        	.match(/(\d{0,3})(\d{0,2})(\d{0,4})/).slice(1).join('-')
        	.replace(/-*$/g, '');
   		}
	}
</script>
