<?php
require_once('../pdotriton.php');
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
  
$FormAction = "proflicense.php?PersonID=".$PersonID;
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
		<script type="text/javascript" src="https://ajax.googleapis.com/../App_Ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<style type="text/css">
			.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
		</style>
	</head>

<body bgcolor="#E4E8E8">

<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  	<tbody><tr>
    <td></td>
    <td class="submenu" height="27" width="763">&nbsp;</td>
  	</tr>
	</tbody></table>
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
echo '<table width="763" bgcolor="#E4E8E8">
		<tr>
			<td>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Professional References</strong> </font></p>
			</td>
		</tr>	
		<tr>
			<td colspan="2"> 
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">List 3 References, which are not related to you and not a current employee of '.$compname.'.</font></p>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp</font></p>
				<p>&nbsp;</p>
			</td>
		</tr>
	</table>';
	$maxRefID = $dbo->query("Select max(RefID) from App_References where PersonID = ".$PersonID.";")->fetchColumn();
	if ($maxRefID > 0) { 
		$selectstmt="select RefID, RefCompany, RefCompanyPhone, RefFirstName, RefLastName, RefPhone, RefEmail, RefStreet1, RefStreet2, RefCity, RefState, RefZip, RefCounty , RefCountry, RefRelate from App_References where PersonID = :PersonID;";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();
		while($row=$result2->fetch(PDO::FETCH_BOTH)) {
			echo '<table width="763" bgcolor="#E4E8E8">
				<tr>
					<td width="20%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[1]).'</font></td>
					<td width="12%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>
					<td width="25%">
						<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
							'.htmlspecialchars($row[3]).'&nbsp'.htmlspecialchars($row[4]).'
						</font>
					</td>			
					<td width="12%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[5]).'</font></td>
					<td width="20%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[6]).'</font></td>
					<td align="center">
						<a http="#" onclick="updateref('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Reference" title="Edit Reference"/></a>&nbsp;&nbsp;
				<a http="#" onclick="deleteref('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Reference" title="Delete Reference"/></a>
			</td>
		</tr>';
		echo '</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>';
		}
	}
echo '<fieldset><legend><strong>Add Reference</strong></legend>
	<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>&nbsp;</td>
		</tr>';
	echo '<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact First Name<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newreffirstname" id="newreffirstname" value="" size="25" maxlength="40">
				</font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Last Name<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newreflastname" id="newreflastname" value="" size="25" maxlength="40">
				</font>
			</td>
		</tr>		
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Phone<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newrefphone" id="newrefphone" value="" size="30" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')"></font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Email<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newrefemail" id="newrefemail" value="" size="20" maxlength="40"></font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Name<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newrefcompanyname" id="newrefcompanyname" value="" size="20" maxlength="40"></font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Phone<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newrefcompanyphone" id="newrefcompanyphone" value="" size="30" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')"></font>
			</td>
		</tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_reference" VALUE="Save Reference" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	</table>
	</fieldset>';
	
echo '<table width="763">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="center">
			 <INPUT TYPE="submit" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>';	
	
echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" NAME=\"RefID\" ID=\"RefID\" VALUE=\"$maxRefID\">";
#	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";

