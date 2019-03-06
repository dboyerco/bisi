<?php
require_once('../../pdotriton.php');

if(!isset($cocode)) { $cocode = ''; }
if(!isset($package)) { $package = ''; }

if($cocode != '') {
	$coname = $dbo->query("select Company_Name from App_HR_Company where bisAcct = '".$cocode."';")->fetchColumn();
}
else {
	$coname = '';
}

if($package != '') {
	$packagename = $dbo->query("select Package_Value from App_Packages where Company_Name = '".$coname."' and Package_Value = '".$package."';")->fetchColumn();
}
else {
	$packagename = '';
}

$available_pages = array();
$company_pages = array();

if($coname > '' && $packagename > '') {
	$appcnt = $dbo->query("select count(*) from Web_App_Pages;")->fetchColumn();

	$sql = "Select Web_App_Name, Web_App_Value from Web_App_Pages where Web_App_Name not in (Select Web_App_Name from WebApp_Web_Pages Where CompanyID = :cocode and Package_Name = :pname) order by Web_App_Name;";
	$row=$dbo->prepare($sql);
	$row->bindValue(':cocode', $cocode);
	$row->bindValue(':pname', $packagename);
	$row->execute();

	while($result=$row->fetch(PDO::FETCH_BOTH)) {
		$available_pages[] = array('appname' => $result['Web_App_Name'], 'appvalue' => $result['Web_App_Value']);
	}

	$sql = "Select Web_App_Name, Web_Page_Number from WebApp_Web_Pages Where CompanyID = :cocode and Package_Name = :pname order by Web_Page_Number;";
	$row=$dbo->prepare($sql);
	$row->bindValue(':cocode', $cocode);
	$row->bindValue(':pname', $packagename);
	$row->execute();

	while($result=$row->fetch(PDO::FETCH_BOTH)) {
		$company_pages[] = array('appname' => $result['Web_App_Name'], 'apppageno' => $result['Web_Page_Number']);
	}
#	echo "Availilabe Pages: ".count($available_pages)."<br />";
#	echo "Company Pages: ".count($company_pages)."<br />";
}
#echo "COname: ".$coname."<br />";
#echo "Pname: ".$packagename."<br />";
echo "<input type=\"hidden\" name=\"ccode\" id=\"ccode\" value=\"$cocode\">";
echo "<input type=\"hidden\" name=\"pname\" id=\"pname\" value=\"$packagename\">";
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
			.dragzones, .dropzones {
				width: 100px;
				min-height: 20px;
				list-style-type: none;
				margin: 0;
				padding: 5px 0 0 0;
				float: right;
				margin-right: 20px;
			}

			.dragzones {
				margin: 0 2px 2px 2px;
				cursor: move;
				padding: 2px;
				font-size: 1.0em;
	    		width: 275px;
			}

			#webpages, #availablepages {
				border: 1px solid green;
				width: 300px;
				min-height: 20px;
				list-style-type: none;
				margin: 0;
				padding: 5px 0 0 0;
				float: left;
				margin-right: 20px;
			}

			#awebpages, #cpwebpages {
				border: 1px solid green;
				width: 300px;
				min-height: 20px;
				list-style-type: none;
				margin: 0;
				padding: 5px 0 0 0;
				float: left;
				margin-right: 20px;
			}
		</style>
		<script language="JavaScript" type="text/javascript">
			var pages = new Array();

		 	$(document).ready(function() {
				var ccode = document.getElementById("ccode").value;
				var pname = document.getElementById("pname").value;
				document.getElementById("coname").value = ccode;
				document.getElementById("package").value = pname;
				var bodyheight = $(window).height() - 75;
				var bodywidth = $(window).width() - 10;
				$(".drop_menu").css({"width":bodywidth});
				$("#headerdiv").css({"width":bodywidth});
			});

			// for the window resize
			$(window).resize(function() {
				var bodyheight = $(window).height() - 75;
				var bodywidth = $(window).width() - 10;
				$(".drop_menu").css({"width":bodywidth});
				$("#headerdiv").css({"width":bodywidth});
			});

			function setconame() {
				var cocode = document.getElementById("coname").value;
				var packagename = '';
				window.location = "webpages.php?cocode="+cocode+"&package="+packagename;
			}

			function setpackagename() {
				var cocode = document.getElementById("coname").value;
				var packagename = document.getElementById("package").value;
				window.location = "webpages.php?cocode="+cocode+"&package="+packagename;
			}

			$(function() {
				$("#webpages, #availablepages").sortable({
					connectWith: ".connectedSortable"
				}).disableSelection();
			});
		</script>
	</head>

	<body>
	<form id="form1" name="form1" action="newpackage.php" method="post">
	<div id="headerdiv" style="color:#000000; BORDER-TOP: #000000 0px solid; BORDER-RIGHT: #000000 0px solid; PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; OVERFLOW: false; BORDER-LEFT: #000000 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: #000000 0px solid; Width: 950px; HEIGHT:150px">
	<nav>
		<ul class="drop_menu">
			<li><a href="https://proteus.bisi.com/HRUsers/1.html"><span style="white-space: nowrap;">Menu</span></a></li>
		</ul>
	</nav>

	<table width="100%">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="center"><font size="6" face="tahoma" color="blue">Add/Edit Web Pages</font></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<center>
	<table width="50%">
		<tr>
			<td align="center">Select a Company</td>
			<td align="center">Select a Package Name</td>
		</tr>
		<tr>
			<td align="center" width="25%">
				<select name="coname" id="coname" onchange="setconame()" style="border-radius: 5px; padding: 5px 2px;">
					<option value=""></option>
					<?php
						$sql = "Select bisAcct, Company_Name from App_HR_Company order by Company_Name";
						$result = $dbo->prepare($sql);
						$result->execute();
						while($rows = $result->fetch(PDO::FETCH_BOTH)) {
							echo "<option value='".$rows[0]."'>".$rows[1]."</option>";
						}
					?>
				</select>
			</td>
			<td align="center" width="25%">
				<select name="package" id="package" onchange="setpackagename()" style="border-radius: 5px; padding: 5px 0px;">
					<option VALUE=""></OPTION>
					<?php
						$sql = "Select Package_Name, Package_Value from App_Packages where Company_Name = '".$coname."';";
						$presult = $dbo->prepare($sql);
						$presult->execute();
						while($prows = $presult->fetch(PDO::FETCH_BOTH)) {
							echo "<option value='".$prows[1]."'>".$prows[0]."</option>";
						}
					?>
				</select>
			</td>
		</tr>
	</table>
	</center>

	</div>
	<center>
	<div id="maindiv" align="center" style="text-align: left	; color:#000000; BORDER-TOP: #000000 0px solid; BORDER-RIGHT: #000000 0px solid; PADDING-RIGHT: 0px; PADDING-LEFT: 300px; PADDING-BOTTOM: 0px; OVERFLOW: auto; BORDER-LEFT: #000000 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: #000000 0px solid; Width: 950px; HEIGHT: 500px;">
		<br /><br />
		<?php
			if ($coname > '' && $packagename > '') {
				$s = 1;
				$ccnt = count($company_pages) - 1;
				$acnt = count($available_pages) - 1;
				echo '<div id="awebpages"><b>Available Web Pages</b></div>';
				echo '<div id="cpwebpages"><b>Existing Web Pages</b>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;
				<input type="button" id="displaypage" onclick="displaywebpage()" value="Display Pages" style="border-radius:8px; background-color: blue; color:white;"></div>';

				echo '<div id="availablepages" class="connectedSortable">';

				for($i = 0; $i <= $acnt; $i++) {
					echo '<div class="dragzones"><span>'.$available_pages[$i]['appname'].'</span></div>';
					$s++;
				}
				echo '</div>';
				echo '<div id="webpages" class="connectedSortable">';
				$s = 1;
				for($i = 0; $i <= $ccnt; $i++) {
					switch ($company_pages[$i]['appname']) {
						case 'Driver License':
							$page = 'DMV';
							break;
						case 'Credit Card':
							$page = 'CardInfo';
							break;
						case 'Landlords':
							$page = 'Landlord';
							break;
						case 'Professional License':
							$page = 'ProfLicense';
							break;
						case 'References':
							$page = 'Reference';
							break;
						case 'Last Page':
							$page = 'Thanks';
							break;
						default:
							$page = $company_pages[$i]['appname'];
					}
					echo '<div class="dragzones"><span>'.$company_pages[$i]['appname'].'</span>';
					if ($company_pages[$i]['appname'] != 'Final Thanks') {
#						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<div class="dropzones"><input type="button" id="editpage'.$s.'" onclick="editwebpage(\''.$page.'\')" value="Edit Page" style="border-radius:8px; background-color: green; color:white;"></div>';
					}
					echo '</div>';
					$s++;
				}
				echo '</div><br /><br />';
			}
		?>
	</div>
	</center>
	<br />
	<table width="100%">
		<tr>
			<td align="center">
				<input TYPE="button" id="save_pages" VALUE="Save Changes" style="font-size:14; font-family=Tahoma; background-color: blue; color:white; border-radius: 8px; padding: 5px 20px;">
			</td>
		</tr>
	</table>
