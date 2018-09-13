<?
require_once('../pdotriton.php');
$page++;
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Package = $dbo->query("Select Package from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$Next_Page = $dbo->query("Select Web_App_Name from WebApp_Web_Pages where Company_Name = '".$compname."' and Package_Name = '".$Package."' and Web_Page_Number = ".$page.";")->fetchColumn();	

$maxMilID = $dbo->query("Select max(MilitaryID) from App_Military where PersonID = ".$PersonID.";")->fetchColumn();
if ($maxMilID == '') {
	$maxMilID = 0;
}	

$FormAction = $Next_Page.".php?PersonID=".$PersonID."&page=".$page;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="files/default.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="jquery-ui/jquery-ui.css">
		<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<script type="text/javascript" src="js/autoFormats.js"></script>
		<style type="text/css">
			.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
		</style>
		<script language="JavaScript" type="text/javascript">
			$(document).ready(function() {
				var Military = document.getElementById("MilitaryID").value;
				if (Military > 0) { 
					document.getElementById("military").value = 'Yes';
				}	
				turnonquestions();
			});	

			function turnonquestions() {
				
				if (document.getElementById("military").value == 'Yes') {
					eldiv = document.getElementById("overlay");
					eldiv.style.visibility = "visible";
					eldiv2 = document.getElementById("overlay2");
					eldiv2.style.visibility = "hidden";
				} else {	
					if (document.getElementById("MilitaryID").value > 0) {
						alert("If you Don't Have Any Military Service\nPlease Delete the Existing Military Record(s)");
						document.getElementById("military").value = 'Yes';
						return;
					} else {
						eldiv = document.getElementById("overlay");
						eldiv.style.visibility = "hidden";
						eldiv2 = document.getElementById("overlay2");
						eldiv2.style.visibility = "visible";
					}	
				};
				return true;
			}
			function getCookie(cname) {
				var name = cname + "=";
				var ca = document.cookie.split(';');
				for(var i = 0; i < ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0) == ' ') {
						c = c.substring(1);
					}
					if (c.indexOf(name) == 0) {
						return c.substring(name.length, c.length);
					}
				}
				return ""; 
			}
			
		</script>
		
	</head>

<body bgcolor="#E4E8E8">
<?php
	echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
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
	<table bgcolor="#E4E8E8" width="763" border="0">
		<tr> 
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr> 
			<td width="350"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Have you had any military service within the last 7 years?</b></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<select name="military" id="military" onchange="turnonquestions();">
						<option value="No">No</option>
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
	<table bgcolor="#E4E8E8" width="763" border="0">
		<tr> 
			<td>
				<div name="overlay2" id="overlay2" align="center" style="visibility: visible background: #E4E8E8;">
					<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<INPUT TYPE="submit" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
					</font>	
				</div>	
			</td>
		</tr>
	</table>	
		
