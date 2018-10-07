<?php

echo '<form method="post" action="' . $FormAction . '" name="ALCATEL">
				<div class="general-page">
					<div class="sub-menu">&nbsp;</div>

					<div class="sub-page">
						<div class="grid-x margins person-form">

							<div class="cell small-12">
								<h3>
									' . $compname . ' Web Application Portal<br>
									<img src="files/horizontal-line.gif" height="3" width="100%">
								</h3>
							</div>

							<div class="cell small-12">
								<span class="sub-heading">Professional References</span><br>
								List 3 References, which are not related to you and not a current employee of ' . $compname . '.<br />&nbsp;
							</div>';

$maxRefID = $dbo->query("Select max(RefID) from App_References where PersonID = " . $PersonID . ";")->fetchColumn();

if($maxRefID > 0) {
  $selectstmt = "select RefID, RefCompany, RefCompanyPhone, RefFirstName, RefLastName, RefPhone, RefEmail, RefStreet1, RefStreet2, RefCity, RefState, RefZip, RefCounty , RefCountry, RefRelate from App_References where PersonID = :PersonID;";
  $result2 = $dbo->prepare($selectstmt);
  $result2->bindValue(':PersonID', $PersonID);
  $result2->execute();

  while($row = $result2->fetch(PDO::FETCH_BOTH)) {
    echo '    <div class="cell small-3">
 							  ' . htmlspecialchars($row[1]) . '
   					  </div>
  						<div class="cell small-3">
  							' . htmlspecialchars($row[2]) . '
  						</div>
              <div class="cell small-6 right">
  							<span onclick="updateref(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Reference" title="Edit Reference"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  							<span onclick="deleteref(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Reference" title="Delete Reference"/></span>
  						</div>
              <div class="cell small-6">
  							' . htmlspecialchars($row[3]) . '&nbsp;' . htmlspecialchars($row[4]) . '
  						</div>
              <div class="cell small-3">
  							' . htmlspecialchars($row[5]) . '
  						</div>
              <div class="cell small-3">
  							' . htmlspecialchars($row[6]) . '
  						</div>

              <div class="cell small-12">
  							<hr>
  						</div>';
  }
}

echo '				<div class="cell small-12">
								<span class="add-reference add-button"><img class="icon" src="images/plus.png" alt="Add Address" title="Add Address" /> Add Reference</span>
							</div>
							<div class="cell small-12">
								<hr>
							</div>

              <div class="cell small-12">
								<input class="button float-center" type="submit" value="Next">
							</div>

              <div class="grid-x margins person-form" name="Reference_dialog" id="Reference_dialog" title="Dialog Title">
  							<div class="cell small-12 required">
  								* Required Fields To Continue
  							</div>

                <div class="cell medium-6 small-12">
  								Contact First Name <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="reffirstname" id="reffirstname" maxlength="40" placeholder="Required">
  							</div>

  							<div class="cell medium-6 small-12">
  								Contact Last Name <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="reflastname" id="reflastname" maxlength="40" placeholder="Required">
  							</div>

                <div class="cell medium-6 small-12">
  								Contact Phone <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="refphone" id="refphone" maxlength="40" placeholder="### ### #### #####" onkeypress="return numericOnly(event, this);" onKeyUp="return frmtphone(this, \'up\')">
  							</div>

                <div class="cell medium-6 small-12">
  								Contact Email <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="refemail" id="refemail" maxlength="40" placeholder="Required">
  							</div>

                <div class="cell medium-6 small-12">
  								Contact Name <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="refcompanyname" id="refcompanyname" maxlength="40" placeholder="Required">
  							</div>

                <div class="cell medium-6 small-12">
  								Contact Phone <span class="required">*</span>
  							</div>
  							<div class="cell medium-6 small-12">
  								<input type="text" name="refcompanyphone" id="refcompanyphone" maxlength="40" placeholder="### ### #### #####" onkeypress="return numericOnly(event, this);" onKeyUp="return frmtphone(this, \'up\')">
  							</div>

                <div class="cell small-12 padding-bottom">
  								<input id="save_reference" class="float-center" type="button" value="Save Reference">
  							</div>
              </div>

              <input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
              <input type="hidden" name="refid" id="refid">
            </div>
          </div>
        </form>';
