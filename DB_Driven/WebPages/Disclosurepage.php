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
	<title>Add HR Users</title>
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
		var url="../Ajax/ajax_disclosure_page.php";
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
//			alert(myObject.length);
			if (myObject.length > 0) { 
				var str = '<br /><center><table id="tbl1" cellpadding="0" cellspacing="0" width="90%"><tbody>';
				str = str + '<tr><td><textarea id="displaymsg1" rows="2" cols="175">'+myObject[0].Display_Msg1+'</textarea></td></tr>';
				str = str + '<tr><td><textarea id="displaymsg2" rows="2" cols="175">'+myObject[0].Display_Msg2+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg3" rows="2" cols="175">'+myObject[0].Display_Msg3+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg4" rows="2" cols="175">'+myObject[0].Display_Msg4+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg5" rows="2" cols="175">'+myObject[0].Display_Msg5+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg6" rows="2" cols="175">'+myObject[0].Display_Msg6+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg7" rows="2" cols="175">'+myObject[0].Display_Msg7+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg8" rows="2" cols="175">'+myObject[0].Display_Msg8+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg9" rows="2" cols="175">'+myObject[0].Display_Msg9+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg10" rows="2" cols="175">'+myObject[0].Display_Msg10+'</textarea></td></tr>'; 
				str = str + '<tr><td><textarea id="displaymsg11" rows="2" cols="175">'+myObject[0].Display_Msg11+'</textarea></td></tr>'; 
				str = str + '</tbody></table></center><br />'; 
			}
			document.getElementById("txtHint").innerHTML=str;
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
					<font size="6" face="tahoma" color="blue">Edit Disclosure Page</font>
				</td>
			</tr>
		</table>
	</center>	
	</div>	
	<div id="maindiv" style="color:#000000; BORDER-TOP: #000000 0px solid; BORDER-RIGHT: #000000 0px solid; PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; OVERFLOW: auto; BORDER-LEFT: #000000 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: #000000 0px solid; HEIGHT: 425px">
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
		var displaymsg3 = document.getElementById("displaymsg3").value;
		var displaymsg4 = document.getElementById("displaymsg4").value;
		var displaymsg5 = document.getElementById("displaymsg5").value;
		var displaymsg6 = document.getElementById("displaymsg6").value;
		var displaymsg7 = document.getElementById("displaymsg7").value;
		var displaymsg8 = document.getElementById("displaymsg8").value;
		var displaymsg9 = document.getElementById("displaymsg9").value;
		var displaymsg10 = document.getElementById("displaymsg10").value;
		var displaymsg11 = document.getElementById("displaymsg11").value;

		$.ajax({
			type: "POST",
			url: "../Ajax/ajax_save_disclosure_page.php", 
			data: {cocode: cocode, pname: pname, displaymsg1: displaymsg1, displaymsg2: displaymsg2, displaymsg3: displaymsg3, displaymsg4: displaymsg4, displaymsg5: displaymsg5, displaymsg6: displaymsg6, displaymsg7: displaymsg7, displaymsg8: displaymsg8, displaymsg9: displaymsg9, displaymsg10: displaymsg10, displaymsg11: displaymsg11},
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
