<?php
include("session.php");
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $fname = mysqli_real_escape_string($db,$_POST['fname']);
    $mname = mysqli_real_escape_string($db,$_POST['mname']);
    $lname = mysqli_real_escape_string($db,$_POST['lname']);
    $gender= mysqli_real_escape_string($db,$_POST['gender']);
    $phone = mysqli_real_escape_string($db,$_POST['phone']);

    $sid = $_SESSION['userid'];

    $sql = "UPDATE user SET first_name = '$fname', middle_name = '$mname', last_name = '$lname', sex = '$gender', phone = '$phone' WHERE state_ID = '$sid' ";
    $result = mysqli_query($db,$sql);

    header("location: index.php");

}
?>
<html>

<head>
    <title>Informations</title>

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
</head>

<body bgcolor = "#FFFFFF">

<div align = "center">
    <div style = "width:400px; border: solid 1px #333333; " align = "left">
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Informations</b></div>

        <div style = "margin:30px">

            <form name="infoform"action = "" method = "post" onsubmit="return checkForm()">
                <label>First Name  :  </label><input type = "text" name = "fname" class = "box"/><br /><br />
                <label>Middle Name  : </label><input type = "text" name = "mname" class = "box" /><br/><br />
                <label>Last Name :    </label><input type = "text" name = "lname" class = "box" /><br/><br />
                <label>Gender :       </label><select name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select> <br>
                <label>Phone Number : </label><input type = "text" name = "phone" class = "box" /><br/><br />
                <input type = "submit" value = " Submit "/><br />
            </form>


        </div>

    </div>

</div>

</body>
</html>