<?php

$num = "";

if(isset($PersonID)) {
	if($PersonID > '') {
		$selectstmt="Select ScreenType, Contractor_Vendor_Name, StationID, Position, Employer, Worked_Here_Before, Insurance_Company, Insurance_Exp_Date from App_Additional_Info where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);

		if(!$result2->execute()) {

		}
		else {
			$row = $result2->fetch(PDO::FETCH_BOTH);
			$stype = $row['ScreenType'];
			$cvname = $row['Contractor_Vendor_Name'];
			$stationid = $row['StationID'];
			$position = $row['Position'];
			$employer = $row['Employer'];
			$empbefore = $row['Worked_Here_Before'];
			$Insurance_Company = $row['Insurance_Company'];

			if($row['Insurance_Exp_Date'] == '1900-01-01') {
				$Insurance_Exp_Date = '';
			}
			else {
				$Insurance_Exp_Date = date("m/d/Y", strtotime($row['Insurance_Exp_Date']));
			}
		}
	}
}

$FormAction = "index.php?pg={$nextPage}&PersonID=" . $PersonID . "&CD=" . $CD;

echo '<form method="post" action="' . $FormAction . '" name="ALCATEL">
				<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">

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
								<span class="sub-heading">Addition Information</span><br>
								<strong>Disclaimer: </strong>All information requested on this page is pertinent and necessary. Not filling out all information can delay the hiring process.<br />&nbsp;
							</div>

							<div class="cell medium-6 small-12 required">
								* Required Fields To Continue
							</div>

							<div class="cell small-12">
								Have you ever been employed by ' . $compname . '? <span class="required">*</span>
							</div>

							<div class="cell small-12">
								<select name="empbefore" id="empbefore">
									<option value="">-</option>
									<option value="No">No</option>
									<option value="Yes">Yes</option>
								</select>
							</div>

							<div class="cell small-12">
								Status <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<select name="screentype" id="screentype">
									<option value="">-</option>
									<option value="contractor">Contractor</option>
									<option value="consultant">Consultant</option>
									<option value="vendor">Vendor</option>
								</select>
							</div>

							<div class="cell small-12">
								Contractor/Vendor Name <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<input type="text" name="cvname" id="cvname" autocorrect="off" autocapitalize="words" value="' . htmlspecialchars($cvname) . '" size="40" maxlength="100">
							</div>

							<div class="cell small-12">
								' . $compname . ' work location <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<select name="stationid" id="stationid">
									<option value="">-</option>
									<option value="Hilo Baseyard">Hilo Baseyard</option>
									<option value="Kona Baseyard">Kona Baseyard</option>
									<option value="Waimea Baseyard">Waimea Baseyard</option>
									<option value="Hilo Main Office">Hilo Main Office</option>
									<option value="Keahole Plant">Keahole Plant</option>
									<option value="Puna Plant">Puna Plant</option>
									<option value="Substation">Substation</option>
									<option value="Other">Other</option>
								</select>
							</div>

							<div class="cell small-12">
								Position <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<input type="text" name="position" id="position" value="' . htmlspecialchars($position) . '" size="40" maxlength="100">
							</div>

							<div class="cell small-12">
								Employer <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<input type="text" name="employer" id="employer" value="' . htmlspecialchars($employer) . '" size="40" maxlength="100">
							</div>

							<div class="cell small-12">
								Insurance Company <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<input type="text" name="insurancecompany" id="insurancecompany" value="' . htmlspecialchars($Insurance_Company) . '" size="40" maxlength="255">
							</div>

							<div class="cell small-12">
								Insurance Exp. Date <span class="required">*</span>
							</div>
							<div class="cell small-12">
								<input type="date" name="insuranceexpdate" id="insuranceexpdate" value="' . htmlspecialchars($Insurance_Exp_Date) . '" maxlength="10" placeholder="mm/dd/yyyy">
							</div>

							<div class="cell small-12">
								<hr>
							</div>

							<div class="cell small-12 padding-bottom">
								<input id="save_additional_info" class="float-center" type="button" value="Next">
							</div>

							<input type="hidden" name="compname" id="compname" value="' . $compname . '">
						</div>
					</div>
				</div>
			</form>';
?>

<script language="JavaScript" type="text/javascript">
	if($('#insuranceexpdate')[0].type != 'date') $('#insuranceexpdate').datepicker();

<?php
	echo "setIndexes('" . $stype . "', '" . $empbefore . "', '" . $stationid . "');";
?>

	function setIndexes(stype, empbefore, stationid) {
		$("#screentype").val(stype);
		$("#empbefore").val(empbefore);
		$("#stationid").val(stationid);
	}

	$("#save_additional_info").click(function() {
		// alert("In save_additional_info");
		var personid = $("#PersonID").val();

		if($("#empbefore").val() > '') {
			var empbefore = $("#empbefore").val();
		}
		else {
			$('#empbefore').focus();
			alert("Have you ever been employed here before is required");
			return false;
		}

 		if($("#screentype").val() > '') {
			var screentype = $("#screentype").val();
		}
		else {
			$('#screentype').focus();
			alert("Status is required");
			return false;
		}

		if($("#cvname").val() > '') {
			var cvname = $("#cvname").val();
		}
		else {
			$('#cvname').focus();
			alert("Contractor/Vendor Name is required");
			return false;
		}

		if($("#stationid").val() > '') {
			var stationid = $("#stationid").val();
		}
		else {
			$('#stationid').focus();
			alert("work location is required");
			return false;
		}

		if($("#position").val() > '') {
			var position = $("#position").val();
		}
		else {
			$('position').focus();
			alert("Position is required");
			return false;
		}

		if($("#employer").val() > '') {
			var employer = $("#employer").val();
		}
		else {
			$('employer').focus();
			alert("Employer is required");
			return false;
		}

		if($("#insurancecompany").val() > '') {
			var insurancecompany = $("#insurancecompany").val();
		}
		else {
			$('insurancecompany').focus();
			alert("Insurance Company is required");
			return false;
		}

		if($("#insuranceexpdate").val() > '') {
			var insuranceexpdate = $("#insuranceexpdate").val();
		}
		else {
			$('insuranceexpdate').focus();
			alert("Insurance Exp. Date is required");
			return false;
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_additional_info.php",
			data: { personid: personid, screentype: screentype, cvname: cvname, stationid: stationid, position: position, employer: employer, empbefore: empbefore, insurancecompany: insurancecompany, insuranceexpdate: insuranceexpdate },
			datatype: "JSON",
			success: function(valor) {
				// alert('Valor: '+valor);
				var obj2 = $.parseJSON(valor);

				if(obj2 > '') {
					alert(obj2);
					return false;
				}
				else {
		 			window.location = 'dmv.php?PersonID=' + personid;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});
</script>
