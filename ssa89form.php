<?php
require_once('../pdotriton.php');
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script language="JavaScript" type="text/javascript">
			$(document).ready(function() {
 //  			$('#submitid').hide();
			});	
	
			$(window).bind('beforeunload', function(){
				return "You are NOT done yet. Do you really want to leave now?";
			});
		</script>	
	</head>
<?

$FormAction = "disclosure1.php?PersonID=".$PersonID;

?>
<body bgcolor="#E4E8E8">
<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  	<tbody><tr>
    <td></td>
    <td class="submenu" height="27" width="763">&nbsp;</td>
  	</tr>
	</tbody></table>
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
	  <tbody><tr>
    	<td width="15"></td>
    	<td width="596">
	 	<h3 align="left">
		<font color="#00248E"><?php echo $compname; ?> Web Application Portal</font> 
		<img src="files/horizontal-line.gif" height="3">
		</h3>
		<br>
<? 
echo "<FORM METHOD=\"GET\" ACTION=\"$FormAction\" NAME=\"ALCATEL\" id=\"ALCATEL\">";
?>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>SSN Verification Form </strong></font></p> 
<p><font face="Verdana, Arial, Helvetica,sans-serif"><br /><br />
<p><font size="2" face="Verdana, Arial, Helvetica,sans-serif"><br /><br />
Please download and complete the SSN Verification form, this form will hold up your background screening, please complete and return to BISI promptly.</p>
<br /><br />
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Clicking on the "Download Form" button will open the form in a seperate tab. Print the form. Complete and return to BISI at service@bisi.com or fax to 303-442-1004.</strong></font></p><br />
<p><font size="3" color="red" face="Verdana, Arial, Helvetica, sans-serif"><strong>Return to this page once you have successfully downloaded the form and click "Next" to complete your background screening.</strong></font></p>
<br />

 <p><br /><INPUT TYPE="button" name="dlform" id="dlform" VALUE="Download Form" onclick="return downloadForm()">
<br /><br />
Fax: 303-442-1004<br />
Email: service@bisi.com<br />
<br />
If you have any issues in downloading this form please contact BISI at service@bisi.com or call 303-442-3960.<br />
<br /><br />
Thank you 
<br /><br /> </p>
<INPUT TYPE="submit" name="submitid" id="submitid" VALUE="Next" style="visibility:hidden"></p>
<?
echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" VALUE=\"$PersonID\">
  	  </FORM>"; 	  
?>
 <script language="JavaScript" type="text/javascript">
	function downloadForm(){
   		el = document.getElementById("submitid");
   		elbutton = document.getElementById("dlform");
		elbutton.style.visibility = "hidden";
		el.style.visibility = "visible";
		$("#ALCATEL").submit(function(){
			$(window).unbind("beforeunload");
		});	
		
		window.open('https://proteus.bisi.com/SSA-89-Docs/ssa-89+instructions.pdf');
	}	
</script>