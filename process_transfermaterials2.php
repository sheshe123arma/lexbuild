<?php
include('include/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $transferQuantity = intval($_POST['quantity']);
    $projectName = mysqli_real_escape_string($db, $_POST['project_name']); 

    // Log the incoming data
    error_log("Received ID: $id");
    error_log("Received Quantity: $transferQuantity");
    error_log("Project Name: $projectName");

    // Get the current details of the selected item from materials_orders
    $query = "SELECT * FROM material_transfers WHERE id = $id";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $currentQuantity = intval($row['quantity']);  // Get the current quantity of the material
            error_log("Current Quantity: $currentQuantity");

            // Check if the transfer quantity is greater than the available quantity
            if ($transferQuantity > $currentQuantity) {
                echo "Insufficient quantity.";
                exit;
            }

            // Subtract the transferred quantity
            $newQuantity = $currentQuantity - $transferQuantity;
            error_log("New Quantity: $newQuantity");

            // Insert into the material_transfers table
            $insertQuery = "INSERT INTO material_transfers (po_number, dr_number, material_name, quantity, unit, price_per_unit, total_payment, supplier, order_date, delivery_date, comments, status, project_name)
                            VALUES ('{$row['po_number']}', '{$row['dr_number']}', '{$row['material_name']}', $transferQuantity, '{$row['unit']}', '{$row['price_per_unit']}', '{$row['total_payment']}', '{$row['supplier']}', '{$row['order_date']}', '{$row['delivery_date']}', '{$row['comments']}', '{$row['status']}', '$projectName')";
            if (mysqli_query($db, $insertQuery)) {
                // Update the quantity in the materials_orders table
                $updateQuery = "UPDATE material_transfers SET quantity = $newQuantity WHERE id = $id";
                if (mysqli_query($db, $updateQuery)) {
                    // Log and confirm the success
                    error_log("Quantity updated successfully in materials_orders table.");
                    echo "success";
                } else {
                    error_log("Database update failed: " . mysqli_error($db));
                    echo "Database update failed: " . mysqli_error($db);
                }
            } else {
                error_log("Insert into material_transfers table failed: " . mysqli_error($db));
                echo "Insert into material_transfers table failed: " . mysqli_error($db);
            }
        } else {
            error_log("Item not found in materials_orders table.");
            echo "Item not found in materials_orders table.";
        }
    } else {
        error_log("Query failed: " . mysqli_error($db));
        echo "Query failed: " . mysqli_error($db);
    }
} else {
    echo "Invalid request.";
}
?>
