	$('#upload').on('click', function() {
		var personid = document.getElementById("PersonID").value;                             
		var UploadType = document.getElementById("UploadType").value;                             
		var file_data = $('#sortpicture').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('file', file_data);
//    	form_data.append('company', company);
		$.ajax({
            url: 'Upload/Upload.php?PersonID='+personid+'&UploadType='+UploadType, // point to server-side PHP script 
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(php_script_response){
 //               alert(php_script_response);
				var obj2 = $.parseJSON(php_script_response);
                alert(obj2);				
		 		window.location.reload();				
            }
		});
	});	
