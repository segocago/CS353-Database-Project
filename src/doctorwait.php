<?php
include("session.php");

$sid = $_SESSION['userid'];

$sql = "SELECT state_ID FROM works_as_doctor WHERE state_ID = '$sid' ";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

$count = mysqli_num_rows($result);

if($count > 0) {
  header("location: doctors_profile.html");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form

    $dept = mysqli_real_escape_string($db,$_POST['dept']);
    $title = mysqli_real_escape_string($db,$_POST['doc_title']);
    $sch = mysqli_real_escape_string($db,$_POST['sch']);

    $sid = $_SESSION['userid'];

    $sql = "INSERT INTO doctor(state_ID,doctor_department,doctor_title,doctor_schedule) VALUES ('$sid', '$dept','$title','$sch')";
    $result = mysqli_query($db,$sql);

    header("location: hospitalcreationpage.php");
}
?>
<html>

<head>
    <title>Doctor Information</title>

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
            var input1 = document.forms['docinfoform']['dept'];
            var input2 = document.forms['docinfoform']['doc_title'];
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
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Doctor Information</b></div>

        <div style = "margin:30px">
            Waiting for Executive Doctor Confirmation. Please contact with your executive doctor. <br>
            If you are an executive doctor please fill the form.<br>
            <form name="docinfoform"action = "" method = "post" onsubmit="return checkForm()">
                <label>Departmant  :  </label><input type = "text" name = "dept" class = "box"/><br /><br />
                <label>Title  : </label><input type = "text" name = "doc_title" class = "box" /><br/><br />
                <label>Schedule :    </label><textarea  type = "text" name = "sch" class = "box" /></textarea><br/><br />
                <input type = "submit" value = " Submit "/><br />
            </form>
        </div>

    </div>

</div>

</body>
</html>
