<?
$FormAction = "index.php?pg={$nextPage}&PersonID=" . $PersonID . "&CD=" . $CD;

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
								<span class="sub-heading">Bank Information</span><br>
								Please provide your bank information.<br />&nbsp;
							</div>';

	$maxBankID = $dbo->query("Select max(BankID) from App_Bank where PersonID = " . $PersonID . ";")->fetchColumn();

	if($maxBankID > 0) {
		$selectaddr="select BankID, Bank_Name, Bank_Address, Bank_City, Bank_State, Bank_Country, Bank_ZipCode, Account_Type, Account_Number from App_Bank where PersonID = :PersonID;";
		$bank_result = $dbo->prepare($selectaddr);
		$bank_result->bindValue(':PersonID', $PersonID);
		$bank_result->execute();

		while($row = $bank_result->fetch(PDO::FETCH_BOTH)) {
			$fullaccount = $row[8];
			$displayaccount = 'xxxxxxxxxxxx';

			echo '	<div class="cell small-6 sub-heading">
						 		' . htmlspecialchars($row[1]) . '
							</div>
							<div class="cell small-6 right">
								<span onclick="updatebank(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Bank" title="Edit Address"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="updatebank(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Bank" title="Delete Address"/></span>
							</div>

							<div class="cell small-6">
								' . htmlspecialchars($row[7]) . '
							</div>
							<div class="cell small-6">
								' . htmlspecialchars($row[6]) . '
							</div>

							<div class="cell small-4">
								' . htmlspecialchars($row[2]) . '
							</div>
							<div class="cell small-4">
								' . htmlspecialchars($row[3]) . '
							</div>';

			if($row[4] > '') {
				echo '<div class="cell small-4">
								' . htmlspecialchars($row[4]) . '
							</div>';
			}
			else {
				echo '<div class="cell small-4">
								' . htmlspecialchars($row[5]) . '
							</div>';
			}

			echo '	<div class="cell small-12">
								<hr>
							</div>';
		}
	}

	echo '			<div class="cell small-12">
								<span class="add-bank add-button"><img class="icon" src="images/plus.png" alt="Add Bank" title="Add Bank" /> Add Bank</span>
							</div>
							<div class="cell small-12">
								<hr>
							</div>

							<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>
							<div class="cell small-6">
								<input class="button float-center" type="submit" value="Next">
							</div>
						</div>

						<div class="grid-x margins person-form" name="Bank_dialog" id="Bank_dialog" title="Dialog Title">
							<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
							<input type="hidden" name="bankid" id="bankid">
							<input type="hidden" name="accounthidden" id="accounthidden">

							<div class="cell small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell medium-6 small-12">
								Bank Name <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="bankname" id="bankname" maxlength="100" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								Bank Street <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="bankaddress" id="bankaddress" maxlength="100" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								Bank City <span class="required">*</span>
							</div>
							<div class="cell medium-4 small-8">
								<input type="text" name="bankcity" id="bankcity" maxlength="40" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								Bank State
							</div>
							<div class="cell medium-4 small-8">
								<select name="bankstate" id="bankstate">
									<option value="">Select a State</option>
									<option value="">-Other-</option>
									' . $state_options . '
								</select>
							</div>

							<div class="cell small-12">
								OR If bank is out of the US, please select the Country
							</div>

							<div class="cell medium-6 small-12">
								Bank Country
							</div>
							<div class="cell medium-6 small-12">
								<select name="bankcountry" id="bankcountry">
									<option value="">Select a Country</option>
									' . $country_options . '
								</select>
							</div>

							<div class="cell medium-6 small-12">
								Bank Zip Code <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="bankzip" id="bankzip" maxlength="10" placeholder="Required">
							</div>

							<div class="cell medium-6 small-12">
								Account Type <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<select name="accounttype" id="accounttype">
									<option value="Checking">Checking</option>
									<option value="Savings">Savings</option>
							</select>
							</div>

							<div class="cell medium-6 small-12">
								Account Number <span class="required">*</span>
							</div>
							<div class="cell medium-6 small-12">
								<input type="text" name="accountnumber" id="accountnumber" maxlength="50" placeholder="Required">
							</div>

							<div class="cell small-12 padding-bottom">
								<input id="save_bank" class="float-center" type="button" value="Save Bank">
							</div>
						</div>
					</form>';
