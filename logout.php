<?php
include "include/connect.php";
session_start();
$name = $_SESSION['name'];
date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

  $remarks="user $name was logout"; 
  mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));
unset($_SESSION['name']);
session_destroy();
header("Location: login.php");
?>