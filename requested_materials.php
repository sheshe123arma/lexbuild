<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<?php
// Assuming $db is your database connection
$query = "SELECT id, project_name, po_number, dr_number, material_name, quantity, unit, price_per_unit, 
                 (quantity * price_per_unit) AS total_payment, supplier, order_date, approved_date, comments, status 
          FROM request_materials 
          WHERE status = 'Pending'"; // Filter by status 'Pending'
$result = mysqli_query($db, $query) or die(mysqli_error($db));
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <h2>List of Requested Material(s)
<!-- Ensure you include Font Awesome -->
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
    <!-- Refresh Button -->
    <a href="#" class="btn btn-sm btn-info" onclick="location.reload();">
        <i class="fas fa-sync-alt"></i> Refresh
    </a>
</div>
        </h2> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>PO Number</th>
                            <th>DR Number</th>
                            <th>Material Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price Per Unit</th>
                            <th>Total Payment</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Approved Date</th>
                            <th>Comments</th>
                            <th>Status</th>
                            <th>Options</th>
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
                        echo '<td>' . $row['quantity'] . '</td>';
                        echo '<td>' . $row['unit'] . '</td>';
                        echo '<td>' . $row['price_per_unit'] . '</td>';
                        echo '<td>' . number_format($row['total_payment'], 2) . '</td>';
                        echo '<td>' . $row['supplier'] . '</td>';
                        echo '<td>' . $row['order_date'] . '</td>';
                        echo '<td id="approved_date_' . $row['id'] . '">' . $row['approved_date'] . '</td>';
                        echo '<td>' . $row['comments'] . '</td>';
                        echo '<td id="status_' . $row['id'] . '">' . $row['status'] . '</td>';
                        echo '<td>';
                        // Delete Icon
                        echo '<a type="button" class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#DeleteMaterialOrder' . $row['id'] . '">';
                        echo '<i class="fas fa-trash"></i>'; // Delete icon
                        echo '</a>&nbsp;';
                        // Delivered Checkbox Icon
echo '<a href="#" class="btn btn-sm btn-success" onclick="updateStatus(' . $row['id'] . ', \'' . $row['status'] . '\')">';
echo '<i class="fas fa-check-circle"></i>'; // Check circle icon to mark as approved
echo '</a>';

                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Handle deletion of material orders
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
    date_default_timezone_set("Asia/Manila");
    $date1 = date("Y-m-d H:i:s");

    // Fetch the material name being deleted (optional, for logs)
    $result = mysqli_query($db, "SELECT material_name FROM materials_orders WHERE id = '$id'");
    $material = mysqli_fetch_assoc($result);
    $material_name = $material['material_name'];

    // Delete the material order record
    $query = "DELETE FROM materials_orders WHERE id = '$id'";
    mysqli_query($db, $query) or die(mysqli_error($db));

    // Log the deletion
    $remarks = "Material Order for $material_name was deleted";
    mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

    echo '<script type="text/javascript">
            alert("Material Order Deleted Successfully!");
            window.location = "requested_materials.php";
          </script>';
}
?>

