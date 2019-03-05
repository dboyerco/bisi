<?
	require_once('../pdotriton.php');
	$days = 0;
	$YR = 0;
	$MO = 0;
	$DY = 0;
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();  
	$maxRecID = $dbo->query("Select max(RecID) from App_Accident_Info where PersonID = ".$PersonID.";")->fetchColumn();	
	$FormAction = "Traffic_Violations.php?PersonID=".$PersonID;
	
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
				if (document.getElementById("RecID").value > 0) {
					document.getElementById("accident").value = 'Yes';
				}	
				if (document.getElementById("accident").value == 'Yes') {
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
				
<?	
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
echo '<table bgcolor="#E4E8E8" width="763">
		<tr>
			<td>
				<p><font size="2"><strong>Accident Information</strong> </font></p>
			</td>
		</tr>
	</table>';		
echo '<table bgcolor="#E4E8E8" width="763">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Have you been in an accident within the last 3 years?</b></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
    	<select name="accident" id="accident" onchange="turnonquestions();">
        	<option value="">Select Option (Required)</option>
        	<option value="No">No</OPTION>
        	<option value="Yes">Yes</option>
      	</select>
    </font></td>
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
	
#	$maxRecID = $dbo->query("Select max(RecID) from App_Accident_Info where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxRecID > 0) { 
		$selectaccident="select * from App_Accident_Info where PersonID = :PersonID;";
			
		$accident_result = $dbo->prepare($selectaccident);
		$accident_result->bindValue(':PersonID', $PersonID);
		$accident_result->execute();
		while($row = $accident_result->fetch(PDO::FETCH_BOTH)) {		
			if ($row["Accident_Date"] == '1900-01-01') {
				$accidentdate = '';
			} else {
				$accidentdate = date("m/d/Y", strtotime($row["Accident_Date"]));
			}
			echo '<table width="763" bgcolor="#E4E8E8">
			<tr valign="top"> 
				<td width="5%"><font size="1">Date:</font></td>
				<td>	
					<font size="1">'.htmlspecialchars($accidentdate).'</font>
				</td>
			</tr>	
			<tr valign="top"> 
				<td width="5%"><font size="1">Info:&nbsp;</font></td>
				<td width="30%">
					<font size="1">'.htmlspecialchars($row["Accident_Info"]).'</font>
				</td>
				<td width="5%"><font size="1">Fatalities:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["Fatalities"]).'</font>
				</td>
				<td width="5%"><font size="1">Injuries:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["Injuries"]).'</font>
				</td>
				<td width="5%"><font size="1">HazMat:&nbsp;</font></td>
				<td width="10%">
					<font size="1">'.htmlspecialchars($row["HazMat"]).'</font>
				</td>';

		echo ' 
			<td align="center">
				<a http="#" onclick="updateaccident('.$row["RecID"].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Employment" title="Edit Employment"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deleteaccident('.$row["RecID"].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Employment" title="Delete Employment"/></a>
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
/*		if ($days > 0){
			$YR = floor($days / 365);
			$MO = floor(($days - (floor($days / 365) * 365)) / 30);
			$DY = $days - (($YR * 365) + ($MO * 30));
		} else {
			$YR = 0;
			$MO = 0;
			$DY = 0;
		}	
*/		
	}	
	
echo '<fieldset><legend><strong>Add Accident Info</strong></legend>
		<table width="100%" bgcolor="#E4E8E8">
  			<tr> 
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Accident Date<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="Accident_Date" id="Accident_Date" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      			</font>
      			</td>
  			</tr>
			<tr valign="top"> 
		    	<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nature of Accident<font color="#FF0000">*</font></font></td>
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      				<textarea name="Accident_Info" id="Accident_Info" rows="5" cols="75" maxlength="256"></textarea>
	  				</font>
	  			</td>
  			</tr>
			<tr> 
			    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fatalities?<font color="#FF0000">*</font></font></td>
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      				<input name="Fatalities" id="Fatalities" size="12" maxlength="25">
      				</font>
      			</td>
  			</tr>
  			<tr valign="top"> 
			    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Injuries?<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      				<input name="Injuries" id="Injuries" size="12" maxlength="25">
      				</font>
      			</td>
  			</tr>
  			<tr valign="top"> 
			    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Hazard Material Spill?<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      				<input name="HazMat" id="HazMat" size="12" maxlength="25">
      				</font>
      			</td>
  			</tr>
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
					<INPUT TYPE="button" id="add_new_accident_info" VALUE="Save Accident Info" style="font-size:medium; color:green;">
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
	  <INPUT TYPE=\"hidden\" name=\"Package\" id=\"Package\" VALUE=\"$Package\">";


