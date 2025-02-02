<?php
include('include/connect.php');

// Fetch all project names
$query = "SELECT project_name FROM projects";
$result = mysqli_query($db, $query);
$projects = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }
    echo json_encode(["projects" => $projects]);
} else {
    echo json_encode(["projects" => []]);
}
?>
