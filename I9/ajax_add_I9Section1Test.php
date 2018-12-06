<?Php
require_once('../pdotriton.php');
$PersonID = $_REQUEST['PersonID'];
$JobID = $_REQUEST['JobID'];
$CustomerID = $_REQUEST['CustomerID'];
$Last_Name = $_REQUEST['Last_Name'];
$First_Name = $_REQUEST['First_Name'];
$Middle_Name = $_REQUEST['Middle_Name'];
$Other_Last_Name = $_REQUEST['Other_Last_Name'];
#$namechg = $_REQUEST['namechg'];
#if ($namechg == ' ') {
#	$namechg = '1900-01-01';
#} else {	
#	$namechg = date("Y-m-d", strtotime($namechg));
#}

$SSN = $_REQUEST['SSN'];
if ($_REQUEST['Date_of_Birth'] == '') {
	$Date_of_Birth = '1900-01-01';
} else {	 
	$Date_of_Birth = date('Y-m-d', strtotime($_REQUEST['Date_of_Birth']));
}	
$Phone = $_REQUEST['Phone'];
$Email = $_REQUEST['Email'];
$Address = $_REQUEST['Address'];
$Apt = $_REQUEST['Apt'];
$City = $_REQUEST['City'];
$State_Code = $_REQUEST['State_Code'];
$Zip_Code = $_REQUEST['Zip_Code'];
$Citizen = $_REQUEST['Citizen'];
$NonCitizen = $_REQUEST['NonCitizen'];
$Permanent_Resident = $_REQUEST['Permanent_Resident'];
$Registration_USCIS = $_REQUEST['Registration_USCIS'];
$Alien_Auth_Work = $_REQUEST['Alien_Auth_Work'];
if ($_REQUEST['Auth_Work_Date'] == '') {
	$Auth_Work_Date = '1900-01-01';
} else {	
	$Auth_Work_Date = date('Y-m-d', strtotime($_REQUEST['Auth_Work_Date']));
}	
$I94_Admission = $_REQUEST['I94_Admission'];
$Foreign_Passport = $_REQUEST['Foreign_Passport'];
$Foreign_Passport_Country = $_REQUEST['Foreign_Passport_Country'];
$Employee_Signature = $_REQUEST['Employee_Signature'];
if ($_REQUEST['Employee_Signature_Date'] == '') {
	$Employee_Signature_Date = '1900-01-01';
} else {
	$Employee_Signature_Date = date('Y-m-d', strtotime($_REQUEST['Employee_Signature_Date']));
}

if (isset($_REQUEST['Digital_Employee_Signature'])) {
	$Digital_Employee_Signature = $_REQUEST['Digital_Employee_Signature'];
} else {	
	$Digital_Employee_Signature = 'N';
}
	
if (isset($_REQUEST['Digital_EmpSignDate'])) {
	if ($_REQUEST['Digital_EmpSignDate'] == '') {
		$Digital_EmpSignDate = '1900-01-01';
	} else {
		$Digital_EmpSignDate = date('Y-m-d', strtotime($_REQUEST['Digital_EmpSignDate']));
	}	
}
if (isset($_REQUEST['Digital_Preparer_Signature'])) {
	$Digital_Preparer_Signature = $_REQUEST['Digital_Preparer_Signature'];
} else {	
	$Digital_Preparer_Signature = 'N';
}
if (isset($_REQUEST['Digital_PTSignDate'])) {
	if ($_REQUEST['Digital_PTSignDate'] == '') {
		$Digital_PTSignDate = '1900-01-01';
	} else {
		$Digital_PTSignDate = date('Y-m-d', strtotime($_REQUEST['Digital_PTSignDate']));
	}	
}
$Did_Used_Preparer = $_REQUEST['Did_Used_Preparer'];
$Did_Not_Used_Preparer = $_REQUEST['Did_Not_Used_Preparer'];
$Preparer_Signature = $_REQUEST['Preparer_Signature'];
if ($_REQUEST['Preparer_Signature_Date'] == '') {
	$Preparer_Signature_Date = '1900-01-01';
} else {	
	$Preparer_Signature_Date = date('Y-m-d', strtotime($_REQUEST['Preparer_Signature_Date']));
}
$Preparer_Last_Name = $_REQUEST['Preparer_Last_Name'];
$Preparer_First_Name = $_REQUEST['Preparer_First_Name'];
$Preparer_Address = $_REQUEST['Preparer_Address'];
$Preparer_City = $_REQUEST['Preparer_City'];
$Preparer_State = $_REQUEST['Preparer_State'];
$Preparer_Zip_Code = $_REQUEST['Preparer_Zip_Code'];
$ipAddress = getenv("REMOTE_ADDR");
$CodeID = $_REQUEST['CD'];
$Entered = date("Y-m-d H:i:s");
$Company_Name = $dbo->query("Select Company_Name from App_Person where PersonID = ".$PersonID.";")->fetchColumn();	
$msg = "";
/*	$to = "Dennis Boyer <dennis.boyer@bisi.com>";
	$hellobody = "inserting I9Section1\n - Add Person - Person: ".$PersonID."\nDate of Birth: ".$Date_of_Birth
				."\nEmployee_Signature_Date: ".$Employee_Signature_Date."\nPreparer_Signature_Date: ".$Preparer_Signature_Date
				."\nEnter Date: ".$Entered;
	$hellosubject = "I9Section1";
	$hellofrom = "I9Section1 <info@bisi.com>";
	mail($to, $hellosubject, $hellobody, "From: $hellofrom");
*/
#$sql = "delete from I9Section1 Where PersonID = :PersonID";
#$delete=$dbo->prepare($sql);
#$delete->bindValue(':PersonID', $PersonID);
#$delete->execute();

