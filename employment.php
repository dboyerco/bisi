<?
$days = 0;
$YR = 0;
$MO = 0;
$DY = 0;

$FormAction = "index.php?pg=education&PersonID=" . $PersonID . "&CD=" . $CD;

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
								<span class="sub-heading">Employment History</span><br>
								Please provide your current and most recent 3 employments or 7 years of employments.<br />&nbsp;
							</div>';

$currentEmployer = 'N';

if(!$testLayout) {
	$maxEmpID = $dbo->query("Select max(EmpID) from App_Employment where PersonID = ".$PersonID.";")->fetchColumn();

	if($maxEmpID > 0) {
		$selectemp = "select EmpID, EmpName, EmpCity, EmpState, EmpStateOther, EmpStreet, EmpZip, EmpDateFrom, EmpDateTo, EmpSupervisor, EmpReasonForLeaving, EmpTitle, EmpPhone, EmpSupervisorPhone, EmpSupervisorEmail, EmpMayWeContact, EmpCurrent, EmpDotReg, EmpDotTst from App_Employment where PersonID = :PersonID;";

		$emp_result = $dbo->prepare($selectemp);
		$emp_result->bindValue(':PersonID', $PersonID);
		$emp_result->execute();

		while($row = $emp_result->fetch(PDO::FETCH_BOTH)) {
			if($row[16] == 'Y') {
				$currentEmployer = 'Y';

				echo '<div class="cell small-12">
								<h3>Current Employment</h3>
							</div>

							<div class="cell small-12 medium-6">
								May we contact your current employer?
							</div>
							<div class="cell small-12 medium-6">
								' . ($row[15] == "Y" ? "Yes" : "No") . '
							</div>';
			}

			echo '<div class="cell small-12 medium-6">
							Company Name:
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[1]) . '
						</div>
						<div class="cell small-12 medium-3">
							' . htmlspecialchars($row[5]) . '<br />
							' . htmlspecialchars($row[2]) . '<br />
							' . ($row[4] > '' ? htmlspecialchars($row[4]) : htmlspecialchars($row[3])) . '
						</div>';

			if($row[7] == '1900-01-01') {
				$fromdate = '';
			}
			else {
				$fromdate = date("m/d/Y", strtotime($row[7]));
			}

			if($row[8] == '1900-01-01') {
				$todate = '';
			}
			else {
				$todate = date("m/d/Y", strtotime($row[8]));
			}

			if($fromdate != '' && $todate != '') {
				$datediff = strtotime($todate) - strtotime($fromdate);
				$days = $days + floor($datediff / (60 * 60 * 24));
			}

			echo '  <div class="cell small-12 medium-6">
								Dates:
							</div>
							<div class="cell small-12 medium-6">
								' . htmlspecialchars($fromdate) . ' - ' . htmlspecialchars($todate) . '
							</div>

							<div class="cell small-12 medium-3">
								Position:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[11]) . '
							</div>
							<div class="cell small-12 medium-3">
								Supervisor:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[9]) . '
							</div>

							<div class="cell small-12 medium-3">
								Phone:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[12]) . '
							</div>
							<div class="cell small-12 medium-3">
								Supervisor Phone:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[13]) . '
							</div>

							<div class="cell small-12 medium-3">
								Reason for Leaving:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[10]) . '
							</div>
							<div class="cell small-12 medium-3">
								Supervisor Email:
							</div>
							<div class="cell small-12 medium-3">
								' . htmlspecialchars($row[14]) . '
							</div>

							<div class="cell small-12" right>
								<span onclick="updateemp(' . $row[0] . ')"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Employment" title="Edit Employment"/></span>&nbsp;&nbsp;&nbsp;
								<span onclick="deleteemp(' . $row[0] . ')"><img class="icon" src="images/deletetrashcan.png" alt="Delete Employment" title="Delete Employment"/></span>
							</div>

							<div class="cell small-12">
								<hr>
							</div>';
		} // end while

		if($days > 0){
			$YR = floor($days / 365);
			$MO = floor(($days - (floor($days / 365) * 365)) / 30);
			$DY = $days - (($YR * 365) + ($MO * 30));
		}
		else {
			$YR = 0;
			$MO = 0;
			$DY = 0;
		}
	} // end if()$maxEmpID > 0)
} // end if(!$testLayout))
else {
	echo '<div class="cell small-12">
					<h3>Current Employment</h3>
				</div>

				<div class="cell small-12 medium-6">
					May we contact your current employer?
				</div>
				<div class="cell small-12 medium-6">
					No
				</div>

				<div class="cell small-12 medium-6">
					Company Name:
				</div>
				<div class="cell small-12 medium-3">
					Zero300 Studios
				</div>
				<div class="cell small-12 medium-3">
					Address Line 1<br />
					Loveland, CO<br />
					USA
				</div>

				<div class="cell small-12 medium-6">
					Dates:
				</div>
				<div class="cell small-12 medium-6">
					01/01/2001 - 05/04/2018
				</div>

				<div class="cell small-12 medium-3">
					Position:
				</div>
				<div class="cell small-12 medium-3">
					Owner
				</div>
				<div class="cell small-12 medium-3">
					Supervisor:
				</div>
				<div class="cell small-12 medium-3">
					Myself
				</div>

				<div class="cell small-12 medium-3">
					Phone:
				</div>
				<div class="cell small-12 medium-3">
					970-123-1234
				</div>
				<div class="cell small-12 medium-3">
					Supervisor Phone:
				</div>
				<div class="cell small-12 medium-3">
					303-987-6543
				</div>

				<div class="cell small-12 medium-3">
					Reason for Leaving:
				</div>
				<div class="cell small-12 medium-3">
					I didn\'t leave
				</div>
				<div class="cell small-12 medium-3">
					Supervisor Email:
				</div>
				<div class="cell small-12 medium-3">
					303-987-6543
				</div>

				<div class="cell small-12" right>
					<span onclick="updateemp(1)"><img class="icon" src="images/pen-edit-icon.png" alt="Edit Employment" title="Edit Employment"/></span>&nbsp;&nbsp;&nbsp;
					<span onclick="deleteemp(1)"><img class="icon" src="images/deletetrashcan.png" alt="Delete Employment" title="Delete Employment"/></span>
				</div>

				<div class="cell small-12">
					<hr>
				</div>';
}

