<?php

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
								<span class="sub-heading">Rental References</span><br>
								List one or more Rental Reference(s).<br />&nbsp;
							</div>';

	$currentRental = 'N';
	$maxRentalID = 0;
	$maxRentalID = $dbo->query("Select max(RentalID) from App_Rentals where PersonID = " . $PersonID . ";")->fetchColumn();

	if($maxRentalID > 0) {
		$selectstmt = "select RentalID, Landlord_Name, Landlord_Phone, Landlord_Email, Current_Rental, Rental_Address, Rental_City, Rental_State, Rental_ZipCode, Rental_Type, Rental_Payment, Rental_MoveIn_Date, Rental_MoveOut_Date from App_Rentals where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();

		while($row = $result2->fetch(PDO::FETCH_BOTH)) {
			if($row[4] == 'Y') {
				$rentalType = "Current";
				$currentRental = $row[4];
			}
			else {
				$rentalType = "Additional";
			}

			if($row[11] == '1900-01-01') {
				$fromdate = '';
			}
			else {
				$fromdate = date("m/d/Y", strtotime($row[10]));
			}

			if($row[12] == '1900-01-01') {
				$todate = '';
			}
			else {
				$todate = date("m/d/Y", strtotime($row[11]));
			}

			echo '	<div class="cell small-12">
								<h3>' . $rentalType . ' Rental</h3>
							</div>

							<div class="cell small-6 sub-heading">
								&nbsp;' . htmlspecialchars($fromdate) . '&nbsp;&nbsp;-&nbsp;&nbsp;' . htmlspecialchars($todate) . '
							</div>
							<div class="cell small-6 right">
								<span onclick="updateRental(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Rental" title="Edit Rental"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span onclick="deleteRental(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Rental" title="Delete Rental"/></span>
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[1]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[2]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[3]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[4]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[5]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[6]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[7]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[8]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[9]) . '
							</div>

							<div class="cell small-3">
								' . htmlspecialchars($row[10]) . '
							</div>

							<div class="cell small-12">
								<hr>
							</div>';
		}
	}

	echo '				<div class="cell small-12">
									<span class="add-rental add-button"><img class="icon" src="images/plus.png" alt="Add Rental" title="Add Rental" /> Add Rental</span>
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

								<div class="grid-x margins person-form" name="Rental_dialog" id="Rental_dialog" title="Dialog Title">
									<div class="cell small-12 required">
										* Required Fields To Continue
									</div>';

	if($currentRental == 'N') {
		echo '				<div class="cell medium-6 small-12">
										Current Rental <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<select name="current" id="current" onchange="setdate()">
											<option value="Y">Yes</option>
											<option value="N">No</OPTION>
										</select>
									</div>';
	}

	echo '					<div class="cell medium-6 small-12">
										Landlord Name <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="landlordname" id="landlordname" maxlength="100" placeholder="Required">
									</div>

									<div class="cell medium-6 small-12">
										Contact Phone <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="landlordphone" id="landlordphone" maxlength="100" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
									</div>

									<div class="cell medium-6 small-12">
										Contact Email <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="landlordemail" id="landlordemail" maxlength="100">
									</div>

									<div class="cell medium-6 small-12">
										Rental Address <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="rentaladdress" id="rentaladdress" maxlength="100">
									</div>

									<div class="cell medium-6 small-12">
										Rental City <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="rentalcity" id="rentalcity" maxlength="100">
									</div>

									<div class="cell medium-6 small-12">
										Rental State <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<select name="rentalstate" id="rentalstate">
											<option value="">Select a State</option>
											<option value="">-Other-</option>
											' . $state_options . '
										</select>
									</div>

									<div class="cell small-12">
										OR If address is out of the US, please select the Country
									</div>

									<div class="cell medium-6 small-12">
										Rental Country
									</div>
									<div class="cell medium-6 small-12">
										<select name="rentalstateother" id="rentalstateother">
											<option value="">Select a Country</option>
											' . $country_options . '
										</select>
									</div>

									<div class="cell medium-6 small-12">
										Postal Code <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="rentalzip" id="rentalzip" maxlength="10">
									</div>

									<div class="cell medium-6 small-12">
										Type <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<select name="rentaltype" id="rentaltype">
											<option value="">Select a Type</option>
											<option value="Rent">Rent</option>
											<option value="Lease">Lease</option>
											<option value="Own">Own</option>
											<option value="Other">Other</option>
										</select>
									</div>

									<div class="cell medium-6 small-12">
										Rental Payment <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="rentalpayment" id="rentalpayment" maxlength="100">
									</div>

									<div class="cell medium-6 small-12">
										Move In Date <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="moveindate" id="moveindate" maxlength="10" placeholder="mm/dd/yyyy">
									</div>

									<div class="cell medium-6 small-12">
										Move Out Date <span class="required">*</span>
									</div>
									<div class="cell medium-6 small-12">
										<input type="text" name="moveouttodate" id="moveouttodate" maxlength="10" placeholder="mm/dd/yyyy">
									</div>

									<div class="cell small-12 padding-bottom">
										<input id="save_rental" class="float-center" type="button" value="Save Rental">
									</div>
								</div>

								<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
								<input type="hidden" name="current" id="current">
								<input type="hidden" name="rentalid" id="rentalid">
							</div>
						</div>
					</form>';