$sql = "update I9Section1 set Last_Name = :Last_Name, First_Name = :First_Name, Middle_Name = :Middle_Name, 
		Other_Last_Name = :Other_Last_Name, SSN = :SSN, Date_of_Birth = :Date_of_Birth, Phone = :Phone, Email = :Email, 
		Address = :Address, Apt= :Apt, City = :City, State_Code = :State_Code, Zip_Code = :Zip_Code, Entered = :Entered, Company_Name = :Company_Name, 
		ipAddress = :ipAddress, Citizen = :Citizen, NonCitizen = :NonCitizen, Permanent_Resident = :Permanent_Resident, 
		Registration_USCIS = :Registration_USCIS, Alien_Auth_Work = :Alien_Auth_Work, Auth_Work_Date = :Auth_Work_Date, 
		I94_Admission = :I94_Admission, Foreign_Passport = :Foreign_Passport, Foreign_Passport_Country = :Foreign_Passport_Country, 
		Employee_Signature = :Employee_Signature, Employee_Signature_Date = :Employee_Signature_Date, Digital_Employee_Signature = :Digital_Employee_Signature, 
		Digital_Employee_Signature_Date = :Digital_EmpSignDate, Digital_Preparer_Signature = :Digital_Preparer_Signature, Digital_Preparer_Signature_Date = :Digital_PTSignDate,
		Did_Used_Preparer = :Did_Used_Preparer, Did_Not_Used_Preparer = :Did_Not_Used_Preparer, Preparer_Signature = :Preparer_Signature, Preparer_Signature_Date = :Preparer_Signature_Date, 
		Preparer_Last_Name = :Preparer_Last_Name, Preparer_First_Name = :Preparer_First_Name, Preparer_Address = :Preparer_Address, 
		Preparer_City = :Preparer_City, Preparer_State = :Preparer_State, Preparer_Zip_Code = :Preparer_Zip_Code where PersonID = :PersonID and JobID = :JobID and CustomerID = :CustomerID";