?>

<script language="JavaScript" type="text/javascript">
	$("#Bank_dialog").dialog({ autoOpen: false });

	$(".add-bank").click(function() {
		addBank();
	});

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function addBank() {
		$("#accounttype").val('Checking');
		$("#bankaddress").val('');
		$("#bankname").val('');
		$("#bankcity").val('');
		$("#bankstate").val('');
		$("#bankcountry").val('');
		$("#bankzip").val('');
		$("#accountnumber").val('');

		$("#Bank_dialog").dialog("option", "title", "Add Bank");
		$("#Bank_dialog").dialog("option", "modal", true);
		$("#Bank_dialog").dialog("option", "width", "100%");
		$("#Bank_dialog").dialog("open");
	}

 	$("#save_bank").click(function() {
		var personid = $("#PersonID").val();
		var bankid = $("#bankid").val();
		var saveLocation = "../App_Ajax_New/ajax_add_bank.php";

		if(bankid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_bank.php";
		}

		if($("#bankname").val() > '') {
			var bankname = $("#bankname").val();
		}
		else {
			$("#bankname").focus();
			alert("Bank name is required");
			return;
		}

		if($("#bankaddress").val() > '') {
			var bankaddress = $("#bankaddress").val();
		}
		else {
			$("#bankaddress").focus();
			alert("Bank street is required");
			return;
		}

		if($("#bankcity").val() > '') {
			var bankcity = $("#bankcity").val();
		}
		else {
			$("#bankcity").focus();
			alert("Bank city is required");
			return;
		}

		if($("#bankstate").val() == '' && $("#bankcountry").val() == '' ) {
			$("#bankstate").focus();
			alert("Bank State or Country is required");
			return;
		}
		else {
			var bankstate = $("#bankstate").val();
			var bankcountry = $("#bankcountry").val();
		}

		if($("#bankzip").val() > '') {
			var bankzipcode = $("#bankzip").val();
		}
		else {
			$("#wbankzip").focus();
			alert("Bank Zip Code is required");
			return;
		}

		var accounttype = $("#accounttype").val();

		if($("#accountnumber").val() > '') {
			var accountnumber = $("#accountnumber").val();
		}
		else {
			$("#accountnumber").focus();
			alert("Account number is required");
			return;
		}

		var data = {
			personid: personid,
			bankid: bankid,
			bankname: bankname,
			bankaddress: bankaddress,
			bankcity: bankcity,
			bankstate: bankstate,
			bankcountry: bankcountry,
			bankzipcode: bankzipcode,
			accounttype: accounttype,
			accountnumber: accountnumber
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

	function updatebank(bankid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_bank.php",
			data: { personid: personid, bankid: bankid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					$("#accounttype").val(obj2.Account_Type);
					$("#bankid").val(obj2.BankID);
					$("#bankaddress").val(obj2.Bank_Address);
					$("#bankname").val(obj2.Bank_Name);
					$("#bankcity").val(obj2.Bank_City);
					$("#bankstate").val(obj2.Bank_State);
					$("#bankcountry").val(obj2.Bank_Country);
					$("#bankzip").val(obj2.Bank_ZipCode);
					$("#accountnumber").val('xxxxxxxxxxxx');
					$("#accounthidden").val(obj2.Account_Number);

					$("#Bank_dialog").dialog("option", "title", "Edit Bank Information");
					$("#Bank_dialog").dialog("option", "modal", true);
					$("#Bank_dialog").dialog("option", "width", "100%");
					$("#Bank_dialog").dialog("open");
				}
				else {
					alert('No Bank Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function deletebank(bankid) {
		if(confirm('Are you sure you want to delete this bank?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_bank.php",
				data: {personid: personid, bankid: bankid},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0,4) == 'Error') {
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

	$("#close_bank").click(function() {
		$("#Bank_dialog").dialog("close");
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var bankid = $("#BankID").val();

		if(bankid == 0) {
			$('#bankname').focus();
			alert('You have not entered at least one bank account');
			return false;
		}
		else {
			return true;
		}
	}
</script>
