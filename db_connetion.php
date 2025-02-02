<?php
// db_connection.php
$db = new mysqli("localhost", "root", "", "monitoring");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