<!-- Modal for Deleting Material Order -->
<?php
// Loop through rows to create modals for each row
$result = mysqli_query($db, $query); // Re-run the query to get all rows
while ($row = mysqli_fetch_assoc($result)) {
?>
    <div id="DeleteMaterialOrder<?php echo $row['id']; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Material Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the material order for <strong><?php echo $row['material_name']; ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="#">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>




<?php
// Handle material request form submission
if (isset($_POST['submitRequest'])) {
    // Establish database connection
    $db = new mysqli("localhost", "root", "", "monitoring");

    // Check for connection errors
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Collect form data
    $project_name = $_POST['project_name'];
    $po_number = $_POST['po_number'];
    $dr_number = $_POST['dr_number'];
    $material_name = $_POST['material_name'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $price_per_unit = $_POST['price_per_unit'];
    $supplier = $_POST['supplier'];
    $order_date = $_POST['order_date'];
    $comments = $_POST['comments'];

    // Calculate total payment
    $total_payment = $quantity * $price_per_unit;

    // Prepare the query to insert data
    $stmt = $db->prepare("INSERT INTO request_materials (project_name, po_number, dr_number, material_name, quantity, unit, price_per_unit, total_payment, supplier, order_date, comments, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");

    // Bind parameters to the query
    $stmt->bind_param("ssssissdsss", $project_name, $po_number, $dr_number, $material_name, $quantity, $unit, $price_per_unit, $total_payment, $supplier, $order_date, $comments);

    // Execute the query and handle errors
    if ($stmt->execute()) {
        echo '<script type="text/javascript">
                alert("Material Request Submitted Successfully!");
                window.location = "requested_materials.php";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Error in submitting material request: ' . $stmt->error . '");
              </script>';
    }

    // Close the statement and database connection
    $stmt->close();
    $db->close();
}
?>

<!-- Modal for Requesting Materials -->
<div id="RequestMaterials" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="width: auto;">
            <div class="modal-header">
                <h3>Request Materials</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#">
                    <!-- Project Name -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <select id="inputProjectName" class="form-control" name="project_name" required>
                                <option value="">Select Project</option>
                                <?php
                                    // Database connection
                                    $conn = new mysqli("localhost", "root", "", "monitoring");
                                    
                                    // Check connection
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    
                                    // Query to fetch project names
                                    $sql = "SELECT project_name FROM projects";
                                    $result = $conn->query($sql);
                                    
                                    // Populate the dropdown with project names
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['project_name'] . "'>" . $row['project_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No projects available</option>";
                                    }
                                    
                                    // Close the database connection
                                    $conn->close();
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- PO Number -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="inputPONumber1" class="form-control" placeholder="PO Number" name="po_number">
                            <label for="inputPONumber1">PO Number</label>
                        </div>
                    </div>
                    <!-- DR Number -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="inputDRNumber1" class="form-control" placeholder="DR Number" name="dr_number">
                            <label for="inputDRNumber1">DR Number</label>
                        </div>
                    </div>
                    <!-- Material Name -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="inputMaterialName1" class="form-control" placeholder="Material Name" name="material_name" required>
                            <label for="inputMaterialName1">Material Name</label>
                        </div>
                    </div>
                    <!-- Quantity -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="number" id="inputQuantity1" class="form-control" placeholder="Quantity" name="quantity" required>
                            <label for="inputQuantity1">Quantity</label>
                        </div>
                    </div>
                    <!-- Unit -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="inputUnit1" class="form-control" placeholder="Unit" name="unit" required>
                            <label for="inputUnit1">Unit</label>
                        </div>
                    </div>
                    <!-- Price per Unit -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="number" id="inputPrice1" class="form-control" placeholder="Price per Unit" name="price_per_unit" required>
                            <label for="inputPrice1">Price per Unit</label>
                        </div>
                    </div>
                    <!-- Supplier Name -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" id="inputSupplier1" class="form-control" placeholder="Supplier Name" name="supplier" required>
                            <label for="inputSupplier1">Supplier Name</label>
                        </div>
                    </div>
                    <!-- Order Date -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="date" id="inputOrderDate1" class="form-control" placeholder="Order Date" name="order_date" required>
                            <label for="inputOrderDate1">Order Date</label>
                        </div>
                    </div>
                    <!-- Additional Comments -->
                    <div class="form-group">
                        <div class="form-label-group">
                            <textarea id="inputComments1" class="form-control" placeholder="Additional Comments" name="comments"></textarea>
                            <label for="inputComments1"></label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="submitRequest" value="Request Materials" class="btn btn-warning">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

           <div id="AddEmployee" class="modal fade" role="dialog">
              <div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content" style="width: auto;">
  <div class="modal-header">
    <h3>Order Materials</h3>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <form method="POST" action="#">
          <!-- New Fields Added -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputPONumber" class="form-control" placeholder="PO Number" name="po_number">
          <label for="inputPONumber">PO Number</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputDRNumber" class="form-control" placeholder="DR Number" name="dr_number">
          <label for="inputDRNumber">DR Number</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputMaterialName" class="form-control" placeholder="Material Name" name="material_name" autofocus="autofocus" required>
          <label for="inputMaterialName">Material Name</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputQuantity" class="form-control" placeholder="Quantity" name="quantity" required>
          <label for="inputQuantity">Quantity</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputUnit" class="form-control" placeholder="Unit" name="unit" required>
          <label for="inputUnit">Unit</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputPrice" class="form-control" placeholder="Price per Unit" name="price_per_unit" required>
          <label for="inputPrice">Price per Unit</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputSupplier" class="form-control" placeholder="Supplier Name" name="supplier" required>
          <label for="inputSupplier">Supplier Name</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="date" id="inputOrderDate" class="form-control" placeholder="Order Date" name="order_date" required>
          <label for="inputOrderDate">Order Date</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <textarea id="inputComments" class="form-control"placeholder="Additional Comments" name="comments"></textarea>
          <label for="inputComments"></label>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
          <span class="glyphicon glyphicon-remove-sign"></span>
        </button>
        <input type="submit" name="submit" value="Order Materials" class="btn btn-success">
      </div>
    </form>
  </div>
</div>



                </table>
              </div>
              </div>
   

<?php
if (isset($_POST['submit'])) {
  // Get form data
  $po_number = $_POST['po_number'];
  $dr_number = $_POST['dr_number'];
  $material_name = $_POST['material_name'];
  $quantity = $_POST['quantity'];
  $unit = $_POST['unit'];
  $price_per_unit = $_POST['price_per_unit'];
  $supplier = $_POST['supplier'];
  $order_date = $_POST['order_date'];
  $comments = $_POST['comments'];

  date_default_timezone_set("Asia/Manila");
  $date1 = date("Y-m-d H:i:s");

  $remarks = "Material Order for PO: $po_number, DR: $dr_number was added";

  // Insert query for the materials order table
  $query = "INSERT INTO materials_orders (po_number, dr_number, material_name, quantity, unit, price_per_unit, supplier, order_date, comments)
            VALUES ('$po_number', '$dr_number', '$material_name', '$quantity', '$unit', '$price_per_unit', '$supplier', '$order_date', '$comments')";

  // Execute the query
  mysqli_query($db, $query) or die(mysqli_error($db));

  // Insert the log entry
  mysqli_query($db, "INSERT INTO logs (action, date_time) VALUES ('$remarks', '$date1')") or die(mysqli_error($db));

  // JavaScript to alert and redirect after successful insertion
  ?>
  <script type="text/javascript">
    alert("New Material Order Added Successfully!");
    window.location = "requested_materials.php";  // Redirect to materials orders page
  </script>
  <?php

?>

    <?php
}
              include('include/scripts.php');
       
       
        ?>
<script type="text/javascript">
function updateStatus(id, currentStatus) {
    var status = 'approved';  // Change 'Delivered' to 'approved'
    var approvedDate = new Date().toISOString().split('T')[0]; // Get the current date in YYYY-MM-DD format

    // Send an AJAX request to the PHP script to update the status and approval date
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "updaterequest_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Successfully updated, change the status on the page
            if (xhr.responseText == 'success') {
                document.getElementById("status_" + id).innerHTML = status;
                document.getElementById("approved_date_" + id).innerHTML = approvedDate;
            } else {
                alert("Failed to update status");
            }
        }
    };
    xhr.send("id=" + id + "&status=" + status + "&approved_date=" + approvedDate);
}


</script> 