echo '<div class="cell small-12">
				<h3>Add Employment</h3>
			</div>
			<div class="cell medium-6 small-12 required">
				* Required Fields To Continue
			</div>
			<div class="cell medium-6 small-12">
				You have entered ' . $YR . ' years ' . $MO . ' months ' . $DY . ' days
			</div>';

	if($currentEmployer == 'N') {
		echo '<div class="cell small-12 medium-6">
						Current employer? <span class="required">*</span>
					</div>
					<div class="cell small-12 medium-6">
						<select name="newempcurrent" id="newempcurrent">
							<option value="Y">Yes</option>
							<option VALUE="N">No</OPTION>
						</select>
					</div>

					<div class="cell small-12 medium-6">
						May we contact your current employer? <span class="required">*</span>
					</div>
					<div class="cell small-12 medium-6">
						<select name="newempcontact" id="newempcontact">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
					</div>';
	}

	echo '<div class="cell small-12 medium-6">
					Company Name <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newempname" id="newempname" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Address <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newempstreet" id="newempstreet" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					City <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newempcity" id="newempcity" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					State <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="newempstate" id="newempstate">
						<option value="">Select a State</option>
						<option value="">-Other-</option>
						' . $state_options . '
					</select>
				</div>

				<div class="cell small-12">
					OR If Employment was out of the US, please select the Country
				</div>

				<div class="cell small-12 medium-6">
					Country
				</div>
				<div class="cell small-12 medium-6">
					<select name="newempstateother" id="newempstateother">
						<option value="">Select a Country</option>';

if(!$testLayout) {
	$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
	$country_result = $dbo->prepare($sql);
	$country_result->execute();

	while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
		echo '	<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
	}
}
else {
	echo '		<option value="usa">USA</option>';
}

