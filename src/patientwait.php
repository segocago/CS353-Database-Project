<?php
include("session.php");

$sid = $_SESSION['userid'];

$sql = "SELECT state_ID FROM patient WHERE state_ID = '$sid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$count = mysqli_num_rows($result);

if($count > 0) {
  header("location: patient.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $a1 = mysqli_real_escape_string($db,$_POST['birth']);
    $a2 = mysqli_real_escape_string($db,$_POST['height']);
    $a3 = mysqli_real_escape_string($db,$_POST['weight']);
    $a4 = mysqli_real_escape_string($db,$_POST['type']);

    $sid = $_SESSION['userid'];

    $sql = "INSERT INTO patient(state_ID,patient_date_of_birth,patient_height,patient_weight,patient_bloodtype) VALUES ('$sid','$a1', '$a2','$a3','$a4')";
    $result = mysqli_query($db,$sql);
    
    header("location: patient.php");
}
?>
<html>

<head>
    <title>Patient Information</title>

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
            var input1 = document.forms['patientinfoform']['dept'];
            var input2 = document.forms['patientinfoform']['doc_title'];
            if(input1.value == "" || input2.value == ""){
                alert("Deparment and Title fields should be filled!");
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
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Patient Information</b></div>

        <div style = "margin:30px">
            <form name="patientinfoform"action = "" method = "post" onsubmit="return checkForm()">
                <label>Date of Birth (YYYY-MM-DD) :  </label><input type = "text" name = "birth" class = "box"/><br /><br />
                <label>Height  : </label><input type = "text" name = "height" class = "box" /><br/><br />
                <label>Weight :    </label><input  type = "text" name = "weight" class = "box" /><br/><br />
                <label>Blood Type :    </label><select name="type">
                    <option value="0+">0+</option>
                    <option value="0-">0-</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
                <input type = "submit" value = " Submit "/><br />
            </form>
        </div>

    </div>

</div>

</body>
</html>