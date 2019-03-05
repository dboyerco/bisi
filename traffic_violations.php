<?
	require_once('../pdotriton.php');
	$days = 0;
	$YR = 0;
	$MO = 0;
	$DY = 0;
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();  
	$maxRecID = $dbo->query("Select max(RecID) from App_Traffic_Violations where PersonID = ".$PersonID.";")->fetchColumn();	
	$DLDSRC = $dbo->query("Select Driver_License_D_S_R_C from App_DL_D_S_R_C_Info where PersonID = ".$PersonID.";")->fetchColumn();
	$DLDSRCI = $dbo->query("Select Driver_License_D_S_R_C_Info from App_DL_D_S_R_C_Info where PersonID = ".$PersonID.";")->fetchColumn();
	if ($noemail == 'Y') {
		$FormAction = "certification.php?PersonID=".$PersonID;
	} else {
		$FormAction = "disclosure1.php?PersonID=".$PersonID;
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />		
		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<style type="text/css">
			.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
		</style>
		<script language="JavaScript" type="text/javascript">
			$(document).ready(function() {
				turnonquestions();
			});	
			function turnonquestions() {
				if ($('#DLDSRC').val() == 'Yes') {
					document.getElementById("Driver_License_D_S_R_C").value = 'Yes';
					turnonDSRCInfo();
				}	

				if ($("#RecID").val() > 0) {
					document.getElementById("violation").value = 'Yes';
				}	
				if (document.getElementById("violation").value == 'Yes') {
					eldiv = document.getElementById("overlay");
					eldiv.style.visibility = "visible";
					eldiv2 = document.getElementById("overlay2");
					eldiv2.style.visibility = "hidden";
				} else {	
					eldiv = document.getElementById("overlay");
					eldiv.style.visibility = "hidden";
					eldiv2 = document.getElementById("overlay2");
					eldiv2.style.visibility = "visible";
				};
				return true;
			}
			function turnonDSRCInfo() {
				if (document.getElementById("Driver_License_D_S_R_C").value == 'Yes') {
					var dldsrci = $('#DLDSRCI').val();
					var str = '<table bgcolor="#E4E8E8" width="763"><tr><td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Please explain reason and date of occurrence. Be as specific as possible.</strong></font></td></tr><tr><td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><textarea name="Driver_License_D_S_R_C_Info" id="Driver_License_D_S_R_C_Info" rows="5" cols="60" maxlength="256" onblur="adddsrcinfo()">'+dldsrci+'</textarea></font></td></tr><tr><td>&nbsp;</td></tr></table>';
					document.getElementById("DSRCInfo").innerHTML=str;
					eldiv = document.getElementById("DSRCInfo");
					eldiv.style.visibility = "visible";
				} else {	
					adddsrcinfo();
					document.getElementById("DSRCInfo").innerHTML='';
					eldiv = document.getElementById("DSRCInfo");
					eldiv.style.visibility = "hidden";
				};
				return true;
			}
	
			function convictedquestion() {
				if (document.getElementById("Convicted_Paid_Fine").value == 'Yes') {
					var str = '<table bgcolor="#E4E8E8" width="763"><tr><td width="225"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">List your Penalty and Sentence.<font color="#FF0000">*</font></font></td><td><textarea name="Convicted" id="Convicted" rows="5" cols="60" maxlength="256"></textarea></td></tr></table>';
					document.getElementById("divconvicted").innerHTML=str;
					eldiv = document.getElementById("divconvicted");
					eldiv.style.visibility = "visible";
				} else {	
					document.getElementById("divconvicted").innerHTML='';
					eldiv = document.getElementById("divconvicted");
					eldiv.style.visibility = "hidden";
				};
				return true;
			}
			function dlgconvictedquestion() {
				if (document.getElementById("dlgConvicted_Paid_Fine").value == 'Yes') {
					var Convicted_Info = document.getElementById("dlghldconvictedinfo").value;
//					var str = '<tr><td width="225"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">List your Penalty and Sentence.<font color="#FF0000">*</font></font></td><td><textarea name="dlgConvicted" id="dlgConvicted" rows="5" cols="60" maxlength="256"></textarea></td></tr>';
					var str = '<table width="100%" border="3" bgcolor="#eeeeee"><tr><td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">List your Penalty and Sentence.<font color="#FF0000">*</font></font></td><td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><textarea name="dlgConvicted" id="dlgConvicted" rows="5" cols="60" maxlength="256">'+Convicted_Info+'</textarea></font></td></tr></table>';
					document.getElementById("dlgdivconvicted").innerHTML=str;
					eldiv = document.getElementById("dlgdivconvicted");
					eldiv.style.visibility = "visible";
				} else {	
					document.getElementById("dlgdivconvicted").innerHTML='';
					eldiv = document.getElementById("dlgdivconvicted");
					eldiv.style.visibility = "hidden";
				};
				return true;
			}
			
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
	</table>				
<?php
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
echo '<table bgcolor="#E4E8E8" width="763">
		<tr>
			<td>
				<p><font size="2"><strong>Traffic Violations</strong> </font></p>
			</td>
		</tr>
	</table>';		
echo '<table bgcolor="#E4E8E8" width="763">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
	<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Have you ever had any driver\'s license denied, suspended, revoked or cancelled by any issuing state agency?</b><font color="#FF0000">*</font></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
		<select name="Driver_License_D_S_R_C" id="Driver_License_D_S_R_C" onchange="turnonDSRCInfo();">
			<option value="">Select Option</option>
			<option value="No">No</OPTION>
			<option value="Yes">Yes</option>
		</select>
		</font>
	</td>
  </tr>  
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  
</table>
<div name="DSRCInfo" id="DSRCInfo" style="visibility: hidden"></div>
<table bgcolor="#E4E8E8" width="763">	
	<tr> 
		<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Have you ever had a traffic violation/conviction within the last 3 years (other than parking violations)?</b><font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
			<select name="violation" id="violation" onchange="turnonquestions();">
				<option value="">Select Option</option>
				<option value="No">No</OPTION>
				<option value="Yes">Yes</option>
			</select>
			</font>
		</td>
	</tr>  
	<tr> 
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>  
</table>

<div name="overlay2" id="overlay2" style="visibility: visible">
	<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
	<div align="center">
		<INPUT TYPE="submit" VALUE="Next" style="font-size:medium; color:green;">
	</div>
</div>';	

echo '<div name="overlay" id="overlay" style="visibility: hidden">';
	
#	$maxRecID = $dbo->query("Select max(RecID) from App_Traffic_Violations where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxRecID > 0) { 
		$selectaccident="select * from App_Traffic_Violations where PersonID = :PersonID;";
			
		$accident_result = $dbo->prepare($selectaccident);
		$accident_result->bindValue(':PersonID', $PersonID);
		$accident_result->execute();
		while($row = $accident_result->fetch(PDO::FETCH_BOTH)) {		
			if ($row["Violation_Date"] == '1900-01-01') {
				$violationdate = '';
			} else {
				$violationdate = date("m/d/Y", strtotime($row["Violation_Date"]));
			}
			echo '<table width="763" bgcolor="#E4E8E8">
			<tr valign="top"> 
				<td width="5%"><font size="1">Date:</font></td>
				<td>	
					<font size="1">'.htmlspecialchars($violationdate).'</font>
				</td>
			</tr>	
			<tr valign="top"> 
				<td width="5%"><font size="1">Info:&nbsp;</font></td>
				<td width="30%">
					<font size="1">'.htmlspecialchars($row["Violation_Info"]).'</font>
				</td>
				<td width="5%"><font size="1">State:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["Violation_State"]).'</font>
				</td>
				<td width="5%"><font size="1">Commercial Vehicle:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["Commercial_Vehicle"]).'</font>
				</td>
				<td width="5%"><font size="1">Convicted:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["Convicted_Paid_Fine"]).'</font>
				</td>
				<td width="5%"><font size="1">Info:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["Convicted_Info"]).'</font>
				</td>';

		echo ' 
			<td align="center">
				<a http="#" onclick="updatetraffic_violation('.$row["RecID"].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Employment" title="Edit Employment"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deletetraffic_violation('.$row["RecID"].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Employment" title="Delete Employment"/></a>
			</td>
			<td>&nbsp;</td>			
		</tr>';

		echo '</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>';
		}
		if ($days > 0){
			$YR = floor($days / 365);
			$MO = floor(($days - (floor($days / 365) * 365)) / 30);
			$DY = $days - (($YR * 365) + ($MO * 30));
		} else {
			$YR = 0;
			$MO = 0;
			$DY = 0;
		}	
		
	}	
	
