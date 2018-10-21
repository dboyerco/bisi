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
	//	alert(Pic);
 		//window.open('https://proteus.bisi.com/dbdemo/export.php?imageData='+Pic,'ExportWindow');
 	//	window.open('https://proteus.bisi.com/dbdemo/DrawLineSave.php','ExportWindow');
	}

	$('#upload').on('click', function() {
//			alert("upload was clicked");
//		var personid = document.getElementById("PersonID").value;                             
//		var AppName = document.getElementById("AppName").value;                             
//			alert("AppName: "+AppName);
		var file_data = $('#sortcsv').prop('files')[0];   
		if (file_data > '') {		
			var form_data = new FormData();                  
			form_data.append('file', file_data);
//    		form_data.append('company', company);
			$.ajax({
				url: 'Upload/Upload.php', // point to server-side PHP script 
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(php_script_response){
 //             	  alert(php_script_response);
					var obj2 = $.parseJSON(php_script_response);
					var end = obj2.indexOf('was');
					end = end - 1;
//					alert(end);
					if (end > 0) { 
						var filename = obj2.substr(0,end);
						$.ajax({
							url: 'SendEmailCSVData.php', // point to server-side PHP script 
							data: {filename: filename},
							datatype: "JSON",
							type: 'post',
							success: function(php_script_response){
 //               			alert(php_script_response);
								var obj2 = $.parseJSON(php_script_response);
								alert(obj2);	
							},	
							error: function(XMLHttpRequest, textStatus, errorThrown) {
								alert('Status: '+textStatus); 
								alert('Error: '+errorThrown);
							} 					
						});
					} else {	
						alert(obj2);	
					}	
//		 			window.location.reload();				
				}
			});
		} else {
			alert("Please select the file you want to upload");
			return false;
		}
	});	
	
	$('#uploaddoc').on('click', function() {
		if (document.getElementById("person").value > '') {		
			var personid = document.getElementById("person").value;  
			var UploadType = 'Document';                             
			var file_data = $('#sortdoc').prop('files')[0]; 
			if (file_data > '') {		
				var form_data = new FormData();                  
				form_data.append('file', file_data);
				$.ajax({
					url: 'Upload/UploadDoc.php?PersonID='+personid+'&UploadType='+UploadType, // point to server-side PHP script 
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(php_script_response){
 //               		alert(php_script_response);
						var obj2 = $.parseJSON(php_script_response);
						alert(obj2);				
						window.location.reload();				
					}
				});
			} else {
				alert("Please select the document you want to upload");
				return false;
			}
		} else {
			alert("Please select the name of the person the document is for.");
			return false;
		}
	});	
	$('#viewdoc').on('click', function() {
		if (document.getElementById("doc").value > '') {		
			var image = document.getElementById("doc").value;
//			window.location ='../../bisi_uploads/'+image;		
			window.open('https://proteus.bisi.com/bisi_uploads/'+image);	
		} else {
			alert("Please select the document you want to view");
			return false;
		}
		return true;
	});	

	function loaddocs(personid) {
//		alert(personid);
		$.ajax({
			type: "POST",
			url: "../../App_Ajax/ajax_load_documents.php", 
			data: {personid: personid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) { 
					$('#doc').find('option').remove();
					$('#doc').append('<option value="">Select a Document</option>');
					for (var i = 0; i < obj2.length; i++) {	
						var Image_URL = obj2[i].Image_URL;
						$('#doc').append('<option value="' + Image_URL + '">' + Image_URL + '</option>');	
					}	
				} else {
					alert('No Documents Found for Person Selected');
				}
				return;
			},	
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			} 					
		}); 		
	}	
