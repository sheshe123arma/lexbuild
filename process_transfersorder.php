<?php
include('include/connect.php');

if (isset($_POST['id'], $_POST['used_quantity'])) {
    $id = $_POST['id'];
    $usedQuantity = (int)$_POST['used_quantity'];
    $projectName = isset($_POST['project_name']) ? mysqli_real_escape_string($db, $_POST['project_name']) : '';
    $comment = isset($_POST['comment']) ? mysqli_real_escape_string($db, $_POST['comment']) : '';

    // Fetch the current material details
    $query = "SELECT quantity, material_name, po_number, dr_number, unit, price_per_unit, supplier, order_date, delivery_date, project_name 
              FROM material_transfers WHERE id = $id";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $currentQuantity = (int)$row['quantity'];
        $materialName = $row['material_name'];
        $poNumber = $row['po_number'];
        $drNumber = $row['dr_number'];
        $unit = $row['unit'];
        $pricePerUnit = $row['price_per_unit'];
        $supplier = $row['supplier'];
        $orderDate = $row['order_date'];
        $deliveryDate = $row['delivery_date'];
        $projectName = !empty($projectName) ? $projectName : $row['project_name']; // Use input or fallback to default

        if ($usedQuantity > $currentQuantity) {
            echo "Error: Not enough materials available.";
            exit;
        }

        // Deduct the quantity
        $newQuantity = $currentQuantity - $usedQuantity;
        $updateQuery = "UPDATE material_transfers SET quantity = $newQuantity WHERE id = $id";

        if (mysqli_query($db, $updateQuery)) {
            // Calculate total cost for used materials
            $totalUsedCost = $pricePerUnit * $usedQuantity;

            // Log the used materials into another table
            $logQuery = "INSERT INTO used_materials (po_number, dr_number, material_name, used_quantity, unit, price_per_unit, total_cost, supplier, order_date, delivery_date, project_name, comment) 
                         VALUES ('$poNumber', '$drNumber', '$materialName', $usedQuantity, '$unit', $pricePerUnit, $totalUsedCost, '$supplier', '$orderDate', '$deliveryDate', '$projectName', '$comment')";

            if (mysqli_query($db, $logQuery)) {
                echo "success";
            } else {
                echo "Error: " . mysqli_error($db);
            }
        } else {
            echo "Error: " . mysqli_error($db);
        }
    } else {
        echo "Error: Material not found.";
    }
} else {
    echo "Error: Missing required data.";
}
?>
