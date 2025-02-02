<?php
include('include/connect.php');

// Get the data from POST request
if (isset($_POST['id']) && isset($_POST['approved_date'])) {
    $id = $_POST['id'];
    $status = 'approved';  // Hardcoded status to 'approved'
    $approved_date = $_POST['approved_date'];

    // Update the status and delivery date in the database
    $query = "UPDATE request_materials SET status = '$status', approved_date = '$approved_date' WHERE id = '$id'";
    if (mysqli_query($db, $query)) {
        echo 'success';
    } else {
        echo 'failure';
    }
} else {
    echo 'failure';
}
?>
