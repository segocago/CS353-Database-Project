<?php
include("session.php");

$sid = $_SESSION['userid'];

$sql = "SELECT state_ID FROM works_as_pharmacist WHERE state_ID = '$sid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$count = mysqli_num_rows($result);

if($count > 0) {
  header("location: pharmacist.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $a1 = mysqli_real_escape_string($db,$_POST['name']);
    $a2 = mysqli_real_escape_string($db,$_POST['address']);
    $a3 = mysqli_real_escape_string($db,$_POST['phone']);

    $sid = $_SESSION['userid'];

    $sql = "INSERT INTO pharmacy(pharmacy_name,pharmacy_address,pharmacy_phone) VALUES ('$a1', '$a2','$a3')";
    $result = mysqli_query($db,$sql);

    $sql = "SELECT pharmacy_ID FROM pharmacy WHERE pharmacy_name = '$a1' and pharmacy_phone = '$a3' ";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $pid = $row['pharmacy_ID'];

    $sql = "INSERT INTO works_as_pharmacist(state_ID,pharmacy_ID) VALUES ('$sid', '$pid')";
    $result = mysqli_query($db,$sql);

    header("location: pharmacist.php");
}
?>
<html>

<head>
    <title>Pharmacy Information</title>

    <style type = "text/css">
        body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
        }
        label {
            font-weight:bold;
            width:100px;
            font-size:14px;
        }
        .box {
            border:#666666 solid 1px;
        }
    </style>
    <script>
        function checkForm(){
            var input1 = document.forms['patientinfoform']['name'];
            var input2 = document.forms['patientinfoform']['address'];
            var input3 = document.forms['patientinfoform']['phone'];
            if(input1.value == "" || input2.value == "" || input3.value == ""){
                alert("All fields should be filled!");
                return false;
            }else{
                return true;
            }
        }
    </script>
</head>

<body bgcolor = "#FFFFFF">

<div align = "center">
    <div style = "width:400px; border: solid 1px #333333; " align = "left">
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Pharmacy Information</b></div>
        <div style = "margin:30px">
          Waiting for Pharmacist Confirmation. Please contact with your pharmacist. <br>
          If you are a new pharmacist on the system please fill the form.<br>
            <form name="patientinfoform"action = "" method = "post" onsubmit="return checkForm()">
                <label>Pharmacy Name :  </label><input type = "text" name = "name" class = "box"/><br /><br />
                <label>Pharmacy Address  : </label><input type = "text" name = "address" class = "box" /><br/><br />
                <label>Pharmacy Phone Number :    </label><input  type = "text" name = "phone" class = "box" /><br/><br />
                <input type = "submit" value = " Submit "/><br />
            </form>
        </div>

    </div>

</div>

</body>
</html>
