<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>



<div id="content-wrapper">
    <div class="container-fluid">
        <h2>List of Stock Materials in Site(s)</h2>
        <!-- Ensure you include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="icon-buttons">

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
<?php
// Assuming $db is your database connection
$query = "SELECT id, project_name, po_number, dr_number, material_name, quantity, unit, price_per_unit, 
                 (quantity * price_per_unit) AS total_payment, supplier, order_date, delivery_date, comments, status 
          FROM material_transfers
          WHERE quantity > 0
          ORDER BY id DESC"; // Sort by ID in descending order
$result = mysqli_query($db, $query) or die(mysqli_error($db));


?>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project Name</th>
                            <th>PO Number</th>
                            <th>DR Number</th>
                            <th>Material Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price Per Unit</th>
                            <th>Total Payment</th>
                            <th>Notes</th>
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
                        echo '<td>' . $row['comments'] . '</td>';
                        echo '<td>';
                        // Delivered Checkbox Icon   
                        echo '<a href="#" class="btn btn-sm btn-success" onclick="openTransferModal(' . $row['id'] . ')">';
                        echo '<i class="fas fa-exchange-alt"></i>'; 
                        echo '</a>';
                        echo '&nbsp';

                        // Used Materials Icon
                        echo '<a href="#" class="btn btn-sm btn-warning" onclick="useMaterial(' . $row['id'] . ')">';
                        echo '<i class="fas fa-box-open"></i>'; // This icon represents used materials
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
            window.location = "order_materials.php";
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
 </table>
              </div>
              </div>
<!-- Transfer Quantity Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transferModalLabel">Transfer Quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="transferForm">
          <input type="hidden" id="transferId" name="id">
          
          <div class="form-group">
            <label for="materialName">Material Name:</label>
            <input type="text" class="form-control" id="materialName" name="material_name" readonly>
          </div>

          <div class="form-group">
            <label for="transferQuantity">Enter Quantity to Transfer:</label>
            <input type="number" class="form-control" id="transferQuantity" name="quantity" required min="1">
          </div>

          <!-- Project Name Selection -->
          <div class="form-group">
            <label for="projectName">Select Project:</label>
            <select class="form-control" id="projectName" name="project_name" required>
              <option value="">Select a Project</option>
              <!-- Options will be dynamically populated from the server -->
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitTransfer()">Transfer</button>
      </div>
    </div>
  </div>
</div>




    <?php
              include('include/scripts.php');
       
       
        ?>
<script type="text/javascript">
  // Open the modal with the selected item's details
  function openTransferModal(id) {
    // Set the ID and clear any previous data
    document.getElementById("transferId").value = id;
    document.getElementById("transferQuantity").value = ""; // Clear any previous input
    document.getElementById("materialName").value = ""; // Clear material name

    // Fetch the material details and project options
    fetchMaterialDetails(id);
    fetchProjectOptions();

    $('#transferModal').modal('show'); // Open the modal
  }

  // Fetch material details based on ID (e.g., material name)
  function fetchMaterialDetails(id) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_material_details.php?id=" + id, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var data = JSON.parse(xhr.responseText);
        if (data.success) {
          document.getElementById("materialName").value = data.material_name; // Set the material name
        } else {
          alert("Failed to fetch material details.");
        }
      }
    };
    xhr.send();
  }

  // Fetch available project options
  function fetchProjectOptions() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_project_options.php", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var data = JSON.parse(xhr.responseText);
        var projectSelect = document.getElementById("projectName");
        projectSelect.innerHTML = '<option value="">Select a Project</option>'; // Reset options
        data.projects.forEach(function(project) {
          var option = document.createElement("option");
          option.value = project.project_name;
          option.textContent = project.project_name;
          projectSelect.appendChild(option);
        });
      }
    };
    xhr.send();
  }

function submitTransfer() {
    var id = document.getElementById("transferId").value;
    var transferQuantity = document.getElementById("transferQuantity").value;
    var projectName = document.getElementById("projectName").value; // Get the selected project name

    if (transferQuantity <= 0) {
      alert("Please enter a valid quantity.");
      return;
    }

    if (!projectName.trim()) {
      alert("Please select a project.");
      return;
    }

    // Send AJAX request to process the transfer
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "process_transfermaterials2.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        if (xhr.responseText == "success") {
          alert("Transfer successful!");
          location.reload(); // Reload the page to update the displayed data
        } else {
          alert("Failed to transfer quantity: " + xhr.responseText);
        }
      }
    };
    xhr.send("id=" + id + "&quantity=" + transferQuantity + "&project_name=" + encodeURIComponent(projectName));
}

function useMaterial(id) {
    let usedQuantity = prompt("Enter the quantity used:");
    if (usedQuantity === null || isNaN(usedQuantity) || usedQuantity <= 0) {
        alert("Please enter a valid quantity.");
        return;
    }

    let comment = prompt("Enter a comment for this transfer (optional):");
    if (comment === null) comment = ""; // Set to an empty string if user cancels

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "process_used_materials.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "success") {
                alert("Material usage recorded successfully!");
                location.reload();
            } else {
                alert("Error: " + xhr.responseText);
            }
        }
    };

    xhr.send("id=" + id + "&used_quantity=" + usedQuantity + "&comment=" + encodeURIComponent(comment));
}


</script>

