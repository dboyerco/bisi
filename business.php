<?
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
								<span class="sub-heading">Business Information</span><br>
								Please provide your business information.<br />&nbsp;
							</div>';

	$maxBusinessID = $dbo->query("Select max(RecID) from App_Business where PersonID = " . $PersonID . ";")->fetchColumn();

	if($maxBusinessID > 0) {
		$selectbus = "select RecID, BusinessID, Business_Name, Business_Address, Business_City, Business_State, Business_Zip, Formation_Date, Registered_Agents, Agent_Address, Status from App_Business where PersonID = :PersonID;";
		$Business_result = $dbo->prepare($selectbus);
		$Business_result->bindValue(':PersonID', $PersonID);
		$Business_result->execute();

		while($row = $Business_result->fetch(PDO::FETCH_BOTH)) {
			$formation_date	= date("m/d/Y", strtotime($row[7]));

			echo '<div class="cell small-6 sub-heading">
							' . htmlspecialchars($row[1]) . '
						</div>
						<div class="cell small-6 right">
							<span onclick="updatebusiness(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" height="15" width="15" alt="Edit Business" title="Edit Business"/></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span onclick="deletebusiness(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" height="15" width="15" alt="Delete Business" title="Delete Business"/></span>
						</div>

						<div class="cell small-6">
							' . htmlspecialchars($row[2]) . '
						</div>
						<div class="cell small-6">
							' . htmlspecialchars($row[3]) . '
						</div>

						<div class="cell small-6">
							' . htmlspecialchars($row[4]) . '
						</div>
						<div class="cell small-6">
							' . htmlspecialchars($row[5]) . '
						</div>

						<div class="cell small-6">
							' . htmlspecialchars($row[6]) . '
						</div>
						<div class="cell small-6">
							' . htmlspecialchars($formation_date) . '
						</div>

						<div class="cell small-6">
							' . htmlspecialchars($row[8]) . '
						</div>
						<div class="cell small-6">
							' . htmlspecialchars($row[9]) . '
						</div>

						<div class="cell small-12">
							' . htmlspecialchars($row[10]) . '
						</div>

						<div class="cell small-12">
							<hr>
						</div>';
		}
	}

	echo '		<div class="cell small-12">
							<span class="add-business add-button"><img class="icon" src="images/plus.png" alt="Add Business" title="Add Business" /> Add Business</span>
						</div>
						<div class="cell small-12">
							<hr>
						</div>';

	if($maxBusinessID > 0) {
		echo '	<div class="cell small-6">
							<input class="button button-prev float-center" type="button" value="Prev">
						</div>
						<div class="cell small-6">
							<input class="button float-center" type="submit" value="Next">
						</div>';
	}

	echo '	</div>

					<div class="grid-x margins person-form" name="Business_dialog" id="Business_dialog" title="Dialog Title">
						<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
						<input type="hidden" name="recid" id="recid">

						<div class="cell small-12 required">
							* Required Fields To Continue
						</div>

						<div class="cell medium-6 small-12">
							Business ID <span class="required">*</span>
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="businessid" id="businessid" maxlength="100" placeholder="Required">
						</div>

						<div class="cell medium-6 small-12">
							Business Name
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="businessname" id="businessname" maxlength="100">
						</div>

						<div class="cell medium-6 small-12">
							Business Street
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="businessaddress" id="businessaddress" maxlength="100">
						</div>

						<div class="cell medium-6 small-12">
							Business City
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="businesscity" id="businesscity" maxlength="100">
						</div>

						<div class="cell medium-6 small-12">
							Business State
						</div>
						<div class="cell medium-4 small-8">
							<select name="businessstate" id="businessstate">
								<option value="">Select a State</option>
								<option value="">-Other-</option>
								' . $state_options . '
							</select>
						</div>

						<div class="cell medium-6 small-12">
							Business Zip Code
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="businesszip" id="businesszip" maxlength="10">
						</div>

						<div class="cell medium-6 small-12">
							Formation Date <span class="required">*</span>
						</div>
						<div class="cell medium-4 small-12">
							<select id="formationdate_month" name="formationdate_month" style="width: 30%">
								' . $months_list . '
							</select>
							/
							<select id="formationdate_day" name="formationdate_day" style="width: 30%">
								' . $days_list . '
							</select>
							/
							<select id="formationdate_year" name="formationdate_year" style="width: 30%">
								' . $years_list . '
							</select>
						</div>

						<div class="cell medium-6 small-12">
							Registered Agents <span class="required">*</span>
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="registered_agents" id="registered_agents" maxlength="100" placeholder="Required">
						</div>

						<div class="cell medium-6 small-12">
							Agent Address <span class="required">*</span>
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="agent_address" id="agent_address" maxlength="100" placeholder="Required">
						</div>

						<div class="cell medium-6 small-12">
							Status <span class="required">*</span>
						</div>
						<div class="cell medium-4 small-8">
							<input type="text" name="status" id="status" maxlength="100" placeholder="Required">
						</div>

						<div class="cell small-12 padding-bottom">
							<input id="save_business" class="float-center" type="button" value="Save Business">
						</div>
					</div>
				</form>';
?>

