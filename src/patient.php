<!DOCTYPE html>
<html lang="en">

<?php

	include('session.php');

	$myStateID = $_SESSION['userid'];
	//in order to case that doctor wants to see patient's page
	if (isset($_GET["patient_ID"])){
		$myStateID = $_GET["patient_ID"];
	}
	
	//--functions--
	//Update your pharmacy's name
  function updatePass($db, $newPass, $myStateID){
    $sqlNewPass = "UPDATE user
                      SET user.password = '$newPass'
                      WHERE  user.state_ID = $myStateID";
    mysqli_query($db,$sqlNewPass);
  }
  if(isset($_POST['newPassBut'])){
		$newPass = $_POST["newPass"];
		updatePass($db, $newPass, $myStateID);
  }

  //Update your pharmacy's phone
  function updatePhone($db, $newPhone, $myStateID){
    $sqlNewPhone = "UPDATE user
                        SET user.phone = '$newPhone'
                        WHERE  user.state_ID = $myStateID";
    mysqli_query($db,$sqlNewPhone);
  }
  if(isset($_POST['newPhoneBut'])){
		$newPhone = $_POST["newPhone"];
		updatePhone($db, $newPhone, $myStateID);
  }

  //Update your pharmacy's address
  function updateAddress($db, $newAddress, $myStateID){
    $sqlNewAddress = "UPDATE patient
                          SET patient.address = '$newAddress'
                          WHERE  user.state_ID = $myStateID";
    mysqli_query($db,$sqlNewAddress);
  }
  if(isset($_POST['newAddressBut'])){
		$newAddress = $_POST["newAddress"];
		updateAddress($db, $newAddress, $myStateID);
	}
	
	
	
   //Update your Emergency Contact
	function insertEC($db, $ecNewName,$ecNewTel,$ecNewRel,$myStateID){
    $sqlInsertEC = "INSERT INTO emergency_contact VALUES('$myStateID', '$ecNewName', '$ecNewTel', '$ecNewRel')";
    $resultEC = mysqli_query($db,$sqlInsertEC);
	}
	if(isset($_POST['newEmergencyButton'])){
		$ecNewName = $_POST["newEmergencyName"];
		$ecNewTel = $_POST["newEmergencyPhone"];
		$ecNewRel = $_POST["newEmergencyRel"];
		insertEC($db, $ecNewName,$ecNewTel,$ecNewRel,$myStateID);
	}
	
	//
	function removeEC($db, $ecRemName,$myStateID){
    $sqlRemEC = "DELETE FROM emergency_contact WHERE state_ID = '$myStateID' and emergency_contact_name = '$ecRemName'";
    $resultEC = mysqli_query($db,$sqlRemEC);
	}
	if(isset($_POST['removeEmerContactsBut'])){
		$ecRemName = $_POST["removeEmerContacts"];
		removeEC($db, $ecRemName,$myStateID);
	}
	
		//Give Rating
  function giveRating($db, $newRating){
		$sqlnewRating = "INSERT INTO rating VALUES (NULL, '$newRating')";
		$resultRate = mysqli_query($db,$sqlnewRating);
	}
	if(isset($_POST['rateButton'])){
		$newRating = $_POST["newRating"];
		giveRating($db, $newRating);
	}
	
	//BAKILACAK!
	 //show Departments in selected Hospital
	 function showDoc($db,$givenDepID){
	$sqlShowDoc = "SELECT first_name, middle_name, last_name, doctor_department, doctor_title FROM doctor NATURAL JOIN user 
					WHERE user.state_ID = doctor.state_ID AND doctor_department IN ( ) ";
	$resultDoc = mysqli_query($db,$sqlShowDoc);
	 }
	 if(isset($_POST['newHospIDBut'])){
	 $givenHospID =  $_POST["newHospID"];
	 showDep($db,$givenHospID);
	}
	
	//filter Tre ID 
	function filterTreID($db,$givenExID){
   $sqlTreID = "SELECT treatment_ID, treatment_description FROM examination_result NATURAL JOIN treatment WHERE examination_ID = '$givenExID'";
   $resultTreID = mysqli_query($db,$sqlTreID);
	 }
	 if(isset($_POST['givenExIDBut'])){
	 $givenExID =  $_POST["givenExID"];
	 filterTreID($db,$givenExID);
	}
	
	//Departments in Selected Hosp
	function sendDepartments($mysqli,$hospitalID){

	$resultDep = $mysqli -> query("SELECT hospital_department FROM hospital_department WHERE hospital_ID = '$hospitalID'"); 
	if($resultDep){
		$num_rows = $resultDep->num_rows;
		
		if($num_rows > 0){
			while ($row = $resultDep->fetch_assoc()) {
				
				$json[]= array(
					'hospital_department' => $row["hospital_department"]
											  
				 );		
			}			
			$jsonstring = json_encode($json);
			echo $jsonstring;
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}
}
	 
	
	
	//------------Information--------
	 //Retrieve your Emergency Contacts
  $sqlEmerContacts = "Select * FROM emergency_contact WHERE state_ID = '$myStateID'";
  //bunu echoluyoruz!!!
  $resultEmerContacts = mysqli_query($db,$sqlEmerContacts);
  
  //show Vaccine History
   $sqlShowVacHist = "SELECT vaccine.vaccine_name, vaccinates.date FROM vaccine NATURAL JOIN vaccinates WHERE patient_state_ID = '$myStateID'";
   $resultVacHist = mysqli_query($db,$sqlShowVacHist);
   
    //show Examination History
   $sqlShowExHist = "SELECT examination_ID, examination_cause, examination_date, examination_diagnose, doctor_state_ID FROM examination NATURAL JOIN examination_done NATURAL JOIN patient WHERE state_ID = '$myStateID'";
   $resultExHist = mysqli_query($db,$sqlShowExHist);
   
   //show Treatment History
   $sqlShowTreHist = "SELECT treatment_ID, treatment_description FROM treatment NATURAL JOIN patient WHERE state_ID = '$myStateID'";
   $resultTreHist = mysqli_query($db,$sqlShowTreHist);
   
   //show Test Results
   $sqlShowTestRes = "SELECT test_result FROM test NATURAL JOIN patient WHERE state_ID = '$myStateID'";
   $resultTestRes = mysqli_query($db,$sqlShowTestRes);
   
   //show Allergies
   $sqlShowAll = "SELECT allergy_name FROM patient_allergies WHERE state_ID = '$myStateID'";
   $resultAll = mysqli_query($db,$sqlShowAll);
   
   //show Chronic Diseases
   $sqlShowChDis = "SELECT chronic_disease FROM patient_chronic_diseases WHERE state_ID = '$myStateID'";
   $resultChDis = mysqli_query($db,$sqlShowChDis);
   
   //show Patient Info
   //$sqlShowPatInfo = "SELECT vaccine.vaccine_name, vaccinates.date FROM vaccine NATURAL JOIN vaccinates WHERE patient_state_ID = '$myStateID'";
   //$resultPatInfo = mysqli_query($db,$sqlShowPatInfo);
   
   //show Hospitals
   $sqlShowHosp = "SELECT hospital_ID, hospital_name, hospital_capacity, hospital_telephone, hospital_address FROM hospital ";
   $resultHosp = mysqli_query($db,$sqlShowHosp);
   
   //show Prescription History
   $sqlShowPreHist = "SELECT prescription_ID, drug_ID, drug_name FROM prescribed NATURAL JOIN drug NATURAL JOIN patient WHERE state_ID = '$myStateID'";
   $resultPreHist = mysqli_query($db,$sqlShowPreHist);
   
    //show Pharmacies
   $sqlShowPha = "SELECT pharmacy_ID, pharmacy_name, pharmacy_address, pharmacy_phone FROM pharmacy ;";
   $resultShowPha = mysqli_query($db,$sqlShowPha);
   
   //Your pharmacy's information
	$sqlMyInfo = "SELECT state_ID, first_name, middle_name, last_name FROM user WHERE state_ID = '$myStateID'";
	$myInfoQ = mysqli_query($db,$sqlMyInfo);
	$rowInfo = mysqli_fetch_array($myInfoQ);
	
	//Age Info
	$sqlAgeInfo = "SELECT age FROM patient_age WHERE state_ID = '$myStateID'";
	$myAgeQ = mysqli_query($db,$sqlAgeInfo);
	$resAge = mysqli_fetch_array($myAgeQ);
	
	
	
	
?>

<html>

<head>
	<title>Patient's Page</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Material Design Bootstrap</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
	<!-- MDBootstrap Datatables  -->
	<link href="css/addons/datatables.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
	<link rel="stylesheet" href="currentPatientTableCSS.css">
</head>

<body>
	<div align = "center">
		<div style = "font-size:24px; padding:3px; "><b>Patient Page</b></div>
		
		<a href = "logout.php" style = "padding:3px; background-color:#D3D3D3; color:#000000; border: solid 1px #333333; margin-left:450px">Log Out</a>
		
		<div style = "margin:20px">  
			<label>Your ID : <?php echo $myStateID; ?></label><br><br/>
            <label>Your First Name : <?php echo $rowInfo['first_name']; ?></label><br><br/>
            <label>Your Middle Name : <?php echo $rowInfo['middle_name']; ?></label><br><br/>
            <label>Your Last Name : <?php echo $rowInfo['last_name']; ?></label><br><br/>
			<label>Your Age : <?php echo $resAge['age']; ?></label><br><br/>
		</div>
			<div style = "font-size:24px; padding:3px; "><b>View Medical Information</b></div>
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>View Vaccine History</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtViewVaccineHistory" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Vaccine_name
								  </th>
								  <th class="th-sm">Date
								  </th>
								</tr>
							  </thead>
							  <tbody>
							  <?php while($rowVacHis = mysqli_fetch_array($resultVacHist)) : ?>
								<tr>
									<td><?php echo $rowVacHis['vaccine_name']; ?></td>
									<td><?php echo $rowVacHis['date']; ?></td>
								</tr>
								<?php endwhile; ?>
							  </tbody>
							</table>
							
						</div>
					</div>	
					
					<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>View Examination History</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtViewExeminationHistory" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Examination_ID:
								  </th>
								  <th class="th-sm">Examination_cause
								  </th>
								  <th class="th-sm">Examination_date
								  </th>
								  <th class="th-sm">Examination_diagnose
								  </th>
								  <th class="th-sm">Doctor_ID
								  </th>
								</tr>
							  </thead>
							  <tbody>
								<?php while($rowExHis = mysqli_fetch_array($resultExHist)) : ?>
								<tr>
									<td><?php echo $rowExHis['examination_ID']; ?></td>
									<td><?php echo $rowExHis['examination_cause']; ?></td>
									<td><?php echo $rowExHis['examination_date']; ?></td>
									<td><?php echo $rowExHis['examination_diagnose']; ?></td>						
									<td><?php echo $rowExHis['doctor_state_ID']; ?></td>
	
									
								</tr>
								<?php endwhile; ?>			
							  </tbody>
							</table>
							
						</div>
					</div>	
					
					<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>View Treatment History</b></div>	
						<div  align = "center">
						<br></br>
							<form action = "" method = "POST">
								<label>Enter Examination ID : </label><input type = "text" name = "givenExID" class = "box" style = "margin-left:30px"/><input type = "submit" name = "givenExIDBut" value = " Search " style = "margin-left:10px"/>
							</form>
						</div>
						<div style = "margin:20px">
						   
							<table id="dtViewTreatmentHistory" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Treatment_ID:
								  </th>
								  <th class="th-sm">Treatment_description
								  </th>
							  </thead>
							  <tbody>
							  <?php while($rowTreHis = mysqli_fetch_array($resultTreHist)) : ?>
								<tr>
									<td><?php echo $rowTreHis['treatment_ID']; ?></td>
									<td><?php echo $rowTreHis['treatment_description']; ?></td>
								</tr>
								<?php endwhile; ?>			
							  </tbody>
							</table>
							
						</div>
					</div>	
					
					<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Test Results</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtTestResults" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Result
								  </th>
								</tr>
							  </thead>
							  <tbody>
							  <?php while($rowTestRes = mysqli_fetch_array($resultTestRes)) : ?>
								<tr>
									<td><?php echo $rowTestRes['test_result']; ?></td>
								</tr>
								<?php endwhile; ?>
							  </tbody>
							</table>
							
						</div>
					</div>	
					

					<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Allergies</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtAllergies" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Allergies
								  </th>
								</tr>
							  </thead>
							  <tbody>
							   <?php while($rowAll = mysqli_fetch_array($resultAll)) : ?>
								<tr>
									<td><?php echo $rowAll['allergy_name']; ?></td>
								</tr>
								<?php endwhile; ?>
							  </tbody>
							</table>
							
						</div>
					</div>		

					<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Chronic Diseases</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtChronicDiseases" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Chronic Diseases
								  </th>
								</tr>
							  </thead>
							  <tbody>
								<?php while($rowChDis = mysqli_fetch_array($resultChDis)) : ?>
								<tr>
									<td><?php echo $rowChDis['chronic_disease']; ?></td>
								</tr>
								<?php endwhile; ?>
							  </tbody>
							</table>
							
						</div>
					</div>	
					
			
			
			<div style = "font-size:24px; padding:3px; "><b>Profile Information</b></div>
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Edit Profile</b></div>
				<div style = "margin:20px">
					<form action = "" method = "POST">
						<label>New Password : </label><input type = "text" name = "newPass" class = "box" style = "margin-left:30px"/><input type = "submit" name = "newPassBut" value = " Change " style = "margin-left:10px"/>
					</form>
					<form action = "" method = "POST">
						<label>New Phone    : </label><input type = "text" name = "newPhone" class = "box" style = "margin-left:27.5px"/><input type = "submit" name = "newPhoneBut" value = " Change " style = "margin-left:10px"/>
					</form>
					<form action = "" method = "POST">
						<label>New Address  : </label><input type = "text" name = "newAddress" class = "box" style = "margin-left:14.5px"/><input type = "submit" name = "newAddressBut" value = " Change " style = "margin-left:10px"/>
					</form>
				</div>
				</div>
			
				
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>View Emergency Contact</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtViewEmergencyContact" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Emergency_contact_name
								  </th>
								  <th class="th-sm">Emergency_contact_phone
								  </th>
								  <th class="th-sm">Emergency_contact_relationship
								  </th>
								   <th class="th-sm">Delete_emergency_contact
								  </th>
							  </thead>
							  <tbody>
								<?php while($rowEmerContacts = mysqli_fetch_array($resultEmerContacts)) : ?>
								<tr>
									<td><?php echo $rowEmerContacts['emergency_contact_name']; ?></td>
									<td><?php echo $rowEmerContacts['emergency_contact_telephone']; ?></td>
									<td><?php echo $rowEmerContacts['emergency_contact_relationship']; ?></td>
									<td>
										<form action="" method="POST">
											<input type= "hidden" name = "removeEmerContacts" value = "<?php echo $rowEmerContacts['emergency_contact_name']; ?>" />
											<input type= "submit" name = "removeEmerContactsBut" value = "Remove from Contact" style="margin:0px auto; display:block;"/>
										</form>
									</td>
								</tr>
								<?php endwhile; ?>				
							  </tbody>
							</table>
							
						</div>
				</div>	
				
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Add Emergency Contact</b></div>
					
				<div style = "margin:20px">
				   
					<form action = "" method = "POST">
						<label>Emergency_contact_name : </label><input type = "text" name = "newEmergencyName" class = "box" /><br/><br/>
						<label>Emergency_contact_phone : </label><input type = "text" name = "newEmergencyPhone" class = "box" style = "margin-left:14.5px" /><br/><br/>
						<label>Emergency_contact_relationship : </label><input type = "text" name = "newEmergencyRel" class = "box" style = "margin-left:13px" /><br/><br/>
						<input type = "submit" name = "newEmergencyButton" value = " Add Emergency Contact " style = "margin-left:115px"/>
					</form>
					
				</div>
				</div>
				<br></br>
				<div style = "font-size:24px; padding:3px; "><b>Book Appointment</b></div>
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Hospitals</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtHospitals" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Hospital_ID
								  </th>
								  <th class="th-sm">Hospital_name
								  </th>
								  <th class="th-sm">Hospital_capacity
								  </th>
								  <th class="th-sm">Hospital_telephone
								  </th>
								  <th class="th-sm">Hospital_address
								  </th>
								</tr>
							  </thead>
							  <tbody>
											
							  </tbody>
							</table>
							
						</div>
				</div>	
				<br></br>
				
						
			
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Hospital Departments</b></div>
						
						<div  align = "center">
						<br></br>
						<form action = "" method = "POST">
								<label>Enter Hospital ID : </label><input type = "text" name = "newHospID" class = "box" style = "margin-left:30px"/><input type = "submit" name = "newHospIDBut" value = " Search " style = "margin-left:10px"/>
						</form>
						</div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtHospitalDepartments" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								<th class="th-sm">Hospital_departments
								  </th>
								</tr>
							  </thead>
							  <tbody>
							  
							
							
							
							  </tbody>
							</table>
							
						</div>
					</div>	
				
				
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Doctors</b></div>	
						
						<div  align = "center">
						<br></br>
						<form action = "" method = "POST">
								<label>Enter Department ID : </label><input type = "text" name = "newDepID" class = "box" style = "margin-left:30px"/><input type = "submit" name = "newDepIDBut" value = " Search " style = "margin-left:10px"/>
						</form>
						</div>
						
						<div style = "margin:20px">
						   
							<table id="dtDoctors" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Doctor_name
								  </th>
								  <th class="th-sm">Doctor_department
								  </th>
								  <th class="th-sm">Doctor_title
								  </th>
								  <th class="th-sm">Appointment
								  </th>
							  </thead>
							  <tbody>
								

								
								</tr>	
								</tr>				
							  </tbody>
							</table>
							
						</div>
					</div>	
				
				<br></br>
				<div style = "font-size:24px; padding:3px; "><b>Buy Drug and Check Drug Availability in Pharmacy</b></div>
				
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>View Prescription History</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtViewPrescriptionHistory" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Prescription_ID
								  </th>
								  <th class="th-sm">Drug_ID
								  </th>
								  <th class="th-sm">Drug_name
								  </th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
								  <?php while($rowPreHist = mysqli_fetch_array($resultPreHist)) : ?>
								<tr>
									<td><?php echo $rowPreHist['prescription_ID']; ?></td>
									<td><?php echo $rowPreHist['drug_ID']; ?></td>
									<td><?php echo $rowPreHist['drug_name']; ?></td>
								</tr>
								<?php endwhile; ?>	
								</tr>
							  </tbody>
							</table>
							
						</div>
					</div>	
				
				<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
						<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Pharmacies</b></div>
						
							
						<div style = "margin:20px">
						   
							<table id="dtPharmacies" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
							  <thead>
								<tr>
								  <th class="th-sm">Pharmacy_ID
								  </th>
								  <th class="th-sm">Pharmacy_name
								  </th>
								  <th class="th-sm">Pharmacy_address
								  </th>
								  <th class="th-sm">Pharmacy_telephone
								  </th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
								  <?php while($rowPha = mysqli_fetch_array($resultShowPha)) : ?>
								<tr>
									<td><?php echo $rowPha['pharmacy_ID']; ?></td>
									<td><?php echo $rowPha['pharmacy_name']; ?></td>
									<td><?php echo $rowPha['pharmacy_address']; ?></td>
									<td><?php echo $rowPha['pharmacy_phone']; ?></td>
								</tr>
								<?php endwhile; ?>	
								</tr>				
							  </tbody>
							</table>
							<label>Enter Pharmacy Name : </label><input type = "text" name = "newPharmacistPhone" class = "box" style = "margin-left:13px" /><br/><br/>
						<input type = "submit" value = " Find " style = "margin-left:115px"/>
						<br></br>
						<label>Enter Drug ID : </label><input type = "text" name = "newPharmacistPhone" class = "box" style = "margin-left:13px" /><br/><br/>
						<input type = "submit" value = " Buy Selected Drug From Selected Pharmacy " style = "margin-left:115px"/>
						</div>
				</div>	
			</div>
	</div>

 <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
	<!-- MDBootstrap Datatables  -->
	<script type="text/javascript" src="js/addons/datatables.min.js"></script>
	<!-- JS for Tables -->
	<script type="text/javascript" src="currentPharmacistTableJS.js"></script>


	<script>
		$(document).ready(function () {
		$('#dtViewVaccineHistory').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtViewExeminationHistory').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtViewTreatmentHistory').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtTestResults').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtAllergies').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtChronicDiseases').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtViewEmergencyContact').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtHospitals').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtHospitalDepartments').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtDoctors').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtViewPrescriptionHistory').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
		$('#dtPharmacies').DataTable({
			"scrollX": true,
			"scrollY": 200,
		});
			$('.dataTables_length').addClass('bs-select');
		});
	</script>
</body>
</html> 