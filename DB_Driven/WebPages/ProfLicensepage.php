<?php
require_once('../../pdotriton.php');

if (!isset($cocode)) {$cocode = '';}
if (!isset($package)) {$package = '';}
echo "<input type=\"hidden\" name=\"ccode\" id=\"ccode\" value=\"$cocode\">";
echo "<input type=\"hidden\" name=\"pname\" id=\"pname\" value=\"$package\">";

?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Edit Web Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
	<link rel="stylesheet" href="../jquery-ui/jquery-ui.css">
	<link rel="stylesheet" href="../CSS/menu.css">
   	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>	
	<script src="../jquery-ui/jquery-ui.js"></script>	
	
	<style type="text/css">
		table.db-table { border-right:1px solid #ccc; border-bottom:1px solid #ccc; }
		table.db-table tr {cursor:pointer;} .highlight {background:yellow;}
		table.db-table tr:nth-child(even){background:#eee;}
		table.db-table th {background:lightgrey; padding:5px; border-left:1px solid #ccc; border-top:1px solid #ccc; }
		table.db-table td {padding:5px; border-left:1px solid #ccc; border-top:1px solid #ccc; white-space: nowrap; }
		table.db-table a:link {text-decoration:none; color:#000000;}
		table.db-table a:visited {text-decoration:none; color:#000000;}
		table.db-table a:active {text-decoration:none; color:#0000000;}
		table.db-table a:focus {outline:none; text-decoration:underline;} 
		table.db-table a:hover {color:#0033FF; text-decoration:underline;}
		
	</style>
<script type="text/javascript">	
	function ajaxwebpages() {
//		alert("In ajaxwebpages");
		var httpxml;
		try {httpxml=new XMLHttpRequest();}   				// Firefox, Opera 8.0+, Safari
		catch (e) {
			try { httpxml=new ActiveXObject("Msxml2.XMLHTTP"); } // Internet Explorer
  				catch (e) {
    				try { httpxml=new ActiveXObject("Microsoft.XMLHTTP");}
    				catch (e) { 
						alert("Your browser does not support AJAX!");
      					return false;
      				}
    			}
  			}
	
		/////// Posting data to backend script ////
		var url="../Ajax/ajax_proflicense_page.php";
//	alert('URL is: '+url);	

		var cocode = document.getElementById("ccode").value;
		var pname = document.getElementById("pname").value;
		url=url+"?cocode="+cocode;
		url=url+"&pname="+pname;
//		alert("URL: "+url);
		httpxml.onreadystatechange=stateChanged;
		httpxml.open("GET",url,true);
		httpxml.send(null);
/////// end of posting data to backend script /////
//		document.getElementById("txtHint").innerHTML="Loading - Please Wait....";


	function stateChanged() {
    	if(httpxml.readyState==4) {
			var myObject = JSON.parse(httpxml.responseText);
//			alert(myObject.data[0].Display_Msg1);
			var str = '<table id="tbl1" cellpadding="0" cellspacing="0" width="75%"><tbody><tr><th align="left">Limit By</th><th align="left">Limit #</th></tr><tr><td><select name="limitby" id="limitby"><option value="C">Current Branch</option><option value="N">Number of Entries</option><option value="Y">Years</option></select></td><td><input name="limitno" id="limitno" value="'+myObject.data[0].LimitEntries+'" size="10"></input></td></tr></tbody></table><br />';
			str = str + '<table id="tbl2" cellpadding="0" cellspacing="0" width="75%"><tbody><tr><th align="left">Display Msg1</th><th align="left">Display Msg2</th></tr><tr><td><textarea id="displaymsg1" rows="3" cols="75">'+myObject.data[0].Display_Msg1+'</textarea></td><td><textarea id="displaymsg2" rows="3" cols="75">'+myObject.data[0].Display_Msg2+'</textarea></td></tr></tbody></table><br />';
			
			str = str +	'<center><table id="fldtbl" cellpadding="0" cellspacing="0" class="db-table" width="75%"><tbody><tr><th>Field Name</th><th>Visible</th><th>Required</th></tr>';
			for(i=0;i<myObject.data.length;i++) { 
				str = str +	'<tr><td><input name="fldname" value="'+myObject.data[i].FLD1+'" readonly size="50"></input></td><td align="center"><select name="fldvisible" disabled><option value="N">N</option><option value="Y">Y</option></select></td><td align="center"><select name="fldrequired" disabled><option value="N">N</option><option value="Y">Y</option></select></td></tr>';
				str = str +	'<tr><td><input name="fldname" value="'+myObject.data[i].FLD2+'" readonly size="50"></input></td><td align="center"><select name="fldvisible" disabled><option value="N">N</option><option value="Y">Y</option></select></td><td align="center"><select name="fldrequired" disabled><option value="N">N</option><option value="Y">Y</option></select></td></tr>';
				str = str +	'<tr><td><input name="fldname" value="'+myObject.data[i].FLD3+'" readonly size="50"></input></td><td align="center"><select name="fldvisible" disabled><option value="N">N</option><option value="Y">Y</option></select></td><td align="center"><select name="fldrequired" disabled><option value="N">N</option><option value="Y">Y</option></select></td></tr>';
				str = str +	'<tr><td><input name="fldname" value="'+myObject.data[i].FLD4+'" readonly size="50"></input></td><td align="center"><select name="fldvisible" disabled><option value="N">N</option><option value="Y">Y</option></select></td><td align="center"><select name="fldrequired" disabled><option value="N">N</option><option value="Y">Y</option></select></td></tr>';
			}	
			str = str + "</tbody></table></center>";
			document.getElementById("txtHint").innerHTML=str;	
			
			var table1Rows = document.getElementById('tbl1').getElementsByTagName('tr');
			for (var i=1; i < table1Rows.length; i++) {
				table1Rows[i].cells[0].firstChild.value = myObject.data[0].LimitEntriesBy;
			}
			var tableRows = document.getElementById('fldtbl').getElementsByTagName('tr');
			var s = 0;
			for (var i=1; i < tableRows.length; i++) {
				for(s=0;s<myObject.data.length;s++) { 
					switch(i){
						case 1:			
							tableRows[i].cells[1].firstChild.value = myObject.data[s].FLD1_Visible;
							tableRows[i].cells[2].firstChild.value = myObject.data[s].FLD1_Required;
							break;
						case 2:			
							tableRows[i].cells[1].firstChild.value = myObject.data[s].FLD2_Visible;
							tableRows[i].cells[2].firstChild.value = myObject.data[s].FLD2_Required;
							break;
						case 3:			
							tableRows[i].cells[1].firstChild.value = myObject.data[s].FLD3_Visible;
							tableRows[i].cells[2].firstChild.value = myObject.data[s].FLD3_Required;
							break;
						case 4:			
							tableRows[i].cells[1].firstChild.value = myObject.data[s].FLD4_Visible;
							tableRows[i].cells[2].firstChild.value = myObject.data[s].FLD4_Required;
							break;
					}		
				}
   			}
    	}
	}
}

</script>	
	
	<script language="JavaScript" type="text/javascript">
	 	$(document).ready(function() {
			var ccode = document.getElementById("ccode").value;	
			var pname = document.getElementById("pname").value;	
			if (ccode > '' && pname > '') {
				ajaxwebpages();
			}	
		});
		function close_window() {
			window.close();
		}
	</script>
</head>		
<body>			
	<form id="form1" name="form1" action="newpackage.php" method="post">
	<div id="headerdiv" style="color:#000000; BORDER-TOP: #000000 0px solid; BORDER-RIGHT: #000000 0px solid; PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; OVERFLOW: false; BORDER-LEFT: #000000 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: #000000 0px solid; HEIGHT:100px">
	<center>
	<table width="50%">
			<tr>
				<td align="center">
					<font size="6" face="tahoma" color="blue">Edit Professional License Page</font>
				</td>
			</tr>
		</table>
	</center>	
	</div>	
	<div id="maindiv" style="color:#000000; BORDER-TOP: #000000 0px solid; BORDER-RIGHT: #000000 0px solid; PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; OVERFLOW: auto; BORDER-LEFT: #000000 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: #000000 0px solid; HEIGHT: 400px">
		<center>
			<div id="txtHint"></div>
		</center>
	</div>	
	<br /><br />
	<center>
		<table width="75%">
			<tr>
				<td align="center">
					<input TYPE="button" id="save_page" VALUE="Save Changes" style="font-size:14; font-family=Tahoma; background-color: blue; color:white; border-radius: 8px; padding: 5px 20px;">	
					<input type="button" id="close" style="font-size:14; background-color: red; color:white; border-radius: 8px;  padding: 5px 20px;" onclick="close_window();" value="Close"></input>
				</td>				
			</tr>
		</table>
	</center>
</form>	
</body>
</html>	
<script language="JavaScript" type="text/javascript">
 	$("#save_page").click(function() {	
//		alert("IN save_pages");
		var cocode = document.getElementById("ccode").value;
		var pname = document.getElementById("pname").value;
		var displaymsg1 = document.getElementById("displaymsg1").value;
		var displaymsg2 = document.getElementById("displaymsg2").value;
		var limitby = document.getElementById("limitby").value;
		var limitno = document.getElementById("limitno").value;
		var tableRows = document.getElementById('fldtbl').getElementsByTagName('tr');
		var values = new Array();
	
   		for (var i=1; i < tableRows.length; i++) {
//			alert(tableRows[i].cells[0].firstChild.value+" - "+tableRows[i].cells[1].firstChild.value);
			values.push({'fldname': tableRows[i].cells[0].firstChild.value, 'fldvisible': tableRows[i].cells[1].firstChild.value, 'fldrequired': tableRows[i].cells[2].firstChild.value});
   		}

		$.ajax({
			type: "POST",
			url: "../Ajax/ajax_save_proflicense_page.php", 
			data: {cocode: cocode, pname: pname, limitby: limitby, limitno: limitno, displaymsg1: displaymsg1, displaymsg2: displaymsg2, values: values},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {	
					location.reload();
				}	
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		});
	});
	
</script>	
