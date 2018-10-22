<?php
if(!$testLayout) {
	$releasefnd = $dbo->query("Select count(*) from App_Uploads where PersonID = {$PersonID} and UploadType = 'Disclosure Authorization Parent';")->fetchColumn();
}
else {
	$releasefnd = "";
}

$FormAction = "index.php?pg={$pageCardInfo}&PersonID={$PersonID}&CD={$CD}";

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
								<span class="sub-heading">Parent\'s Release Form</span>
							</div>

							<div class="cell small-12">
								Please download and complete the Disclosure Authorization form with your parent or guardians signature. Your background screening will not be processed until this form is completed and returned to BISI promptly.<br /><br />
							</div>

							<div class="cell small-12">
								<strong>Clicking on the "Download Form" button will open the form in a separate tab. Print the form. Complete and return to BISI at service@bisi.com or fax to 303-442-1004 or <a href="#openPhotoDialog">Upload the Document.</a></strong><br /><br />
							</div>

							<div class="cell small-12">
								<span class="required"><strong>Return to this page once you have successfully downloaded the form and click "Next" to complete your background screening.</strong></span><br /><br />
							</div>

							<div class="cell small-12">
								<input type="button" name="dlform" id="dlform" value="Download Form" onclick="downloadForm()"><br /><br />
							</div>

							<div class="cell small-12">
								Fax: 303-442-1004<br />
								Email: service@bisi.com<br /><br />
							</div>

							<div class="cell small-12">
								If you have any issues in downloading this form please contact BISI at service@bisi.com or call 303-442-3960.<br /><br />
								Thank you<br /><br />
							</div>

							<div class="cell small-6">
								<input class="button button-prev float-center" type="button" value="Prev">
							</div>
							<div class="cell small-6">
								<input type="submit" name="submitid" id="submitid" value="Next">
							</div>
						</div>
					</div>';

include('Upload/UploadDialog.php');

echo '		<input type="hidden" name="PersonID" id="PersonID" value=" ' . $PersonID . '">
					<input type="hidden" name="UploadType" id="UploadType" value="Disclosure Authorization Parent">
					<input type="hidden" name="releasefnd" id="releasefnd" value="' . $releasefnd . '">
  			</form>';
?>

<script src="Upload/Upload.js"></script>
<script language="JavaScript" type="text/javascript">
	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function downloadForm() {
		$("#dlform").hide();
		$("#submitid").show();
		window.open('https://proteus.bisi.com/docs/Foothills%20Park%20and%20Recreation%20District.pdf');
	}

	$().ready(function() {
		$("#submitid").hide();

		if($("#releasefnd").val() > 0) {
			$("#submitid").show();
		}
	});
</script>
