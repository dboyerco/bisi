<?
#echo 'PersonID is '.$PersonID."<br />";
#echo 'AliasID is '.$AliasID."<br />";
require_once('../pdotriton.php');
$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$noemail = $dbo->query("Select No_Email from App_Person where PersonID = ".$PersonID.";")->fetchColumn();  
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;
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
		<script language="JavaScript" type="text/javascript" src="../App_JS/validate.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoTab.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/jquery.js"></script>
		<script language="JavaScript" type="text/javascript" src="../App_JS/autoFormats.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
		<script src="jquery-ui/jquery-ui.js"></script>
		<style type="text/css">
			.nobord {outline: none; border-color: transparent; background: #E4E8E8; -webkit-box-shadow: none; box-shadow: none;}
		</style>
	</head>

<?
#echo "<body bgcolor=\"#E4E8E8\" onload=\"setindexes()\">";
echo '<body bgcolor="#E4E8E8">';
echo "<FORM METHOD=\"POST\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";

?>

<p><img src="files/hdspacer.gif"></p>
	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
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
			<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Business Information</strong> </font></p>
		</td>
	</tr>	
	<tr> 
		<td colspan="2"> 
			<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please provide your business information.</font>
		</td>
	</tr>
	<tr> 
		<td colspan="2"> </td>
	</tr>
</table>
  
<?php  
	$maxBusinessID = $dbo->query("Select max(RecID) from App_Business where PersonID = ".$PersonID.";")->fetchColumn();	
	if ($maxBusinessID > 0) { 
		$selectbus="select RecID, BusinessID, Business_Name, Business_Address, Business_City, Business_State, Business_Zip, Formation_Date, Registered_Agents, Agent_Address, Status from App_Business where PersonID = :PersonID;";
		$Business_result = $dbo->prepare($selectbus);
		$Business_result->bindValue(':PersonID', $PersonID);
		$Business_result->execute();
		while($row = $Business_result->fetch(PDO::FETCH_BOTH)) {	
			$formation_date	= date("m/d/Y", strtotime($row[7]));
			echo '<table width="763" id="bustbl" bgcolor="#E4E8E8">';
			echo '<tr><td width="100"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;'.htmlspecialchars($row[1]).'</font></td>';
			echo '<td width="100"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[2]).'</font></td>';
			echo '<td width="50"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[3]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[4]).'</font></td>';	
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[5]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[6]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($formation_date).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[8]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[9]).'</font></td>';
			echo '<td width="30"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.htmlspecialchars($row[10]).'</font></td>';
			echo '<td width="60">
				<a http="#" onclick="updatebusiness('.$row[0].')"><img src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Business" title="Edit Business"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a http="#" onclick="deletebusiness('.$row[0].')"><img src="images/deletetrashcan.png" height="15" width="15" alt="Delete Business" title="Delete Business"/></a>
				</td>
			</tr>
			</table>
			<table width="763" bgcolor="#E4E8E8">
			<tr>
				<td><hr></td>
			</tr>
			</table>
			<table width="763" id="bustbl2" bgcolor="#E4E8E8">';
		}	
		echo '</table>';
	} 

	if ($maxBusinessID > 0) { 	
		echo '<table width="763">
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td align="center">
			 		<INPUT TYPE="submit" VALUE="Next" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>';	
	}