<?	
echo '<div name="overlay" id="overlay" style="visibility: hidden; width:763px;">';
echo '<table bgcolor="#E4E8E8" width="763" border="0">
		<tr>
			<td>
				<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Military Service Information</strong> </font></p>
			</td>
		</tr>	
		<tr> 
			<td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please enter your Military service information.</font></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>';
	if ($maxMilID > 0) { 
		$cnt = 1;
		$selectstmt="select MilitaryID, MilitaryBranch, MilitaryRank, MilitaryYears, StartDate, TerminationDate from App_Military where PersonID = :PersonID;"; 	
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();
		while($row=$result2->fetch(PDO::FETCH_BOTH)) {
			$startdate = date("m/d/Y", strtotime($row[4]));	
			$termdate = date("m/d/Y", strtotime($row[5]));	
			echo '<table width="763" bgcolor="#E4E8E8">
				<tr>
					<td width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[1]).'</font></td>
					<td width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>
					<td width="10%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($startdate).'</font></td>			
					<td width="30%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($termdate).'</font></td>';
			$sqlstmt="select Image_URL from App_Uploads where PersonID = :PersonID and UploadType = 'Military' and UploadID = $row[0];"; 	
			$result3 = $dbo->prepare($sqlstmt);
			$result3->bindValue(':PersonID', $PersonID);
			$result3->execute();
			while($row3=$result3->fetch(PDO::FETCH_BOTH)) {
				echo '<td width="5%"><a href="https://proteus.bisi.com/bisi_uploads/'.$row3[0].'" target="blank""><span style="font-size:small; font-family=Tahoma; color:#000000;"><img src="https://proteus.bisi.com/dbtest/images/DocumentBlue.png" height="15" width="15" alt="Display Military Doc" title="Display Military Doc"></span></a></td>';
			}
			$cnt++;	
			echo '<td align="center">
					<a http="#" onclick="updatemil('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Military" title="Edit Military"/></a>&nbsp;&nbsp;
					<a http="#" onclick="deletemil('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Military" title="Delete Military"/></a>
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
	
echo '<fieldset><legend><strong>Add Military Information</strong></legend>
		<table width="100%" bgcolor="#E4E8E8">
			<tr>
				<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Please fax a copy of your DD Form 214 to 303-442-1004 or upload it <a href="#openPhotoDialog">here</a></b></font>
				</td>
			</tr>	
			<tr><td>&nbsp;</td></tr>
		</table>
		<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
			<td>&nbsp;</td>
		</tr>';
	echo '<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Branch<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newmilbranch" id="newmilbranch" value="" size="25" maxlength="40">
				</font>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Current/Most Recent Rank<font color="FF0000">*</font></font></td>
			<td>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newmilrank" id="newmilrank" value="" size="25" maxlength="40">
				</font>
			</td>
		</tr>		
		<tr valign="top"> 
			<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Start Date<font color="FF0000">*</font></font></td>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newstartdate" id="newstartdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')"></font>
			</td>
		</tr>
		<tr> 
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Termination Date<font color="#FF0000">*</font></font></td>
			<td nowrap>
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newmiltermdate" id="newmiltermdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,\'up\')" >
				</font>
			</td>	
		</tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<INPUT TYPE="button" id="add_new_military" VALUE="Save Military Info" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
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
</table>	
</div>	';
echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" NAME=\"MilitaryID\" ID=\"MilitaryID\" VALUE=\"$maxMilID\">";
#	  <INPUT TYPE=\"hidden\" NAME=\"days\" ID=\"days\" VALUE=\"$days\">";

?>
		<div name="Military_dialog" id="Military_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgmilitaryid" id="dlgmilitaryid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Branch<font color="FF0000">*</font></font></td>
					<td>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgmilbranch" id="dlgmilbranch" value="" size="25" maxlength="40">
						</font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Current/Most Recent Rank<font color="FF0000">*</font></font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgmilrank" id="dlgmilrank" value="" size="30"></font>
					</td>
				</tr>
				<tr valign="top"> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Start Date<font color="FF0000">*</font></font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
						<input name="dlgstartdate" id="dlgstartdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,'up')"></font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Termination Date<font color="#FF0000">*</font></font></td>
					<td nowrap>
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgmiltermdate" id="dlgmiltermdate" size="10" maxlength="10" placeholder="mm/dd/yyyy" onKeyUp="return frmtdate(this,'up')" >
						</font>
					</td>	
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>			
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_military" VALUE="Save Military Info">
						<INPUT TYPE="button" id="close_military" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
<?php	
	include ('Upload/UploadDialog.php');
?>	
</FORM>
</body>
</html>

 <script language="JavaScript" type="text/javascript">
 	$( "#Military_dialog" ).dialog({ autoOpen: false });

	$( "#add_new_military" ).click(function() {	
		
		var personid = document.getElementById("PersonID").value;		
		
		if (document.getElementById("newmilbranch").value > '') {
			var newmilbranch = document.getElementById("newmilbranch").value;
		} else {		
			document.ALCATEL.newmilbranch.focus();
			alert("Branch is required");
			return;
		}	
							
		if (document.getElementById("newmilrank").value > '') {
			var newmilrank = document.getElementById("newmilrank").value;
		} else {		
			document.ALCATEL.newmilrank.focus();
			alert("Rank is required");
			return;
		}	
		if (document.getElementById("newstartdate").value > '') {
			var newstartdate = document.getElementById("newstartdate").value;
		} else {		
			document.ALCATEL.newmilyears.focus();
			alert("Start Date is required");
			return;
		}	
		if (document.getElementById("newmiltermdate").value > '') {
			var newmiltermdate = document.getElementById("newmiltermdate").value;
		} else {		
			document.ALCATEL.newmiltermdate.focus();
			alert("Termination Date is required");
			return;
		}	
								
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_military.php", 
			data: {personid: personid, newmilbranch: newmilbranch, newmilrank: newmilrank, newstartdate: newstartdate, newmiltermdate: newmiltermdate},
					
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EduID = obj2;
					document.getElementById("newmilbranch").value = '';
					document.getElementById("newmilrank").value = '';
					document.getElementById("newstartdate").value = '';
					document.getElementById("newmiltermdate").value = '';
					location.reload();				
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	
 	function updatemil(militaryid) {
		var personid = document.getElementById("PersonID").value;
		
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_military.php", 
			data: {personid: personid, militaryid: militaryid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 	
					for (var i = 0; i < obj2.length; i++) {	
						var MilitaryID = obj2[i].MilitaryID;
						var MilitaryBranch = obj2[i].MilitaryBranch;
						var MilitaryRank = obj2[i].MilitaryRank;
						var StartDate = obj2[i].StartDate;
						var sd = obj2[i].StartDate;
						var StartDate = sd.substr(5,2)+"/"+sd.substr(8)+"/"+sd.substr(0,4);
						var td = obj2[i].TerminationDate;
						var TerminationDate = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
			    	}
			
					document.getElementById("dlgmilitaryid").value = MilitaryID;
					document.getElementById("dlgmilbranch").value = MilitaryBranch;
					document.getElementById("dlgmilrank").value = MilitaryRank;
					document.getElementById("dlgstartdate").value = StartDate;
					document.getElementById("dlgmiltermdate").value = TerminationDate;
  
					$( "#Military_dialog" ).dialog( "option", "title", "Edit Military");	
					$( "#Military_dialog" ).dialog( "option", "modal", true );
					$( "#Military_dialog" ).dialog( "option", "width", 700 );
					$( "#Military_dialog" ).dialog( "open" );
				} else {
					alert('No Military Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	}	
	
 	$("#save_military").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var miltaryid = document.getElementById("dlgmilitaryid").value;

		if (document.getElementById("dlgmilbranch").value > '') {
			var dlgmilbranch = document.getElementById("dlgmilbranch").value;
		} else {		
			document.ALCATEL.dlgmilbranch.focus();
			alert("Branch is required");
			return;
		}	

		if (document.getElementById("dlgmilrank").value > '') {
			var dlgmilrank = document.getElementById("dlgmilrank").value;
		} else {		
			document.ALCATEL.dlgmilrank.focus();
			alert("Rank is required");
			return;
		}	
		
		if (document.getElementById("dlgstartdate").value > '') {
			var dlgstartdate = document.getElementById("dlgstartdate").value;
		} else {		
			document.ALCATEL.dlgstartdate.focus();
			alert("Start Date is required");
			return;
		}	
		if (document.getElementById("dlgmiltermdate").value > '') {
			var dlgmiltermdate = document.getElementById("dlgmiltermdate").value;
		} else {		
			document.ALCATEL.dlgmiltermdate.focus();
			alert("Termination Date is required");
			return;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_military.php", 
			data: {personid: personid, miltaryid: miltaryid, dlgmilbranch: dlgmilbranch, dlgmilrank: dlgmilrank, dlgstartdate: dlgstartdate, dlgmiltermdate: dlgmiltermdate},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Military_dialog" ).dialog( "close" );
					location.reload();				
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deletemil(militaryid) {	
//		alert("In dltedu");
		if (confirm('Are you sure you want to delete the military record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_military.php", 
				data: {personid: personid, militaryid:militaryid},
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
	
	$( "#close_military" ).click(function() {	
		$( "#Military_dialog" ).dialog( "close" );
	});
 </script>
 <script src="Upload/Upload.js"></script>

