<?php
   include('config.php');
   session_start();
   
   $user_check = $_SESSION['userid'];
   
   $ses_sql = mysqli_query($db,"select state_ID from user where state_ID = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['state_ID'];
   
   if(!isset($_SESSION['userid'])){
      header("location: index.php");
   }
?>