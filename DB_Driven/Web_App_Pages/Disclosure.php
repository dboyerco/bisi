<?php
require_once('../pdotriton.php');
$PersonID = $_REQUEST['PersonID'];
$page = $_REQUEST['page'];
$page++;

$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Next_Page = $dbo->query("Select Web_App_Name from WebApp_Web_Pages where Company_Name = '".$compname."' and Package_Name = '".$Package."' and Web_Page_Number = ".$page.";")->fetchColumn();	

$FormAction = $Next_Page.".php?PersonID=".$PersonID."&page=".$page;

#$FormAction = "disclosure2.php?PersonID=".$PersonID;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<script type="text/javascript" src="js/autoFormats.js"></script>		
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
	
<FORM METHOD="POST" NAME="ALCATEL">
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tbody>
			<tr>
				<td>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>DISCLOSURE REGARDING BACKGROUND INVESTIGATION</strong></font></p> 
<p><font face="Verdana, Arial, Helvetica,sans-serif"><br />
<?php echo $compname; ?>(“the Company”) may obtain information about you from a consumer reporting agency for employment purposes.  Thus, you may be the subject of a “consumer report” and/or an “investigative consumer report” which may include information about your character, general reputation, personal characteristics, and/or mode of living, and which can involve personal interviews with sources such as your neighbors, friends or associates.  These reports may contain information regarding your criminal history, credit history, motor vehicle records (“driving records”), verification of your education or employment history or other background checks.</p><br /><p> You have the right, upon written request made within a reasonable time after receipt of this notice, to request disclosure of the nature and scope of any investigative consumer report. Please be advised that the nature and scope of the most common form of investigative consumer report obtained with regard to applicants for employment is an investigation into your education and/or employment history conducted by <b>Background Information Services, Inc., 1800 30th Street, Suite 204, Boulder, Colorado 80301, 800/433-6010, http://www.bisi.com</b>, or another outside organization. You should carefully consider whether to exercise your right to request disclosure of the nature and scope of any investigative consumer report.

<br /><br /> </p><p>The Company may disclose the personal information we or BIS has collected from or in relation to you as set out above to any employee of the Company who requires that information in the performance of his/her functions, and then, only for the purpose of the “Background Screening Process,” described above, to any third parties conducting the Background Screening Process, as well as to any other third parties, with your consent, or where such disclosure is required or permitted by applicable law.

<br /><br /> </p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> ELECTRONIC SIGNATURES</strong></font></p>
 <p><font face="Verdana, Arial, Helvetica, sans-serif"><strong> </strong>
Electronic printed signatures (instead of handwritten signatures) are
  legal and accepted under the Uniform Commercial Code, as follows: &quot;Any 
  form of writing, stamping, or printing of a name, initials, or mark makes the 
  instrument signed.&quot; I understand by typing my name and initials it acts 
  as an original signature under the UCC sections 1-201:717. </font></p>
<p>&nbsp;</p>
<p>By signing below, I acknowledge that I have read and understand 
the above Disclosures.<br></p>

<p><font face="Verdana, Arial, Helvetica, sans-serif">Applicant Signature: 
  <input name="signature" id="signature" type="text" size="30" maxlength="45"> &nbsp;</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif">Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?
	$date = date("m/d/Y");
	echo '<label for="signdate">'.$date.'</label>';
?>	
	</font></p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif"> <strong>Click <a href="https://proteus.bisi.com/FCRAdocs/Summary of Rights 2013.pdf" target="blank">HERE
</a> to view A Summary of Your Rights Under the FCRA.</strong></font></p>
				</td>
			</tr>
		</tbody>
	</table>
<table align="center">
  <tr>
    <td align="center"><p><br><INPUT id="savesign" TYPE="button" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;"></p></td>
  <tr>
</table>  
<?
echo "
<INPUT TYPE=\"hidden\" NAME=\"signdate\" ID=\"signdate\" value=\"$date\">
<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
<input type=\"hidden\" name=\"nextpage\" id=\"nextpage\" value=\"$Next_Page\">
<input type=\"hidden\" name=\"pageno\" id=\"pageno\" value=\"$page\">
 </FORM>
";
?>

 <script language="JavaScript" type="text/javascript">
	$( "#savesign" ).click(function() {
//		alert("In savesign");
		var personid = document.getElementById("PersonID").value;
		var signdate = document.getElementById("signdate").value;
		var NYchk = 'N';

		if (document.getElementById("signature").value > '') {
			var signature = document.getElementById("signature").value;
		} else {		
			alert("Signature is required");
			$("#signature").focus();
			return;
		}	
		
		var whichsign = "Signature1";
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_signature.php", 
			data: {personid: personid, signature: signature, signdate: signdate, whichsign: whichsign, NYchk: NYchk},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					var nextpage = document.getElementById("nextpage").value;
					var page = document.getElementById("pageno").value;
		 			window.location = nextpage+'.php?PersonID='+personid+"&page="+page;	
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); 
				alert('Error: '+errorThrown);
			} 					
		}); 	
	});
		
</script>