if ($maxBusinessID == 0) { 
		
	echo '<fieldset><legend><strong>Add Business Information</strong></legend>
		<table width="100%" bgcolor="#E4E8E8">
		<tr>
			<td><font color="FF0000" size="1" face="Verdana, Arial, Helvetica, sans-serif">* Denotes Required Field</font></td>
		</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business ID<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newbusinessid" id="newbusinessid" size="20" maxlength="100" placeholder="Required">
				</font>
			</td>
		</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business Name<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newbusinessname" id="newbusinessname" size="20" maxlength="100" placeholder="Required">
				</font>
			</td>
		</tr>';
	echo '<tr> 
		<td width="160"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business Street<font color="#FF0000">*</font></font></td>
		<td width="351">
			<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newbusinessaddress" id="newbusinessaddress" size="20" maxlength="100" placeholder="Required">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business City<font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
				<input name="newbusinesscity" id="newbusinesscity" size="20" maxlength="40" placeholder="Required">
			</font>
		</td>
	</tr>
	<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business State<font color="#FF0000">*</font></font></td>
		<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
				<select name="newbusinessstate" id="newbusinessstate">
					<option value="">Select a State</option>';
					$sql = "Select Name, Abbrev from State order by Name";
					$state_result = $dbo->prepare($sql);
					$state_result->execute();
					while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {		
						echo "<option value=".$rows[1].">".$rows[0]."</option>";
					}		
			echo '</select></span>
		</td>
	</tr>';	
	echo '<tr> 
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Business Zip Code<font color="#FF0000">*</font></font></td>
		<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
			<input name="newbusinesszip" id="newbusinesszip" size="10" maxlength="10">
			</font>
		</td>
	</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Formation Date<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newformatdate" id="newformationdate" size="20" maxlength="100" placeholder="mm/dd/yyyy">
				</font>
			</td>
		</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Registered Agents<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newregistered_agents" id="newregistered_agents" size="20" maxlength="100" placeholder="Required">
				</font>
			</td>
		</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Agent_Address<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newagent_address" id="newagent_address" size="20" maxlength="100" placeholder="Required">
				</font>
			</td>
		</tr>';
	echo '<tr>
			<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Status<font color="#FF0000">*</font></font></td>
			<td width="351">
				<font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
					<input name="newstatus" id="newstatus" size="20" maxlength="100" placeholder="Required">
				</font>
			</td>
		</tr>';
	echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<INPUT TYPE="button" id="add_new_business" VALUE="Save Business" style="font-size:medium; font-family=Tahoma; color:green; border-radius:5px; padding: 5px 24px;">
		</td>
	</tr>
	</table></fieldset>';
}	
echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" ID=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" NAME=\"BusinessID\" ID=\"BusinessID\" VALUE=\"$maxBusinessID\">";
?>
	<div name="Business_dialog" id="Business_dialog" title="Dialog Title">
		<div>
			<br/>
			<input type="hidden" name="dlgrecid" id="dlgrecid">
			<table width="100%" align="left" border="0" bgcolor="#eeeeee">
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Business ID<font color="#FF0000">*</font></font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbusinessid" id="dlgbusinessid" size="20" maxlength="100" placeholder="Required">
						</font>
					</td>
				</tr>
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Business Name</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbusinessname" id="dlgbusinessname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td width="160"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Business Street</font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbusinessaddress" id="dlgbusinessaddress" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Business City</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbusinesscity" id="dlgbusinesscity" size="20" maxlength="40" >
						</font>
					</td>
				</tr>
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Business State</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgbusinessstate" id="dlgbusinessstate">
							<option value="">Select a State</option>
							<option value="other">-Other-</option>
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
				<tr> 
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Business Zip Code</font></td>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgbusinesszip" id="dlgbusinesszip" size="10" maxlength="10">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Formation Date<font color="#FF0000">*</font></font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgformatdate" id="dlgformationdate" size="20" maxlength="100" placeholder="mm/dd/yyyy">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Registered Agents<font color="#FF0000">*</font></font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgregistered_agents" id="dlgregistered_agents" size="20" maxlength="100" placeholder="Required">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Agent_Address<font color="#FF0000">*</font></font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgagent_address" id="dlgagent_address" size="20" maxlength="100" placeholder="Required">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Status<font color="#FF0000">*</font></font></td>
					<td width="351">
						<font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							<input name="dlgstatus" id="dlgstatus" size="20" maxlength="100" placeholder="Required">
						</font>
					</td>
				</tr>
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_business" VALUE="Save Business">
						<INPUT TYPE="button" id="close_business" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>		
	</div>
