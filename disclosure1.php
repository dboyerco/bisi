<?php
if(!$testLayout) {
	$etype = $dbo->query("Select Email_Type from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
	$state = $dbo->query("Select State_Addr from App_Address where PersonID = " . $PersonID . " and Current_Address = 'Y';")->fetchColumn();
	$rights = $dbo->query("Select SummaryOfRightsURL from State where Abbrev = '" . $state . "';")->fetchColumn();
}
else {
	$etype = "B";
	$rights = "StateSummaryOfRights/COLORADO%20SUMMARY%20OF%20RIGHTS.pdf";
}

echo '<form method="post" name="ALCATEL">
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
								<span class="sub-heading">DISCLOSURE REGARDING BACKGROUND INVESTIGATION</span>
							</div>

							<div class="cell small-12">
								' . $compname . ' ';

switch($etype) {
	case 'B':
		include('../disclosures/businessneeds.php');
		break;
	case 'C':
		include('../disclosures/clinical.php');
		break;
	case 'D':
		include('../disclosures/default.php');
		break;
	case 'T':
		include('../disclosures/tenant.php');
		break;
	case 'V':
		include('../disclosures/volunteer.php');
		break;
}

echo '					<br /><br />
							</div>

							<div class="cell small-12">
								<span class="sub-heading">ELECTRONIC SIGNATURES</span>
							</div>

							<div class="cell small-12">
								Electronic printed signatures (instead of handwritten signatures) are legal and accepted under the Uniform Commercial Code, as follows: &quot;Any form of writing, stamping, or printing of a name, initials, or mark makes the instrument signed.&quot; I understand by typing my name and initials it acts as an original signature under the UCC sections 1-201:717.<br /><br />
							</div>

							<div class="cell small-12">
								By signing below, I acknowledge that I have read and understand the above Disclosures.<br /><br />
							</div>

							<div class="cell small-8 medium-6">
								Applicant Signature:
							</div>
							<div class="cell small-4 medium-6">
								Date:
							</div>
							<div class="cell small-8 medium-6">
								<input type="text" name="signature" id="signature" maxlength="45">
							</div>
							<div class="cell small-4 medium-6">
								' . date("m/d/Y") . '
							</div>

							<div class="cell small-12">
								<strong>Click <a href="https://proteus.bisi.com/FCRAdocs/Summary%20of%20Rights%202013.pdf" target="blank">HERE</a> to view A Summary of Your Rights Under the FCRA.</strong>
							</div>
							<div class="cell small-12">
								' . ($rights > '' ? '<strong>Click <a href="https://proteus.bisi.com/' . $rights . '" target="blank">HERE</a> to view your States Summary of Rights.</strong>' : '') . '
							</div>

							<div class="cell small-12 padding-bottom">
								<input class="float-center" id="savesign" type="button" value="Next">
							</div>

							<input type="hidden" name="signdate" id="signdate" value="' . $date . '">
							<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
						</form>';
?>

 <script language="JavaScript" type="text/javascript">
	$("#savesign").click(function() {
		var personid = document.getElementById("PersonID").value;
		var signdate = document.getElementById("signdate").value;
		var NYchk = 'N';
		var type = 'Disclosure';

		if(document.getElementById("signature").value > '') {
			var signature = document.getElementById("signature").value;
		}
		else {
			document.ALCATEL.signature.focus();
			alert("Signature is required");
			return;
		}

		var whichsign = "Signature1";

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_signature.php",
			data: {personid: personid, type: type, signature: signature, signdate: signdate, whichsign: whichsign, NYchk: NYchk},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2 > '' ) {
					alert(obj2);
				}
				else {
					window.location = 'disclosure2.php?PersonID='+personid;
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	});
</script>
