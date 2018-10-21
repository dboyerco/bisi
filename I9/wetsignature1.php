<?php
session_start();

$PersonID = $_REQUEST['PersonID'];
$SignType = $_REQUEST['SignType'];
if (isset($_REQUEST['CD'])) {
	$CodeID = $_REQUEST['CD'];
} else {
	$CodeID = $_SESSION['SESS_CodeID'];
}	
if (isset($_REQUEST['JobId'])) {
	$JobID = $_REQUEST['JobId'];
} else {
	$JobID = $_SESSION['SESS_JobID'];
}	
if (isset($_REQUEST['CustomerId'])) {
	$CustomerID = $_REQUEST['CustomerId'];
} else {
	$CustomerID = $_SESSION['SESS_CustomerID'];
}	

#echo "CodeID: ".$CodeID."<br />";

	define('UPLOAD_DIR', 'Signature/');
#    $signature = $_REQUEST['signature'];
#	echo 'PersonID = '.$PersonID.' - Type '.$SignType.'<br />'; 

    if (array_key_exists('imageData',$_REQUEST)) {
    
    	$imgData = $_REQUEST['imageData'];
		$imgData = str_replace('data:image/png;base64,','',$imgData);
		$imgData = str_replace(' ','+',$imgData);
		$data = base64_decode($imgData);
		$file = UPLOAD_DIR.'PersonID-'.$PersonID.$SignType.'.png';
#		echo 'filename = '.$file; 
		$success = file_put_contents($file, $data);

#		echo '<script type="text/javascript">
#				window.history.go(-2)</script>';
#		$htmlredirect = "https://proteus.bisi.com/tenant/action.php?RefID=".$RefID."&Stage=3";
#		header("Location: ".$htmlredirect); 
#		exit;
#		print $success ? '<script type="text/javascript">window.history.go(-2)</script>': 'Unable to save the file';
		$JobID = $_SESSION['SESS_JobID'];
		$CustomerID = $_SESSION['SESS_CustomerID'];
		$CodeID = $_SESSION['SESS_CodeID'];
		print $success ? '<script type="text/javascript">window.location = "I9Section1.php?PersonID='.$PersonID.'&JobId='.$JobID.'&CustomerId='.$CustomerID.'&CD='.$CodeID.'"</script>': 'Unable to save the file';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8" />
   <title>Wet Signature</title>
   <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  
   <script type="text/javascript">
      $(document).ready(function () {
         initialize();
      });
 

      // works out the X, Y position of the click inside the canvas from the X, Y position on the page
      function getPosition(mouseEvent, sigCanvas) {
         var x, y;
         if (mouseEvent.pageX != undefined && mouseEvent.pageY != undefined) {
            x = mouseEvent.pageX;
            y = mouseEvent.pageY;
         } else {
            x = mouseEvent.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            y = mouseEvent.clientY + document.body.scrollTop + document.documentElement.scrollTop;
         }
 
         return { X: x - sigCanvas.offsetLeft, Y: y - sigCanvas.offsetTop };
      }
      function resetcanvas() {
         var sigCanvas = document.getElementById("canvasSignature");
         var context = sigCanvas.getContext("2d");
		 context.clearRect(0,0,sigCanvas.width, sigCanvas.height);		  
	  }	 
      function initialize() {
         // get references to the canvas element as well as the 2D drawing context
         var sigCanvas = document.getElementById("canvasSignature");
         var context = sigCanvas.getContext("2d");
		 context.clearRect(0,0,sigCanvas.width, sigCanvas.height);	
         context.strokeStyle = 'Black';
		 context.lineWidth = '1';	

         // This will be defined on a TOUCH device such as iPad or Android, etc.
         var is_touch_device = 'ontouchstart' in document.documentElement;
 
         if (is_touch_device) {
            // create a drawer which tracks touch movements
            var drawer = {
               isDrawing: false,
               touchstart: function (coors) {
                  context.beginPath();
                  context.moveTo(coors.x, coors.y);
                  this.isDrawing = true;
               },
               touchmove: function (coors) {
                  if (this.isDrawing) {
                     context.lineTo(coors.x, coors.y);
                     context.stroke();
                  }
               },
               touchend: function (coors) {
                  if (this.isDrawing) {
                     this.touchmove(coors);
                     this.isDrawing = false;
                  }
               }
            };
 
            // create a function to pass touch events and coordinates to drawer
            function draw(event) {
 
               // get the touch coordinates.  Using the first touch in case of multi-touch
               var coors = {
                  x: event.targetTouches[0].pageX,
                  y: event.targetTouches[0].pageY
               };
 
               // Now we need to get the offset of the canvas location
               var obj = sigCanvas;
 
               if (obj.offsetParent) {
                  // Every time we find a new object, we add its offsetLeft and offsetTop to curleft and curtop.
                  do {
                     coors.x -= obj.offsetLeft;
                     coors.y -= obj.offsetTop;
                  }
				  // The while loop can be "while (obj = obj.offsetParent)" only, which does return null
				  // when null is passed back, but that creates a warning in some editors (i.e. VS2010).
                  while ((obj = obj.offsetParent) != null);
               }
 
               // pass the coordinates to the appropriate handler
               drawer[event.type](coors);
            }
 

            // attach the touchstart, touchmove, touchend event listeners.
            sigCanvas.addEventListener('touchstart', draw, false);
            sigCanvas.addEventListener('touchmove', draw, false);
            sigCanvas.addEventListener('touchend', draw, false);
 
            // prevent elastic scrolling
            sigCanvas.addEventListener('touchmove', function (event) {
               event.preventDefault();
            }, false); 
         }
         else {
            // start drawing when the mousedown event fires, and attach handlers to
            // draw a line to wherever the mouse moves to
            $("#canvasSignature").mousedown(function (mouseEvent) {
               var position = getPosition(mouseEvent, sigCanvas);
 
               context.moveTo(position.X, position.Y);
               context.beginPath();
 
               // attach event handlers
               $(this).mousemove(function (mouseEvent) {
                  drawLine(mouseEvent, sigCanvas, context);
               }).mouseup(function (mouseEvent) {
                  finishDrawing(mouseEvent, sigCanvas, context);
               }).mouseout(function (mouseEvent) {
                  finishDrawing(mouseEvent, sigCanvas, context);
               });
            });
 
         }
      }
 
      // draws a line to the x and y coordinates of the mouse event inside
      // the specified element using the specified context
      function drawLine(mouseEvent, sigCanvas, context) {
         var position = getPosition(mouseEvent, sigCanvas);
	//	 alert('X = '+position.X+' Y = '+position.Y);
         context.lineTo(position.X, position.Y);
         context.stroke();
      }
 
      // draws a line from the last coordiantes in the path to the finishing
      // coordinates and unbind any event handlers which need to be preceded
      // by the mouse down event
      function finishDrawing(mouseEvent, sigCanvas, context) {
         // draw the line to the finishing coordinates
         drawLine(mouseEvent, sigCanvas, context);
         context.closePath();
         // unbind any events which could draw
         $(sigCanvas).unbind("mousemove")
                     .unbind("mouseup")
                     .unbind("mouseout");
      }
	function UploadPic(PersonID, SignType) {
    	// Generate the image data
    	var Pic = document.getElementById("canvasSignature").toDataURL("image/png");
    	// Sending the image data to Server
		//	alert('About to redirect to export.php');
		imageData = document.getElementById("imageData");
		imageData.value = Pic;
		personid = document.getElementById("PersonID");
		personid.value = PersonID;
		signtype = document.getElementById("SignType");
		signtype.value = SignType;
//		alert("CodeID: "+CodeID.value);
 		//window.open('https://proteus.bisi.com/dbdemo/export.php?imageData='+Pic,'ExportWindow');
 	//	window.open('https://proteus.bisi.com/dbdemo/DrawLineSave.php','ExportWindow');
	}
   </script>
  
</head>
 
<body bgcolor="#E4E8E8")>

