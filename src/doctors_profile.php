<?php
include ('session.php');
if(isset($_POST['action'])) {
	error_log($_POST['action']);
	$action = $_POST['action'];
	$mysqli = new mysqli("dijkstra.ug.bcc.bilkent.edu.tr", "cagatay.sel", "O0M3xJo1", "cagatay_sel");
	if($action === 'get_doctor_information'){
		
		if($_POST["stateID"]){
			sendDoctorInformation($mysqli,$_POST["stateID"]);
		}
	}else if($action === 'get_hospital_information'){
			
			if($_POST["stateID"] ){
				sendHospitalInformation($mysqli,$_POST["stateID"]);
			}		

			echo NULL;
	}else if($action === 'get_departments'){
		if($_POST["hospitalID"]){
			sendDepartments($mysqli,$_POST["hospitalID"]);
		}

	}else if($action === "add_department"){
		if($_POST["hospital_department"]||$_POST["hospital_ID"]){
			addDepartment($mysqli,$_POST["hospital_ID"],$_POST["hospital_department"]);
		}

	}else if($action === 'remove_department') {
		if($_POST["hospital_department"]||$_POST["hospital_ID"]){
			removeDepartment($mysqli,$_POST["hospital_ID"],$_POST["hospital_department"]);
		}
		

	}
	else if($action === 'get_doctors') {
		if($_POST["hospital_ID"]){
			sendDoctors($mysqli,$_POST["hospital_ID"]);
		}
		

	}else if($action === 'add_doctor') {
		if($_POST["state_ID"]||$_POST["doctor_department"]||$_POST["doctor_title"]||$_POST["doctor_schedule"]||$_POST["hospital_ID"]){
			addDoctor($mysqli,$_POST["state_ID"],$_POST["doctor_department"],$_POST["doctor_title"],$_POST["doctor_schedule"],$_POST["hospital_ID"]);
		}
		

	}else if($action === 'remove_doctor') {
		if($_POST["state_ID"]||$_POST["hospital_ID"]){
			removeDoctor($mysqli,$_POST["state_ID"],$_POST["hospital_ID"]);
		}
		

	}else if($action === 'get_patient_information') {
		if($_POST["state_ID"]){
			sendPatientInformation($mysqli,$_POST["state_ID"]);
		}
		

	}else if($action === 'add_allergy') {
		if($_POST["state_ID"]||$_POST["allergy_name"]){
			addAllergy($mysqli,$_POST["state_ID"],$_POST["allergy_name"]);
		}
		

	}else if($action === 'add_chronic_disease') {
		if($_POST["state_ID"]||$_POST["chronic_disease"]){
			addChronicDisease($mysqli,$_POST["state_ID"],$_POST["chronic_disease"]);
		}
		

	}else if($action === 'add_examination') {
		if($_POST["doctor_ID"]||$_POST["state_ID"]||$_POST["examination_cause"]||$_POST["examination_date"]||$_POST["examination_diagnose"]){
			addExamination($mysqli,$_POST["doctor_ID"],$_POST["state_ID"],$_POST["examination_cause"],$_POST["examination_date"],$_POST["examination_diagnose"]);
		}
		

	}else if($action === 'add_test') {
		if($_POST["examination_ID"]||$_POST["test_name"]||$_POST["test_result"]){
			addTest($mysqli,$_POST["examination_ID"],$_POST["test_name"],$_POST["test_result"]);
		}
		

	}else if($action === 'add_treatment') {
		if($_POST["examination_ID"]||$_POST["treatment_description"]){
			addTreatment($mysqli,$_POST["examination_ID"],$_POST["treatment_description"]);
		}
		

	}else if($action === 'add_prescription') {
		if($_POST["examination_ID"]){
			addPrescription($mysqli,$_POST["examination_ID"]);
		}
		

	}else if($action === 'add_vaccine') {
		
		if($_POST["vaccine_ID"]||$_POST["state_ID"]||$_POST["doctor_ID"]||$_POST["date"]){
			
			addVaccine($mysqli,$_POST["vaccine_ID"],$_POST["state_ID"],$_POST["doctor_ID"],$_POST["date"]);
		}
		

	}else if($action === 'get_drugs') {
		
			
		sendDrugs($mysqli);	

	}else if($action === 'add_prescribed_drug') {
		
		if($_POST["prescription_ID"]||$_POST["drug_ID"]){
			
			addPrescribedDrug($mysqli,$_POST["prescription_ID"],$_POST["drug_ID"]);
		}
		

	}else if($action === 'request_state_ID') {
		
		$state_ID = $_SESSION["userid"];
		error_log($state_ID);
		echo $state_ID;
		

	}else {
		error_log("Non identified action");
	}			
		
}

