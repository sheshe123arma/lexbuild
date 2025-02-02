<?php
include('include/connect.php');
include('include/header.php');
include('include/sidebar1.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Materials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        table {
            width: 100%;
            table-layout: auto;
        }

        th, td {
            word-wrap: break-word;
            text-align: center;
        }

        .filter-container {
            margin-bottom: 15px;
        }

        .filter-container select,
        .filter-container input[type="submit"] {
            padding: 5px 10px;
            margin-right: 10px;
        }

        .total-cost {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div id="content-wrapper">
        <div class="container-fluid">
            <h2>List of Used Materials in Site(s)</h2>

            <!-- Filter Dropdown -->
            <div class="filter-container">
                <form method="GET" action="">
                    <label for="projectFilter">Filter by Project:</label>
                    <select name="projectFilter" id="projectFilter">
                        <option value="">All Projects</option>
                        <?php
                        // Fetch distinct project names for the dropdown
                        $projectQuery = "SELECT DISTINCT project_name FROM used_materials ORDER BY project_name";
                        $projectResult = mysqli_query($db, $projectQuery) or die(mysqli_error($db));

                        while ($projectRow = mysqli_fetch_assoc($projectResult)) {
                            $selected = (isset($_GET['projectFilter']) && $_GET['projectFilter'] === $projectRow['project_name']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($projectRow['project_name']) . "' $selected>" . htmlspecialchars($projectRow['project_name']) . "</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" value="Filter">
                </form>
            </div>

            <?php
            // Apply filter if projectFilter is set
            $filterCondition = "";
            if (isset($_GET['projectFilter']) && !empty($_GET['projectFilter'])) {
                $projectName = mysqli_real_escape_string($db, $_GET['projectFilter']);
                $filterCondition = "WHERE project_name = '$projectName'";
            }

            // Query to calculate total cost based on filter
            $totalCostQuery = "SELECT SUM(used_quantity * price_per_unit) AS total_cost_sum FROM used_materials $filterCondition";
            $totalCostResult = mysqli_query($db, $totalCostQuery) or die(mysqli_error($db));
            $totalCostRow = mysqli_fetch_assoc($totalCostResult);
            $totalCost = $totalCostRow['total_cost_sum'] ? number_format($totalCostRow['total_cost_sum'], 2) : '0.00';

            // Query to fetch data for the table
            $query = "SELECT id, project_name, po_number, dr_number, material_name, used_quantity, unit, price_per_unit, 
                             (used_quantity * price_per_unit) AS total_cost, supplier, order_date, delivery_date, used_date, comment 
                      FROM used_materials
                      $filterCondition
                      ORDER BY id DESC";
            $result = mysqli_query($db, $query) or die(mysqli_error($db));
            ?>
            <div class="icon-buttons">
    <a href="#" data-toggle="modal" data-target="#AddEmployee" class="btn btn-sm btn-info">
        <i class="fas fa-shopping-cart"></i> Order
    </a>
    <a href="#" data-toggle="modal" data-target="#RequestMaterials" class="btn btn-sm btn-info">
        <i class="fas fa-clipboard-list"></i> Request Materials
    </a>
    <a href="order_materials.php" class="btn btn-sm btn-info">
        <i class="fas fa-check-circle"></i> Ordered Materials
    </a>
    <a href="requested_materials.php" class="btn btn-sm btn-info">
        <i class="fas fa-check-circle"></i> Requested
    </a>
    <a href="approved_materials.php" class="btn btn-sm btn-info">
        <i class="fas fa-thumbs-up"></i> Approved
    </a>
    <a href="onstock_materials.php" class="btn btn-sm btn-info">
        <i class="fas fa-boxes"></i> On Stock
    </a>
    <a href="sitestock_materials.php" class="btn btn-sm btn-info">
        <i class="fas fa-warehouse"></i> Site Stock
    </a>
    <a href="used_materials.php" class="btn btn-sm btn-info">
        <i class="fas fa-recycle"></i> Used Materials
    </a>
    <!-- Print Button -->
<a href="#" onclick="window.print();" class="btn btn-sm btn-info">
    <i class="fas fa-print"></i> Print
</a>

    <!-- Refresh Button -->
    <a href="#" class="btn btn-sm btn-info" onclick="location.reload();">
        <i class="fas fa-sync-alt"></i> Refresh
    </a>
</div>

            <!-- Total Cost Display -->
            <div class="total-cost">
                <strong>Total Cost of Materials: </strong> ₱<?php echo $totalCost; ?>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Project</th>
                                <th>PO Number</th>
                                <th>DR Number</th>
                                <th>Material Name</th>
                                <th>Used Quantity</th>
                                <th>Unit</th>
                                <th>Price Per Unit</th>
                                <th>Total Cost</th>
                                <th>Supplier</th>
                                <th>Date Ordered</th>
                                <th>Delivered Date</th>
                                <th>Date Used</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . $row['project_name'] . '</td>';
                            echo '<td>' . $row['po_number'] . '</td>';
                            echo '<td>' . $row['dr_number'] . '</td>';
                            echo '<td>' . $row['material_name'] . '</td>';
                            echo '<td>' . $row['used_quantity'] . '</td>';
                            echo '<td>' . $row['unit'] . '</td>';
                            echo '<td>' . $row['price_per_unit'] . '</td>';
                            echo '<td>' . number_format($row['total_cost'], 2) . '</td>';
                            echo '<td>' . $row['supplier'] . '</td>';
                            echo '<td>' . $row['order_date'] . '</td>';
                            echo '<td>' . $row['delivery_date'] . '</td>';
                            echo '<td>' . $row['used_date'] . '</td>';
                            echo '<td>' . $row['comment'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/scripts.php'); ?>
<script>
function printTable() {
    // Hide all elements except for the table
    var bodyContent = document.body.innerHTML;
    var tableContent = document.querySelector('.table-responsive').outerHTML;
    
    // Create a new window for printing
    var printWindow = window.open('', '', 'height=800, width=1200');
    
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: landscape; margin: 0; }'); // Landscape A4 size
    printWindow.document.write('body { margin: 0; padding: 0; font-family: Arial, sans-serif; }');
    printWindow.document.write('.table-responsive { width: 100%; table-layout: fixed; }');
    printWindow.document.write('table { border-collapse: collapse; width: 100%; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    
    // Add the table content to the print window
    printWindow.document.write(tableContent);
    
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Trigger print dialog
    printWindow.print();
}
</script>


</body>
</html>
