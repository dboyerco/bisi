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
		var url="Ajax/ajax_web_pages.php";
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
			var str = "<table id='tbl' cellpadding='0' cellspacing='0' class='db-table' width='50%'><thread><tr><th>Web App Name</th><th>Page Number</th></tr></thread><tbody>"; 
//			alert(myObject.data.length);
			for(i=0;i<myObject.data.length;i++) { 
				str = str +	'<tr><td width="25%" align="center"><select name="appname"><option value=""></option>';
				for(s=0;s<myObject.data03.length;s++) {
					str = str +	'<option value="'+myObject.data03[s].Web_App_Value+'">'+myObject.data03[s].Web_App_Name+'</option>';
				}
				str = str +	'</select></td><td width="25%" align="center"><select name="pageno"><option value=""></option><option value="0">0</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option></select></td></tr>';
			}	
			str = str + "</tbody></table></div></section><br /><br />";
			
			document.getElementById("txtHint").innerHTML=str;
			
			var tableRows = document.getElementById('tbl').getElementsByTagName('tr');
			var s = 0;
   			for (var i=1; i < tableRows.length; i++) {
				if (s <= myObject.data02.length) {
					tableRows[i].cells[0].firstChild.value = myObject.data02[s].Web_App_Name;
					tableRows[i].cells[1].firstChild.value = myObject.data02[s].Web_Page_Number;
					s++;
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
					<font size="6" face="tahoma" color="blue">Edit Credit Card Page</font>
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
 	$("#save_pages").click(function() {	
//		alert("IN save_pages");
		var cocode = document.getElementById("coname").value;
		var pname = document.getElementById("package").value;

		var tableRows = document.getElementById('tbl').getElementsByTagName('tr');
		var values = new Array();
	
   		for (var i=1; i < tableRows.length; i++) {
			if (tableRows[i].cells[1].firstChild.value > '0') {
//				alert(tableRows[i].cells[0].firstChild.value+" - "+tableRows[i].cells[1].firstChild.value);
				values.push({'appname': tableRows[i].cells[0].firstChild.value, 'pageno': tableRows[i].cells[1].firstChild.value});
			}
   		}

		$.ajax({
			type: "POST",
			url: "ajax_save_web_pages.php", 
			data: {cocode: cocode, pname: pname, values: values},
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
