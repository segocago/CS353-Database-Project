<?php
	include('session.php');

	$mysid = $user_check;
	
	//error texts
	$errorCurPharmacists = "";
	$errorRemPharmacist = "";
	$errorAddPharmacist = "";
	$errorCurDrugsPhar = "";
	$errorAddNumDrugsPhar = "";
	$errorRemNumDrugsPhar = "";
	$errorcurDrugAmountPhar = "";
	$errorNewDrugIDPhar = "";
	$errorRemDrugIDPhar = "";
	$errorPharNewName = "";
	$errorPharNewPhone = "";
	$errorPharNewAddress = "";
	$errorCurDrugsSys = "";
	$errorNewDrugNameSys = "";
	$errorCurVaccsSys = "";
	$errorNewVaccNameSys = "";
	$errorCurAltDrugsSys = "";
	$errorNewAltDrugSys  = "";

	//Get my pharmacy's ID
  $sqlMyPharmacyID = "SELECT pharmacy_ID FROM works_as_pharmacist WHERE state_ID = '$mysid'";
	$myPharmacyIDQ = mysqli_query($db,$sqlMyPharmacyID);
  $rowPharID = mysqli_fetch_array($myPharmacyIDQ);
	$myPharmacyID = $rowPharID['pharmacy_ID'];

	//-------------Functions----------------------
  //Remove a working pharmacist from my pharmacy
  function removePharmacist($db, $remPharmacistID, $errorRemPharmacist){
      $sqlRemPharmacist = "DELETE FROM works_as_pharmacist WHERE state_ID = '$remPharmacistID'";
			if (mysqli_query($db,$sqlRemPharmacist) == TRUE) {
				$errorRemPharmacist = "You have removed a pharmacist with $remPharmacistID from your pharmacy.";
				echo  "<script type='text/javascript'>alert('$errorRemPharmacist');</script>";
			} else {
				$errorRemPharmacist = "Error: "  . $db->error;
				echo  "<script type='text/javascript'>alert('$errorRemPharmacist');</script>";
			}
	}
	if(isset($_POST['removeIDButton'])) {
		$remPharmacistID = $_POST["removeID"];
		removePharmacist($db, $remPharmacistID, $errorRemPharmacist);
  }

  //Add a new pharmacist to my pharmacy
  function addNewPharmacist($db, $newPharmacistID, $myPharmacyID, $errorAddPharmacist){
			$sqlAddPharmacist = "INSERT INTO works_as_pharmacist VALUES ('$newPharmacistID', '$myPharmacyID')";
			if (mysqli_query($db,$sqlAddPharmacist) == TRUE) {
				$errorAddPharmacist = "You have added a pharmacist with $newPharmacistID to your pharmacy.";
				echo  "<script type='text/javascript'>alert('$errorAddPharmacist');</script>";
			} else  {
				$errorAddPharmacist = "Error: " . $db->error;
				echo  "<script type='text/javascript'>alert('$errorAddPharmacist');</script>";
			}
  }
  if(isset($_POST['addNewPharmacist'])){
		$newPharmacistID = $_POST["newPharmacistID"];
		addNewPharmacist($db, $newPharmacistID, $myPharmacyID, $errorAddPharmacist);
	}

  //Add number of stored drugs to my pharmacy's stock
  function addNumDrugsPhar($db, $addNumDrugsPharID, $addNumDrugsPharAmount, $myPharmacyID, $errorAddNumDrugsPhar){
    $sqlAddNumDrugsPhar = "UPDATE stores
                          SET number_in_stock = number_in_stock + '$addNumDrugsPharAmount'
													WHERE  stores.pharmacy_ID = '$myPharmacyID' and stores.drug_ID = '$addNumDrugsPharID'";
		mysqli_query($db,$sqlAddNumDrugsPhar);
		if (mysqli_affected_rows($db) > 0) {
			$errorAddNumDrugsPhar = "You have added $addNumDrugsPharAmount drugs with $addNumDrugsPharID to your pharmacy's stock.";
			echo  "<script type='text/javascript'>alert('$errorAddNumDrugsPhar');</script>";
		} else {
			$errorAddNumDrugsPhar = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorAddNumDrugsPhar');</script>";
		}
	}
	if(isset($_POST['addNumDrugBut'])) {
		$addNumDrugsPharID = $_POST["addNumDrugID"];
    $addNumDrugsPharAmount = $_POST["addNumDrugAmount"];
		addNumDrugsPhar($db, $addNumDrugsPharID, $addNumDrugsPharAmount, $myPharmacyID, $errorAddNumDrugsPhar);
  }

  //Remove number of stored drugs from my pharmacy' stock
  function remNumDrugsPhar($db, $remNumDrugsPharID, $remNumDrugsPharAmount, $myPharmacyID, $errorRemNumDrugsPhar){
    $sqlremNumDrugsPhar = "UPDATE stores
                          SET number_in_stock = number_in_stock - '$remNumDrugsPharAmount'
													WHERE  stores.pharmacy_ID = '$myPharmacyID' and stores.drug_ID = '$remNumDrugsPharID'";
		mysqli_query($db,$sqlremNumDrugsPhar);								
		if (mysqli_affected_rows($db) > 0) {
			$errorRemNumDrugsPhar = "You have removed $remNumDrugsPharAmount drugs with $remNumDrugsPharID from your pharmacy's stock.";
			echo  "<script type='text/javascript'>alert('$errorRemNumDrugsPhar');</script>";
		} else {
			$errorRemNumDrugsPhar = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorRemNumDrugsPhar');</script>";
		}
	}
	if(isset($_POST['remNumDrugBut'])) {
		$remNumDrugsPharID = $_POST["remNumDrugID"];
    $remNumDrugsPharAmount = $_POST["remNumDrugAmount"];
		remNumDrugsPhar($db, $remNumDrugsPharID, $remNumDrugsPharAmount, $myPharmacyID, $errorRemNumDrugsPhar);
	}
	
	//Search the drugs' amount between 2 given numbers
	$resultSearchDrugFromPhar = "";//search array
	$output = "";
  function searchDrugFromPhar($db, $curDrugAmountPharLow, $curDrugAmountPharUpp, $myPharmacyID, $resultSearchDrugFromPhar, $errorcurDrugAmountPhar){
  	$sqlSearchDrugFromPhar = "SELECT drug_ID, number_in_stock FROM stores WHERE number_in_stock BETWEEN '$curDrugAmountPharLow' AND '$curDrugAmountPharUpp'";
		$resultSearchDrugFromPhar = mysqli_query($db,$sqlSearchDrugFromPhar);
		$numSearchDrugFromPhar = mysqli_num_rows($resultSearchDrugFromPhar);
		if($numSearchDrugFromPhar == 0){
			$errorcurDrugAmountPhar = "You don't have any working pharmacists.";
			echo  "<script type='text/javascript'>alert('$errorcurDrugAmountPhar');</script>";
		}
		return $resultSearchDrugFromPhar;
	}
	if(isset($_POST['curDrugAmountPharBut'])){
		$curDrugAmountPharLow = $_POST["curDrugAmountPharLow"];
		$curDrugAmountPharUpp = $_POST["curDrugAmountPharUpp"];
		$resultSearchDrugFromPhar = searchDrugFromPhar($db, $curDrugAmountPharLow, $curDrugAmountPharUpp, $myPharmacyID, $resultSearchDrugFromPhar, $errorcurDrugAmountPhar);
		while($row = mysqli_fetch_array($resultSearchDrugFromPhar)){
			$drug_ID = $row['drug_ID'];
			$number_in_stock = $row['number_in_stock'];
			$output .= '<tr>';
			$output .= '<td>'.$drug_ID.'</td>';
			$output .= '<td>'.$number_in_stock.'</td>';
			$output .= '</tr>';
		}
	}

  //Add a new drug to my pharmacy's stock
  function addNewDrugToPhar($db, $myPharmacyID, $newDrugIDPhar, $newDrugAmountPhar, $errorNewDrugIDPhar){
  	$sqlNewDrugIDPhar = "INSERT INTO stores VALUES ('$myPharmacyID', '$newDrugIDPhar', '$newDrugAmountPhar')";
		if (mysqli_query($db,$sqlNewDrugIDPhar) == TRUE) {
			$errorNewDrugIDPhar = "You have added $newDrugAmountPhar new drugs with $newDrugIDPhar to your pharmacy's stock.";
			echo  "<script type='text/javascript'>alert('$errorNewDrugIDPhar');</script>";
		} else {
			$errorNewDrugIDPhar = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorNewDrugIDPhar');</script>";
		}
  }
  if(isset($_POST['addNewDrugPharBut'])){
		$newDrugIDPhar = $_POST["newDrugIDPhar"];
   	$newDrugAmountPhar = $_POST["newDrugAmountPhar"];
		addNewDrugToPhar($db, $myPharmacyID, $newDrugIDPhar, $newDrugAmountPhar, $errorNewDrugIDPhar);
	}
	
	//Delete a new drug from my pharmacy's stock
  function remDrugFromPhar($db, $myPharmacyID, $remDrugIDPhar, $errorRemDrugIDPhar){
  	$sqlremDrugIDPhar = "DELETE FROM stores WHERE stores.pharmacy_ID = '$myPharmacyID' and stores.drug_ID = '$remDrugIDPhar'";
		if (mysqli_query($db,$sqlremDrugIDPhar) == TRUE) {
			$errorRemDrugIDPhar = "You have removed a drug with $remDrugIDPhar ID from your pharmacy's stock.";
			echo  "<script type='text/javascript'>alert('$errorRemDrugIDPhar');</script>";
		} else {
			$errorRemDrugIDPhar = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorRemDrugIDPhar');</script>";
		}
  }
  if(isset($_POST['remDrugPharBut'])){
		$remDrugIDPhar = $_POST["remDrugIDPhar"];
		remDrugFromPhar($db, $myPharmacyID, $remDrugIDPhar, $errorRemDrugIDPhar);
  }

  //Update your pharmacy's name
  function updatePharName($db, $pharNewName, $myPharmacyID, $errorPharNewName){
    $sqlPharNewName = "UPDATE pharmacy
                      SET pharmacy.pharmacy_name = '$pharNewName'
											WHERE  pharmacy.pharmacy_ID = $myPharmacyID";
		mysqli_query($db,$sqlPharNewName);
		if (mysqli_affected_rows($db) > 0) {
			$errorPharNewName = "You have updated your pharmacy's name to $pharNewName.";
			echo  "<script type='text/javascript'>alert('$errorPharNewName');</script>";
		} else {
			$errorPharNewName = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorPharNewName');</script>";
		}
  }
  if(isset($_POST['pharNewNameBut'])){
		$pharNewName = $_POST["pharNewName"];
		updatePharName($db, $pharNewName, $myPharmacyID, $errorPharNewName);
  }

  //Update your pharmacy's phone
  function updatePharPhone($db, $pharNewPhone, $myPharmacyID, $errorPharNewPhone){
    $sqlpharNewPhone = "UPDATE pharmacy
                        SET pharmacy.pharmacy_phone = '$pharNewPhone'
												WHERE  pharmacy.pharmacy_ID = '$myPharmacyID'";
		mysqli_query($db,$sqlpharNewPhone);
		if (mysqli_affected_rows($db) > 0) {
			$errorPharNewPhone = "You have updated your pharmacy's phone to $pharNewPhone.";
			echo  "<script type='text/javascript'>alert('$errorPharNewPhone');</script>";
		} else {
			$errorPharNewPhone = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorPharNewPhone');</script>";
		}
  }
  if(isset($_POST['pharNewPhoneBut'])){
		$pharNewPhone = $_POST["pharNewPhone"];
		updatePharPhone($db, $pharNewPhone, $myPharmacyID, $errorPharNewPhone);
  }

  //Update your pharmacy's address
  function updatePharAddress($db, $pharNewAddress, $myPharmacyID, $errorPharNewAddress){
    $sqlpharNewAddress = "UPDATE pharmacy
                          SET pharmacy.pharmacy_address = '$pharNewAddress'
                          WHERE  pharmacy.pharmacy_ID = '$myPharmacyID'";
		mysqli_query($db,$sqlpharNewAddress);
		if (mysqli_affected_rows($db) > 0) {
			$errorPharNewAddress = "You have updated your pharmacy's address to $pharNewAddress.";
			echo  "<script type='text/javascript'>alert('$errorPharNewAddress');</script>";
		} else {
			$errorPharNewAddress = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorPharNewAddress');</script>";
		}
  }
  if(isset($_POST['pharNewAddressBut'])){
		$pharNewAddress = $_POST["pharNewAddress"];
		updatePharAddress($db, $pharNewAddress, $myPharmacyID, $errorPharNewAddress);
	}

	//Add a new drug to the system
  function addNewDrugToSys($db, $newDrugNameSys, $errorNewDrugNameSys){
		$sqlnewDrugNameSys = "INSERT INTO drug VALUES (NULL, '$newDrugNameSys')";
		if (mysqli_query($db,$sqlnewDrugNameSys) == TRUE) {
			$errorNewDrugNameSys = "You have added a new drug named $newDrugNameSys to the system.";
			echo  "<script type='text/javascript'>alert('$errorNewDrugNameSys');</script>";
		} else {
			$errorNewDrugNameSys = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorNewDrugNameSys');</script>";
		}
	}
	if(isset($_POST['newDrugNameSysBut'])){
		$newDrugNameSys = $_POST["newDrugNameSys"];
		addNewDrugToSys($db, $newDrugNameSys, $errorNewDrugNameSys);
	}
	
	//Add a new vaccine to the system
  function addNewVaccToSys($db, $newVaccNameSys, $errorNewVaccNameSys){
		$sqlNewVaccNameSys = "INSERT INTO vaccine VALUES (NULL, '$newVaccNameSys')";
		if (mysqli_query($db,$sqlNewVaccNameSys) == TRUE) {
			$errorNewVaccNameSys = "You have added a new vaccine named $newVaccNameSys to the system.";
			echo  "<script type='text/javascript'>alert('$errorNewVaccNameSys');</script>";
		} else {
			$errorNewVaccNameSys = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorNewVaccNameSys');</script>";
		}
	}
	if(isset($_POST['newDrugVaccSysBut'])){
		$newVaccNameSys = $_POST["newVaccNameSys"];
		addNewVaccToSys($db, $newVaccNameSys, $errorNewVaccNameSys);
	}
	
	//Add a new alternative to a drug to the system
  function addNewAltDrugToSys($db, $newAltForDrugIDSys, $newAltDrugIDSys, $errorNewAltDrugSys){
		$sqlNewAltDrugSys = "INSERT INTO alternative_to VALUES ('$newAltForDrugIDSys', '$newAltDrugIDSys')";
		if (mysqli_query($db,$sqlNewAltDrugSys) == TRUE) {
			$errorNewAltDrugSys = "You have added a new alternative to the drug named $newAltForDrugIDSys to system.";
			echo  "<script type='text/javascript'>alert('$errorNewAltDrugSys');</script>";
		} else {
			$errorNewAltDrugSys = "Error: " . $db->error;
			echo  "<script type='text/javascript'>alert('$errorNewAltDrugSys');</script>";
		}
	}
	if(isset($_POST['newAltDrugSysBut'])){
		$newAltForDrugIDSys = $_POST["newAltForDrugIDSys"];
		$newAltDrugIDSys = $_POST["newAltDrugIDSys"];
		addNewAltDrugToSys($db, $newAltForDrugIDSys, $newAltDrugIDSys, $errorNewAltDrugSys);
	}

	//-------------Information----------------------
	//Your pharmacy's information
	$sqlMyPharmacyInfo = "SELECT pharmacy_ID, pharmacy_name, pharmacy_address, pharmacy_phone FROM pharmacy WHERE pharmacy_ID = '$myPharmacyID'";
	$myPharmacyInfoQ = mysqli_query($db,$sqlMyPharmacyInfo);
	$rowPharInfo = mysqli_fetch_array($myPharmacyInfoQ);

	//Currently working pharmacists in my pharmacy
  $sqlCurPharmacists = "SELECT user.state_id, user.first_name, user.middle_name, user.last_name, user.sex, user.phone
                        FROM user
                        WHERE user.state_id in (SELECT state_id
                                                FROM works_as_pharmacist
                                                WHERE works_as_pharmacist.pharmacy_ID = '$myPharmacyID') ;";
	$resultCurPharmacists = mysqli_query($db,$sqlCurPharmacists);
	$numCurPharmacists = mysqli_num_rows($resultCurPharmacists);
	if($numCurPharmacists == 0){
		$errorCurPharmacists = "You don't have any working pharmacists.";
	}
	
	//Current drugs in the pharmacy's stock
  $sqlCurDrugsPhar = "SELECT drug_ID, drug_name, number_in_stock
                      FROM  pharmacy NATURAL JOIN stores NATURAL JOIN drug
                      WHERE  pharmacy.pharmacy_ID = '$myPharmacyID'";
  $resultCurDrugsPhar = mysqli_query($db,$sqlCurDrugsPhar);
	$numCurDrugsPhar = mysqli_num_rows($resultCurDrugsPhar);
	if($numCurDrugsPhar == 0){
		$errorCurDurgsPhar = "You don't have any drugs in this pharmacy.";
	}
	
	//Current drugs in the system
  $sqlCurDrugsSys = "SELECT drug.drug_ID, drug.drug_name FROM drug";
  $resultCurDrugsSys = mysqli_query($db,$sqlCurDrugsSys);
	$numCurDrugsPhar = mysqli_num_rows($resultCurDrugsSys);
	if($numCurDrugsPhar == 0){
		$errorCurDrugsSys = "There is no drugs in the system.";
	}
	
	//Current vaccines in the system
  $sqlCurVaccsSys = "SELECT vaccine.vaccine_ID, vaccine.vaccine_name FROM vaccine";
  $resultCurVaccsSys = mysqli_query($db,$sqlCurVaccsSys);
	$numCurVaccsPhar = mysqli_num_rows($resultCurVaccsSys);
	if($numCurVaccsPhar == 0){
		$errorCurVaccsSys = "There is no vaccines in the system.";
	}

	//Current drugs and their alternatives in the system
  $sqlCurAltDrugsSys = "SELECT alternative_to.drug_ID, alternative_to.alternative_drug_ID FROM alternative_to";
  $resultCurAltDrugsSys = mysqli_query($db,$sqlCurAltDrugsSys);
	$numCurVaccsPhar = mysqli_num_rows($resultCurAltDrugsSys);
	if($numCurVaccsPhar == 0){
		$errorCurAltDrugsSys = "There is no vaccines in the system.";
	}