function addPrescribedDrug($mysqli,$prescription_ID,$drug_ID){
	$result = $mysqli -> query("INSERT INTO prescribed VALUES('$prescription_ID','$drug_ID')"); 
}

function addVaccine($mysqli,$vaccine_ID,$state_ID,$doctor_ID,$date){
	
	$result = $mysqli -> query("INSERT INTO vaccinates VALUES('$vaccine_ID','$state_ID','$doctor_ID','$date')"); 
	//echo ("INSERT INTO vaccinates VALUES('$vaccine_ID,'$state_ID','$doctor_ID','$date')"); 
}

function addPrescription($mysqli,$examination_ID){

	//Insertion into prescription
	//echo ("last exam id pres: ");
	//echo $examination_ID;
	$result = $mysqli -> query("INSERT INTO prescription VALUES('NULL')");
	//echo  ("INSERT INTO prescription VALUES('NULL')");
	$last_prescription_id = $mysqli->insert_id;
	echo $last_prescription_id;

	//Update examination result
	//echo $last_prescription_id;
	//echo $examination_ID;	
	$update_result = $mysqli -> query("UPDATE examination_result SET prescription_ID = '$last_prescription_id' WHERE examination_ID = '$examination_ID'"); 

}

function addTest($mysqli,$examination_ID,$test_name,$test_result){

	//Insertion into test
	$result = $mysqli -> query("INSERT INTO test VALUES(NULL,'$test_result','$test_name')"); 
	$last_test_id = $mysqli->insert_id;

	//Update examination result
	echo $last_test_id ;
	//echo $examination_ID;	
	$update_result = $mysqli -> query("UPDATE examination_result SET test_ID = '$last_test_id' WHERE examination_ID = '$examination_ID'"); 
	
}

function addTreatment($mysqli,$examination_ID,$treatment_description){
	
	//Insertion into treatment
	$result = $mysqli -> query("INSERT INTO treatment VALUES(NULL,'$treatment_description')"); 
	$last_treatment_id = $mysqli->insert_id;

	//Update examination result
	echo $last_treatment_id ;
	//echo $examination_ID;	
	$update_result = $mysqli -> query("UPDATE examination_result SET treatment_ID = '$last_treatment_id' WHERE examination_ID = '$examination_ID'"); 
	

}

function addExamination($mysqli,$doctor_ID,$state_ID,$examination_cause,$examination_date,$examination_diagnose){

	//Insertion to examination
	$result = $mysqli -> query("INSERT INTO examination VALUES(NULL,'$examination_cause','$examination_date','$examination_diagnose')"); 
	$last_exam_id = $mysqli->insert_id;
	echo $last_exam_id;
	

	//Insertion to examination done
	$result = $mysqli -> query("INSERT INTO examination_done VALUES('$state_ID','$doctor_ID','$last_exam_id')"); 

	//Insertion to examination result
	$result = $mysqli -> query("INSERT INTO examination_result VALUES('$last_exam_id',NULL,NULL,NULL)");
	

}

function addChronicDisease ($mysqli,$state_ID,$chronic_disease){
	
	$result = $mysqli -> query("INSERT INTO patient_chronic_diseases VALUES('$state_ID','$chronic_disease')"); 

	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "insert_chronic_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		echo "chronic_disease_fail";
		echo NULL;
	}

}

