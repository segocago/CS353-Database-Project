<?php
   include("config.php");
   session_start();
    $error = "";

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form

      $sid = mysqli_real_escape_string($db,$_POST['stateid']);
      $pw = mysqli_real_escape_string($db,$_POST['pw']);
      $pw = md5($pw);

      $sql = "SELECT state_ID FROM user WHERE state_ID = '$sid' and password = '$pw' ";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];

      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row

      if($count == 1) {
         $_SESSION['userid'] = $sid;
         $selection = $_POST['type'];
         switch ($selection){
             case "Patient":
                 header("location: patientwait.php");
                 break;
             case "Doctor":
                 header("location: doctorwait.php");
                 break;
             case "Pharmacist":
                 header("location: pharmacistwait.php");
                 break;
         }

      }else {
         $error = "Login Error";
      }
   }
?>
<html>

   <head>
      <title>Login Page</title>

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
        var input1 = document.forms['loginform']['sname'];
        var input2 = document.forms['loginform']['sid'];
          if(input1.value == "" || input2.value == ""){
            alert("Usarname or Password can't be blank!");
            return false;
          }else{
            return true;
          }
      }
      </script>
   </head>

   <body bgcolor = "#FFFFFF">

      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>

            <div style = "margin:30px">

               <form name="loginform"action = "" method = "post" onsubmit="return checkForm()">
                  <label>State ID  :</label><input type = "text" name = "stateid" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "pw" class = "box" /><br/><br />
                  <select name="type">
                      <option value="Patient">Patient</option>
                      <option value="Doctor">Doctor</option>
                      <option value="Pharmacist">Pharmacist</option>
                  </select>
                  <br>
                  <input type = "submit" value = " Submit "/><br />
               </form>
                <a href="register.php">Register</a>

               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

            </div>

         </div>

      </div>

   </body>
</html>