</form>
</body>
</html>
<script language="JavaScript" type="text/javascript">
	function editwebpage(page) {
//		alert(page);
		var cocode = document.getElementById("ccode").value;
//		alert(cocode);
		var pname = document.getElementById("pname").value;
//		alert(pname);
		var s = 0;
		window.open("https://proteus.bisi.com/HRUsers/WebPages/"+page+"page.php?cocode="+cocode+"&package="+pname);
	};

	function displaywebpage() {
		window.open("https://proteus.bisi.com/Web_App_Pages/index.php?PersonID=0");
	};

 	$("#save_pages").click(function() {
//		alert("IN save_pages");
		var cocode = document.getElementById("ccode").value;
		var pname = document.getElementById("pname").value;
		var values = new Array();
		var webpage = '';
		var cnt = 0;
		var pages = $("#webpages > div").length
		alert(pages + " pages to save");
		$('#webpages div').each(function() {
    		webpage = $(this).find("span").text();
//    		alert("webpage: "+webpage);
			if (webpage > '') {
				values.push({'appname': webpage});
//				alert("Page: "+webpage);
				cnt++;
			}
		});

//   		alert(cnt);
   		if (cnt > 0) {
			$.ajax({
				type: "POST",
				url: "../../../HRUsers/Ajax/ajax_save_web_pages.php",
				data: {cocode: cocode, pname: pname, values: values},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2 > '' ) {
						alert(obj2);
					}
					else {
						location.reload();
					}

					return;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
		else {
			alert('No Web Pages selected to save');
		}
	});
</script>