function addAllergy($mysqli,$state_ID,$allergy_name){

	error_log($state_ID);
	error_log($allergy_name);
	$result = $mysqli -> query("INSERT INTO patient_allergies VALUES ('$state_ID','$allergy_name')"); 

	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "insert_doc_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}

}

function sendPatientInformation($mysqli,$state_ID){

	$result = $mysqli -> query("SELECT first_name,middle_name,last_name,patient_height,patient_weight,patient_bloodtype,age FROM patient NATURAL JOIN user NATURAL JOIN patient_age WHERE state_ID = '$state_ID'"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {

				$json[]= array(
					'first_name' => $row["first_name"],
					'middle_name' => $row["middle_name"],
					'last_name' => $row["last_name"],
					'patient_height' => $row["patient_height"],
					'patient_weight' => $row["patient_weight"],
					'patient_bloodtype' => $row["patient_bloodtype"],		  
					'age' => $row["age"],
				 );		
			}
			
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}else {
			echo "no_doctor";
		}	
		
	}else {		
		
		echo NULL;
	}

}

function removeDoctor($mysqli,$state_ID,$hospital_ID){

	$result = $mysqli -> query("DELETE FROM works_as_doctor WHERE state_ID = '$state_ID' AND hospital_ID = '$hospital_ID'"); 

	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "delete_doc_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}

}

function addDoctor($mysqli,$state_ID,$doctor_department,$doctor_title,$doctor_schedule,$hospital_ID){
	echo $doctor_department;
	echo $doctor_title;
	echo $doctor_schedule;
	//Insert into doctor
	$result = $mysqli -> query("UPDATE doctor SET doctor_department= '$doctor_department', doctor_title='$doctor_title', doctor_schedule= '$doctor_schedule' WHERE state_ID ='$state_ID'"); 
	//echo ("INSERT INTO doctor VALUES('$state_ID','$doctor_department','$doctor_title','$doctor_schedule')"); 

	/*if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "insert_doc_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}*/

	//Insert into works as
	$role ="normal";
	$result = $mysqli -> query("INSERT INTO works_as_doctor VALUES('$state_ID','$hospital_ID','$role')"); 

	/*if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "insert_works_as_doc_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}*/
}

function sendDoctorInformation($mysqli,$stateID){

	$result = $mysqli -> query("SELECT first_name,middle_name,last_name,doctor_department,doctor_title,doctor_schedule FROM doctor NATURAL JOIN user WHERE state_ID = '$stateID'"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {

				$json[]= array(
					'first_name' => $row["first_name"],
					'middle_name' => $row["middle_name"],
					'last_name' => $row["last_name"],
					'doctor_department' => $row["doctor_department"],
					'doctor_title' => $row["doctor_title"],
					'doctor_schedule' => $row["doctor_schedule"]		  
				 );		
			}
			
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}else {
			echo "no_doctor";
		}	
		
	}else {		
		
		echo NULL;
	}
}

function sendHospitalInformation($mysqli,$stateID){

	$result = $mysqli -> query("SELECT hospital_ID,hospital_name,hospital_capacity,hospital_telephone,hospital_address FROM works_as_doctor NATURAL JOIN hospital WHERE state_ID = '$stateID'"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				
				$json[]= array(
					'hospital_ID' => $row["hospital_ID"],
					'hospital_name' => $row["hospital_name"],
					'hospital_capacity' => $row["hospital_capacity"],
					'hospital_telephone' => $row["hospital_telephone"],
					'hospital_address' => $row["hospital_address"]
						  
				 );		
			}			
			$jsonstring = json_encode($json[0]);
			echo $jsonstring;
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}
}

