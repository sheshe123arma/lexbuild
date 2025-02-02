<?php
 $db = mysqli_connect("localhost","root","","monitoring");

 if(mysqli_connect_errno())
 {
 	echo "failed to connect to MySQL:".mysqli_connect_error();
 }
?>