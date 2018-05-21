<?php
	require_once('../pdotriton.php');
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$releasefnd = $dbo->query("Select count(*) from App_Uploads where PersonID = ".$PersonID." and UploadType = 'Disclosure Authorization Parent';")->fetchColumn();	
	$FormAction = "Thanks.php?PersonID=".$PersonID;
#	$FormAction = "disclosure1.php?PersonID=".$PersonID;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>


<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable --><meta
http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="files/default.css" type="text/css" rel="stylesheet">
<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />		
<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script language="JavaScript" type="text/javascript">
		$(document).ready(function() {
			if (document.getElementById("releasefnd").value > 0) {
		   		el = document.getElementById("submitid");
				el.style.visibility = "visible";
			}	
		});	
	</script>	

</head>

<body bgcolor="#E4E8E8">
<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  		<tbody>
  			<tr>
    			<td></td>
    			<td class="submenu" height="27" width="763">&nbsp;</td>
  			</tr>
		</tbody>
	</table>
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tbody>
			<tr>
				<td>
					<h3 align="left">
						<font color="#00248E"><?php echo $compname; ?> Web Application Portal</font> 
						<img src="files/horizontal-line.gif" height="3" width="700">
					</h3>
					<br>
				</td>			
			</tr>
		</tbody>
	<table>	
	
<?php 
echo "<FORM METHOD=\"GET\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
?>
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tbody>
			<tr>
				<td>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Parent's Release Form </strong></font></p> 
<p><font face="Verdana, Arial, Helvetica,sans-serif"><br /><br />
<p><font size="2" face="Verdana, Arial, Helvetica,sans-serif"><br /><br />
Please download and complete the Disclosure Authorization form with your parent or guardians signature. Your background screening will not be processed until this form is completed and returned to BISI promptly.</p>
<br /><br />
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Clicking on the "Download Form" button will open the form in a separate tab. Print the form. 
Complete and return to BISI at service@bisi.com or fax to 303-442-1004 or <a href='#openPhotoDialog'>Upload the Document.</a></strong></font></p><br />
<p><font size="3" color="red" face="Verdana, Arial, Helvetica, sans-serif"><strong>Return to this page once you have successfully downloaded the form and click "Next" to complete your background screening.</strong></font></p>
<br />

 <p><br /><INPUT TYPE="button" name="dlform" id="dlform" VALUE="Download Form" onclick="return downloadForm()" style="border-radius:5px; padding: 5px 24px;">
<br /><br />
Fax: 303-442-1004<br />
Email: service@bisi.com<br />
<br />
If you have any issues in downloading this form please contact BISI at service@bisi.com or call 303-442-3960.<br />
<br /><br />
Thank you 
<br /><br /> </p>
<INPUT TYPE="submit" name="submitid" id="submitid" VALUE="Next" style="visibility:hidden; border-radius:5px; padding: 5px 24px;"></p>
				</td>
			</tr>
		</tbody>
	</table>		
<?
	include ('Upload/UploadDialog.php');

echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" id=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" NAME=\"UploadType\" id=\"UploadType\"VALUE=\"Disclosure Authorization Parent\">
	  <INPUT TYPE=\"hidden\" NAME=\"releasefnd\" id=\"releasefnd\"VALUE=\"$releasefnd\">
  	  </FORM></body></html>"; 	  
?>
 <script language="JavaScript" type="text/javascript">
	function downloadForm(){
   		el = document.getElementById("submitid");
   		elbutton = document.getElementById("dlform");
		elbutton.style.visibility = "hidden";
		el.style.visibility = "visible";
		window.open('https://proteus.bisi.com/docs/Foothills Park and Recreation District.pdf');
	}	
</script>
<script src="Upload/Upload.js"></script>