function sendDrugs($mysqli){

	$result = $mysqli -> query("SELECT drug_ID,drug_name FROM drug"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				
				$json[]= array(
					'drug_ID' => $row["drug_ID"],
					'drug_name' => $row["drug_name"]
											  
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

function sendDepartments($mysqli,$hospitalID){

	$result = $mysqli -> query("SELECT hospital_department FROM hospital_department WHERE hospital_ID = '$hospitalID'"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {
				
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

function addDepartment($mysqli,$hospital_ID,$hospital_department){
	
	$result = $mysqli -> query("INSERT INTO hospital_department VALUES('$hospital_ID','$hospital_department')"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "insert_department_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}
}

function removeDepartment($mysqli,$hospital_ID,$hospital_department){
	
	$result = $mysqli -> query("DELETE FROM hospital_department where hospital_ID = '$hospital_ID' AND hospital_department = '$hospital_department'"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			
			echo "insert_department_success";
			
		}else {
			error_log ("no row");
		}	
		
	}else {		
		
		echo NULL;
	}

}

function sendDoctors($mysqli,$hospital_ID){

	$result = $mysqli -> query("SELECT state_ID,first_name,last_name,phone FROM works_as_doctor NATURAL JOIN user WHERE hospital_ID = '$hospital_ID'"); 
	if($result){
		$num_rows = $result->num_rows;
		
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {

				$json[]= array(
					'state_ID' => $row["state_ID"],
					'first_name' => $row["first_name"],
					'last_name' => $row["last_name"],
					'phone' => $row["phone"]
				 );		
			}
			
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}else {
			echo "no_doctor";
		}	
		
	}else {		
		
		echo NULL;
	}

	

}

	
	
	

function login($mysqli){
	if( $_POST["username"] || $_POST["password"] ) {
		
		$username = $_POST["username"];
		$password = $_POST["password"];

		$result = $mysqli -> query("SELECT * FROM student WHERE sname = '$username' AND sid = '$password'"); 
		if($result){			
			$row = $result->fetch_assoc();
			if($row){			
				$sid = $row["sid"];
				$result->free();
				return $sid;								
			}
		}else {
			$result->free();
			return null;
		}				
	}	 	 
}


function sendAppliedCompanies($mysqli, $sid) {
	$result = $mysqli -> query("SELECT cid,quota FROM apply NATURAL JOIN company WHERE sid = '$sid'"); 
	if($result){
		$num_rows = $result->num_rows;
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {

				$json[]= array(
					'cid' => $row["cid"],
					'quota' => $row["quota"]			  
				 );		
			}
			
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}else {
			echo "no_comp";
		}	
		
	}else {		
		
		echo NULL;
	}	
}

function getApplicableCompanies($mysqli,$sid){
	$result = $mysqli -> query("SELECT company.cid, cname, quota-IFNULL(c_sid,0) as remainingQuota from company left join (select cid,count(sid) as c_sid from apply group by cid) as T on T.cid = company.cid where quota-IFNULL(c_sid,0)>0 and company.cid not in (select cid from apply where sid='$sid')");
	if($result){
		$num_rows = $result->num_rows;
		if($num_rows > 0){
			while ($row = $result->fetch_assoc()) {

				$json[]= array(
					'cid' => $row["cid"],
					'cname' => $row["cname"],
					'remainingQuota' => $row["remainingQuota"]			  
				 );		
			}
			
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}else {
			echo "no_comp";
		}	
		
	}else {		
		
		echo NULL;
	}	

}

function nonAppliedCompanies($mysqli,$sid){
	$result = $mysqli -> query("SELECT * FROM company WHERE cid NOT IN (SELECT cid FROM apply WHERE sid = '$sid')"); 
	if($result){
		
		while ($row = $result->fetch_assoc()) {
			$json[]= array(
				'cid' => $row["cid"]			  
			 );
		}
		$jsonstring = json_encode($json);
 		echo $jsonstring;
		
	}else {
		echo NULL;
	}	
}

function cancelApplication($mysqli,$sid,$cid){
	
	$mysqli -> query("DELETE from apply where sid = '$sid' AND cid = '$cid'");
	
	sendAppliedCompanies($mysqli,$sid) ;
		
}

function applyCompany($mysqli,$sid,$cid){
	$result =$mysqli -> query("INSERT INTO apply VALUES ('$sid','$cid')");
	if($result){
		echo "apply_suc";
	}
}

exit();
?>