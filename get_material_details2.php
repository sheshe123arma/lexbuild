<?php
include('include/connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch material details
    $query = "SELECT material_name FROM materials_orders WHERE id = $id";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(["success" => true, "material_name" => $row['material_name']]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
