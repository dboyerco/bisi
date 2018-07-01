<?
//$PersonID = $_REQUEST['PersonID'];

if($noemail == 'Y') {
	$FormAction = "index.php?pg=certification&PersonID=" . $PersonID . "&CD=" . $CD;
}
else {
	$FormAction = "index.php?pg=disclosure1&PersonID=" . $PersonID . "&CD=" . $CD;
}

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
								<span class="sub-heading">Education History</span><br>
								List Your Highest Degree Information.<br />&nbsp;
							</div>';

$highestdegree = 'N';

if(!$testLayout) {
	$maxEduID = $dbo->query("Select max(EduID) from App_Education where PersonID = " . $PersonID . ";")->fetchColumn();

	if($maxEduID > 0) {
		$selectstmt = "select EduID, EduCollegeName, EduCity, EduState, EduDatesAttendedFrom, EduDatesAttendedTo, EduCollegeMajor, EduCollegeDegree, EduGraduated, EduIsHighestDegree from App_Education where PersonID='$PersonID';";
		$result2 = $dbo->prepare($selectstmt);
		$result2->bindValue(':PersonID', $PersonID);
		$result2->execute();

		while($row = $result2->fetch(PDO::FETCH_BOTH)) {
			if($row[9] == 'Y') {
				$highestdegree = 'Y';

				echo '<div class="cell small-12">
								<h3>Highest Degree</h3>
							</div>';
			}

			if($row[4] == '1900-01-01') {
				$fromdate = '';
			}
			else {
				$fromdate = date("m/d/Y", strtotime($row[4]));
			}

			if($row[5] == '1900-01-01') {
				$todate = '';
			}
			else {
				$todate = date("m/d/Y", strtotime($row[5]));
			}

			echo '<div class="cell small-12 medium-11">
							<div class="grid-x">
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[1]) . '
								</div>
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[2]) . ', ' . htmlspecialchars($row[3]) . '
								</div>
								<div class="cell small-6 medium-3">
									' . htmlspecialchars($fromdate) . ' - ' . htmlspecialchars($todate) . '
								</div>
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[6]) . '
								</div>
								<div class="cell small-6 medium-2">
									' . htmlspecialchars($row[7]) . '
								</div>
								<div class="cell small-6 medium-1">
									' . ($row[8] == 'Y' ? "Graduated" : "Did Not Graduated") . '
								</div>
							</div>
						</div>

						<div class="cell small-12 medium-1 top right">
							<span onclick="updateedu(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Education" title="Edit Education"/></span>&nbsp;&nbsp;
							<span onclick="deleteedu(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete Education" title="Delete Education"/></span>
						</div>

						<div class="cell small-12">
							<hr>
						</div>';
		}
	}
}
else {
	echo '<div class="cell small-12">
					<h3>Highest Degree</h3>
				</div>

				<div class="cell small-12 medium-11">
					<div class="grid-x">
						<div class="cell small-6 medium-2">
							eduname
						</div>
						<div class="cell small-6 medium-2">
							educity, CO
						</div>
						<div class="cell small-6 medium-3">
							09/01/1987 - 05/21/1988
						</div>
						<div class="cell small-6 medium-2">
							the major
						</div>
						<div class="cell small-6 medium-2">
							Bachelor of Arts
						</div>
						<div class="cell small-6 medium-1">
							Graduated
						</div>
					</div>
				</div>

				<div class="cell small-12 medium-1 top right">
					<span onclick="updateedu(1)"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Education" title="Edit Education"/></span>&nbsp;&nbsp;
					<span onclick="deleteedu(1)"><img class="icon" src="images/deletetrashcan.png" alt="Delete Education" title="Delete Education"/></span>
				</div>

				<div class="cell small-12">
					<hr>
				</div>';
}