?>
		<div name="Reference_dialog" id="Reference_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgrefid" id="dlgrefid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact First Name<font color="FF0000">*</font></font></td>
					<td>
						<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgreffirstname" id="dlgreffirstname" value="" size="25" maxlength="40">
						</font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Last Name<font color="FF0000">*</font></font></td>
					<td>
						<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgreflastname" id="dlgreflastname" value="" size="25" maxlength="40">
						</font>
					</td>
				</tr>		
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Phone<font color="FF0000">*</font></font></td>
					<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgrefphone" id="dlgrefphone" value="" size="30" placeholder="### ### #### #####" 
						onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')"></font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Contact Email<font color="FF0000">*</font></font></td>
					<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgrefemail" id="dlgrefemail" value="" size="20" maxlength="40"></font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Name<font color="FF0000">*</font></font></td>
					<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgrefcompanyname" id="dlgrefcompanyname" value="" size="20" maxlength="40"></font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Phone<font color="FF0000">*</font></font></td>
					<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgrefcompanyphone" id="dlgrefcompanyphone" value="" size="30" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')"></font>
					</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>			
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_reference" VALUE="Save Reference">
						<INPUT TYPE="button" id="close_reference" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>


 <script language="JavaScript" type="text/javascript">
 	$( "#Reference_dialog" ).dialog({ autoOpen: false });

	$( "#add_new_reference" ).click(function() {	
		
		var personid = document.getElementById("PersonID").value;		
		
		if (document.getElementById("newreffirstname").value > '') {
			var newreffirstname = document.getElementById("newreffirstname").value;
		} else {		
			document.ALCATEL.newreffirstname.focus();
			alert("Contact First Name is required");
			return;
		}	
		if (document.getElementById("newreflastname").value > '') {
			var newreflastname = document.getElementById("newreflastname").value;
		} else {		
			document.ALCATEL.newreflastname.focus();
			alert("Contact Last Name is required");
			return;
		}	
					
		if (document.getElementById("newrefphone").value > '') {
			var newrefphone = document.getElementById("newrefphone").value;
		} else {		
			document.ALCATEL.newrefphone.focus();
			alert("Contact Phone # is required");
			return;
		}	
						
		if (document.getElementById("newrefemail").value > '') {
			var newrefemail = document.getElementById("newrefemail").value;
		} else {		
			document.ALCATEL.newrefemail.focus();
			alert("Contact Email is required");
			return;
		}	
	
		if (document.getElementById("newrefcompanyname").value > '') {
			var newrefcompanyname = document.getElementById("newrefcompanyname").value;
		} else {		
			document.ALCATEL.newrefcompanyname.focus();
			alert("Company Name is required");
			return;
		}	
		if (document.getElementById("newrefcompanyphone").value > '') {
			var newrefcompanyphone = document.getElementById("newrefcompanyphone").value;
		} else {		
			document.ALCATEL.newrefcompanyphone.focus();
			alert("Company Phone # is required");
			return;
		}	
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_reference.php", 
			data: {personid: personid, newreffirstname: newreffirstname, newreflastname: newreflastname, newrefphone: newrefphone, newrefemail: newrefemail, newrefcompanyname: newrefcompanyname, newrefcompanyphone: newrefcompanyphone},
					
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EduID = obj2;
					document.getElementById("newreffirstname").value = '';
					document.getElementById("newreflastname").value = '';
					document.getElementById("newrefphone").value = '';
					document.getElementById("newrefemail").value = '';
					document.getElementById("newrefcompanyname").value = '';
					document.getElementById("newrefcompanyphone").value = '';
					location.reload();
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	
 	function updateref(refid) {
		var personid = document.getElementById("PersonID").value;
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_reference.php", 
			data: {personid: personid, refid: refid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var RefID = obj2[i].RefID;
						var RefFirstName = obj2[i].RefFirstName;
						var RefLastName = obj2[i].RefLastName;
						var RefPhone = obj2[i].RefPhone;
						var RefEmail = obj2[i].RefEmail;
						var RefCompany = obj2[i].RefCompany;
						var RefCompanyPhone = obj2[i].RefCompanyPhone;
			    	}
			
					document.getElementById("dlgrefid").value = RefID;
					document.getElementById("dlgreffirstname").value = RefFirstName;
					document.getElementById("dlgreflastname").value = RefLastName;
					document.getElementById("dlgrefphone").value = RefPhone;
					document.getElementById("dlgrefemail").value = RefEmail;
					document.getElementById("dlgrefcompanyname").value = RefCompany;
					document.getElementById("dlgrefcompanyphone").value = RefCompanyPhone;
  
					$( "#Reference_dialog" ).dialog( "option", "title", "Edit Reference");	
					$( "#Reference_dialog" ).dialog( "option", "modal", true );
					$( "#Reference_dialog" ).dialog( "option", "width", 700 );
					$( "#Reference_dialog" ).dialog( "open" );
				} else {
					alert('No Reference Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	}	
	
 	$("#save_reference").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var refid = document.getElementById("dlgrefid").value;

		if (document.getElementById("dlgreffirstname").value > '') {
			var reffirstname = document.getElementById("dlgreffirstname").value;
		} else {		
			document.ALCATEL.dlgeduname.focus();
			alert("Contact First Name is required");
			return;
		}	

		if (document.getElementById("dlgreflastname").value > '') {
			var reflastname = document.getElementById("dlgreflastname").value;
		} else {		
			document.ALCATEL.dlgreflastname.focus();
			alert("Contact last Name is required");
			return;
		}	
		
		if (document.getElementById("dlgrefphone").value > '') {
			var refphone = document.getElementById("dlgrefphone").value;
		} else {		
			document.ALCATEL.dlgrefphone.focus();
			alert("Contact Phone # is required");
			return;
		}	
		
		if (document.getElementById("dlgrefemail").value > '') {
			var refemail = document.getElementById("dlgrefemail").value;
		} else {		
			document.ALCATEL.dlgrefemail.focus();
			alert("Contact Email is required");
			return;
		}			

		if (document.getElementById("dlgrefcompanyname").value > '') {
			var refcompanyname = document.getElementById("dlgrefcompanyname").value;
		} else {		
			document.ALCATEL.dlgrefcompanyname.focus();
			alert("Company Name is required");
			return;
		}	
		
		if (document.getElementById("dlgrefcompanyphone").value > '') {
			var refcompanyphone = document.getElementById("dlgrefcompanyphone").value;
		} else {		
			document.ALCATEL.dlgrefcompanyphone.focus();
			alert("Company Phone # is required");
			return;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_reference.php", 
			data: {personid: personid, refid: refid, reffirstname: reffirstname, reflastname: reflastname, refphone: refphone, refemail: refemail, refcompanyname: refcompanyname, refcompanyphone: refcompanyphone},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Reference_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deleteref(RefID) {	
//		alert("In dltedu");
		if (confirm('Are you sure you want to delete this Reference?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_reference.php", 
				data: {personid: personid, RefID: RefID},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false; 
					} else {
						location.reload();
					}
				},	
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				} 					
			});
		}
	}
	$( "#close_reference" ).click(function() {	
		$( "#Reference_dialog" ).dialog( "close" );
	});
 
 
 </script>
 <script language="JavaScript" type="text/javascript">
 
 var frmvalidator = new Validator("ALCATEL");
//frmvalidator.setAddnlValidationFunction("DoCustomValidation");
/*
function DoCustomValidation() {
	var frm = document.forms["ALCATEL"];
  	if(frm.companyname2.value.trim() == 'N/A' || frm.companyname2.value.trim() == 'n/a' ) {
  	} else {
  		if(frm.companyname2.value.trim() == '') {
  			document.ALCATEL.companyname2.focus();
			alert('Company Name 2 is required.');
	    	return false;
		} 
  		if(frm.companyaddr2.value.trim() == '') {
  			document.ALCATEL.companyaddr2.focus();
			alert('Please enter the Street for Company 2.');
	    	return false;
		} 
  		if(frm.companycity2.value.trim() == '') {
  			document.ALCATEL.companycity2.focus();
			alert('Please enter the City for Company 2.');
	    	return false;
		} 
  		if(frm.companystate2.value.trim() == '') {
  			document.ALCATEL.companystate2.focus();
			alert('Please enter the State for Company 2.');
	   		return false;
		} 
		if (frm.companystate2.value.trim() == 'XX' && frm.companystateother2.value.trim() == '') {
			document.ALCATEL.companystateother2.focus();
			alert('Please select a Province/Country for Company 2');
	   		return false;
  		} 
  		if(frm.companyposition2.value.trim() == '') {
  			document.ALCATEL.companyposition2.focus();
			alert('Please enter the Position for Company 2.');
	   		return false;
		} 
	  	if(frm.companydatefrommonth2.value.trim() == '' || frm.companydatefromday2.value.trim() == '' || frm.companydatefromyear2.value.trim() == '') {
			if (frm.companydatefrommonth2.value.trim() == '') {
				document.ALCATEL.companydatefrommonth2.focus();
  			} else if (frm.companydatefromday2.value.trim() == '') {	
				document.ALCATEL.companydatefromday2.focus();
  			} else if (frm.companydatefromyear2.value.trim() == '') {	
				document.ALCATEL.companydatefromyear2.focus();
			}
			alert('Please enter the From Date for Company 2.');
	    	return false;
		} 
	  	if(frm.companydatetomonth2.value.trim() == '' || frm.companydatetoday2.value.trim() == '' || frm.companydatetoyear2.value.trim() == '') {
			if (frm.companydatetomonth2.value.trim() == '') {
				document.ALCATEL.companydatetomonth2.focus();
  			} else if (frm.companydatetoday2.value.trim() == '') {	
				document.ALCATEL.companydatetoday2.focus();
  			} else if (frm.companydatetoyear2.value.trim() == '') {	
				document.ALCATEL.companydatetoyear2.focus();
			}
			alert('Please enter the To Date for Company 2');
	    	return false;
		} 
  		if(frm.companysuper2.value.trim() == '') {
  			document.ALCATEL.companysuper2.focus();
			alert('Please enter the Supervisor for Company 2.');
	   		return false;
		} 
  		if(frm.companyphone2.value.trim() == '') {
  			document.ALCATEL.companyphone2.focus();
			alert('Please enter the Phone Number for Company 2.');
	   		return false;
		} 
	
	}

  	if(frm.companyname3.value.trim() == 'N/A' || frm.companyname3.value.trim() == 'n/a' ) {
  	} else {
  		if(frm.companyname3.value.trim() == '') {
  			document.ALCATEL.companyname3.focus();
			alert('Company Name 3 is required.');
	    	return false;
		} 
  		if(frm.companyaddr3.value.trim() == '') {
  			document.ALCATEL.companyaddr3.focus();
			alert('Please enter the Street for Company 3.');
	    	return false;
		} 
  		if(frm.companycity3.value.trim() == '') {
  			document.ALCATEL.companycity3.focus();
			alert('Please enter the City for Company 3.');
	    	return false;
		} 
  		if(frm.companystate3.value.trim() == '') {
  			document.ALCATEL.companystate3.focus();
			alert('Please enter the State for Company 3.');
	   		return false;
		} 
		if (frm.companystate3.value.trim() == 'XX' && frm.companystateother3.value.trim() == '') {
			document.ALCATEL.companystateother3.focus();
			alert('Please select a Province/Country for Company 2');
	   		return false;
  		} 
  		if(frm.companyposition3.value.trim() == '') {
  			document.ALCATEL.companyposition3.focus();
			alert('Please enter the Position for Company 3.');
	   		return false;
		} 
	  	if(frm.companydatefrommonth3.value.trim() == '' || frm.companydatefromday3.value.trim() == '' || frm.companydatefromyear3.value.trim() == '') {
			if (frm.companydatefrommonth3.value.trim() == '') {
				document.ALCATEL.companydatefrommonth3.focus();
  			} else if (frm.companydatefromday3.value.trim() == '') {	
				document.ALCATEL.companydatefromday3.focus();
  			} else if (frm.companydatefromyear3.value.trim() == '') {	
				document.ALCATEL.companydatefromyear3.focus();
			}
			alert('Please enter the From Date for Company 3.');
	    	return false;
		} 
	  	if(frm.companydatetomonth3.value.trim() == '' || frm.companydatetoday3.value.trim() == '' || frm.companydatetoyear3.value.trim() == '') {
			if (frm.companydatetomonth3.value.trim() == '') {
				document.ALCATEL.companydatetomonth3.focus();
  			} else if (frm.companydatetoday3.value.trim() == '') {	
				document.ALCATEL.companydatetoday3.focus();
  			} else if (frm.companydatetoyear3.value.trim() == '') {	
				document.ALCATEL.companydatetoyear3.focus();
			}
			alert('Please enter the To Date for Company 3');
	    	return false;
		} 
  		if(frm.companysuper3.value.trim() == '') {
  			document.ALCATEL.companysuper3.focus();
			alert('Please enter the Supervisor for Company 3.');
	   		return false;
		} 
  		if(frm.companyphone3.value.trim() == '') {
  			document.ALCATEL.companyphone3.focus();
			alert('Please enter the Phone Number for Company 3.');
	   		return false;
		} 
	
	}

	return true;
}
*/
</script>