/*
$sql = "insert into I9Section1 (PersonID, Last_Name, First_Name, Middle_Name, Other_Last_Name, SSN, Date_of_Birth, Phone, Email, 
								Address, Apt, City, State_Code, Zip_Code, Entered, Company_Name, ipAddress, Citizen, NonCitizen, Permanent_Resident, Registration_USCIS, 
								Alien_Auth_Work, Auth_Work_Date, I94_Admission, Foreign_Passport, Foreign_Passport_Country, 
								Employee_Signature, Employee_Signature_Date, Did_Used_Preparer, Did_Not_Used_Preparer, 
								Preparer_Signature, Preparer_Signature_Date, Preparer_Last_Name, Preparer_First_Name, 
								Preparer_Address, Preparer_City, Preparer_State, Preparer_Zip_Code, CodeID) 
						values(:PersonID, :Last_Name, :First_Name, :Middle_Name, :Other_Last_Name, :SSN, :Date_of_Birth, 
						       :Phone, :Email, :Address, :Apt, :City, :State_Code, :Zip_Code, :Entered, :Company_Name, :ipAddress, 
						       :Citizen, :NonCitizen, :Permanent_Resident, :Registration_USCIS, :Alien_Auth_Work, :Auth_Work_Date, 
						       :I94_Admission, :Foreign_Passport, :Foreign_Passport_Country, :Employee_Signature, :Employee_Signature_Date,  
							   :Did_Used_Preparer, :Did_Not_Used_Preparer, :Preparer_Signature, :Preparer_Signature_Date,  
							   :Preparer_Last_Name, :Preparer_First_Name, :Preparer_Address, :Preparer_City, :Preparer_State, :Preparer_Zip_Code, :CodeID)";
*/
$save_result = $dbo->prepare($sql);
$save_result->bindValue(':PersonID', $PersonID);
$save_result->bindValue(':JobID', $JobID);
$save_result->bindValue(':CustomerID', $CustomerID);
$save_result->bindValue(':Last_Name', $Last_Name);
$save_result->bindValue(':First_Name', $First_Name);
$save_result->bindValue(':Middle_Name', $Middle_Name);
$save_result->bindValue(':Other_Last_Name', $Other_Last_Name);
$save_result->bindValue(':SSN', $SSN);
$save_result->bindValue(':Date_of_Birth', $Date_of_Birth, PDO::PARAM_STR);
$save_result->bindValue(':Phone', $Phone);
$save_result->bindValue(':Email', $Email);
$save_result->bindValue(':Address', $Address);
$save_result->bindValue(':Apt', $Apt);
$save_result->bindValue(':City', $City);
$save_result->bindValue(':State_Code', $State_Code);
$save_result->bindValue(':Zip_Code', $Zip_Code);
$save_result->bindValue(':Entered', $Entered, PDO::PARAM_STR); 
$save_result->bindValue(':Company_Name', $Company_Name); 
$save_result->bindValue(':ipAddress', $ipAddress);
$save_result->bindValue(':Citizen', $Citizen);
$save_result->bindValue(':NonCitizen', $NonCitizen);
$save_result->bindValue(':Permanent_Resident', $Permanent_Resident);
$save_result->bindValue(':Registration_USCIS', $Registration_USCIS);
$save_result->bindValue(':Alien_Auth_Work', $Alien_Auth_Work);
$save_result->bindValue(':Auth_Work_Date', $Auth_Work_Date);
$save_result->bindValue(':I94_Admission', $I94_Admission); 
$save_result->bindValue(':Foreign_Passport', $Foreign_Passport);
$save_result->bindValue(':Foreign_Passport_Country', $Foreign_Passport_Country); 
$save_result->bindValue(':Employee_Signature', $Employee_Signature); 
$save_result->bindValue(':Employee_Signature_Date', $Employee_Signature_Date, PDO::PARAM_STR);  
$save_result->bindValue(':Did_Used_Preparer', $Did_Used_Preparer);
$save_result->bindValue(':Did_Not_Used_Preparer', $Did_Not_Used_Preparer);
$save_result->bindValue(':Preparer_Signature', $Preparer_Signature);
$save_result->bindValue(':Preparer_Signature_Date', $Preparer_Signature_Date);  
$save_result->bindValue(':Preparer_Last_Name', $Preparer_Last_Name);
$save_result->bindValue(':Preparer_First_Name', $Preparer_First_Name); 
$save_result->bindValue(':Preparer_Address', $Preparer_Address);
$save_result->bindValue(':Preparer_City', $Preparer_City); 
$save_result->bindValue(':Preparer_State', $Preparer_State);
$save_result->bindValue(':Preparer_Zip_Code', $Preparer_Zip_Code); 
$save_result->bindValue(':Digital_Employee_Signature', $Digital_Employee_Signature);
$save_result->bindValue(':Digital_EmpSignDate', $Digital_EmpSignDate, PDO::PARAM_STR);
$save_result->bindValue(':Digital_Preparer_Signature', $Digital_Preparer_Signature);
$save_result->bindValue(':Digital_PTSignDate', $Digital_PTSignDate, PDO::PARAM_STR);
#$save_result->bindValue(':CodeID', $CodeID); 
      
if (!$save_result->execute()) {
	$msg .= "Error Updating I9 Section 1 Info. Error Code: ".$save_result->errorCode();
} else {
	$Status = 'I9 Section 1 Data Added';
	$EntryDate = date("Y-m-d H:i:s");
	$Comment = $First_Name.' '.$Last_Name.' has added data to Section 1.';
	$sql2 = "insert into App_Log (PersonID, Status, Status_Date, Email, Comment) values(:PersonID, :Status, :Status_Date, :Email, :Comment);";
	$save2_result = $dbo->prepare($sql2);
	$save2_result->bindValue(':PersonID', $PersonID);
	$save2_result->bindValue(':Status', $Status);
	$save2_result->bindValue(':Status_Date', $EntryDate, PDO::PARAM_STR);
	$save2_result->bindValue(':Email', '');
	$save2_result->bindValue(':Comment', $Comment);
	if (!$save2_result->execute()) {
		if ($save2_result->errorCode() != 23000) {		
			$hellofrom = "Log Update <service@bisi.com>";
			$helloto = "Dennis Boyer <dennis.boyer@bisi.com>";
			$hellosubject = "Section1 Complete Log Update Error";
			$hellobody = "PersonID = ".$PersonID."\nError Inserting into Log. Error Code: ".$save2_result->errorCode();
			mail($helloto, $hellosubject, $hellobody, "From: $hellofrom");
		}	
	} 
}
	
echo json_encode($msg); 
?>