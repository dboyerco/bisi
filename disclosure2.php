<?php
if(!$testLayout) {
	$etype = $dbo->query("Select Email_Type from App_Person where PersonID = " . $PersonID . ";")->fetchColumn();
	$state = $dbo->query("Select State_Addr from App_Address where PersonID = " . $PersonID . " and Current_Address = 'Y';")->fetchColumn();
	$rights = $dbo->query("Select SummaryOfRightsURL from State where Abbrev = '" . $state . "';")->fetchColumn();
	$DOB = $dbo->query("Select Date_of_Birth from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
	$DOB = date("m/d/Y", strtotime($DOB));
	$Date = date("m/d/Y");
	$datediff = strtotime($Date) - strtotime($DOB);
	$days = floor($datediff / (60 * 60 * 24));
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
								<span class="sub-heading">ACKNOWLEDGMENT AND AUTHORIZATION REGARDING BACKGROUND INVESTIGATION</span>
							</div>

							<div class="cell small-12">';

switch ($etype) {
	case 'B':
		include ('../authorizations/businessneeds.php');
		break;
	case 'C':
		include ('../authorizations/clinical.php');
		break;
	case 'D':
		include ('../authorizations/default.php');
		break;
	case 'T':
		include ('../authorizations/tenant.php');
		break;
	case 'V':
		include ('../authorizations/volunteer.php');
		break;
}

echo '					<br /><br />
							</div>

							<div class="cell small-12">
								<strong>California applicants and employees only:</strong> By signing below, you also acknowledge receipt of the NOTICE REGARDING BACKGROUND INVESTIGATION PURSUANT TO CALIFORNIA LAW.<br /><br />
							</div>

							<div class="cell small-12">
								<strong>Massachusetts and New Jersey applicants and employees only:</strong> You have the right to inspect and promptly receive a copy of any investigative consumer report requested by the Company by contacting the consumer reporting agency identified above directly.<br /><br />
							</div>

							<div class="cell small-12">
								<strong>Minnesota applicants and employees only:</strong> You have the right, upon written request to Agency, to receive a complete and accurate disclosure of the nature and scope of any consumer report.  Agency must make this disclosure within five days of receipt of your request or of Company’s request for the report, whichever is later.  Please check this box if you would like to receive a copy of a consumer report if one is obtained by the Company.<br /><br />
							</div>

							<div class="cell small-12">
								<strong>New York applicants and employees only:</strong> You have the right to inspect and receive a copy of any investigative consumer report requested by the Company by contacting the consumer reporting agency identified above directly.  By signing below, you also acknowledge receipt of Article 23-A of the New York Correction Law. Click <a href="../docs/New York Article 23-A.pdf" target="blank">HERE</a> to view information regarding NY Article 23a.<br /><br />
							</div>

							<div class="cell small-12">
								<strong>Oklahoma applicants and employees only:</strong> Please check this box if you would like to receive a copy of a consumer report if one is obtained by the Company.<br /><br />
							</div>

							<div class="cell small-12">
								<strong>State of Washington applicants and employees only:</strong> You have the right to receive a complete and accurate disclosure of the nature and scope of any investigative consumer report as well as a written summary of your rights and remedies under Washington law.<br /><br />
								Please indicate if you are a resident of the following states and want to receive a copy of your consumer report.<br />
								<input type="checkbox" name="NYchk" id="NYchk">&nbsp;MN, OK, CA APPLICANTS: Check box to receive a copy of any investigative consumer report.<br /><br/>
							</div>

							<div class="cell small-12">
								<span class="sub-heading">ELECTRONIC SIGNATURES</span>
							</div>

							<div class="cell small-12">
								Electronic printed signatures (instead of handwritten signatures) are legal and accepted under the Uniform Commercial Code, as follows: &quot;Any form of writing, stamping, or printing of a name, initials, or mark makes the instrument signed.&quot; I understand by typing my name and initials it acts as an original signature under the UCC sections 1-201:717.<br /><br />
							</div>

							<div class="cell small-8 medium-6">
								Applicant Signature:
							</div>
							<div class="cell small-4 medium-6">
								Date:
							</div>
							<div class="cell small-8 medium-6">
								<input type="text" name="signature2" id="signature2" maxlength="45">
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

							<input type="hidden" name="signdate2" id="signdate2" value="' . $Date . '">
							<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
							<input type="hidden" name="cd" id="cd" value="' . $CD . '">
							<input type="hidden" name="days" id="days" value="' . $days .'">

							<div name="overlay" id="overlay" style="visibility: hidden; width:300px;margin: auto auto;background-color:White;border:5px solid #696969; border-radius:20px; position:absolute;top:75%;left:25%;padding:5px;text-align:center;">Processing data. Please Wait....<br />It should take less than a minute.<br />
							</div>
						</div>
					</div>
				</div>
			</form>

			<script>
				var nextPage = "' . $nextPage . '";
				var pageUnder18 = "' . $pageUnder18 . '";
				var pageThanks = "' . $pageThanks . '";
				var nodays = ' . $days . ';
			</script>';
?>

			<script language="JavaScript" type="text/javascript">
				$("#savesign").click(function() {
					var personid = $("#PersonID").val();
					var signdate = $("#signdate2").val();
					var type = 'Authorization';

					if($("#NYchk").checked == true) {
						var NYchk = 'Y';
					}
					else {
						var NYchk = 'N';
					}

					if($("#signature2").val() > '') {
						var signature = $("#signature2").val();
					}
					else {
						document.ALCATEL.signature.focus();
						alert("Signature is required");
						return;
					}

					var whichsign = "Signature2";

					$.ajax({
						type: "POST",
			url: "../App_Ajax_New/ajax_save_signature.php",
			data: {personid: personid, type: type, signature: signature, signdate: signdate, whichsign: whichsign, NYchk: NYchk},
						datatype: "JSON",
						success: function(valor) {
							var obj2 = $.parseJSON(valor);

							if(obj2 > '' ) {
								alert(obj2);
							}
							else {
								el = $("#savesign");
								eldiv = $("#overlay");

								el.style.visibility = "hidden";
								eldiv.style.visibility = "visible";

								if(nodays < 6570) {
									window.location = 'index.php?pg=' + pageUnder18 + '&PersonID=' + personid + '&CD=' + cd;
								}
								else {
									window.location = 'index.php?pg=' + pageThanks + '&PersonID=' + personid + '&CD=' + cd;
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
			</script>
