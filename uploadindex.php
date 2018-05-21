<?
require_once('../pdotriton.php');
$mobile_browser = '0';
 
if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
}
 
if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
    $mobile_browser++;
}    
 
$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
$mobile_agents = array(
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
    'wapr','webc','winw','winw','xda ','xda-');
 
if (in_array($mobile_ua,$mobile_agents)) {
    $mobile_browser++;
}
 
#if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
#    $mobile_browser++;
#}
 
if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
    $mobile_browser = 0;
}
 
if ($mobile_browser > 0) {
	echo '<html><body><br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">PLEASE UTILIZE A COMPUTER TO COMPLETE THIS SUBMISSION - Phones and tablets are not supported.</td></tr><tr><td>&nbsp;</td></table></body></html>';
}
else {
	if (!isset($PersonID)) {
		echo '<html><body><br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the Background Screen Application please use the link in the email that was sent to you.</td></tr><tr><td>&nbsp;</td></table></body></html>';
	} else {
		$compname = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
		$codeid = $dbo->query("Select CodeID from App_Person where PersonID = ".$PersonID.";")->fetchColumn();
		if (!isset($CD)) {
			$CD = '';
		}	
		$cnt = 0;
		$end = strrpos($_SERVER['REQUEST_URI'], '/');	
		$appname = substr($_SERVER['REQUEST_URI'],1,$end - 1);
		$cnt = $dbo->query("Select count(*) from App_Person where PersonID = ".$PersonID." and App_Name = '".$appname."';")->fetchColumn();
		if ($PersonID == '' || $cnt == 0 || $codeid != $CD) {
			if ($cnt == 0) { 
				echo '<html><body><br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:large; font-family=Tahoma; color:red;">Invalid PersonID.</span><br /><span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the Background Screen Application please use the link in the email that was sent to you.</td></tr><tr><td>&nbsp;</td></table></body></html>';
			} else {
				echo '<html><body><br /><table style="border:5px solid black; border-radius:10px;"><tr><td>&nbsp;</td></tr><tr><td><span style="font-size:medium; font-family=Tahoma; color:#000000;">To access the Background Screen Application please use the link in the email that was sent to you.</span></td></tr><tr><td>&nbsp;</td></table></body></html>';
			}	
		} else { 	
			echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
			<html>
			<head>
			<title>'.$compname.' Background Screen Application</title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			</head>
			<frameset rows="80,*" cols="*" frameborder="NO" border="0" framespacing="0">
				<frame src="heading.html" name="topFrame" scrolling="NO" noresize >
				<frameset bgcolor="#E5EAE4" rows="*" cols="80,790,*" bgcolor="#E5EAE4" framespacing="0" frameborder="NO" border="0">
					<frame rows="*" cols="40,*" src="nav.html" name="leftFrame" scrolling="NO" >
					<frame rows="*" cols="763,*" src="uploadrelease.php?PersonID='.$PersonID.'" name="mainFrame" >
					<frame rows="*" cols="*,*" src="nav.html" name="leftFrame" scrolling="NO" >
				</frameset>
			</frameset>
			<noframes><body>
			</body></noframes>
			</html>';
		}
	}	
}   
?>	