?>

<!DOCTYPE html>
<html lang="en">

<html>

<head>
	<title>Pharmacist's Page</title>

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
	<link rel="stylesheet" href="currentPharmacistTableCSS.css">
</head>

<body>
	<div align = "center">
		<div style = "margin:10px">
			<div style = "font-size:24px; padding:3px; ">
				<b>Pharmacist's Page</b>
				<a href = "logout.php" style = "padding:3px; background-color:#D3D3D3; color:#000000; border: solid 1px #333333; margin-left:450px">Log Out</a>
			</div>
		
			<!--/Pharmacy Information of My Pharmacy-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Your Pharmacy's Information</b></div>
				<div style = "margin:20px">  
						<label>Your Pharmacisy's ID : <?php echo $myPharmacyID; ?></label><br><br/>
            <label>Your Pharmacisy's Name : <?php echo $rowPharInfo['pharmacy_name']; ?></label><br><br/>
            <label>Your Pharmacisy's Address : <?php echo $rowPharInfo['pharmacy_address']; ?></label><br><br/>
            <label>Your Pharmacisy's Phone : <?php echo $rowPharInfo['pharmacy_phone']; ?></label><br><br/>
				</div>
			</div>	
			
			<!--/Manage Pharmacists in My Pharmacy-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Manage Pharmacists in this Pharmacy</b></div>
				<div style = "margin:20px">
					<table id="dtPharmacistsInPharmacy" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
					  <thead>
						<tr>
						  <th>First name</th>
						  <th>Last name</th>
						  <th>Phone Number</th>
							<th>Sex</th>
						  <th>Manage</th>
						</tr>
					  </thead>
					  <tbody>
						<?php while($rowCurPharmacists = mysqli_fetch_array($resultCurPharmacists)) : ?>
						<tr>
							<td><?php echo $rowCurPharmacists['first_name']." ". $rowCurPharmacists['middle_name']; ?></td>
							<td><?php echo $rowCurPharmacists['last_name']; ?></td>
							<td><?php echo $rowCurPharmacists['phone']; ?></td>
							<td><?php echo $rowCurPharmacists['sex']; ?></td>
							<td>
								<form action="pharmacist.php" method="POST">
									<input type= "hidden" name = "removeID" value = "<?php echo $rowCurPharmacists['state_id']; ?>" />
									<input type= "submit" name = "removeIDButton" value = "Remove from the Pharmacy" style="margin:0px auto; display:block;"/>
								</form>
							</td>
						</tr>
						<?php endwhile; ?>
					  </tbody>
					</table>

					<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $errorRemPharmacist; ?></div>

          <div style = "width:760px; border: solid 1px #333333; margin-top:0px" align = "left">
					<div style = "margin:20px">
					<form action = "pharmacist.php" method = "POST">
						<label>New Pharmacist's State ID : </label><input type = "text" name = "newPharmacistID" class = "box" style = "margin-left:5px"/>
						<input type = "submit" value = " Add New Pharmacist " name = "addNewPharmacist" style = "margin-left:10px"/>
					</form>
					</div>
					</div>
				</div>
			</div>	
			
			<!--/Manage Pharmacy's Drugs-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Manage Pharmacy's Drugs</b></div>
				<div style = "margin:20px">
					<table id="dtManagePharmacyDrugs" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
					  <thead>
						<tr>
							<th>Drug-ID</th>
							<th>Drug-name</th>
							<th>Number of Pieces</th>
							<th>Manage Drugs</th>
						</tr>
					  </thead>
					  <tbody>
            <?php while($rowCurDrugsPhar = mysqli_fetch_array($resultCurDrugsPhar)) : ?>
						<tr>
							<td><?php echo $rowCurDrugsPhar['drug_ID']; ?></td>
							<td><?php echo $rowCurDrugsPhar['drug_name']; ?></td>
							<td><?php echo $rowCurDrugsPhar['number_in_stock']; ?></td>
							<td>
								<form action="pharmacist.php" method="POST">
                  <input type= "text" name = "addNumDrugAmount"?>
									<input type= "hidden" name = "addNumDrugID" value = "<?php echo $rowCurDrugsPhar['drug_ID']; ?>" />
									<input type= "submit" name = "addNumDrugBut" value = "+" />
                  <input type= "text" name = "remNumDrugAmount"/>
                  <input type= "hidden" name = "remNumDrugID" value = "<?php echo $rowCurDrugsPhar['drug_ID']; ?>" />
									<input type= "submit" name = "remNumDrugBut" value = "-" />
								</form>
							</td>
						</tr>
						<?php endwhile; ?>
					  </tbody>
					</table>

					<div style = "font-size:11px; color:#cc0000; margin-top:10px; margin-bottom:10px"><?php echo $errorCurDrugsPhar; ?></div>

					<div style = "width:760px; border: solid 1px #333333; margin-top:0px" align = "left">
					<div style = "margin:20px">
					<form action = "pharmacist.php" method = "POST">
						<label style = "font-weight:bold;" >Search between amount of Drugs from the Pharmacy's Stock</label><br><br/>
						<label>Drug Amount Lower : </label><input type = "text" name = "curDrugAmountPharLow" class = "box" style = "margin-left:5px"/>
						<label>Drug Amount Upper : </label><input type = "text" name = "curDrugAmountPharUpp" class = "box" style = "margin-left:5px" />
						<input type = "submit" name = "curDrugAmountPharBut" value = " Search Between Amounts for Drug IDs " style = "margin:0px auto; display:block; margin-top:10px;"/>
						<div style = "width:700px; border: solid 1px #333333; margin-top:5px; margin-vottom:5px;" align = "center"></div>
					</form>
					<table id="dtSearchPharmacyDrugs" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
						<thead>
						<tr>
							<th>Drug-ID</th>
							<th>Number of Pieces</th>
						</tr>
						</thead>
						<tbody>
						<?php echo $output; ?>
						</tbody>
					</table>
					</div>
					</div>
					
					<div style = "width:760px; border: solid 1px #333333; margin-top:10px" align = "left">
					<div style = "margin:20px">
					<form action = "" method = "POST">
						<label style = "font-weight:bold;" >Add a new Drug to the Pharmacy's Stock</label><br><br/>
						<label>Drug-ID : </label><input type = "text" name = "newDrugIDPhar" class = "box" style = "margin-left:5px"/>
						<label>Amount : </label><input type = "text" name = "newDrugAmountPhar" class = "box" style = "margin-left:5px" />
						<input type = "submit" name = "addNewDrugPharBut" value = " Add New Drug " style = "margin-left:10px"/>
					</form>
					</div>
					</div>

					<div style = "width:760px; border: solid 1px #333333; margin-top:10px" align = "left">
					<div style = "margin:20px">
					<form action = "" method = "POST">
						<label style = "font-weight:bold;" >Remove a Drug from the Pharmacy's Stock</label><br><br/>
						<label>Drug-ID : </label><input type = "text" name = "remDrugIDPhar" class = "box" style = "margin-left:5px"/>
						<input type = "submit" name = "remDrugPharBut" value = " Remove a Drug " style = "margin-left:10px"/>
					</form>
					</div>
					</div>
				</div>
			</div>
			
			<!--/Edit Your Pharmacy-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Edit Your Pharmacy</b></div>
				<div style = "margin:20px">
					<form action = "" method = "POST">
						<label>Your Pharmacy's New Name : </label><input type = "text" name = "pharNewName" class = "box" style = "margin-left:30px"/><input type = "submit" name = "pharNewNameBut" value = " Change " style = "margin-left:10px"/>
					</form>
					<form action = "" method = "POST">
						<label>Your Pharmacy's New Address : </label><input type = "text" name = "pharNewAddress" class = "box" style = "margin-left:14.5px"/><input type = "submit" name = "pharNewAddressBut" value = " Change " style = "margin-left:10px"/>
					</form>
					<form action = "" method = "POST">
						<label>Your Pharmacy's New Phone : </label><input type = "text" name = "pharNewPhone" class = "box" style = "margin-left:27.5px"/><input type = "submit" name = "pharNewPhoneBut" value = " Change " style = "margin-left:10px"/>
					</form>
				</div>
			</div>
			
			<!--/Manage Drugs in the System-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Manage Drugs in the System</b></div>
				<div style = "margin:20px">
					<table id="dtDrugsInSystem" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
					  <thead>
						<tr>
							<th>Drug-ID</th>
							<th>Drug-name</th>
						</tr>
					  </thead>
					  <tbody>
						<?php while($rowCurDrugsSys = mysqli_fetch_array($resultCurDrugsSys)) : ?>
						<tr>
							<td><?php echo $rowCurDrugsSys['drug_ID']; ?></td>
							<td><?php echo $rowCurDrugsSys['drug_name']; ?></td>
						</tr>
						<?php endwhile; ?>
					  </tbody>
					</table>

					<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $errorCurDrugsSys; ?></div>

					<div style = "width:760px; border: solid 1px #333333; margin-top:20px" align = "left">
					<div style = "margin:20px">
						<form action = "" method = "POST">
							<label style = "font-weight:bold;" >Add a new Drug to the System</label><br><br/>
							<label>New Drug Name : </label><input type = "text" name = "newDrugNameSys" class = "box" style = "margin-left:23px" />
							<input type = "submit" name = "newDrugNameSysBut" value = " Add a New Drug to the System " style = "margin-left:10px"/>
						</form>
					</div>
					</div>
				</div>
			</div>
			
			<!--/Manage Vaccines in the System-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Manage Vaccines in the System</b></div>
				<div style = "margin:20px">
					<table id="dtVaccinesInSystem" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
					  <thead>
						<tr>
							<th>Vaccine-ID</th>
							<th>Vaccine-name</th>
						</tr>
					  </thead>
					  <tbody>
						<?php while($rowCurVaccsSys = mysqli_fetch_array($resultCurVaccsSys)) : ?>
						<tr>
							<td><?php echo $rowCurVaccsSys['vaccine_ID']; ?></td>
							<td><?php echo $rowCurVaccsSys['vaccine_name']; ?></td>
						</tr>
						<?php endwhile; ?>
					  </tbody>
					</table>

					<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $errorCurVaccsSys; ?></div>
					
					<div style = "width:760px; border: solid 1px #333333; margin-top:20px" align = "left">
					<div style = "margin:20px">
						<form action = "" method = "POST">
						<label style = "font-weight:bold;" >Add new Vaccine to the System</label><br><br/>
							<label>New Vaccine Name : </label><input type = "text" name = "newVaccNameSys" class = "box" style = "margin-left:23px" />
							<input type = "submit" name = "newDrugVaccSysBut" value = " Add a New Vaccine to the System " style = "margin-left:10px"/>
						</form>
					<div style = "font-size:11px; color:#cc0000; margin-top:10px"></div>
					</div>
					</div>
					
				</div>
			</div>
			
			<!--/Manage Alternative Drugs in the System-->
			<div style = "width:800px; border: solid 1px #333333; margin-top:20px" align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:10px; "><b>Manage Alternative Drugs in the System</b></div>
				<div style = "margin:20px">
					<table id="dtAlternativeDrugsSystem" class="table table-striped table-bordered table-sm " cellspacing="0" width="100%">
					  <thead>
						<tr>
							<th>Drug-ID</th>
							<th>Alternative Drug-ID</th>
						</tr>
					  </thead>
					  <tbody>
						<?php while($CurAltDrugsSys = mysqli_fetch_array($resultCurAltDrugsSys)) : ?>
						<tr>
							<td><?php echo $CurAltDrugsSys['drug_ID']; ?></td>
							<td><?php echo $CurAltDrugsSys['alternative_drug_ID']; ?></td>
						</tr>
						<?php endwhile; ?>
					  </tbody>
					</table>

					<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $errorCurAltDrugsSys; ?></div>
					
					<div style = "width:760px; border: solid 1px #333333; margin-top:20px" align = "left">
					<div style = "margin:20px">
						<form action = "" method = "POST">
							<label style = "font-weight:bold;" >Add Alternative to a Drug to the System</label><br><br/>
							<label>Drug-ID : </label><input type = "text" name = "newAltForDrugIDSys" class = "box" style = "margin-left:88px"/><br><br/>
							<label>Alternative Drug-ID : </label><input type = "text" name = "newAltDrugIDSys" class = "box" style = "margin-left:10px"/>
							<input type = "submit" name = "newAltDrugSysBut" value = " Add Alternative to the Drug " style = "margin-left:10px"/>
						</form>
					</div>
					</div>
					
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
			$('#dtPharmacistsInPharmacy').DataTable({
			"scrollX": true,
			"scrollY": 200,
			});
			$('#dtManagePharmacyDrugs').DataTable({
				"scrollX": true,
				"scrollY": 200,
			});
			$('#dtSearchPharmacyDrugs').DataTable({
				"scrollX": true,
				"scrollY": 200,
			});
			$('#dtDrugsInSystem').DataTable({
				"scrollX": true,
				"scrollY": 200,
			});
			$('#dtVaccinesInSystem').DataTable({
				"scrollX": true,
				"scrollY": 200,
			});
			$('#dtAlternativeDrugsSystem').DataTable({
				"scrollX": true,
				"scrollY": 200,
			});
				$('.dataTables_length').addClass('bs-select');
		});
	</script>
</body>
</html> 