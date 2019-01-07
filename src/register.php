<?php
include("config.php");
session_start();
$error = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $sid = mysqli_real_escape_string($db,$_POST['stateid']);
    $pw = mysqli_real_escape_string($db,$_POST['pw']);
    $type = mysqli_real_escape_string($db,$_POST['type']);
    $pw = md5($pw);

    $sql = "SELECT state_ID FROM user WHERE state_ID = '$sid' ";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    $count = mysqli_num_rows($result);

    // If result matched $myusername and $mypassword, table row must be 1 row

    if($count < 1) {
        $_SESSION['userid'] = $sid;

        $sql = "INSERT INTO user(state_ID,password) VALUES ('$sid', '$pw')";
        $result = mysqli_query($db,$sql);
        $_SESSION['userid'] = $sid;

        $selection = $_POST['type'];
        switch ($selection){
            case "Patient":
                header("location: informationpage.php");
                break;
            case "Doctor":
                $sql = "INSERT INTO doctor(state_ID) VALUES ('$sid')";
                $result = mysqli_query($db,$sql);
                header("location: informationpage.php");
                break;
            case "Pharmacist":
                $sql = "INSERT INTO pharmacist(state_ID) VALUES ('$sid')";
                $result = mysqli_query($db,$sql);
                header("location: informationpage.php");
                break;
        }
    }else {
        $error = "Login Error";
    }
}
?>
<html>

<head>
    <title>Register Page</title>

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
            var input1 = document.forms['registerform']['stateid'];
            var input2 = document.forms['registerform']['pw'];
            var input3 = document.forms['registerform']['cpw'];
            if(input1.value == "" || input2.value == "" || input3.value == ""){
                alert("Usarname or Password can't be blank!");
                return false;
            }else{
                if(input2.value != input3.value) {
                    alert("Pasword and Confirm password should be same!");
                    return false;
                } else {
                    return true;
                }
            }
        }
    </script>
</head>

<body bgcolor = "#FFFFFF">

<div align = "center">
    <div style = "width:400px; border: solid 1px #333333; " align = "left">
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Register</b></div>

        <div style = "margin:30px">

            <form name="registerform"action = "" method = "post" onsubmit="return checkForm()">
                <label>State ID  :         </label><input type = "text" name = "stateid" class = "box"/><br /><br />
                <label>Password  :         </label><input type = "password" name = "pw" class = "box" /><br/><br />
                <label>Confirm Password  : </label><input type = "password" name = "cpw" class = "box" /><br/><br />
                <select name="type">
                    <option value="Patient">Patient</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Pharmacist">Pharmacist</option>
                </select>
                <input type = "submit" value = " Submit "/><br />
            </form>
            <a href="index.php">Login</a>

            <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

        </div>

    </div>

</div>

</body>
</html>
