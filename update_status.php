<?php
include('include/connect.php');

// Get the data from POST request
if (isset($_POST['id']) && isset($_POST['status']) && isset($_POST['delivery_date'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $delivery_date = $_POST['delivery_date'];

    // Update the status and delivery date in the database
    $query = "UPDATE materials_orders SET status = '$status', delivery_date = '$delivery_date' WHERE id = '$id'";
    if (mysqli_query($db, $query)) {
        echo 'success';
    } else {
        echo 'failure';
    }
} else {
    echo 'failure';
}
?>
