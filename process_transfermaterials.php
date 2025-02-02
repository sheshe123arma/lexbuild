<?php
include('include/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $transferQuantity = intval($_POST['quantity']);
    $projectName = mysqli_real_escape_string($db, $_POST['project_name']);  // Get the project name from the form input

    // Get the current details of the selected item from materials_orders
    $query = "SELECT * FROM materials_orders WHERE id = $id";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $currentQuantity = intval($row['quantity']);
        
        if ($transferQuantity > $currentQuantity) {
            echo "Insufficient quantity.";
            exit;
        }

        // Subtract the transferred quantity
        $newQuantity = $currentQuantity - $transferQuantity;

        // Insert the details into the material_transfers table
        $insertQuery = "INSERT INTO material_transfers (po_number, dr_number, material_name, quantity, unit, price_per_unit, total_payment, supplier, order_date, delivery_date, comments, status, project_name)
                        VALUES ('{$row['po_number']}', '{$row['dr_number']}', '{$row['material_name']}', $transferQuantity, '{$row['unit']}', '{$row['price_per_unit']}', '{$row['total_payment']}', '{$row['supplier']}', '{$row['order_date']}', '{$row['delivery_date']}', '{$row['comments']}', '{$row['status']}', '$projectName')";

        if (mysqli_query($db, $insertQuery)) {
            // Update the original table with the new quantity
            $updateQuery = "UPDATE materials_orders SET quantity = $newQuantity WHERE id = $id";
            if (mysqli_query($db, $updateQuery)) {
                echo "success";
            } else {
                echo "Database update failed.";
            }
        } else {
            echo "Insert into new table failed.";
        }
    } else {
        echo "Item not found.";
    }
} else {
    echo "Invalid request.";
}
?>