if($maxEduID == 0) {
	echo '<div class="cell small-12">
					<h3>Add Education</h3>
				</div>
				<div class="cell small-12 required">
					* Required Fields To Continue
				</div>';

	if($highestdegree == 'N') {
		echo '<div class="cell small-12 medium-6">
						Highest Degree? <span class="required">*</span>
					</div>
					<div class="cell small-12 medium-6">
						<select name="neweduhighest" id="neweduhighest">
							<option value="Y">Yes</option>
							<option value="N">No</OPTION>
						</select>
					</div>';
	}
	else {
		echo '<input type="hidden" name="neweduhighest" id="neweduhighest" value="N">';
	}

	echo '<div class="cell small-12 medium-6">
					Name of Institution <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="neweduname" id="neweduname" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					City <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="neweducity" id="neweducity" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					State <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="newedustate" id="newedustate">
						<option value="">Select a State</option>
						' . $state_options . '
					</select>
				</div>

				<div class="cell small-12 medium-6">
					OR If Education was out of the US, please select the Country
				</div>
				<div class="cell small-12 medium-6">
					<select name="newedustateother" id="newedustateother">
						<option value="">Select a Country</option>
						' . $country_options . '
					</select>
				</div>

				<div class="cell small-12 medium-6">
					Attended From <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newedufromdate" id="newedufromdate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					Attended To <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newedutodate" id="newedutodate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					Major <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newedumajor" id="newedumajor" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Degree <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="newedudegree" id="newedudegree">
						<option VALUE="">Select Degree</option>
						<option value="Associates">Associates</option>
						<option value="Bachelor of Arts">Bachelor of Arts</option>
						<option value="Bachelor of Science">Bachelor of Science</option>
						<option value="Certificate">Certificate</option>
						<option value="Doctorate">Doctorate</option>
						<option value="GED or Equilavent">GED or Equilavent</option>
						<option value="High School Diploma">High School Diploma</option>
						<option value="Judicial Doctorate">Judicial Doctorate</option>
						<option value="Masters">Masters</option>
					</select>
				</div>

				<div class="cell small-12 medium-6">
					Did You Graduate? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="newedugraduate" id="newedugraduate">
						<option value="N">No</option>
						<option value="Y">Yes</option>
					</select>
				</div>

				<div class="cell small-12 padding-bottom">
					<input id="add_new_employment" class="float-center" type="button" value="Save Education">
				</div>';
}

echo '<div class="cell small-12 padding-bottom">
				<input class="float-center" type="submit" value="Next">
			</div>

			<input type="hidden" name="PersonID" id="PersonID" value="' . $PersonID . '">
	  	<input type="hidden" name="EduID" id="EmpID" value="' . $maxEduID . '">';
?>
	<div name="Education_dialog" id="Education_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgeduid" id="dlgeduid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
				<tr>
					<td><font size="2"><strong>Highest Degree?</strong></font></td>
					<td><font size="2">
					    <select name="dlgeduhighest" id="dlgeduhighest">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select></font>
					</td>
				</tr>
				<tr>
					<td width="160" valign="top">
						<font size="2"><strong>Did You Graduate?</strong></font>
					</td>
					<td width="351"> <font size="2">
						<select name="dlgedugraduated" id="dlgedugraduated">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select></font>
					</td>
				</tr>
				<tr>
					<td width="160"><font size="2">Name of Institution</font></td>
					<td width="351">
						<font size="2">
							<input type="text" name="dlgeduname" id="dlgeduname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">City</font></td>
					<td><font size="2">
							<input type="text" name="dlgeducity" id="dlgeducity" size="20" maxlength="40" >
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">State</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgedustate" id="dlgedustate">
							<option value="">Select a State</option>
<?php
	echo $state_options;
?>
						</select></span>
					</td>
				</tr>
				<tr>
					<td><font size="2">&nbsp;</font></td>
					<td><font size="2"> OR If address is out of the US, please select the Country</font></td>
				</tr>
				<tr>
					<td><font size="2">Country</font></td>
					<td><span style="font-size:small; font-family=Tahoma; color:#000000;">
						<select name="dlgedustateother" id="dlgedustateother">
							<option value="">Select a Country</option>
<?php
	echo $country_options;
?>
						</select></span>
					</td>
				</tr>
				<tr>
					<td><font size="2">Attended From</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgedufromdate" id="dlgedufromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy"
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Attended To</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgedutodate" id="dlgedutodate" size="10" maxlength="10" placeholder="mm/dd/yyyy"
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Major</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgedumajor" id="dlgedumajor" size="40" maxlength="40">
						</font>
					</td>
				</tr>
				<tr>
					<td nowrap><font size="2">Degree</font></td>
					<td nowrap><font size="2">
						<select name="dlgedudegree" id="dlgedudegree">
							<option VALUE="">Select Degree</option>
							<OPTION VALUE="Associates">Associates</OPTION>
							<OPTION VALUE="Bachelor of Arts">Bachelor of Arts</OPTION>
							<OPTION VALUE="Bachelor of Science">Bachelor of Science</OPTION>
							<OPTION VALUE="Certificate">Certificate</OPTION>
							<OPTION VALUE="Doctorate">Doctorate</OPTION>
							<OPTION VALUE="GED or Equilavent">GED or Equilavent</OPTION>
							<OPTION VALUE="High School Diploma">High School Diploma</OPTION>
							<OPTION VALUE="Judicial Doctorate">Judicial Doctorate</OPTION>
							<OPTION VALUE="Masters">Masters</OPTION>
						</select>
						</font>
					</td>
				</tr>
			</table>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_education" VALUE="Save Address">
						<INPUT TYPE="button" id="close_education" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>
	</div>
