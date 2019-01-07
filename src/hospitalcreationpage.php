<?php
include("session.php");
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $hname = mysqli_real_escape_string($db,$_POST['name']);
    $cap = mysqli_real_escape_string($db,$_POST['capacity']);
    $phone = mysqli_real_escape_string($db,$_POST['phone']);
    $adress= mysqli_real_escape_string($db,$_POST['adress']);
    $role = mysqli_real_escape_string($db,"executive");

    $sid = $_SESSION['userid'];

    $sql = "INSERT INTO hospital(hospital_name,hospital_capacity,hospital_telephone,hospital_address) VALUES ('$hname', '$cap','$phone','$adress')";
    $result = mysqli_query($db,$sql);

    $sql = "SELECT hospital_ID FROM hospital WHERE hospital_name = '$hname' and hospital_telephone = '$phone' ";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $hid = $row['hospital_ID'];

    $sql = "INSERT INTO works_as_doctor(state_ID,hospital_ID,role) VALUES ('$sid', '$hid','$role')";
    $result = mysqli_query($db,$sql);

    header("location: doctors_profile.html");

}
?>
<html>

<head>
    <title>Hospital Information</title>

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
            var input1 = document.forms['hosinfoform']['name'];
            var input2 = document.forms['hosinfoform']['capacity'];
            var input3 = document.forms['hosinfoform']['phone'];
            var input4 = document.forms['hosinfoform']['adress'];
            if(input1.value == "" || input2.value == "" || input3.value == "" || input4.value == ""){
                alert("Every field should be filled!");
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
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Hospital Information</b></div>

        <div style = "margin:30px">

            <form name="hosinfoform"action = "" method = "post" onsubmit="return checkForm()">
                <label>Hospital Name  :  </label><input type = "text" name = "name" class = "box"/><br /><br />
                <label>Hospital capacity  : </label><input type = "text" name = "capacity" class = "box" /><br/><br />
                <label>Hospital Phone :    </label><input type = "text" name = "phone" class = "box" /><br/><br />
                <label>Hospital adress :    </label><textarea  type = "text" name = "adress" class = "box" /></textarea><br/><br />
                <input type = "submit" value = " Submit "/><br />
            </form>
        </div>

    </div>

</div>

</body>
</html>