</FORM>
</body>
</html>
<script language="JavaScript" type="text/javascript">
	$( "#Business_dialog" ).dialog({ autoOpen: false });

 	$( "#add_new_business" ).click(function() {	
		var personid = document.getElementById("PersonID").value;
 	 
		if (document.getElementById("newbusinessid").value > '') {
			var businessid = document.getElementById("newbusinessid").value;
		} else {		
			document.ALCATEL.newbusinessid.focus();
			alert("Business ID is required");
			return;
		}	
		if (document.getElementById("newbusinessname").value > '') {
			var businessname = document.getElementById("newbusinessname").value;
		} else {		
			document.ALCATEL.newbusinessname.focus();
			alert("Business name is required");
			return;
		}	
 	 
		if (document.getElementById("newbusinessaddress").value > '') {
			var businessaddress = document.getElementById("newbusinessaddress").value;
		} else {		
			document.ALCATEL.newbusinessaddress.focus();
			alert("Business address is required");
			return;
		}	
			
		if (document.getElementById("newbusinesscity").value > '') {
			var businesscity = document.getElementById("newbusinesscity").value;
		} else {		
			document.ALCATEL.newbusinesscity.focus();
			alert("Business city is required");
			return;
		}	
			
		if (document.getElementById("newbusinessstate").value == '') {
			document.ALCATEL.newbusinessstate.focus();
			alert("Business State is required");
			return;
		} else {		
			var businessstate = document.getElementById("newbusinessstate").value;
		}	
			
		if (document.getElementById("newbusinesszip").value > '') {
			var businesszip = document.getElementById("newbusinesszip").value;
		} else {		
			document.ALCATEL.newbusinesszip.focus();
			alert("Business Zip Code is required");
			return;
		}	
		if (document.getElementById("newformationdate").value > '') {
			var formationdate = document.getElementById("newformationdate").value;
		} else {		
			document.ALCATEL.newformationdate.focus();
			alert("Formation Date is required");
			return;
		}	
		if (document.getElementById("newregistered_agents").value > '') {
			var registered_agents = document.getElementById("newregistered_agents").value;
		} else {		
			document.ALCATEL.newregistered_agents.focus();
			alert("Registered Agents is required");
			return;
		}	
		if (document.getElementById("newagent_address").value > '') {
			var agent_address = document.getElementById("newagent_address").value;
		} else {		
			document.ALCATEL.newagent_address.focus();
			alert("Agent Address is required");
			return;
		}	
		if (document.getElementById("newstatus").value > '') {
			var status = document.getElementById("newstatus").value;
		} else {		
			document.ALCATEL.newstatus.focus();
			alert("Status is required");
			return;
		}	
				
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_business.php", 
			data: {personid: personid, businessname: businessname, businessaddress: businessaddress, businesscity: businesscity, businessstate: businessstate, businesszip: businesszip, businessid: businessid, formationdate: formationdate, registered_agents: registered_agents, agent_address: agent_address, status: status},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					document.getElementById("newbusinessid").value = '';
					document.getElementById("newbusinessaddress").value = '';
					document.getElementById("newbusinessname").value = '';
					document.getElementById("newbusinesscity").value = '';
					document.getElementById("newbusinessstate").value = '';
					document.getElementById("newbusinesszip").value = '';
					document.getElementById("newformationdate").value = '';
					document.getElementById("newregistered_agents").value = '';
					document.getElementById("newagent_address").value = '';
					document.getElementById("newstatus").value = '';
					location.reload();
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	function updatebusiness(recid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_business.php", 
			data: {personid: personid, recid: recid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 
					for (var i = 0; i < obj2.length; i++) {	
						var RecID = obj2[i].RecID;
						var Business_Address = obj2[i].Business_Address;
						var Business_Name = obj2[i].Business_Name;
						var Business_City = obj2[i].Business_City;
						var Business_State = obj2[i].Business_State;
						var Business_Zip = obj2[i].Business_Zip;
						var BusinessID = obj2[i].BusinessID;
						var fd = obj2[i].Formation_Date;
						var Formation_Date = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
 						var Registered_Agents = obj2[i].Registered_Agents;
						var Agent_Address = obj2[i].Agent_Address;
						var Status = obj2[i].Status;
			    	}
					document.getElementById("dlgrecid").value = RecID;
					document.getElementById("dlgbusinessaddress").value = Business_Address;
					document.getElementById("dlgbusinessname").value = Business_Name;
					document.getElementById("dlgbusinesscity").value = Business_City;
					document.getElementById("dlgbusinessstate").value = Business_State;
					document.getElementById("dlgbusinesszip").value = Business_Zip;
					document.getElementById("dlgbusinessid").value = BusinessID;
					document.getElementById("dlgformationdate").value = Formation_Date;
					document.getElementById("dlgregistered_agents").value = Registered_Agents;
					document.getElementById("dlgagent_address").value = Agent_Address;
					document.getElementById("dlgstatus").value = Status;

					$( "#Business_dialog" ).dialog( "option", "title", "Edit Business Information");	
					$( "#Business_dialog" ).dialog( "option", "modal", true );
					$( "#Business_dialog" ).dialog( "option", "width", 700 );
					$( "#Business_dialog" ).dialog( "open" );
				} else {
					alert('No Business Data Found');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	}	
 	$("#save_business").click(function() {	
		var personid = document.getElementById("PersonID").value;
		var recid = document.getElementById("dlgrecid").value;	
		
		if (document.getElementById("dlgbusinessid").value > '') {
			var businessid = document.getElementById("dlgbusinessid").value;
		} else {		
			document.ALCATEL.dlgbusinessid.focus();
			alert("Business ID is required");
			return;
		}	
			
 		if (document.getElementById("dlgbusinessname").value > '') {
			var businessname = document.getElementById("dlgbusinessname").value;
		} else {		
			document.ALCATEL.dlgbusinessname.focus();
			alert("Business name is required");
			return;
		}	
		if (document.getElementById("dlgbusinessaddress").value > '') {
			var businessaddress = document.getElementById("dlgbusinessaddress").value;
		} else {		
			document.ALCATEL.dlgbusinessaddress.focus();
			alert("Business address is required");
			return;
		}	
			
		if (document.getElementById("dlgbusinesscity").value > '') {
			var businesscity = document.getElementById("dlgbusinesscity").value;
		} else {		
			document.ALCATEL.dlgbusinesscity.focus();
			alert("Business city is required");
			return;
		}	
			
		if (document.getElementById("dlgbusinessstate").value == '') {
			document.ALCATEL.dlgbusinessstate.focus();
			alert("Business State is required");
			return;
		} else {		
			var businessstate = document.getElementById("dlgbusinessstate").value;
		}	
			
		if (document.getElementById("dlgbusinesszip").value > '') {
			var businesszip = document.getElementById("dlgbusinesszip").value;
		} else {		
			document.ALCATEL.dlgbusinesszip.focus();
			alert("Business Zip is required");
			return;
		}	
		if (document.getElementById("dlgformationdate").value > '') {
			var formationdate = document.getElementById("dlgformationdate").value;
		} else {		
			document.ALCATEL.dlgformationdate.focus();
			alert("Formation Date is required");
			return;
		}	
		if (document.getElementById("dlgregistered_agents").value > '') {
			var registered_agents = document.getElementById("dlgregistered_agents").value;
		} else {		
			document.ALCATEL.dlgregistered_agents.focus();
			alert("Registered Agents is required");
			return;
		}	
		if (document.getElementById("dlgagent_address").value > '') {
			var agent_address = document.getElementById("dlgagent_address").value;
		} else {		
			document.ALCATEL.dlgagent_address.focus();
			alert("Agent Address is required");
			return;
		}	
		if (document.getElementById("dlgstatus").value > '') {
			var status = document.getElementById("dlgstatus").value;
		} else {		
			document.ALCATEL.dlgstatus.focus();
			alert("Status is required");
			return;
		}	

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_business.php", 
			data: {personid: personid, recid: recid, businessname: businessname, businessaddress: businessaddress, businesscity: businesscity, businessstate: businessstate, businesszip: businesszip, businessid: businessid, formationdate: formationdate, registered_agents: registered_agents, agent_address: agent_address, status: status},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					$( "#Business_dialog" ).dialog( "close" );
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
					$( "#Business_dialog" ).dialog( "close" );
					location.reload();
//				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 
	});
	
	function deletebusiness(recid) {	
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete the business info?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_business.php", 
				data: {personid: personid, recid: recid},
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
	
	$( "#close_business" ).click(function() {	
		$( "#Business_dialog" ).dialog( "close" );
	});
</script>	

<script language="JavaScript" type="text/javascript">
var frmvalidator = new Validator("ALCATEL");
frmvalidator.setAddnlValidationFunction("DoCustomValidation");

function DoCustomValidation() {
	var businessid = document.getElementById("BusinessID").value;
//	alert('Number of Days: '+nodays);
	if (businessid == 0) {
		document.ALCATEL.newbusinessname.focus();
		alert('You have not entered your Business info');
		return false;
	} else {
		return true;		
	}
}	
</script>