?>

<script language="JavaScript" type="text/javascript">
 	$("#Rental_dialog").dialog({ autoOpen: false });
	if($('#moveindate')[0].type != 'date' ) $('#moveindate').datepicker();
	if($('#moveouttodate')[0].type != 'date' ) $('#moveouttodate').datepicker();

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	$(".add-rental").click(function() {
		addRental();
	});

	function addRental() {
		$("#current").val('');
		$("#rentalid").val('');
		$("#landlordname").val('');
		$("#landlordphone").val('');
		$("#landlordemail").val('');
		$("#rentaladdress").val('');
		$("#rentalcity").val('');
		$("#rentalstate").val('');
		$("#rentalstateother").val('');
		$("#rentalzipcode").val('');
		$("#rentaltype").val('');
		$("#rentalpayment").val('');
		$("#moveindate").val('');
		$("#moveoutdate").val('');

		$("#Rental_dialog").dialog("option", "title", "Add Rental");
		$("#Rental_dialog").dialog("option", "modal", true);
		$("#Rental_dialog").dialog("option", "width", "100%");
		$("#Rental_dialog").dialog("open");
	}

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

			$("#moveoutdate").placeholder = '';
			$("#moveoutdate").val(today);
		}
		else {
			$("#moveoutdate").placeholder = 'mm/dd/yyyy';
			$("#moveoutdate").val('');
		}
	}

	$("#save_rental").click(function() {
		var personid = $("#PersonID").val();
		var rentalid = $("#dlgrentalid").val();
		var currentRental = $("#dlgcurrent").val();
		var saveLocation = "../App_Ajax_New/ajax_add_rental.php";

		if(rentalid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_rental.php";
		}

		if($("#landlordname").val() > '') {
			var landlordname = $("#landlordname").val();
		}
		else {
			$("#landlordname").focus();
			alert("Landlord Name is required");
			return;
		}

		if($("#landlordphone").val() > '') {
			var landlordphone = $("#landlordphone").val();
		}
		else {
			$("#landlordphone").focus();
			alert("Contact Phone # is required");
			return;
		}

		if($("#landlordemail").val() > '') {
			var landlordemail = $("#landlordemail").val();
		}
		else {
			$("#landlordemail").focus();
			alert("Contact Email is required");
			return;
		}

		if($("#rentaladdress").val() > '') {
			var rentaladdress = $("#rentaladdress").val();
		}
		else {
			$("#rentaladdress").focus();
			alert("Rental Address is required");
			return;
		}

		if($("#rentalcity").val() > '') {
			var rentalcity = $("#rentalcity").val();
		}
		else {
			$("#rentalcity").focus();
			alert("Rental City is required");
			return;
		}

		if($("#rentalstate").val() == '' && $("#rentalstateother").val() == '' ) {
			$("#rentalstate").focus();
			alert("Rental State or Country is required");
			return;
		}
		else {
			var rentalstate = $("#rentalstate").val();
			var rentalstateother = $("#rentalstateother").val();
		}

 		if($("#rentalzipcode").val() > '') {
			var rentalzipcode = $("#rentalzipcode").val();
		}
		else {
			$("#rentalzipcode").focus();
			alert("Rental Postal Code is required");
			return;
		}

 		if($("#rentaltype").val() > '') {
			var rentaltype = $("#rentaltype").val();
		}
		else {
			$("#rentaltype").focus();
			alert("Type is required");
			return;
		}

		if($("#rentalpayment").val() > '') {
			var rentalpayment = $("#rentalpayment").val();
		}
		else {
			$("#rentalpayment").focus();
			alert("Rental Payment is required");
			return;
		}

		if($("#moveindate").val() > '') {
			var moveindate = $("#moveindate").val();
		}
		else {
			$("#moveindate").focus();
			alert("Move In Date is required");
			return;
		}

		if($("#moveoutdate").val() > '') {
			var moveoutdate = $("#moveoutdate").val();
		}
		else {
			$("#moveoutdate").focus();
			alert("Move Out Date is required");
			return;
		}

		if(!isValidDiff(moveindate, moveoutdate)) {
			$('#moveindate').focus();
			alert("Move In Date can not be greater than Move Out Date");
			return false;
		}

		var data = {
			personid: personid,
			rentalid: rentalid,
			landlordname: landlordname,
			landlordphone: landlordphone,
			landlordemail: landlordemail,
			currentRental: currentRental,
			rentaladdress: rentaladdress,
			rentalcity: rentalcity,
			rentalstate: rentalstate,
			rentalstateother: rentalstateother,
			rentaltype: rentaltype,
			rentalpayment: rentalpayment,
			moveindate: moveindate,
			moveoutdate: moveoutdate,
			rentalzipcode: rentalzipcode
		};

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
					$( "#Rental_dialog" ).dialog( "close" );
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

 	function updateRental(rentalid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_rental.php",
			data: { personid: personid, rentalid: rentalid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					var movein = obj2.Rental_MoveIn_Date;
					var moveout = obj2.Rental_MoveOut_Date;
					var Rental_MoveIn_Date = movein.substr(5, 2) + "/" + movein.substr(8) + "/" + movein.substr(0,4);
					var Rental_MoveOut_Date = moveout.substr(5, 2) + "/" + moveout.substr(8) + "/" + moveout.substr(0,4);

 					$("#current").val(obj2.CurrentRental);
					$("#rentalid").val(obj2.RentalID);
					$("#landlordname").val(obj2.LandlordName);
					$("#landlordphone").val(obj2.LandlordPhone);
					$("#landlordemail").val(obj2.LandlordEmail);
					$("#rentaladdress").val(obj2.Rental_Address);
					$("#rentalcity").val(obj2.Rental_City);
					$("#rentalstate").val(obj2.Rental_State);
					$("#rentalstateother").val(obj2.Rental_Country);
					$("#rentalzipcode").val(obj2.Rental_ZipCode);
					$("#rentaltype").val(obj2.Rental_Type);
					$("#rentalpayment").val(obj2.Rental_Payment);
					$("#moveindate").val(Rental_MoveIn_Date);
					$("#moveoutdate").val(Rental_MoveOut_Date);

					$("#Rental_dialog").dialog("option", "title", "Edit Rental");
					$("#Rental_dialog").dialog("option", "modal", true);
					$("#Rental_dialog").dialog("option", "width", "100%");
					$("#Rental_dialog").dialog("open");
				}
				else {
					alert('No Rental Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function deleteRental(rentalid) {
		if(confirm('Are you sure you want to delete this Rental?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_rental.php",
				data: { personid: personid, rentalid: rentalid },
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					}
					else {
						location.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_rental").click(function() {
		$("#Rental_dialog").dialog("close");
	});

	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var RentalID = $("#RentalID").val();

		if(RentalID == 0) {
			$("#newlandlordname").focus();
			alert('You have not entered at least one Rental');
			return false;
		}
		else {
			return true;
		}
	}
</script>
