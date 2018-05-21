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

<?php
require_once('../pdotriton.php');

$num="";
if (isset($PersonID)) {
	$ipaddress = getenv("REMOTE_ADDR");
	echo "<input type=\"hidden\" name=\"PersonID\" id=\"PersonID\" value=\"$PersonID\">";

	if ($PersonID > '') {
		$compname = $dbo->query("select Company_Name from App_Person where PersonID = '".$PersonID."';")->fetchColumn();
		$selectstmt="Select ScreenType, Contractor_Vendor_Name, StationID, Position, Employer, Worked_Here_Before, Insurance_Company, Insurance_Exp_Date from App_Additional_Info where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		if (!$result2->execute()) {
		} else {	
			$row=$result2->fetch(PDO::FETCH_BOTH);
			$stype=$row['ScreenType'];
			$cvname=$row['Contractor_Vendor_Name'];
			$stationid=$row['StationID'];
			$position=$row['Position'];
			$employer=$row['Employer'];
			$empbefore=$row['Worked_Here_Before'];
			$Insurance_Company=$row['Insurance_Company'];
			if($row['Insurance_Exp_Date'] == '1900-01-01') {
				$Insurance_Exp_Date = '';
			} else {	
				$Insurance_Exp_Date=date("m/d/Y", strtotime($row['Insurance_Exp_Date']));
			}
		}	
	}
}	
$FormAction = "dmv.php?PersonID=".$PersonID;
echo "<body bgcolor=\"#E4E8E8\" onload=\"setindexes('$stype','$empbefore','$stationid')\">";
echo "<FORM METHOD=\"POST\" action=\"$FormAction\" NAME=\"ALCATEL\">";
?>
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
	</table>				
	<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td>
				<p><font face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2">Addition Information</font></strong></font></p>
			</td>
		</tr>
		<tr>
			<td>	
				<p><font face="Verdana, Arial, Helvetica, sans-serif"><strong>Disclaimer: </strong>All information requested on this page 
  					is pertinent and necessary. Not filling out all information can delay the hiring process.</font></p>
			</td>
		</tr>
		</tr>
	</table>		
<?php		
echo '
<table border="0" width="763" bgcolor="#E4E8E8">
	<tr> 
		<td colspan="2"><b><font color="FF0000">*</font><font size="1" color="FF0000" face="Verdana, Arial, Helvetica, sans-serif"> Required Fields To Continue</font></b></td>
	</tr>
	<tr> 
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Have you ever been employed by '.$compname.'?</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<select name="empbefore" id="empbefore">
					<option value="">-</option>
					<option value="No">No</option>
					<option value="Yes">Yes</option>
				</select>
			</font>
		</td>	
	</tr>
	<tr> 
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Status</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				<select name="screentype" id="screentype">
					<option value="">-</option>
					<option value="contractor">Contractor</option>
					<option value="consultant">Consultant</option>
					<option value="vendor">Vendor</option>
				</select>
			</font>
		</td>	
	</tr>
	<tr> 
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Contractor/Vendor Name</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="cvname" id="cvname" autocorrect="off" autocapitalize="words" value="'.htmlspecialchars($cvname).'" size="40" maxlength="100">
			</font>
		</td>	
	</tr>
	<tr> 		
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.$compname.' work location</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
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
			</font>
		</td>
	</tr>
	<tr> 				
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Position</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="position" id="position" value="'.htmlspecialchars($position).'" size="40" maxlength="100">
			</font>
		</td>
	</tr>
	<tr> 				
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Employer</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="employer" id="employer" value="'.htmlspecialchars($employer).'" size="40" maxlength="100">
			</font>
		</td>
	</tr>
	<tr> 				
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Insurance Company</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="insurancecompany" id="insurancecompany" value="'.htmlspecialchars($Insurance_Company).'" size="40" maxlength="255">
			</font>
		</td>
	</tr>
	<tr> 				
		<td><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Insurance Exp. Date</font><font color="#FF0000">*</font></b></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="insuranceexpdate" id="insuranceexpdate" value="'.htmlspecialchars($Insurance_Exp_Date).'" onKeyUp="return frmtdate(this,\'up\')" size="13" maxlength="10" placeholder="mm/dd/yyyy">
			</font>
		</td>
	</tr>
