<!--Form I-9 Preparer and/or Translator Signature-->
<div id="openwetsignature" class="modalDialog">	
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h3>Form I-9 Employee Signature</h3>
		<hr>
		<br/>
		<input type="hidden" name="dlgPhotoID" id="dlgPhotoID">
		<table bgcolor="#E4E8E8" border="0" cellpadding="0" cellspacing="0">
  			<tbody>
  				<tr>
    				<td></td>
    				<td class="submenu" height="27" width="763">&nbsp;</td>
  				</tr>
			</tbody>
		</table>
	 	<div name="canvasDiv" id="canvasDiv" style="visibility: visible; margin: auto auto;background-color:#E4E8E8;padding:35px;">
			<p>Please use your mouse to sign your name in the box below.</p>
      		<canvas id="canvasSignature" width="500px" height="75px" style="border:1px solid #000000;"></canvas>
			<br />
			<br />
   		</div>
		<div id="buttoncontainer" style="display:block;background-color:#E4E8E8;padding:35px;">
			<a href="#" id="savepngbtn" class="search_button" rel="modal:close">
				<font color="#2E2EFE">--</font><font color="white">Save Signature</font><font color="#2E2EFE">--</font>
			</a>

<!--			<input type="button" id="savepngbtn" name="savepngbtn" value="Save Signature" onClick="UploadPic(<? echo $PersonID ?>, '<? echo $SignType ?>');">-->
			<input type="reset" value="Clear" onClick="resetcanvas();" />
		</div>
		<input type="hidden" name="imageData" id="imageData" />
		<input type="hidden" name="PersonID" id="PersonID" />
		<input type="hidden" name="SignType" id="SignType" />
	</div>	
</div>			
<div id="openUploadDocDialog" class="modalDialog">	
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h3>Upload Document</h3>
		<hr>
		<br/>
		<input type="hidden" name="dlgPhotoID" id="dlgPhotoID">
		<table id="dlgNewDoc" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td>
						Select the person the document is for and then select the document you want to upload.
					</td>
				</tr>
				<tr>
					<td align="center">
						<select name="person" id="person">
							<option value=""></option>
							<?php
								$sql = "Select PersonID, Last_Name, First_Name, Middle_Name from App_Person where Company_Name = :Company_Name and AppCompleted = 'Y' and Completed = 'N' and Archived = 'N' and Deleted = 'N' order by Last_Name";
								$person_result = $dbo->prepare($sql);
								$person_result->bindValue(':Company_Name', $companyname);
								$person_result->execute();
								while($rows = $person_result->fetch(PDO::FETCH_BOTH)) {		
									echo "<option value=".$rows[0].">".$rows[0].' - '.$rows[2]." ".$rows[3]." ".$rows[1]."</option>";
								}		
							?>	
						</select>	
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
						<input type="file" id="sortdoc" name="sortdoc" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
		<center>
			<a href="#" id="uploaddoc" class="search_button" rel="modal:close">
				<font color="#2E2EFE">--</font><font color="white">Upload Document</font><font color="#2E2EFE">--</font>
			</a>
		</center>			
	</div>	
</div>			
<div id="openViewDocDialog" class="modalDialog">	
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h3>View Document</h3>
		<hr>
		<br/>
		<input type="hidden" name="dlgPhotoID" id="dlgPhotoID">
		<table id="dlgNewViewDoc" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td>
						Select the person that has the document you want to view and then select the document you want to view.
					</td>
				</tr>
				<tr>
					<td align="center">
						<select name="person" id="person" onchange="loaddocs(this.options[this.selectedIndex].value)">
							<option value="">Select Person</option>
							<?php
								$sql = "Select PersonID, Last_Name, First_Name, Middle_Name from App_Person where Company_Name = :Company_Name and Completed = 'N' and Archived = 'N' and Deleted = 'N' and PersonID in (Select PersonID from App_Uploads) order by Last_Name";
								$person_result = $dbo->prepare($sql);
								$person_result->bindValue(':Company_Name', $companyname);
								$person_result->execute();
								while($rows = $person_result->fetch(PDO::FETCH_BOTH)) {		
									echo "<option value=".$rows[0].">".$rows[2]." ".$rows[3]." ".$rows[1]."</option>";
								}		
							?>	
						</select>	
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
							<select name="doc" id="doc">
								<option value="">Select a Document</option>
							</select></span>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<br /><br />
		<center>
			<a href="#" id="viewdoc" class="search_button" rel="modal:close">
				<font color="#2E2EFE">--</font><font color="white">View Document</font><font color="#2E2EFE">--</font>
			</a>
		</center>			
	</div>	
</div>