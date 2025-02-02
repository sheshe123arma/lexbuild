<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Employee(s)
  <a href="#" onclick="printPayrollTable()" class="btn btn-sm btn-success">Print Payrolls</a></h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file">Select an ODS file:</label>
        <input type="file" name="file" id="file" accept=".ods" required>
        <button type="submit">Upload</button>
    </form>


<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Salary</th>
                    <th>SSS</th>
                    <th>Philhealth</th>
                    <th>Pagibig</th>
                    <th>Cash Advance</th>
                    <th>Deduction</th>
                    <th>Days Present</th>
                    <th>Overtime</th>
                    <th>Undertime</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM payrolls";
                $result = mysqli_query($db, $query) or die(mysqli_error($db));

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['salary'] . '</td>';
                    echo '<td>' . $row['sss'] . '</td>';
                    echo '<td>' . $row['philhealth'] . '</td>';
                    echo '<td>' . $row['pagibig'] . '</td>';
                    echo '<td>' . $row['cash_advance'] . '</td>';
                    echo '<td>' . $row['deduction'] . '</td>';
                    echo '<td>' . $row['days_present'] . '</td>';
                    echo '<td>' . $row['overtime'] . '</td>';
                    echo '<td>' . $row['undertime'] . '</td>';
                    echo '<td>' . $row['start_date'] . '</td>';
                    echo '<td>' . $row['end_date'] . '</td>';
                               echo '<td><a type="button" class="btn btn-sm btn-warning fa fa-edit fw-fa" href="#" data-toggle="modal" data-target="#UpdateEmployee'.$row['id'].'">Edit</a>';
                                echo '<a type="button" class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#DeleteEmployee' . $row['id'] . '">Delete</a>';
                               ?>
<!-- Delete Modal -->
<div id="DeleteEmployee<?php echo $row['id']; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Employee</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong><?php echo $row['name']; ?></strong>?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="">
                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Handle delete request
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];

    // Set timezone and log the current time
    date_default_timezone_set("Asia/Manila");
    $date1 = date("Y-m-d H:i:s");

    // Fetch employee name for logging (optional)
    $result = mysqli_query($db, "SELECT name FROM payrolls WHERE id = '$id'");
    if ($result && mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
        $name = $employee['name'];

        // Delete the employee record
        $query = "DELETE FROM payrolls WHERE id = '$id'";
        if (mysqli_query($db, $query)) {
            // Log the deletion
            $remarks = "Employee $name was deleted";
            mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

            // Show success message and refresh page
            echo '<script type="text/javascript">
                    alert("Employee Deleted Successfully!");
                    window.location = "payroll.php";
                  </script>';
        } else {
            echo '<script type="text/javascript">
                    alert("Failed to delete employee.");
                  </script>';
        }
    } else {
        echo '<script type="text/javascript">
                alert("Employee not found.");
              </script>';
    }
}
?>
   <!-- Edit Modal -->
    <div id="UpdateEmployee<?php echo $row['id']; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="width: auto">
                <div class="modal-header">
                    <h3>Edit Payroll</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="number" class="form-control" name="salary" value="<?php echo $row['salary']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>SSS</label>
                            <input type="number" class="form-control" name="sss" value="<?php echo $row['sss']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>PhilHealth</label>
                            <input type="number" class="form-control" name="philhealth" value="<?php echo $row['philhealth']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Pag-IBIG</label>
                            <input type="number" class="form-control" name="pagibig" value="<?php echo $row['pagibig']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Cash Advance</label>
                            <input type="number" class="form-control" name="cash_advance" value="<?php echo $row['cash_advance']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Deductions</label>
                            <input type="number" class="form-control" name="deduction" value="<?php echo $row['deduction']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Overtime</label>
                            <input type="number" class="form-control" name="overtime" value="<?php echo $row['overtime']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Undertime</label>
                            <input type="number" class="form-control" name="undertime" value="<?php echo $row['undertime']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="<?php echo $row['start_date']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="end_date" value="<?php echo $row['end_date']; ?>" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <input type="submit" name="update" value="Update" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

           <div id="AddEmployee" class="modal fade" role="dialog">
              <div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content" style="width: auto;">
  <div class="modal-header">
    <h3>Add New Employee</h3>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <form method="POST" action="#">
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputName" class="form-control" placeholder="Name" name="name" autofocus="autofocus" required>
          <label for="inputName">Name</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputSalary" class="form-control" placeholder="Salary" autofocus="autofocus" name="salary" >
          <label for="inputSalary">Salary</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputAge" class="form-control" placeholder="Age" name="age" required>
          <label for="inputAge">Age</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputAddress" class="form-control" placeholder="Address" name="add" required>
          <label for="inputAddress">Address</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputContact" class="form-control" placeholder="Contact Number" name="contact" required>
          <label for="inputContact">Contact Number</label>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="position1" class="form-control" placeholder="Position" name="position" required>
          <label for="position1">Position</label>
        </div>
      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
          <span class="glyphicon glyphicon-remove-sign"></span>
        </button>
        <input type="submit" name="submit" value="Save" class="btn btn-success">
      </div>
    </form>
  </div>
</div>

<!-- Backend Logic to Handle Updates -->
<?php
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $salary = $_POST['salary'];
    $sss = $_POST['sss'];
    $philhealth = $_POST['philhealth'];
    $pagibig = $_POST['pagibig'];
    $cash_advance = $_POST['cash_advance'];
    $deduction = $_POST['deduction'];
    $overtime = $_POST['overtime'];
    $undertime = $_POST['undertime'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $updateQuery = "UPDATE payrolls 
                    SET name = '$name', salary = '$salary', sss = '$sss', philhealth = '$philhealth', 
                        pagibig = '$pagibig', cash_advance = '$cash_advance', deduction = '$deduction',
                        overtime = '$overtime', undertime = '$undertime', start_date = '$start_date', end_date = '$end_date'
                    WHERE id = '$id'";

    if (mysqli_query($db, $updateQuery)) {
        echo '<script type="text/javascript">
                alert("Payroll updated successfully!");
                window.location = "payroll.php";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Error updating payroll. Please try again.");
              </script>';
    }
}
?>

                </table>
              </div>
              </div>
   

<?php
if(isset($_POST['submit'])){
  $name = $_POST['name'];
  $age = $_POST['age'];
  $add = $_POST['add'];
  $contact = $_POST['contact'];
  $position = $_POST['position'];
  $salary = $_POST['salary']; // Get the salary data

  date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

  $remarks = "employee $name was Added";  

  // Update query to include salary field
  $query = "INSERT INTO employees(name, salary, age, address, contact_number, position)
            VALUES ('$name', '$salary', '$age', '$add', '$contact', '$position')";
  
  mysqli_query($db, $query) or die(mysqli_error($db));
  
  mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

  ?>
  <script type="text/javascript">
    alert("New Employee Added Successfully!.");
    window.location = "employees.php";
  </script>
    <?php
}

              include('include/scripts.php');
       
       
        ?>
<script>
function printPayrollTable() {
    // Select the table content
    const tableContent = document.querySelector('.table-responsive').innerHTML;

    // Open a new window for printing
    const printWindow = window.open('', '_blank');

    // Write the table content to the new window
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Payrolls</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table, th, td {
                    border: 1px solid black;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
            </style>
        </head>
        <body>
            <h2>Payroll Report</h2>
            <div>${tableContent}</div>
            <script>window.print();<\/script>
        </body>
        </html>
    `);

    // Close the document to trigger rendering
    printWindow.document.close();
}
</script>