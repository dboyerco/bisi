<?php
require_once('../pdotriton.php');
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$etype = $dbo->query("Select Email_Type from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$state = $dbo->query("Select State_Addr from App_Address where PersonID = ".$PersonID." and Current_Address = 'Y';")->fetchColumn();	
$rights = $dbo->query("Select SummaryOfRightsURL from State where Abbrev = '".$state."';")->fetchColumn();
$FormAction = "disclosure2.php?PersonID=".$PersonID;
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
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>ACKNOWLEDGMENT AND AUTHORIZATION REGARDING BACKGROUND INVESTIGATION</strong></font></p> 
<p><font face="Verdana, Arial, Helvetica,sans-serif">
<?php
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


/*	if ($etype == 'T') {
		echo 'I acknowledge receipt of the DISCLOSURE REGARDING BACKGROUND INVESTIGATION and A SUMMARY OF YOUR RIGHTS UNDER THE FAIR CREDIT REPORTING ACT and certify that I have read and understand both of those documents. I hereby authorize the obtaining of "consumer reports" and/or “investigative consumer reports” by '. $compname.' ("the Company")at any time after receipt of this authorization and throughout my occupancy, if applicable. To this end, I hereby authorize, without reservation, any law enforcement agency, administrator, state or federal agency, institution, school or university (public or private), information service bureau, employer, insurance company or other party to furnish any and all background information requested by Background Information Services, Inc., 1800 30th Street, Suite 204, Boulder, Colorado 80301, 800/433-6010, http://www.bisi.com (“the Agency”), another outside organization acting on behalf of the Company, and/or the Company itself. I agree that a facsimile (“fax”) or electronic or photographic copy of this Authorization shall be as valid as the original. I agree that my electronic signature, acknowledgement, acceptance, and/or authorization are the equivalent of my handwritten signature.';
	} else {
		echo 'I acknowledge receipt of the DISCLOSURE REGARDING BACKGROUND INVESTIGATION and A SUMMARY OF YOUR RIGHTS UNDER THE FAIR CREDIT REPORTING ACT and certify that I have read and understand both of those documents.  I hereby authorize the obtaining of "consumer reports" and/or “investigative consumer reports” by '.$compname. '("the Company") at any time after receipt of this authorization and throughout my employment, if applicable. To this end, I hereby authorize, without reservation, any law enforcement agency, administrator, state or federal agency, institution, school or university (public or private), information service bureau, employer, insurance company or other party to furnish any and all background information requested by <b>Background Information Services, Inc., 1800 30th Street, Suite 204, Boulder, Colorado 80301, 800/433-6010, http://www.bisi.com</b> (“the Agency”), another outside organization acting on behalf of the Company, and/or the Company itself.  I agree that a facsimile (“fax”) or electronic or photographic copy of this Authorization shall be as valid as the original.I agree that my electronic signature, acknowledgement, acceptance, and/or authorization are the equivalent of my handwritten signature.';
	}
*/	
?>	
<br /><br /> 
<b>California applicants and employees only:</b>  By signing below, you also acknowledge receipt of the NOTICE REGARDING BACKGROUND INVESTIGATION PURSUANT TO CALIFORNIA LAW. <br /><br />

<b>Massachusetts and New Jersey applicants and employees only:</b>  You have the right to inspect and promptly receive a copy of any investigative consumer report requested by the Company by contacting the consumer reporting agency identified above directly.<br /><br />

<b>Minnesota applicants and employees only:</b>  You have the right, upon written request to Agency, to receive a complete and accurate disclosure of the nature and scope of any consumer report.  Agency must make this disclosure within five days of receipt of your request or of Company’s request for the report, whichever is later.  Please check this box if you would like to receive a copy of a consumer report if one is obtained by the Company.<br /><br />

<b>New York applicants and employees only:</b>  You have the right to inspect and receive a copy of any investigative consumer report requested by the Company by contacting the consumer reporting agency identified above directly.  By signing below, you also acknowledge receipt of Article 23-A of the New York Correction Law. Click <a href="../docs/New York Article 23-A.pdf" target="blank">HERE</a> to view information regarding NY Article 23a.  <br /><br />

<b>Oklahoma applicants and employees only:</b>  Please check this box if you would like to receive a copy of a consumer report if one is obtained by the Company.<br /><br />  

<b>State of Washington applicants and employees only:</b>  You have the right to receive a complete and accurate disclosure of the nature and scope of any investigative consumer report as well as a written summary of your rights and remedies under Washington law. <br /><br />
Please indicate if you are a resident of the following states and want to receive a copy of your consumer report.<br />
<input type="checkbox" name="NYchk" id="NYchk">&nbsp;MN, OK, CA APPLICANTS: Check box to receive a copy of any investigative consumer report. <br /><br/>
</p>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> ELECTRONIC SIGNATURES</strong></font></p>
 <p><font face="Verdana, Arial, Helvetica, sans-serif"><strong> </strong>
Electronic printed signatures (instead of handwritten signatures) are
  legal and accepted under the Uniform Commercial Code, as follows: &quot;Any 
  form of writing, stamping, or printing of a name, initials, or mark makes the 
  instrument signed.&quot; I understand by typing my name and initials it acts 
  as an original signature under the UCC sections 1-201:717. </font></p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif">Applicant Signature: 
  <input name="signature2" id="signature2" type="text" size="30" maxlength="45"> &nbsp;</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif">Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?
	$date = date("m/d/Y");
	echo '<label for="signdate2">'.$date.'</label>';
?>	

  </font></p>
	<div name="overlay" id="overlay" style="visibility: hidden; width:300px;margin: auto auto;background-color:White;border:5px solid #696969; border-radius:20px; position:absolute;top:75%;left:25%;padding:5px;text-align:center;">Processing data. Please Wait....<br />It should take less than a minute. <br /></div>
 <p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif"> <strong>Click <a href="https://proteus.bisi.com/FCRAdocs/Summary of Rights 2013.pdf" target="blank">HERE
</a> to view A Summary of Your Rights Under the FCRA.</strong></font></p>
<?php
	if ($rights > '') {
echo '<p><font face="Verdana, Arial, Helvetica, sans-serif"> <strong>Click <a href="https://proteus.bisi.com/'.$rights.'" target="blank">HERE
</a> to view your States Summary of Rights.</strong></font></p>';
	}
?>

				</td>
			</tr>
		</tbody>
	</table>
<table align="center">
	<tr>
    	<td>
    		<p>
    			<br><INPUT TYPE="button" name="savesign" id="savesign" VALUE="Submit Data to BISI" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
    		</p>
    	</td>
  	</tr>
</table>
<?
echo "
<INPUT TYPE=\"hidden\" NAME=\"signdate2\" ID=\"signdate2\" value=\"$date\">
<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
  </FORM>
";
?>
<script language="JavaScript" type="text/javascript">
	$( "#savesign" ).click(function() {
		var personid = document.getElementById("PersonID").value;
		var signdate = document.getElementById("signdate2").value;
		var type = 'Authorization';

		if (document.getElementById("NYchk").checked == true) {
			var NYchk = 'Y';
		} else {
			var NYchk = 'N';
		}		
		if (document.getElementById("signature2").value > '') {
			var signature = document.getElementById("signature2").value;
		} else {		
			document.ALCATEL.signature.focus();
			alert("Signature is required");
			return;
		}	
		var whichsign = "Signature2";
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_signature.php", 
			data: {personid: personid, type: type, signature: signature, signdate: signdate, whichsign: whichsign, NYchk: NYchk},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					el = document.getElementById("savesign");
					eldiv = document.getElementById("overlay");
					el.style.visibility = "hidden";
					eldiv.style.visibility = "visible";
//					window.location = 'cardinfo.php?PersonID='+personid;
					window.location = 'Thanks.php?PersonID='+personid;
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
</script>
