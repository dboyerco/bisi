<?php
	require_once('../pdotriton.php');
	$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
	$FormAction = "uploadrelease.php?PersonID=".$PersonID;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>


<title>BIS Online Background Screen Application</title><!-- InstanceEndEditable --><meta
http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="files/default.css" type="text/css" rel="stylesheet">
<link href="Upload/Upload.css" rel="stylesheet" type="text/css" />		
<script language="JavaScript" type="text/javascript" src="js/validate.js"></script>
<script language="JavaScript" type="text/javascript" src="js/autoTab.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
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
	<table>	
	
<?php 
echo "<FORM METHOD=\"GET\" ACTION=\"$FormAction\" NAME=\"ALCATEL\">";
?>
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="763">
		<tbody>
			<tr>
				<td>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Upload Parent's Release Form </strong></font></p> 
<p><font face="Verdana, Arial, Helvetica,sans-serif"><br /><br />
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href='#openPhotoDialog'><strong>Click here to Upload the Document.</a></strong></font></p>
<br /><br />
If you have any issues in uploading the form please contact BISI at service@bisi.com or call 303-442-3960.<br />
<br /><br />
Thank you 
				</td>
			</tr>
		</tbody>
	</table>		
<?
	include ('Upload/UploadDialog.php');

echo "<INPUT TYPE=\"hidden\" NAME=\"PersonID\" id=\"PersonID\" VALUE=\"$PersonID\">
	  <INPUT TYPE=\"hidden\" NAME=\"UploadType\" id=\"UploadType\"VALUE=\"Disclosure Authorization Parent\">
  	  </FORM></body></html>"; 	  
?>
 <script language="JavaScript" type="text/javascript">
	function close_window(){
        Window.top.close();    	
 	}	
 function downloadForm(){
   		el = document.getElementById("submitid");
   		elbutton = document.getElementById("dlform");
		elbutton.style.visibility = "hidden";
		el.style.visibility = "visible";
		window.open('https://proteus.bisi.com/docs/Foothills Park and Recreation District.pdf');
	}	
</script>
<script src="Upload/Upload.js"></script>