</FORM>

<script language="JavaScript" type="text/javascript">
 	$( "#Education_dialog" ).dialog({ autoOpen: false });
 	$( "#add_new_education" ).click(function() {

		var personid = document.getElementById("PersonID").value;
		var neweduhighest = document.getElementById("neweduhighest").value;

		if (document.getElementById("neweduname").value > '') {
			var neweduname = document.getElementById("neweduname").value;
		} else {
			document.ALCATEL.neweduname.focus();
			alert("Name of Institution is required");
			return;
		}

		if (document.getElementById("neweducity").value > '') {
			var neweducity = document.getElementById("neweducity").value;
		} else {
			document.ALCATEL.neweducity.focus();
			alert("City is required");
			return;
		}

		if (document.getElementById("newedustate").value == '' && document.getElementById("newedustateother").value == '' ) {
			document.ALCATEL.newedustate.focus();
			alert("State or Country is required");
			return;
		} else {
			var newedustate = document.getElementById("newedustate").value;
			var newedustateother = document.getElementById("newedustateother").value;
		}

		if (document.getElementById("newedufromdate").value > '') {
			if (!isValidDate('newedufromdate')) {
				$('#newedufromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {
				var newedufromdate = document.getElementById("newedufromdate").value;
			}
		} else {
			document.ALCATEL.newedufromdate.focus();
			alert("Attended From Date is required");
			return;
		}

		if (document.getElementById("newedutodate").value > '') {
			if (!isValidDate('newedutodate')) {
				$('#newedutodate').focus();
				alert("Invalid To Date");
				return false;
			} else {
				var newedutodate = document.getElementById("newedutodate").value;
			}
		} else {
			document.ALCATEL.newedutodate.focus();
			alert("Attended To Date is required");
			return;
		}
		if (!isValidDiff(newedufromdate,newedutodate)) {
			$('#newedufromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if (document.getElementById("newedumajor").value > '') {
			var newedumajor = document.getElementById("newedumajor").value;
		} else {
			document.ALCATEL.newedumajor.focus();
			alert("Major is required");
			return;
		}
		if (document.getElementById("newedudegree").value > '') {
			var newedudegree = document.getElementById("newedudegree").value;
		} else {
			document.ALCATEL.newedudegree.focus();
			alert("Degree is required");
			return;
		}
		if (document.getElementById("newedugraduate").value > '') {
			var newedugraduate = document.getElementById("newedugraduate").value;
		} else {
			document.ALCATEL.newedugraduate.focus();
			alert("Did You Graduate is required");
			return;
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_education.php",
			data: {personid: personid, neweduhighest: neweduhighest, neweduname: neweduname, neweducity: neweducity, newedustate: newedustate, newedustateother: newedustateother, newedufromdate: newedufromdate, newedutodate: newedutodate, newedumajor: newedumajor, newedudegree: newedudegree, newedugraduate: newedugraduate},

			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EduID = obj2;
					document.getElementById("neweduhighest").value = 'N';
					document.getElementById("neweduname").value = '';
					document.getElementById("neweducity").value = '';
					document.getElementById("newedustate").value = '';
					document.getElementById("newedustateother").value = '';
					document.getElementById("newedufromdate").value = '';
					document.getElementById("newedutodate").value = '';
					document.getElementById("newedumajor").value = '';
					document.getElementById("newedudegree").value = '';
					document.getElementById("newedugraduate").value = 'N';
					location.reload();
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

 	function updateedu(eduid) {
		var personid = document.getElementById("PersonID").value;

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_education.php",
			data: {personid: personid, eduid: eduid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) {
					for (var i = 0; i < obj2.length; i++) {
						var EduID = obj2[i].EduID;
						var EduName = obj2[i].EduCollegeName;
						var EmpStreet = obj2[i].EmpStreet;
						var EduCity = obj2[i].EduCity;
						var EduState = obj2[i].EduState;
						var EduStateOther = obj2[i].EduStateOther;
						var fd = obj2[i].EduDatesAttendedFrom;
						var EduDateFrom = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var td = obj2[i].EduDatesAttendedTo;
						var EduDateTo = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
						var EduMajor = obj2[i].EduCollegeMajor;
						var EduDegree = obj2[i].EduCollegeDegree;
						var EduGraduated = obj2[i].EduGraduated;
						var EduIsHighestDegree = obj2[i].EduIsHighestDegree;
			    	}

					document.getElementById("dlgeduid").value = EduID;
					document.getElementById("dlgeduname").value = EduName;
					document.getElementById("dlgeducity").value = EduCity;
					document.getElementById("dlgedustate").value = EduState;
					document.getElementById("dlgedustateother").value = EduStateOther;
					document.getElementById("dlgedufromdate").value = EduDateFrom;
					document.getElementById("dlgedutodate").value = EduDateTo;
					document.getElementById("dlgedumajor").value = EduMajor;
					document.getElementById("dlgedudegree").value = EduDegree;
					document.getElementById("dlgedugraduated").value = EduGraduated;
					document.getElementById("dlgeduhighest").value = EduIsHighestDegree;

					$( "#Education_dialog" ).dialog( "option", "title", "Edit Employment");
					$( "#Education_dialog" ).dialog( "option", "modal", true );
					$( "#Education_dialog" ).dialog( "option", "width", 700 );
					$( "#Education_dialog" ).dialog( "open" );
				} else {
					alert('No Education Data Found');
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	}
 	$("#save_education").click(function() {
		var personid = document.getElementById("PersonID").value;
		var eduid = document.getElementById("dlgeduid").value;

		if (document.getElementById("dlgeduname").value > '') {
			var eduname = document.getElementById("dlgeduname").value;
		} else {
			document.ALCATEL.dlgeduname.focus();
			alert("Name of Instition is required");
			return;
		}

		if (document.getElementById("dlgeducity").value > '') {
			var educity = document.getElementById("dlgeducity").value;
		} else {
			document.ALCATEL.dlgeducity.focus();
			alert("City is required");
			return;
		}

		if (document.getElementById("dlgedustate").value == '' && document.getElementById("dlgedustateother").value == '' ) {
			document.ALCATEL.dlgedustate.focus();
			alert("State or Country is required");
			return;
		} else {
			var edustate = document.getElementById("dlgedustate").value;
			var edustateother = document.getElementById("dlgedustateother").value;
		}

		if (document.getElementById("dlgedufromdate").value > '') {
			if (!isValidDate('dlgedufromdate')) {
				$('#dlgedufromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {
				var edufromdate = document.getElementById("dlgedufromdate").value;
			}
		} else {
			document.ALCATEL.dlgedufromdate.focus();
			alert("Attended From Date is required");
			return;
		}

		if (document.getElementById("dlgedutodate").value > '') {
			if (!isValidDate('dlgedutodate')) {
				$('#dlgedutodate').focus();
				alert("Invalid To Date");
				return false;
			} else {
				var edutodate = document.getElementById("dlgedutodate").value;
			}
		} else {
			document.ALCATEL.dlgedutodate.focus();
			alert("Attended To Date is required");
			return;
		}
		if (!isValidDiff(edufromdate,edutodate)) {
			$('#dlgedufromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if (document.getElementById("dlgedumajor").value > '') {
			var edumajor = document.getElementById("dlgedumajor").value;
		} else {
			document.ALCATEL.dlgedumajor.focus();
			alert("Major is required");
			return;
		}

		if (document.getElementById("dlgedudegree").value > '') {
			var edudegree = document.getElementById("dlgedudegree").value;
		} else {
			document.ALCATEL.dlgedudegree.focus();
			alert("Degree is required");
			return;
		}
		var edugraduated = document.getElementById("dlgedugraduated").value;
		var eduhighest = document.getElementById("dlgeduhighest").value;

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_education.php",
			data: {personid: personid, eduid: eduid, eduname: eduname, educity: educity, edustate: edustate, edustateother: edustateother, edufromdate: edufromdate, edutodate: edutodate, edumajor: edumajor, edudegree: edudegree, edugraduated: edugraduated, eduhighest: eduhighest},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {
					$( "#Education_dialog" ).dialog( "close" );
					location.reload();
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

	function deleteedu(EduID) {
//		alert("In dltedu");
		if (confirm('Are you sure you want to delete this education record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_education.php",
				data: {personid: personid, EduID: EduID},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					} else {
						location.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				}
			});
		}
	}
	$( "#close_education" ).click(function() {
		$( "#Education_dialog" ).dialog( "close" );
	});
 </script>
  <script language="JavaScript" type="text/javascript">
var frmvalidator = new Validator("ALCATEL");
frmvalidator.setAddnlValidationFunction("DoCustomValidation");

function DoCustomValidation() {
	var EduID = document.getElementById("EduID").value;
	if (EduID == 0) {
		document.ALCATEL.newbankname.focus();
		alert('You have not entered your highest degree earned');
		return false;
	} else {
		return true;
	}
}
</script>
