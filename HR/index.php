<?php
  if(isSet($_GET['APP']) && isSet($_GET['APP']) != "") {
    $app = $_GET['APP'];
  }
  else {
    die("This is not a valid URL on this system!");
  }

  require_once('../../pdotriton.php');

  $sql = "SELECT bisAcct FROM App_HR_Company WHERE App_Name = :App_Name";
	$result = $dbo->prepare($sql);
	$result->bindValue(':App_Name', $app);
	$result->execute();
  $row = $result->fetch(PDO::FETCH_BOTH);
  $cocode = $row['bisAcct'];

  if($cocode == "") {
    die("This is not a valid URL on this system!");
  }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
  <head>
    <title>BISI CARE Portal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <style type="text/css">
      @media screen and (max-width: 1020px) {
        #container, #header, #content, #footer {
          float: none;
          width: auto;
        }

        #subtitle, #share, #slider, #sidebar{
          display:none;
        }

        p {
          font-size: 2em;
        }
      }
    </style>
  </head>

  <frameset rows="100,*" cols="*" frameborder="NO" border="0" framespacing="0">
    <frame src="heading.html" name="topFrame" scrolling="NO" noresize >
    <frameset bgcolor="#E5EAE4" rows="*" cols="5%,*,5%" bgcolor="#E5EAE4" framespacing="0" frameborder="NO" border="0">
      <frame src="nav.html" name="leftFrame" scrolling="NO" >
      <frame src="../../HRLogin/loginform.php?CO=<?php echo $cocode; ?>&APP=<?php echo $app; ?>" name="mainFrame" >
      <frame src="nav.html" name="leftFrame" scrolling="NO" >
    </frameset>
  </frameset>
  <noframes>
    <body></body>
  </noframes>
</html>
