<?
	require_once('../pdotriton.php');
	$days = 0;
	$YR = 0;
	$MO = 0;
	$DY = 0;
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();  
	$maxRecID = $dbo->query("Select max(RecID) from App_DRV_EXP where PersonID = ".$PersonID.";")->fetchColumn();	
	$FormAction = "Accident_Info.php?PersonID=".$PersonID;
	
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
					document.getElementById("haveyoudriven").value = 'Yes';
				}	
				if (document.getElementById("haveyoudriven").value == 'Yes') {
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
				<p><font size="2"><strong>Driving Experience</strong> </font></p>
			</td>
		</tr>
	</table>';		
echo '<table bgcolor="#E4E8E8" width="763">
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Have you ever driven a commercial vehicle?</b></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
    	<select name="haveyoudriven" id="haveyoudriven" onchange="turnonquestions();">
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
	
	if ($maxRecID > 0) { 
		$selectdrvexp="select * from App_DRV_EXP where PersonID = :PersonID;";
			
		$drvexp_result = $dbo->prepare($selectdrvexp);
		$drvexp_result->bindValue(':PersonID', $PersonID);
		$drvexp_result->execute();
		while($row = $drvexp_result->fetch(PDO::FETCH_BOTH)) {		
		echo '<table width="763" bgcolor="#E4E8E8">
			<tr valign="top"> 
				<td width="15%"><font size="1">Vehicle Type:&nbsp;</font></td>
				<td width="30%">
						<input name="typevichle" id="typevichle" value="'.htmlspecialchars($row["Vehicle_Type"]).'" class="nobord" readonly style="font-size:8px;">
				</td>
				<td width="10%"><font size="1">Approx Miles:&nbsp;</font></td>
				<td width="10%">
					<font size="1"> 
						'.htmlspecialchars($row["Approx_Miles"]).'<br />';
				echo '</font>
				</td>';
				if ($row["Date_Driven_From"] == '1900-01-01') {
					$fromdate = '';
				} else {
					$fromdate = date("m/d/Y", strtotime($row["Date_Driven_From"]));
				}
				if ($row["Date_Driven_To"] == '1900-01-01') {
					$todate = '';
				} else {
					$todate = date("m/d/Y", strtotime($row["Date_Driven_To"]));
				}

		echo ' 
			<td><font size="1">Dates:</font></td>
			<td>	
				<font size="1">
					'.htmlspecialchars($fromdate).' - '.htmlspecialchars($todate).' 
				</font>
			</td>
			<td align="center">
				<a http="#" onclick="updatedrvexp('.$row["RecID"].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Employment" title="Edit Employment"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deletedrvexp('.$row["RecID"].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Employment" title="Delete Employment"/></a>
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
	
echo '<fieldset><legend><strong>Add Driving Experience</strong></legend>
		<table width="100%" bgcolor="#E4E8E8">
		  <tr valign="top"> 
    		<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Type of Vehicle<font color="#FF0000">*</font></font></td>
			    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	    			<select name="Vehicle_Type" id="Vehicle_Type">
        				<option value="">Select Vehicle Type (Required)</option>
        				<option value="Straight Truck">Straight Truck</OPTION>
        				<option value="Tractor Semi-Trailer">Tractor Semi-Trailer</option>
        				<option value="Tractor Twin Trailers">Tractor Twin Trailers</option>
        				<option value="Tractor Tanker">Tractor Tanker</option>
        				<option value="Other">Other (Specify Below)</option>
      				</select>
    			</font>
    			</td>
  			</tr>
			<tr> 
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">If you selected Other, please enter Vehicle type here:</font></td>
    			<td><font size="1" face=\"Verdana, Arial, Helvetica, sans-serif\">
      				<input name="Vehicle_Type_Other" id="Vehicle_Type_Other" size="50" maxlength="50">
      			</font>
      			</td>
  			</tr>
  			<tr valign="top"> 
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Approx miles<font color="#FF0000">*</font></font></td>
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      				<input name="Approx_Miles" id="Approx_Miles" size="12" maxlength="25">
					&nbsp;&nbsp;</font>
				</td>
  			</tr>
  			<tr valign="top"> 
   		 		<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Date Driven From<font color="#FF0000">*</font></font></td>
    			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="Date_Driven_From" id="Date_Driven_From" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      			</font>
      			</td>
  			</tr>
  			<tr> 
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Date Driven To<font color="#FF0000">*</font></font></td>
    			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="Date_Driven_To" id="Date_Driven_To" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
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
					<INPUT TYPE="button" id="add_new_drv_exp" VALUE="Save Driving Experience" style="font-size:medium; color:green;">
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
	<div name="DRV_EXP_dialog" id="DRV_EXP_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgrecid" id="dlgrecid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
		  		<tr valign="top"> 
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Type of Vehicle<font color="#FF0000">*</font></font></td>
				    <td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	    				<select name="dlgVehicle_Type" id="dlgVehicle_Type">
        					<option value="">Select Vehicle Type (Required)</option>
        					<option value="Straight Truck">Straight Truck</OPTION>
        					<option value="Tractor Semi-Trailer">Tractor Semi-Trailer</option>
        					<option value="Tractor Twin Trailers">Tractor Twin Trailers</option>
        					<option value="Tractor Tanker">Tractor Tanker</option>
        					<option value="Other">Other (Specify Below)</option>
      					</select>
    					</font>
    				</td>
  				</tr>
				<tr> 
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">If you selected Other, please enter Vehicle type here:</font></td>
    				<td><font size="1" face=\"Verdana, Arial, Helvetica, sans-serif\">
      					<input name="dlgVehicle_Type_Other" id="dlgVehicle_Type_Other" size="50" maxlength="50">
      				</font>
      				</td>
  				</tr>
  				<tr valign="top"> 
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Approx miles<font color="#FF0000">*</font></font></td>
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      					<input name="dlgApprox_Miles" id="dlgApprox_Miles" size="12" maxlength="25">
							&nbsp;&nbsp;</font>
					</td>
  				</tr>
  				<tr valign="top"> 
   		 			<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Date Driven From<font color="#FF0000">*</font></font></td>
    				<td nowrap><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="dlgDate_Driven_From" id="dlgDate_Driven_From" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      				</font>
      				</td>
  				</tr>
  				<tr> 
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Date Driven To<font color="#FF0000">*</font></font></td>
    				<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgDate_Driven_To" id="dlgDate_Driven_To" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')">
      					</font>
      				</td>
  				</tr>
			</table>	
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_drv_exp" VALUE="Save Driving Experience">
						<INPUT TYPE="button" id="close_drv_exp" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#DRV_EXP_dialog" ).dialog({ autoOpen: false });
 	
 	$( "#add_new_drv_exp" ).click(function() {	
		var personid = document.getElementById("PersonID").value;
 		if (document.getElementById("Vehicle_Type").value == '' && document.getElementById("Vehicle_Type_Other").value == '') {
			$('#Vehicle_Type').focus();
			alert("Vehicle Type or Vehicle Type Other is required");
			return;
		} else {		
			var vehicle_type = document.getElementById("Vehicle_Type").value;
			var vehicle_type_other = document.getElementById("Vehicle_Type_Other").value;
		}	
			
		if (document.getElementById("Vehicle_Type_Other").value > '') {
			var vehicle_type_other = document.getElementById("Vehicle_Type_Other").value;
		} 	
	
		if (document.getElementById("Approx_Miles").value > '') {
			var approx_miles = document.getElementById("Approx_Miles").value;
		} else {		
			document.ALCATEL.Approx_Miles.focus();
			alert("Approx Miles is required");
			return;
		}	
						
		if (document.getElementById("Date_Driven_From").value > '') {
			if (!isValidDate('Date_Driven_From')) {
				$('#Date_Driven_From').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var date_driven_from = document.getElementById("Date_Driven_From").value;
			}	
		} else {		
			$('#Date_Driven_From').focus();
			alert("Date Driven From is required");
			return;
		}	
	
		if (document.getElementById("Date_Driven_To").value > '') {
			if (!isValidDate('Date_Driven_To')) {
				$('#Date_Driven_To').focus();
				alert("Invalid To Date");
				return false;
			} else {					
				var date_driven_to = document.getElementById("Date_Driven_To").value;
			}	
		} else {		
			$('#Date_Driven_To').focus();
			alert("Date Driven To is required");
			return;
		}	

		if (!isValidDiff(date_driven_from,date_driven_to)) {
			$('#Date_Driven_From').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_add_drv_exp.php", 
			data: {personid: personid, vehicle_type: vehicle_type, vehicle_type_other: vehicle_type_other, approx_miles: approx_miles, date_driven_from: date_driven_from, date_driven_to: date_driven_to},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
//					alert(obj2);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
//					var RecID = obj2;
					document.getElementById("Vehicle_Type").value = '';
					document.getElementById("Vehicle_Type_Other").value = '';
					document.getElementById("Approx_Miles").value = '';
					document.getElementById("Date_Driven_From").value = '';
					document.getElementById("Date_Driven_To").value = '';
					location.reload(true);						
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});


 	function updatedrvexp(recid) {
// 	alert ("In updatedrvexp");
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_find_drv_exp.php", 
			data: {personid: personid, recid: recid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var RecID = obj2[i].RecID;
						var Vehicle_Type = obj2[i].Vehicle_Type;
						var Vehicle_Type_Other = obj2[i].Vehicle_Type_Other;
						var Approx_Miles = obj2[i].Approx_Miles;
						var fd = obj2[i].Date_Driven_From;
						var Date_Driven_From = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var td = obj2[i].Date_Driven_To;
						var Date_Driven_To = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
			    	}
					document.getElementById("dlgrecid").value = RecID;
					document.getElementById("dlgVehicle_Type").value = Vehicle_Type;
					document.getElementById("dlgVehicle_Type_Other").value = Vehicle_Type_Other;
					document.getElementById("dlgApprox_Miles").value = Approx_Miles;
					document.getElementById("dlgDate_Driven_From").value = Date_Driven_From;
					document.getElementById("dlgDate_Driven_To").value = Date_Driven_To;
					
					$( "#DRV_EXP_dialog" ).dialog( "option", "title", "Edit Driving Experience");	
					$( "#DRV_EXP_dialog" ).dialog( "option", "modal", true );
					$( "#DRV_EXP_dialog" ).dialog( "option", "width", 700 );
					$( "#DRV_EXP_dialog" ).dialog( "open" );
				} else {
					alert('No Driving Experience Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
	

 	$("#save_drv_exp").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var recid = document.getElementById("dlgrecid").value;

		if (document.getElementById("dlgVehicle_Type").value == '' && document.getElementById("dlgVehicle_Type_Other").value == '') {
			$('#dlgVehicle_Type').focus();
			alert("Vehicle Type or Vehicle Type Other is required");
			return;
		} else {		
			var vehicle_type = document.getElementById("dlgVehicle_Type").value;
			var vehicle_type_other = document.getElementById("dlgVehicle_Type_Other").value;
		}	

		if (document.getElementById("dlgApprox_Miles").value > '') {
			var approx_miles = document.getElementById("dlgApprox_Miles").value;
		} else {		
			document.ALCATEL.dlgApprox_Miles.focus();
			$('#dlgApprox_Miles').focus();
			alert("Approx Miles is required");
			return;
		}	
					
		if (document.getElementById("dlgDate_Driven_From").value > '') {
			if (!isValidDate('dlgDate_Driven_From')) {
				$('#dlgDate_Driven_From').focus();
				alert("Invalid From Date");
				return false;
			} else {					
				var date_driven_from = document.getElementById("dlgDate_Driven_From").value;
			}	
		} else {		
			$('#dlgDate_Driven_From').focus();
			alert("Date Driven From is required");
			return;
		}	
	
		if (document.getElementById("dlgDate_Driven_To").value > '') {
			if (!isValidDate('dlgDate_Driven_From')) {
				$('#dlgDate_Driven_To').focus();
				alert("Invalid To Date");
				return false;
			} else {					
				var date_driven_to = document.getElementById("dlgDate_Driven_To").value;
			}	
		} else {		
			$('#dlgDate_Driven_To').focus();
			alert("Date Driven To is required");
			return;
		}	
		if (!isValidDiff(date_driven_from,date_driven_to)) {
			$('#dlgDate_Driven_From').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax_New/ajax_save_drv_exp.php", 
			data: {personid: personid, recid: recid, vehicle_type: vehicle_type, vehicle_type_other: vehicle_type_other, approx_miles: approx_miles, date_driven_from: date_driven_from, date_driven_to: date_driven_to},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#DRV_EXP_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deletedrvexp(RecID) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this driving experience record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax_New/ajax_delete_drv_exp.php", 
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
	$( "#close_drv_exp" ).click(function() {	
		$( "#DRV_EXP_dialog" ).dialog( "close" );
	});
 
 </script>
 <script language="JavaScript" type="text/javascript">
 	var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {

		var recid = $("#RecID").val();
//		var nodays = $("#days").val();

		if(document.getElementById("haveyoudriven").value == '') {
				document.getElementById("haveyoudriven").focus();	
				alert('Please select Yes/No');
				return false;
		}  	
		if (document.getElementById("haveyoudriven").value == 'Yes') {
			if(recid > 0) {
				return true;
			} else {
				$("#Vehicle_Type").focus();
				alert('You have not entered any driving experiences');
				return false;
			}
		} else {
			return true;
		}		
	}
</script>