echo '		</select>
				</div>

				<div class="cell small-12 medium-6">
					Phone <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" text="number" name="newempphone" id="newempphone" value="" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					From Date <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newempfromdate" id="newempfromdate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					To Date <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newemptodate" id="newemptodate" maxlength="10" placeholder="mm/dd/yyyy" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					Position <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newemptitle" id="newemptitle" value="" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Supervisor <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newempsuper" id="newempsuper" value="" maxlength="40"></font>
				</div>

				<div class="cell small-12 medium-6">
					Supervisor Phone <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" text="number" name="newsphone" id="newsphone" placeholder="### ### #### #####" onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,\'up\')">
				</div>

				<div class="cell small-12 medium-6">
					Supervisor Email <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newsemail" id="newsemail" maxlength="40">
				</div>

				<div class="cell small-12 medium-6">
					Reason for leaving <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<input type="text" name="newreason" id="newreason" maxlength="40">
				</div>';

if($Package == "mountain") {
	echo '<div class="cell small-12 medium-6">
					Were you subject to FMCSA or PHMSA Safety Regulations while employed? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="newempdotreg" id="newempdotreg">
						<option value="N">NO</option>
						<option value="Y">YES</option>
					</select>
				</div>

				<div class="cell small-12 medium-6">
					Was this job designated as a safety sensitive function in any DOT regulated mode and therefore subject to alcohol and controlled substances testing requirements? <span class="required">*</span>
				</div>
				<div class="cell small-12 medium-6">
					<select name="newempdottst" id="newempdottst">
						<option value="N">NO</option>
						<option value="Y">YES</option>
					</select>
				</div>';
}

echo '<div class="cell small-12 padding-bottom">
				<input id="add_new_employment" class="float-center" type="button" value="Save Employment">
			</div>

			<div class="cell small-12 padding-bottom">
				<input class="float-center" type="submit" value="Next">
			</div>

			<input type="hidden" name="PersonID" id="PersonID" VALUE="' . $PersonID . '">
	  	<input type="hidden" name="EmpID" id="EmpID" VALUE=" ' . $maxEmpID . '">
	  	<input type="hidden" name="Current" id="Current" VALUE="' . $currentEmployer . '">
	  	<input type="hidden" name="Package\" id="Package" VALUE="' . $Package . '">
	  	<input type="hidden" name="nodays\" ID="nodays" VALUE=" ' . $days . '">
		</div>';
?>

	<div name="Employment_dialog" id="Employment_dialog" title="Dialog Title">
		<div>
			<input type="hidden" name="dlgempid" id="dlgempid">
			<table width="100%" align="left" border="3" bgcolor="#eeeeee">
				<tr>
					<td><font size="2">Current Employment</font></td>
					<td>
					    <select name="dlgcurrent" id="dlgcurrent">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="160" valign="top">
						<font size="2"><strong> May we contact your current employer?</strong></font>
					</td>
					<td width="351"> <font size="2">
						<select name="dlgcontact" id="dlgcontact">
							<option VALUE="N">No</OPTION>
							<option value="Y">Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="160"><font size="2">Company Name</font></td>
					<td width="351">
						<font size="2">
							<input type="text" name="dlgempname" id="dlgempname" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr>
					<td width="160"><font size="2">Address</font></td>
					<td width="351">
						<font size="2">
							<input type="text" name="dlgaddr" id="dlgaddr" size="20" maxlength="100">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">City</font></td>
					<td><font size="2">
							<input type="text" name="dlgcity" id="dlgcity" size="20" maxlength="40" >
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">State</font></td>
					<td><span style="font-size:small; color:#000000;">
						<select name="dlgstate" id="dlgstate">
							<option value="">Select a State</option>
							<option value="other">-Other-</option>
<?php
if(!$testLayout) {
	$sql = "Select Name, Abbrev from State order by Name";
	$state_result = $dbo->prepare($sql);
	$state_result->execute();

	while($rows = $state_result->fetch(PDO::FETCH_BOTH)) {
		echo '<option value="' . $rows[1] . '">' . $rows[0] . '</option>';
	}
}
else {
	echo '<option value="co">CO</option>';
}
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
					<td><span style="font-size:small;color:#000000;">
						<select name="dlgstateother" id="dlgstateother">
							<option value="">Select a Country</option>