echo '<fieldset><legend><strong>Add Traffic Violations/Convictions Information (last 3 years from date of application)</strong></legend>
		<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td><font color="000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">You have entered '.$YR.' years '.$MO.' months '.$DY.' days</font></td>
		</tr>
  			<tr> 
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Date<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="Violation_Date" id="Violation_Date" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      			</font>
      			</td>
  			</tr>
			<tr valign="top"> 
			    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Type of Violation<font color="#FF0000">*</font></font></td>
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      				<textarea name="Violation_Info" id="Violation_Info" rows="5" cols="60" maxlength="256"></textarea>
	 			 	</font>
	  			</td>
  			</tr>
			<tr> 
			    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">State<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      				<select name="Violation_State" id="Violation_State">
						<option value="">Select a State</option>
						<option value="">-Other-</option>';
						$sql = "Select Name, Abbrev from State order by Name";
						$state_result = $dbo->prepare($sql);
						$state_result->execute();
						while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
							echo "<option value=".$rows[1].">".$rows[0]."</option>";
						}		
					echo '</select></span>
				</td>
  			</tr>
  			<tr valign="top"> 
			    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Commercial Vehicle?<font color="#FF0000">*</font></font></td>
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
    				<select name="Commercial_Vehicle" id="Commercial_Vehicle">
        				<option value="">Select Option</option>
        				<option VALUE="No">No</OPTION>
        				<option value="Yes">Yes</option>
      				</select>
      				</font>
      			</td>
  			</tr>
  			<tr valign="top"> 
			    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Convicted(Yes or No)?<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<select name="Convicted_Paid_Fine" id="Convicted_Paid_Fine" onchange="convictedquestion();">
						<option value="">Select Option</option>                             
						<option VALUE="No">No</OPTION>
						<option value="Yes">Yes</option>
					</select>
					</font>
				</td>
  			</tr>
		</table>';
		echo '<div name="divconvicted" id="divconvicted" style="visibility: hidden"></div>';
  		echo '<table width="100%" bgcolor="#E4E8E8">	
		  		<tr> 
    				<td><font size="1">&nbsp;</font></td>
    				<td><font size="1">&nbsp;</font></td>
  				</tr>
			</table>';

		echo '<table width="100%" bgcolor="#E4E8E8">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
					<INPUT TYPE="button" id="add_new_traffic_violation" VALUE="Save Traffic Violation" style="font-size:medium; color:green;">
				</td>
			</tr>
		</table>';