</table>';
echo '<table border="0" width="763" bgcolor="#E4E8E8">		
	<tr>
        <td colspan="2"><hr></td>
    </tr>
</table>';
echo '<table width="763">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="center">
			 <INPUT type="button" id="save_additional_info" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>';	
echo "<input type=\"hidden\" name=\"compname\" id=\"compname\" value=\"$compname\">";
	
?>
</FORM>

<script language="JavaScript" type="text/javascript">
	function setindexes(stype, empbefore, stationid) {
//		alert('In setindexes - '+stype);
		var stype2 = document.getElementById("screentype");
		var empbefore2 = document.getElementById("empbefore");
		var stationid2 = document.getElementById("stationid");
		for(var x=0;x < stype2.length; x++) {
			if (stype == stype2.options[x].value) {
				stype2.selectedIndex = x;
			}
		}
		for(var x=0;x < empbefore2.length; x++) {
			if (empbefore == empbefore2.options[x].value) {
				empbefore2.selectedIndex = x;
			}
		}
		for(var x=0;x < stationid2.length; x++) {
			if (stationid == stationid2.options[x].value) {
				stationid2.selectedIndex = x;
			}
		}
	}
	$( "#save_additional_info" ).click(function() {	
//		alert("In save_additional_info");
		var personid = document.getElementById("PersonID").value;

		if (document.getElementById("empbefore").value > '') {
			var empbefore = document.getElementById("empbefore").value;
		} else {		
			$('#empbefore').focus();
			alert("Have you ever been employed here before is required");
			return false;
		}	

 		if (document.getElementById("screentype").value > '') {
			var screentype = document.getElementById("screentype").value;
		} else {		
			$('#screentype').focus();
			alert("Status is required");
			return false;
		}	
		if (document.getElementById("cvname").value > '') {
			var cvname = document.getElementById("cvname").value;
		} else {	
			$('#cvname').focus();
			alert("Contractor/Vendor Name is required");
			return false;
		}						
		if (document.getElementById("stationid").value > '') {
			var stationid = document.getElementById("stationid").value;
		} else {		
			$('#stationid').focus();
			alert("work location is required");
			return false;
		}	
		if (document.getElementById("position").value > '') {
			var position = document.getElementById("position").value;
		} else {		
			$('position').focus();
			alert("Position is required");
			return false;
		}	
		if (document.getElementById("employer").value > '') {
			var employer = document.getElementById("employer").value;
		} else {		
			$('employer').focus();
			alert("Employer is required");
			return false;
		}	
		if (document.getElementById("insurancecompany").value > '') {
			var insurancecompany = document.getElementById("insurancecompany").value;
		} else {		
			$('insurancecompany').focus();
			alert("Insurance Company is required");
			return false;
		}	
		if (document.getElementById("insuranceexpdate").value > '') {
			var insuranceexpdate = document.getElementById("insuranceexpdate").value;
		} else {		
			$('insuranceexpdate').focus();
			alert("Insurance Exp. Date is required");
			return false;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_additional_info.php", 
			data: {personid: personid, screentype: screentype, cvname: cvname, stationid: stationid, position: position, employer: employer, empbefore: empbefore, insurancecompany: insurancecompany, insuranceexpdate: insuranceexpdate},
			datatype: "JSON",
			success: function(valor) {
//				alert('Valor: '+valor);
				var obj2 = $.parseJSON(valor);
				if (obj2 > '') {
					alert(obj2);
					return false; 
				} else {
		 			window.location ='dmv.php?PersonID='+personid;	
				}	
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); 
				alert('Error: '+errorThrown);
			} 					
		});
	});	
</script>
</body>
</html>