<?php
if(!$testLayout) {
	$sql = "Select Alpha2Code, FullName from isocountrycodes Order By FullName;";
	$country_result = $dbo->prepare($sql);
	$country_result->execute();

	while($rows = $country_result->fetch(PDO::FETCH_BOTH)) {
		echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
	}
}
else {
	echo '<option value="usa">USA</option>';
}
?>

						</select></span>
					</td>
				</tr>
				<tr>
					<td><font size="2">Phone</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgphone" id="dlgphone" size="30" placeholder="### ### #### #####"
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">From Date</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgfromdate" id="dlgfromdate" size="10" maxlength="10" placeholder="mm/dd/yyyy"
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">To Date</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgtodate" id="dlgtodate" size="10" maxlength="10" placeholder="mm/dd/yyyy"
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtdate(this,'up')">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Position</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgtitle" id="dlgtitle" size="40" maxlength="40">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Supervisor</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgsuper" id="dlgsuper" size="40" maxlength="40">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Supervisor Phone</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgsphone" id="dlgsphone" size="30" placeholder="### ### #### #####"
							onkeypress="return numericOnly(event,this);" onKeyUp="return frmtphone(this,'up')">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Supervisor Email</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgsemail" id="dlgsemail" size="40" maxlength="40">
						</font>
					</td>
				</tr>
				<tr>
					<td><font size="2">Reason for leaving</font></td>
					<td nowrap>
						<font size="2">
							<input type="text" name="dlgreason" id="dlgreason" size="40" maxlength="40">
						</font>
					</td>
				</tr>
			</table>

<?php
if ($Package == "mountain") {
	echo "<table width='100%' align='left' border='3' bgcolor='#eeeeee'><tr>
			<td valign='top' width='200'>
				<font size='2'>
					Were you subject to FMCSA or PHMSA Safety Regulations while employed? <strong><font color='FF0000' size='2'>*</font></strong>
				</font>
			</td>
			<td>
				<font size='2'>
					<select name='dlgempdotreg' id='dlgempdotreg'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr>
		<tr>
			<td>
				<font size='2'>
					Was this job designated as a safety sensitive function in any DOT regulated mode and therefore subject to alcohol and controlled substances testing requirements?
					<strong><font color='FF0000' size='2'>*</font></strong>
				</font>
			</td>
			<td>
				<font size='2'>
					<select name='dlgempdottst' id='dlgempdottst'>
						<option value='N'>NO</option>
						<option value='Y'>YES</option>
					</select>
				</font>
			</td>
		</tr></table>";
	}
?>
			<table width="100%" bgcolor="#eeeeee">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center">
						<INPUT TYPE="button" id="save_employment" VALUE="Save Employment">
						<INPUT TYPE="button" id="close_employment" VALUE="Close">
					</td>
				</tr>
			</table>
		</div>
	</div>