<script language="JavaScript" type="text/javascript">
	$("#Business_dialog").dialog({ autoOpen: false });

	$(".add-business").click(function() {
		addBusiness();
	});

	$('.button-prev').click(function() {
		location.href = prevAction;
	});

	function addBusiness() {
		$("#recid").val('');
		$("#businessid").val('');
		$("#businessaddress").val('');
		$("#businessname").val('');
		$("#businesscity").val('');
		$("#businessstate").val('');
		$("#businesszip").val('');
		$("#formationdate_day").val('');
		$("#formationdate_month").val('');
		$("#formationdate_year").val('');
		$("#registered_agents").val('');
		$("#agent_address").val('');
		$("#status").val('');

		$("#Business_dialog").dialog("option", "title", "Add Business");
		$("#Business_dialog").dialog("option", "modal", true);
		$("#Business_dialog").dialog("option", "width", "100%");
		$("#Business_dialog").dialog("open");
	}

 	$("#save_business").click(function() {
		var personid = $("#PersonID").val();
		var recid = $("#recid").val();

		var saveLocation = "../App_Ajax_New/ajax_add_business.php";

		if(recid > 0) {
			saveLocation = "../App_Ajax_New/ajax_save_business.php";
		}

		if($("#businessid").val() > '') {
			var businessid = $("#businessid").val();
		}
		else {
			$("#businessid").focus();
			alert("Business ID is required");
			return;
		}

		if($("#businessname").val() > '') {
			var businessname = $("#businessname").val();
		}
		else {
			$("#businessname").focus();
			alert("Business name is required");
			return;
		}

		if($("#businessaddress").val() > '') {
			var businessaddress = $("#businessaddress").val();
		}
		else {
			$("#businessaddress").focus();
			alert("Business address is required");
			return;
		}

		if($("#businesscity").val() > '') {
			var businesscity = $("#businesscity").val();
		}
		else {
			$("#businesscity").focus();
			alert("Business city is required");
			return;
		}

		if($("#businessstate").val() == '') {
			$("#businessstate").focus();
			alert("Business State is required");
			return;
		}
		else {
			var businessstate = $("#businessstate").val();
		}

		if($("#businesszip").val() > '') {
			var businesszip = $("#businesszip").val();
		}
		else {
			$("#businesszip").focus();
			alert("Business Zip Code is required");
			return;
		}

		if($("#formationdate_day").val() > '' && $("#formationdate_month").val() > '' && $("#formationdate_year").val() > '') {
			var formationdate = $("#formationdate_year").val() + '-' + $("#formationdate_month").val() + '-' + $("#formationdate_day").val();
		}
		else {
			$("#formationdate_day").focus();
			alert("Formation Date is required");
			return;
		}

		if($("#registered_agents").val() > '') {
			var registered_agents = $("#registered_agents").val();
		}
		else {
			$("#registered_agents").focus();
			alert("Registered Agents is required");
			return;
		}

		if($("#agent_address").val() > '') {
			var agent_address = $("#agent_address").val();
		}
		else {
			$("#agent_address").focus();
			alert("Agent Address is required");
			return;
		}

		if($("#status").val() > '') {
			var status = $("#status").val();
		}
		else {
			$("#status").focus();
			alert("Status is required");
			return;
		}

		var data = {
			recid: recid,
			personid: personid,
			businessname: businessname,
			businessaddress: businessaddress,
			businesscity: businesscity,
			businessstate: businessstate,
			businesszip: businesszip,
			businessid: businessid,
			formationdate: formationdate,
			registered_agents: registered_agents,
			agent_address: agent_address,
			status: status
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

	function updatebusiness(recid) {
		var personid = $("#PersonID").val();

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_business.php",
			data: { personid: personid, recid: recid },
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor)[0];

				if(obj2) {
					var fd = obj2.Formation_Date.split('-');
					//var Formation_Date = fd.substr(5, 2) + "/" + fd.substr(8) + "/" + fd.substr(0, 4);

					$("#recid").val(obj2.RecID);
					$("#businessaddress").val(obj2.Business_Address);
					$("#businessname").val(obj2.Business_Name);
					$("#businesscity").val(obj2.Business_City);
					$("#businessstate").val(obj2.Business_State);
					$("#businesszip").val(obj2.Business_Zip);
					$("#businessid").val(obj2.BusinessID);
					$("#formationdate_day").val(fd[2]);
					$("#formationdate_month").val(fd[1]);
					$("#formationdate_year").val(fd[0]);
					$("#registered_agents").val(obj2.Registered_Agents);
					$("#agent_address").val(obj2.Agent_Address);
					$("#status").val(obj2.Status);

					$("#Business_dialog").dialog("option", "title", "Edit Business Information");
					$("#Business_dialog").dialog("option", "modal", true);
					$("#Business_dialog").dialog("option", "width", "100%");
					$("#Business_dialog").dialog("open");
				}
				else {
					alert('No Business Data Found');
				}

				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: ' + textStatus);
				alert('Error: ' + errorThrown);
			}
		});
	}

	function deletebusiness(recid) {
		if(confirm('Are you sure you want to delete the business info?')) {
			var personid = $("#PersonID").val();

			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_business.php",
				data: { personid: personid, recid: recid },
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);

					if(obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					}
					else {
						location.reload();
						return;
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: ' + textStatus);
					alert('Error: ' + errorThrown);
				}
			});
		}
	}

	$("#close_business").click(function() {
		$("#Business_dialog").dialog("close");
	});
</script>
