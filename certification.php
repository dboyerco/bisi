<?php
require_once('../pdotriton.php');
$companyname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
$FormAction = "Thanks.php?PersonID=".$PersonID;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="jquery-ui/jquery-ui.js"></script>
	</head>

<body bgcolor="#E4E8E8">

	<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0" width="763">
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
						<font color="#00248E"><?php echo $companyname; ?> Web Application Portal</font>
						<img src="files/horizontal-line.gif" height="3" width="700">
					</h3>
					<br>
				</td>
			</tr>
		</tbody>
	<table>

<FORM METHOD="POST" NAME="ALCATEL">
<?
echo "
<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
<INPUT TYPE=\"hidden\" NAME=\"noemail\" ID=\"noemail\" VALUE=\"$noemail\">
  </FORM>
";
?>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tr>
			<?php
				echo '<td>
					I agree that I have read and accept the Authorization statement below.<br /><br />
					By having BIS prepare a Background Report for you, you are certifying that '.$companyname.' is requesting such information (and intends to use such information) from BIS exclusively for employment/contractor/volunteer purposes. <br /><br />You are also certifying that: (1) A clear and conspicuous disclosure has been made in writing to the Consumer by End-User (in a document that consists solely of the disclosure) stating that a Consumer Report may be obtained for employment/contractor/volunteer purposes; (2) the Consumer has authorized in writing the procurement of the Consumer Report that is being ordered; (3) information from the report to be provided by BIS will not be used in violation of any applicable Federal or State equal employment opportunity law or regulation, or any other applicable law; and (4) if applicable, End-User will comply with the adverse action requirements described in Section 604(b)(3) of the Fair Credit Reporting Act, as well as any other pertinent adverse action requirements. <br /><br />In addition, if the Consumer lives in California or is applying to work in California or works in California, by having BIS prepare a Consumer Report or Investigative Consumer Report, you are also certifying that: (1) you have complied with all disclosure and authorization requirements set forth in California Civil Code 1786.16, (2) you have provided the Consumer a means to check a box to indicate that he or she would like a copy of any report received by you from BIS, (3) you will comply with any adverse requirements set forth under California law (including those identified in Section Cal. Civ. 1786.40) should they become applicable, and (4) you have otherwise met all requirements for obtaining a Consumer Report or Investigative Consumer Report under California law.
				</td>';
			?>
		</tr>
	</table>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center">
				<INPUT TYPE="button" id="agree" VALUE="Agree">
				<INPUT TYPE="button" id="disagree" VALUE="Disagree">
			</td>
		</tr>
	</table>

<script language="JavaScript" type="text/javascript">
	$( "#agree" ).click(function() {
		var personid = document.getElementById("PersonID").value;
		window.location = 'Thanks.php?PersonID='+personid;
	});

	$("#disagree").click(function() {
		location.reload();
	});
</script>
