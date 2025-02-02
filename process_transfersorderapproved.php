<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('include/connect.php');

    // Ensure the database connection works
    if (!$db) {
        die('Database connection failed: ' . mysqli_connect_error());
    }

    // Get the POST data
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $project_name = mysqli_real_escape_string($db, $_POST['project_name']);
    $po_number = mysqli_real_escape_string($db, $_POST['po_number']);
    $dr_number = mysqli_real_escape_string($db, $_POST['dr_number']);
    $material_name = mysqli_real_escape_string($db, $_POST['material_name']);
    $quantity = mysqli_real_escape_string($db, $_POST['quantity']);
    $unit = mysqli_real_escape_string($db, $_POST['unit']);
    $price_per_unit = mysqli_real_escape_string($db, $_POST['price_per_unit']);
    $total_payment = mysqli_real_escape_string($db, $_POST['total_payment']);
    $supplier = mysqli_real_escape_string($db, $_POST['supplier']);
    $order_date = mysqli_real_escape_string($db, $_POST['order_date']);
    $approved_date = mysqli_real_escape_string($db, $_POST['approved_date']);
    $comments = mysqli_real_escape_string($db, $_POST['comments']);
    $status = mysqli_real_escape_string($db, $_POST['status']); // Status from request

    // Begin transaction to ensure both operations are successful
    mysqli_begin_transaction($db);

    try {
        // Insert data into materials_orders table
        $insert_query = "INSERT INTO materials_orders (
            project_name, po_number, dr_number, material_name, quantity, unit, 
            price_per_unit, total_payment, supplier, order_date, approved_date, comments, status
        ) VALUES (
            '$project_name', '$po_number', '$dr_number', '$material_name', '$quantity', '$unit',
            '$price_per_unit', '$total_payment', '$supplier', '$order_date', '$approved_date', '$comments', '$status'
        )";

        if (!mysqli_query($db, $insert_query)) {
            throw new Exception(mysqli_error($db));
        }

        // Update status in request_materials table
        $update_query = "UPDATE request_materials SET status = 'ordered' WHERE id = '$id'";

        if (!mysqli_query($db, $update_query)) {
            throw new Exception(mysqli_error($db));
        }

        // Commit the transaction
        mysqli_commit($db);

        // Refresh the page automatically after success
        echo '<script>window.location.href = "approved_materials.php";</script>';
    } catch (Exception $e) {
        // Rollback transaction on failure
        mysqli_rollback($db);

        // No message is shown to the user
    }
}
?>