echo '</fieldset>';

	echo '<table width="763">
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center">
				 <INPUT TYPE="submit" VALUE="Next" style="font-size:medium; color:green;">
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>';	
echo '<div>';	

echo "<INPUT TYPE=\"hidden\" name=\"PersonID\" id=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" name=\"RecID\" id=\"RecID\" VALUE=\"$maxRecID\">
	  <INPUT TYPE=\"hidden\" name=\"Package\" id=\"Package\" VALUE=\"$Package\">
	  <INPUT TYPE=\"hidden\" name=\"DLDSRC\" id=\"DLDSRC\" VALUE=\"$DLDSRC\">
	  <INPUT TYPE=\"hidden\" name=\"DLDSRCI\" id=\"DLDSRCI\" VALUE=\"$DLDSRCI\">
	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";

?>
	<div name="Traffic_Violation_dialog" id="Traffic_Violation_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgrecid" id="dlgrecid">
			<input type="hidden" name="dlghldconvictedinfo" id="dlghldconvictedinfo">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
		  		<tr valign="top"> 
	    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Violation Date<font color="#FF0000">*</font></font></td>
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgViolation_Date" id="dlgViolation_Date" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      					</font>
      				</td>
  				</tr>
				<tr> 
			    	<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Violation Info<font color="#FF0000">*</font></font></td>
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      					<textarea name="dlgViolation_Info" id="dlgViolation_Info" rows="5" cols="75" maxlength="256"></textarea>
	  					</font>
	  				</td>
  				</tr>
  				<tr valign="top"> 
				    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">State<font color="#FF0000">*</font></font></td>
	    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
    	  				<select name="dlgViolation_State" id="dlgViolation_State">
							<option value="">Select a State</option>
							<option value="">-Other-</option>
							<?php
								$sql = "Select Name, Abbrev from State order by Name";
								$state_result = $dbo->prepare($sql);
								$state_result->execute();
								while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
									echo "<option value=".$rows[1].">".$rows[0]."</option>";
								}
							?>			
						</select></span>
					</td>
  				</tr>
  				<tr valign="top"> 
				    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Commercial Vehicle?<font color="#FF0000">*</font></font></td>
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
    					<select name="dlgCommercial_Vehicle" id="dlgCommercial_Vehicle">
        					<option value="">Select Option</option>
        					<option VALUE="No">No</OPTION>
        					<option value="Yes">Yes</option>
      					</select>
      					</font>
      				</td>
  				</tr>
  				<tr> 
				    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Convicted(Yes or No)?<font color="#FF0000">*</font></font></td>
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<select name="dlgConvicted_Paid_Fine" id="dlgConvicted_Paid_Fine" onchange="dlgconvictedquestion();">
							<option value="">Select Option</option>                             
							<option VALUE="No">No</OPTION>
							<option value="Yes">Yes</option>
						</select>
						</font>
					</td>
  				</tr>
  				<tr>
  					<td>
					</td>	
				</tr>	
			</table>	
			<div name="dlgdivconvicted" id="dlgdivconvicted" style="visibility: hidden"></div>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_traffic_violation" VALUE="Save Traffic Violation">
						<INPUT TYPE="button" id="close_traffic_violation" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>

 <script language="JavaScript" type="text/javascript">
 	$( "#Traffic_Violation_dialog" ).dialog({ autoOpen: false });
 	
 	$( "#add_new_traffic_violation" ).click(function() {	
		var personid = document.getElementById("PersonID").value;

		if (document.getElementById("Violation_Date").value > '') {
			if (!isValidDate('Violation_Date')) {
				$('#Violation_Date').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var violation_date = document.getElementById("Violation_Date").value;
			}	
		} else {		
			$('#Violation_Date').focus();
			alert("Violation Date is required");
			return;
		}	

		if (document.getElementById("Violation_Info").value == '') {
			$('#Violation_Info').focus();
			alert("Violation Info is required");
			return;
		} else {		
			var violation_info = document.getElementById("Violation_Info").value;
		}	
			
		if (document.getElementById("Violation_State").value > '') {
			var violation_state = document.getElementById("Violation_State").value;
		} else {		
			$('#Violation_State').focus();
			alert("Violation State is required");
			return;
		}	

		if (document.getElementById("Commercial_Vehicle").value > '') {
			var commercial_vehicle = document.getElementById("Commercial_Vehicle").value;
		} else {		
			$('#Commercial_Vehicle').focus();
			alert("Commercial Vehicle is required");
			return;
		}	

		if (document.getElementById("Convicted_Paid_Fine").value == '') {
			$('#Convicted_Paid_Fine').focus();
			alert("Convicted Paid Fine is required");
			return;
		} else {	
			var convicted_paid_fine = document.getElementById("Convicted_Paid_Fine").value;
		}	
		if (convicted_paid_fine == 'Yes') {
			if (document.getElementById("Convicted").value == '') {
				$('#Convicted').focus();
				alert("Convicted is required");
				return;
			} else {
				var convicted_info = document.getElementById("Convicted").value;
			}
		} else {
			var convicted_info = '';
		}
//		alert("commercial vehicle: "+commercial_vehicle);
//		alert("convicted paid fine: "+convicted_paid_fine);
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_add_traffic_violation.php", 
			data: {personid: personid, violation_date: violation_date, violation_info: violation_info, violation_state: violation_state, commercial_vehicle: commercial_vehicle, convicted_paid_fine: convicted_paid_fine, convicted_info: convicted_info},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
//					alert(obj2);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
//					var RecID = obj2;
					document.getElementById("Violation_Date").value = '';
					document.getElementById("Violation_Info").value = '';
					document.getElementById("Violation_State").value = '';
					document.getElementById("Commercial_Vehicle").value = '';
					document.getElementById("Convicted_Paid_Fine").value = '';
//					document.getElementById("Convicted").value = '';
					location.reload(true);	
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});

	function adddsrcinfo() {
//		alert("In adddsrcinfo");
		var personid = document.getElementById("PersonID").value;
		var Driver_License_D_S_R_C = $('#Driver_License_D_S_R_C').val();
//		alert("Driver_License_D_S_R_C: "+Driver_License_D_S_R_C);
		if ($('#Driver_License_D_S_R_C').val() == 'Yes') {
			if ($('#Driver_License_D_S_R_C_Info').val() == '') {
				$('#Driver_License_D_S_R_C_Info').focus();
				alert('Reason and Date of Occurrence is required');
				return;
			} else {	
				var Driver_License_D_S_R_C_Info = $('#Driver_License_D_S_R_C_Info').val();
			}
		} else {
			var Driver_License_D_S_R_C_Info = '';
//			document.getElementById('Driver_License_D_S_R_C_Info').value = '';
		}
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_update_driver_license_d_s_r_c.php", 
			data: {personid: personid, Driver_License_D_S_R_C: Driver_License_D_S_R_C, Driver_License_D_S_R_C_Info: Driver_License_D_S_R_C_Info},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				}	
				location.reload(true);	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				location.reload(true);	