?>

<script language="JavaScript" type="text/javascript">
  $("#Reference_dialog").dialog({ autoOpen: false });

  $(".add-reference").click(function() {
    addReference();
  });

  function addReference() {
		$("#refid").val('');
    $("#reffirstname").val('');
    $("#reflastname").val('');
    $("#refphone").val('');
    $("#refemail").val('');
    $("#refcompanyname").val('');
    $("#refcompanyphone").val('');

		$("#Reference_dialog").dialog("option", "title", "Add Reference");
		$("#Reference_dialog").dialog("option", "modal", true);
		$("#Reference_dialog").dialog("option", "width", "100%");
		$("#Reference_dialog").dialog("open");
	}

 	function updateref(refid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_reference.php",
			data: { personid: personid, refid: refid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					$("#refid").val(obj2.RefID);
					$("#reffirstname").val(obj2.RefFirstName);
					$("#reflastname").val(obj2.RefLastName);
					$("#refphone").val(obj2.RefPhone);
					$("#refemail").val(obj2.RefEmail);
					$("#refcompanyname").val(obj2.RefCompany);
					$("#refcompanyphone").val(obj2.RefCompanyPhone);

					$("#Reference_dialog").dialog("option", "title", "Edit Reference");
					$("#Reference_dialog").dialog("option", "modal", true);
					$("#Reference_dialog").dialog("option", "width", "100%");
					$("#Reference_dialog").dialog("open");
				}
        else {
					alert('No Reference Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
        alert('Error: ' + errorThrown);
			}
		});
	}

 	$("#save_reference").click(function() {
		var personid = $("#PersonID").val();
		var refid = $("#dlgrefid").val();
    var saveLocation = "../App_Ajax_New/ajax_add_reference.php";

		if(refid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_reference.php";
		}

		if($("#reffirstname").val() > '') {
			var reffirstname = $("#reffirstname").val();
		}
    else {
			$("#reffirstname").focus();
			alert("Contact First Name is required");
			return;
		}

		if($("#reflastname").val() > '') {
			var reflastname = $("#reflastname").val();
		}
    else {
			$("#reflastname").focus();
			alert("Contact last Name is required");
			return;
		}

		if($("#refphone").val() > '') {
			var refphone = $("#refphone").val();
		}
    else {
			$("#refphone").focus();
			alert("Contact Phone # is required");
			return;
		}

		if($("#refemail").val() > '') {
			var refemail = $("#refemail").val();
		}
    else {
			$("#refemail").focus();
			alert("Contact Email is required");
			return;
		}

		if($("#refcompanyname").val() > '') {
			var refcompanyname = $("#refcompanyname").val();
		}
    else {
			$("#refcompanyname").focus();
			alert("Company Name is required");
			return;
		}

		if($("#refcompanyphone").val() > '') {
			var refcompanyphone = $("#refcompanyphone").val();
		}
    else {
			$("#refcompanyphone").focus();
			alert("Company Phone # is required");
			return;
		}

    var data = {
      personid: personid,
      refid: refid,
      reffirstname: reffirstname,
      reflastname: reflastname,
      refphone: refphone,
      refemail: refemail,
      refcompanyname: refcompanyname,
      refcompanyphone: refcompanyphone
    };

		$.ajax({
			type: "POST",
			url: saveLocation,
			data: data,
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);

				if(obj2.length > 30) {
					alert(obj2);
				}
        else {
					$("#Reference_dialog").dialog("close");
					location.reload();
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
        alert('Error: ' + errorThrown);
			}
		});
	});

	function deleteref(RefID) {
		if(confirm('Are you sure you want to delete this Reference?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_reference.php",
				data: { personid: personid, RefID: RefID },
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					}
          else {
						location.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
          alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_reference").click(function() {
		$("#Reference_dialog").dialog("close");
	});
</script>