?>
	<div name="Accident_Info_dialog" id="Accident_Info_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgrecid" id="dlgrecid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
		  		<tr valign="top"> 
	    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Accident Date<font color="#FF0000">*</font></font></td>
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgAccident_Date" id="dlgAccident_Date" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      					</font>
      				</td>
  				</tr>
				<tr> 
			    	<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nature of Accident<font color="#FF0000">*</font></font></td>
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      					<textarea name="dlgAccident_Info" id="dlgAccident_Info" rows="5" cols="75" maxlength="256"></textarea>
	  					</font>
	  				</td>
  				</tr>
  				<tr valign="top"> 
				    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fatalities?<font color="#FF0000">*</font></font></td>
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      					<input name="dlgFatalities" id="dlgFatalities" size="12" maxlength="25">
      					</font>
      				</td>
  				</tr>
  				<tr valign="top"> 
				    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Injuries?<font color="#FF0000">*</font></font></td>
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      					<input name="dlgInjuries" id="dlgInjuries" size="12" maxlength="25">
      					</font>
      				</td>
  				</tr>
  				<tr> 
				    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Hazard Material Spill?<font color="#FF0000">*</font></font></td>
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
      					<input name="dlgHazMat" id="dlgHazMat" size="12" maxlength="25">
      					</font>
      			</td>
  				</tr>
			</table>	
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_accident_info" VALUE="Save Accident Info">
						<INPUT TYPE="button" id="close_accident_info" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#Accident_Info_dialog" ).dialog({ autoOpen: false });
 	
 	$( "#add_new_accident_info" ).click(function() {	
		var personid = document.getElementById("PersonID").value;

		if (document.getElementById("Accident_Date").value > '') {
			if (!isValidDate('Accident_Date')) {
				$('#Accident_Date').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var accident_date = document.getElementById("Accident_Date").value;
			}	
		} else {		
			$('#Accident_Date').focus();
			alert("Accident Date is required");
			return;
		}	

		if (document.getElementById("Accident_Info").value == '') {
			$('#Accident_Info').focus();
			alert("Accident Info is required");
			return;
		} else {		
			var accident_info = document.getElementById("Accident_Info").value;
		}	
			
		if (document.getElementById("Fatalities").value > '') {
			var fatalities = document.getElementById("Fatalities").value;
		} else {		
			$('#Fatalities').focus();
			alert("Fatalities is required");
			return;
		}	

		if (document.getElementById("Injuries").value > '') {
			var injuries = document.getElementById("Injuries").value;
		} else {		
			$('#Injuries').focus();
			alert("Injuries is required");
			return;
		}	

		if (document.getElementById("HazMat").value > '') {
			var hazmat = document.getElementById("HazMat").value;
		} else {		
			$('#HazMat').focus();
			alert("HazMat is required");
			return;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_add_accident_info.php", 
			data: {personid: personid, accident_date: accident_date, accident_info: accident_info, fatalities: fatalities, injuries: injuries, hazmat: hazmat},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
//					alert(obj2);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
//					var RecID = obj2;
					document.getElementById("Accident_Date").value = '';
					document.getElementById("Accident_Info").value = '';
					document.getElementById("Fatalities").value = '';
					document.getElementById("Injuries").value = '';
					document.getElementById("HazMat").value = '';
					location.reload(true);	
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});


 	function updateaccident(recid) {
 	alert ("In updateaccident");
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_accident_info.php", 
			data: {personid: personid, recid: recid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var RecID = obj2[i].RecID;
						var fd = obj2[i].Accident_Date;
						var Accident_Date = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var Accident_Info = obj2[i].Accident_Info;
						var Fatalities = obj2[i].Fatalities;
						var Injuries = obj2[i].Injuries;
						var HazMat = obj2[i].HazMat;
			    	}
					document.getElementById("dlgrecid").value = RecID;
					document.getElementById("dlgAccident_Date").value = Accident_Date;
					document.getElementById("dlgAccident_Info").value = Accident_Info;
					document.getElementById("dlgFatalities").value = Fatalities;
					document.getElementById("dlgInjuries").value = Injuries;
					document.getElementById("dlgHazMat").value = HazMat;
					
					$( "#Accident_Info_dialog" ).dialog( "option", "title", "Edit Accident Info");	
					$( "#Accident_Info_dialog" ).dialog( "option", "modal", true );
					$( "#Accident_Info_dialog" ).dialog( "option", "width", 700 );
					$( "#Accident_Info_dialog" ).dialog( "open" );
				} else {
					alert('No Accident Info Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
	

 	$("#save_accident_info").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var recid = document.getElementById("dlgrecid").value;

		if (document.getElementById("dlgAccident_Date").value > '') {
			if (!isValidDate('dlgAccident_Date')) {
				$('#dlgAccident_Date').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var accident_date = document.getElementById("dlgAccident_Date").value;
			}	
		} else {		
			$('#dlgAccident_Date').focus();
			alert("Accident Date is required");
			return;
		}	

		if (document.getElementById("dlgAccident_Info").value == '') {
			$('#dlgAccident_Info').focus();
			alert("Accident Info is required");
			return;
		} else {		
			var accident_info = document.getElementById("dlgAccident_Info").value;
		}	
			
		if (document.getElementById("dlgFatalities").value > '') {
			var fatalities = document.getElementById("dlgFatalities").value;
		} else {		
			$('#dlgFatalities').focus();
			alert("Fatalities is required");
			return;
		}	

		if (document.getElementById("dlgInjuries").value > '') {
			var injuries = document.getElementById("dlgInjuries").value;
		} else {		
			$('#dlgInjuries').focus();
			alert("Injuries is required");
			return;
		}	

		if (document.getElementById("dlgHazMat").value > '') {
			var hazmat = document.getElementById("dlgHazMat").value;
		} else {		
			$('#dlgHazMat').focus();
			alert("HazMat is required");
			return;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_save_accident_info.php", 
			data: {personid: personid, recid: recid, accident_date: accident_date, accident_info: accident_info, fatalities: fatalities, injuries: injuries, hazmat: hazmat},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Accident_Info_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deleteaccident(RecID) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this accident info record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_accident_info.php", 
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
	$( "#close_accident_info" ).click(function() {	
		$( "#Accident_Info_dialog" ).dialog( "close" );
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