//				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
	
	function updatetraffic_violation(recid) {
// 		alert ("In updatetraffic_violation - "+recid);
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_traffic_violation.php", 
			data: {personid: personid, recid: recid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var RecID = obj2[i].RecID;
						var fd = obj2[i].Violation_Date;
						var Violation_Date = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var Violation_Info = obj2[i].Violation_Info;
						var Violation_State = obj2[i].Violation_State;
						var Commercial_Vehicle = obj2[i].Commercial_Vehicle;
						var Convicted_Paid_Fine = obj2[i].Convicted_Paid_Fine;
						var Convicted_Info = obj2[i].Convicted_Info;
			    	}
					document.getElementById("dlgrecid").value = RecID;
					document.getElementById("dlgViolation_Date").value = Violation_Date;
					document.getElementById("dlgViolation_Info").value = Violation_Info;
					document.getElementById("dlgViolation_State").value = Violation_State;
					document.getElementById("dlgCommercial_Vehicle").value = Commercial_Vehicle;
					document.getElementById("dlgConvicted_Paid_Fine").value = Convicted_Paid_Fine;
					if (document.getElementById("dlgConvicted_Paid_Fine").value == 'Yes') {
						document.getElementById("dlghldconvictedinfo").value = Convicted_Info;
						var str = '<table width="100%" border="3" bgcolor="#eeeeee"><tr><td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">List your Penalty and Sentence.<font color="#FF0000">*</font></font></td><td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><textarea name="dlgConvicted" id="dlgConvicted" rows="5" cols="60" maxlength="256">'+Convicted_Info+'</textarea></font></td></tr></table>';
						document.getElementById("dlgdivconvicted").innerHTML=str;
						eldiv = document.getElementById("dlgdivconvicted");
						eldiv.style.visibility = "visible";
					} else {	
						document.getElementById("dlghldconvictedinfo").value = '';
						document.getElementById("dlgdivconvicted").innerHTML='';
						eldiv = document.getElementById("dlgdivconvicted");
						eldiv.style.visibility = "hidden";
					};
					$( "#Traffic_Violation_dialog" ).dialog( "option", "title", "Edit Traffic violation");	
					$( "#Traffic_Violation_dialog" ).dialog( "option", "modal", true );
					$( "#Traffic_Violation_dialog" ).dialog( "option", "width", 700 );
					$( "#Traffic_Violation_dialog" ).dialog( "open" );
				} else {
					alert('No Traffic Violation Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
	

 	$("#save_traffic_violation").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var recid = document.getElementById("dlgrecid").value;

		if (document.getElementById("dlgViolation_Date").value > '') {
			if (!isValidDate('dlgViolation_Date')) {
				$('#dlgViolation_Date').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var violation_date = document.getElementById("dlgViolation_Date").value;
			}	
		} else {		
			$('#dlgViolation_Date').focus();
			alert("Violation Date is required");
			return;
		}	

		if (document.getElementById("dlgViolation_Info").value == '') {
			$('#dlgViolation_Info').focus();
			alert("Violation Info is required");
			return;
		} else {		
			var violation_info = document.getElementById("dlgViolation_Info").value;
		}	
			
		if (document.getElementById("dlgViolation_State").value > '') {
			var violation_state = document.getElementById("dlgViolation_State").value;
		} else {		
			$('#dlgViolation_State').focus();
			alert("Violation State is required");
			return;
		}	

		if (document.getElementById("dlgCommercial_Vehicle").value > '') {
			var commercial_vehicle = document.getElementById("dlgCommercial_Vehicle").value;
		} else {		
			$('#dlgCommercial_Vehicle').focus();
			alert("Commercial Vehicle is required");
			return;
		}	

		if (document.getElementById("dlgConvicted_Paid_Fine").value == '') {
			$('#dlgConvicted_Paid_Fine').focus();
			alert("Convicted Paid Fine is required");
			return;
		} else {	
			var convicted_paid_fine = document.getElementById("dlgConvicted_Paid_Fine").value;
		}	
		if (convicted_paid_fine == 'Yes') {
			if (document.getElementById("dlgConvicted").value == '') {
				$('#dlgConvicted').focus();
				alert("Convicted is required");
				return;
			} else {
				var convicted_info = document.getElementById("dlgConvicted").value;
			}
		} else {
			var convicted_info = '';
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_save_traffic_violation.php", 
			data: {personid: personid, recid: recid, violation_date: violation_date, violation_info: violation_info, violation_state: violation_state, commercial_vehicle: commercial_vehicle, convicted_paid_fine: convicted_paid_fine, convicted_info: convicted_info},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Traffic_Violation_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deletetraffic_violation(RecID) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this Traffic Violation record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_traffic_violation.php", 
				data: {personid: personid, RecID: RecID},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false; 
					} else {
						location.reload();
						return;
					}
				},	
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				} 					
			});
		}
	}
	$( "#close_traffic_violation" ).click(function() {	
		$( "#Traffic_Violation_dialog" ).dialog( "close" );
	});
 
 </script>
 <script language="JavaScript" type="text/javascript">
 	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {

		var recid = $("#RecID").val();
//		var nodays = $("#days").val();

		if(document.getElementById("accident").value == '') {
				document.getElementById("accident").focus();	
				alert('Please select Yes/No');
				return false;
		}  	
		if (document.getElementById("accident").value == 'Yes') {
			if(recid > 0) {
				return true;
			} else {
				$("#Vehicle_Type").focus();
				alert('You have not entered any accident info');
				return false;
			}
		} else {
			return true;
		}		
	}
</script>