<!--<p><img src="../files/hdspacer.gif"></p>-->

<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td></td>
    <td class="submenu" height="27" width="763">&nbsp;</td>
  </tr>
</tbody></table>
 	<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="10" width="770">
  	<tbody><tr>
    	<td width="15"></td>
    	<td width="597">
	 	<h3 align="left"> 
			<font color='#00248E'>
				<?php 
					if ($SignType == 'Emp') {
						echo 'Form I-9 Employee Signature';
					}
					if ($SignType == 'Prep') {
						echo 'Form I-9 Preparer and/or Translator Signature';
					}
				?>		
			</font> 
		</h3>
</td>
  </tr>
</tbody></table>
	<form action='wetsignature1.php?PersonID=<? echo $PersonID ?>&CD=<? echo $CodeID ?>&SignType=<? echo $SignType ?>' id='form1'>
	 	<div name="canvasDiv" id="canvasDiv" style="visibility: visible; margin: auto auto;background-color:#E4E8E8;padding:35px;">
			<p>Please use your mouse to sign your name in the box below.</p>
      		<canvas id="canvasSignature" width="500px" height="75px" style="border:1px solid #000000;"></canvas>
			<br />
			<br />
   		</div>
		<div id="buttoncontainer" style="display:block;background-color:#E4E8E8;padding:35px;">
			<input type="submit" id="savepngbtn" name="savepngbtn" value="Save Signature" onClick="UploadPic(<? echo $PersonID ?>, '<? echo $SignType ?>');">
			<input type="reset" value="Clear" onClick="resetcanvas();" />
		</div>
		<input type="hidden" name="imageData" id="imageData" />
		<input type="hidden" name="PersonID" id="PersonID" />
		<input type="hidden" name="SignType" id="SignType" />
		
	</form>
</body>
</html> 