</FORM>


 <script language="JavaScript" type="text/javascript">
 	$( "#Employment_dialog" ).dialog({ autoOpen: false });
 	$( "#add_new_employment" ).click(function() {
		var personid = document.getElementById("PersonID").value;
		if (document.getElementById("Current").value == 'Y') {
			var empcontact = '';
			var empcurrent = 'N';
		} else {
			var empcontact = document.getElementById("newempcontact").value;
			var empcurrent = document.getElementById("newempcurrent").value;
		}
		if (document.getElementById("newempname").value > '') {
			var empname = document.getElementById("newempname").value;
		} else {
			document.ALCATEL.newempname.focus();
			alert("Company Name is required");
			return;
		}

		if (document.getElementById("newempstreet").value > '') {
			var empstreet = document.getElementById("newempstreet").value;
		} else {
			document.ALCATEL.newempstreet.focus();
			alert("Address is required");
			return;
		}

		if (document.getElementById("newempcity").value > '') {
			var empcity = document.getElementById("newempcity").value;
		} else {
			document.ALCATEL.newempcity.focus();
			alert("City is required");
			return;
		}

		if (document.getElementById("newempstate").value == '' && document.getElementById("newempstateother").value == '' ) {
			document.ALCATEL.newempstate.focus();
			alert("State or Country is required");
			return;
		} else {
			var empstate = document.getElementById("newempstate").value;
			var empstateother = document.getElementById("newempstateother").value;
		}

		if (document.getElementById("newempfromdate").value > '') {
			if (!isValidDate('newempfromdate')) {
				$('#newempfromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {
				var empfromdate = document.getElementById("newempfromdate").value;
			}
		} else {
			$('#newempfromdate').focus();
			alert("From Date is required");
			return;
		}

		if (document.getElementById("newemptodate").value > '') {
			if (!isValidDate('newemptodate')) {
				$('#newemptodate').focus();
				alert("Invalid To Date");
				return false;
			} else {
				var emptodate = document.getElementById("newemptodate").value;
			}
		} else {
			$('#newemptodate').focus();
			alert("To Date is required");
			return;
		}
		if (!isValidDiff(empfromdate,emptodate)) {
			$('#newempfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if (document.getElementById("newemptitle").value > '') {
			var emptitle = document.getElementById("newemptitle").value;
		} else {
			document.ALCATEL.newemptitle.focus();
			alert("Position is required");
			return;
		}
		if (document.getElementById("newempsuper").value > '') {
			var empsuper = document.getElementById("newempsuper").value;
		} else {
			document.ALCATEL.newempsuper.focus();
			alert("Supervisor is required");
			return;
		}
		if (document.getElementById("newempphone").value > '') {
			var empphone = document.getElementById("newempphone").value;
		} else {
			document.ALCATEL.newempphone.focus();
			alert("Phone is required");
			return;
		}

		if (document.getElementById("newsphone").value > '') {
			var empsphone = document.getElementById("newsphone").value;
		} else {
			var empsphone = '';
		}
		if (document.getElementById("newsemail").value > '') {
			var empsemail = document.getElementById("newsemail").value;
		} else {
			var empsemail = '';
		}
		if (document.getElementById("newreason").value > '') {
			var empreason = document.getElementById("newreason").value;
		} else {
			var empreason = '';
		}
		if (document.getElementById("Package").value == "mountain") {
			if (document.getElementById("newempdotreg").value > '') {
				var empdotreg = document.getElementById("newempdotreg").value;
			} else {
				document.ALCATEL.newempdotreg.focus();
				alert("Subject to FMCS Regulations is required.");
				return;
			}
			if (document.getElementById("newempdottst").value > '') {
				var empdottst = document.getElementById("newempdottst").value;
			} else {
				document.ALCATEL.newempdottst.focus();
				alert("Designated as safety sensitive is required.");
				return;
			}
		} else {
			var empdotreg = '';
			var empdottst = '';
		}
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_add_employment.php",
			data: {personid: personid, empcontact:empcontact, empcurrent:empcurrent, empname: empname, empstreet: empstreet, empcity: empcity, empstate: empstate, empstateother: empstateother, empfromdate: empfromdate, emptodate: emptodate, emptitle: emptitle, empsuper: empsuper, empphone: empphone, empsphone: empsphone, empsemail: empsemail, empreason: empreason, empdotreg: empdotreg, empdottst: empdottst},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2.length > 30) {
					alert(obj2);
				} else {
					var EmpID = obj2;
					if (document.getElementById("Current").value == 'N') {
						document.getElementById("newempcontact").value = 'N';
						document.getElementById("newempcurrent").value = 'N';
					}
					document.getElementById("newempname").value = '';
					document.getElementById("newempstreet").value = '';
					document.getElementById("newempcity").value = '';
					document.getElementById("newempstate").value = '';
					document.getElementById("newempstateother").value = '';
					document.getElementById("newemptitle").value = '';
					document.getElementById("newempfromdate").value = '';
					document.getElementById("newemptodate").value = '';
					document.getElementById("newempsuper").value = '';
					document.getElementById("newempphone").value = '';
					if (document.getElementById("Package").value == "mountain") {
						document.getElementById("newempdotreg").value = '';
						document.getElementById("newempdottst").value = '';
					}
					window.location.reload();
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

 	function updateemp(empid) {
		var personid = document.getElementById("PersonID").value;
		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_find_employment.php",
			data: {personid: personid, empid: empid},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (valor.length > 0) {
					for (var i = 0; i < obj2.length; i++) {
						var EmpID = obj2[i].EmpID;
						var EmpName = obj2[i].EmpName;
						var EmpStreet = obj2[i].EmpStreet;
						var EmpCity = obj2[i].EmpCity;
						var EmpState = obj2[i].EmpState;
						var EmpStateOther = obj2[i].EmpStateOther;
						var fd = obj2[i].EmpDateFrom;
						var EmpDateFrom = fd.substr(5,2)+"/"+fd.substr(8)+"/"+fd.substr(0,4);
						var td = obj2[i].EmpDateTo;
						var EmpDateTo = td.substr(5,2)+"/"+td.substr(8)+"/"+td.substr(0,4);
						var EmpSupervisor = obj2[i].EmpSupervisor;
						var EmpReasonForLeaving = obj2[i].EmpReasonForLeaving;
						var EmpTitle = obj2[i].EmpTitle;
						var EmpPhone = obj2[i].EmpPhone;
						var EmpCurrent = obj2[i].EmpCurrent;
						var EmpMayWeContact = obj2[i].EmpMayWeContact;
						var EmpSupervisorPhone = obj2[i].EmpSupervisorPhone;
						var EmpSupervisorEmail = obj2[i].EmpSupervisorEmail;
						var EmpDotReg = obj2[i].EmpDotReg;
						var EmpDotTst = obj2[i].EmpDotTst;
			    	}
					document.getElementById("dlgempid").value = EmpID;
					document.getElementById("dlgcontact").value = EmpMayWeContact;
					document.getElementById("dlgcurrent").value = EmpCurrent;
					document.getElementById("dlgempname").value = EmpName;
					document.getElementById("dlgaddr").value = EmpStreet;
					document.getElementById("dlgcity").value = EmpCity;
					document.getElementById("dlgstate").value = EmpState;
					document.getElementById("dlgstateother").value = EmpStateOther;
					document.getElementById("dlgfromdate").value = EmpDateFrom;
					document.getElementById("dlgtodate").value = EmpDateTo;
					document.getElementById("dlgsuper").value = EmpSupervisor;
					document.getElementById("dlgreason").value = EmpReasonForLeaving;
					document.getElementById("dlgtitle").value = EmpTitle;
					document.getElementById("dlgphone").value = EmpPhone;
					document.getElementById("dlgsphone").value = EmpSupervisorPhone;
					document.getElementById("dlgsemail").value = EmpSupervisorEmail;
					if (document.getElementById("Package").value == 'mountain') {
						document.getElementById("dlgempdotreg").value = EmpDotReg;
						document.getElementById("dlgempdottst").value = EmpDotTst;
					}

					$( "#Employment_dialog" ).dialog( "option", "title", "Edit Employment");
					$( "#Employment_dialog" ).dialog( "option", "modal", true );
					$( "#Employment_dialog" ).dialog( "option", "width", 700 );
					$( "#Employment_dialog" ).dialog( "open" );
				} else {
					alert('No Employment Data Found');
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	}
 	$("#save_employment").click(function() {
		var personid = document.getElementById("PersonID").value;
		var empid = document.getElementById("dlgempid").value;
		var current_employment = document.getElementById("dlgcurrent").value;

		if (document.getElementById("dlgempname").value > '') {
			var empname = document.getElementById("dlgempname").value;
		} else {
			document.ALCATEL.dlgempname.focus();
			alert("Company Name is required");
			return;
		}
		contact = document.getElementById("dlgcontact").value;

		if (document.getElementById("dlgaddr").value > '') {
			var addr = document.getElementById("dlgaddr").value;
		} else {
			document.ALCATEL.dlgaddr.focus();
			alert("Street is required");
			return;
		}

		if (document.getElementById("dlgcity").value > '') {
			var city = document.getElementById("dlgcity").value;
		} else {
			document.ALCATEL.dlgcity.focus();
			alert("City is required");
			return;
		}

		if (document.getElementById("dlgstate").value == '' && document.getElementById("dlgstateother").value == '' ) {
			document.ALCATEL.dlgstate.focus();
			alert("State or Country is required");
			return;
		} else {
			var state = document.getElementById("dlgstate").value;
			var stateother = document.getElementById("dlgstateother").value;
		}
		if (document.getElementById("dlgphone").value > '') {
			var phone = document.getElementById("dlgphone").value;
		} else {
			document.ALCATEL.dlgphone.focus();
			alert("Phone is required");
			return;
		}

		if (document.getElementById("dlgfromdate").value > '') {
			if (!isValidDate('dlgfromdate')) {
				$('#dlgfromdate').focus();
				alert("Invalid From Date");
				return false;
			} else {
				var fromdate = document.getElementById("dlgfromdate").value;
			}
		} else {
			document.ALCATEL.dlgfromdate.focus();
			alert("From Date is required");
			return;
		}

		if (document.getElementById("dlgtodate").value > '') {
			if (!isValidDate('dlgtodate')) {
				$('#dlgtodate').focus();
				alert("Invalid To Date");
				return false;
			} else {
				var todate = document.getElementById("dlgtodate").value;
			}
		} else {
			document.ALCATEL.dlgtodate.focus();
			alert("To Date is required");
			return;
		}
		if (!isValidDiff(fromdate,todate)) {
			$('#dlgfromdate').focus();
			alert("From Date can not be greater than To Date");
			return false;
		}

		if (document.getElementById("dlgtitle").value > '') {
			var position = document.getElementById("dlgtitle").value;
		} else {
			document.ALCATEL.dlgtitle.focus();
			alert("Position is required");
			return;
		}

		if (document.getElementById("dlgsuper").value > '') {
			var supervisor = document.getElementById("dlgsuper").value;
		} else {
			document.ALCATEL.dlgsuper.focus();
			alert("Supervisor is required");
			return;
		}
		if (document.getElementById("dlgsphone").value > '') {
			var sphone = document.getElementById("dlgsphone").value;
		} else {
			var sphone = '';
		}
		if (document.getElementById("dlgsemail").value > '') {
			var semail = document.getElementById("dlgsemail").value;
		} else {
			var semail = '';
		}
		if (document.getElementById("dlgreason").value > '') {
			var reason = document.getElementById("dlgreason").value;
		} else {
			var reason = '';
		}
		if (document.getElementById("Package").value == "mountain") {
			var empdotreg = document.getElementById("dlgempdotreg").value;
			var empdottst = document.getElementById("dlgempdottst").value;
		} else {
			var empdotreg = '';
			var empdottst = '';
		}

		$.ajax({
			type: "POST",
			url: "../App_Ajax/ajax_save_employment.php",
			data: {personid: personid, empid: empid, empname: empname, addr: addr, city: city, state: state, stateother: stateother, phone: phone, fromdate: fromdate, todate: todate, current_employment: current_employment, contact: contact, position: position, supervisor: supervisor, sphone: sphone, semail: semail, reason: reason, empdotreg: empdotreg, empdottst: empdottst},
			datatype: "JSON",
			success: function(valor) {
				var obj2 = $.parseJSON(valor);
				if (obj2 > '' ) {
					alert(obj2);
				} else {
					$( "#Employment_dialog" ).dialog( "close" );
					location.reload();
				}
				return;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('Status: '+textStatus); alert('Error: '+errorThrown);
			}
		});
	});

	function deleteemp(EmpID) {
//		alert("In dltaka");
		if (confirm('Are you sure you want to delete this employment record?')) {
			var personid = document.getElementById("PersonID").value;
			$.ajax({
				type: "POST",
				url: "../App_Ajax/ajax_delete_employment.php",
				data: {personid: personid, EmpID: EmpID},
				datatype: "JSON",
				success: function(valor) {
					var obj2 = $.parseJSON(valor);
					if (obj2.substring(0,4) == 'Error') {
						alert(obj2);
						return false;
					} else {
						location.reload();
						return;
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert('Status: '+textStatus); alert('Error: '+errorThrown);
				}
			});
		}
	}
	$( "#close_employment" ).click(function() {
		$( "#Employment_dialog" ).dialog( "close" );
	});


 </script>
 <script language="JavaScript" type="text/javascript">
	 var frmvalidator = new Validator("ALCATEL");
	frmvalidator.setAddnlValidationFunction("DoCustomValidation");

	function DoCustomValidation() {
		var empid = document.getElementById("EmpID").value;
		var nodays = document.getElementById("nodays").value;
//		alert('Number of Days: '+nodays);
		if (empid > 2 || nodays >= 2555) {
			return true;
		} else {
			document.ALCATEL.newempname.focus();
			alert('You have not entered at least 3 employments or 7 years of employments');
			return false;
		}
	}
</script>
