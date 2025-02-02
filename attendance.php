<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">
    <div class="container-fluid">
        <h2>List of Attendance(s)
            <a href="#" data-toggle="modal" data-target="#AddEmployee" class="btn btn-sm btn-info">Add Attendance</a>
        </h2>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Date Started</th>
                            <th>Date Ended</th>
                            <th>Days Present</th>
                            <th>Overtime Hours</th>
                            <th>Undertime Hours</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM attendance";
                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['salary']; ?></td>
                                <td><?php echo $row['date_started']; ?></td>
                                <td><?php echo $row['date_ended']; ?></td>
                                <td><?php echo $row['days_present']; ?></td>
                                <td><?php echo $row['overtime_hours']; ?></td>
                                <td><?php echo $row['undertime_hours']; ?></td>
                                <td>
                                                                       
                                    <a type="button" class="btn btn-sm btn-danger" href="#" 
                                       data-toggle="modal" data-target="#DeleteAttendance<?php echo $row['id']; ?>">Delete</a>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div id="DeleteAttendance<?php echo $row['id']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete Attendance</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete <strong><?php echo $row['name']; ?></strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" action="">
                                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];

    date_default_timezone_set("Asia/Manila");
    $date1 = date("Y-m-d H:i:s");

    // Fetch the name of the employee being deleted (optional, for logs)
    $result = mysqli_query($db, "SELECT name FROM attendance WHERE id = '$id'");
    $attendance = mysqli_fetch_assoc($result);
    $name = $attendance['name'];

    // Delete the attendance record
    $query = "DELETE FROM attendance WHERE id = '$id'";
    mysqli_query($db, $query) or die(mysqli_error($db));

    // Log the deletion
    $remarks = "Attendance record for $name was deleted";
    mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

    echo '<script type="text/javascript">
            alert("Attendance Deleted Successfully!");
            window.location = "attendance.php";
          </script>';
}
?>

 
<div id="AddEmployee" class="modal fade" role="dialog">
              <div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content" style="width: auto;">
  <div class="modal-header">
    <h3>Add Attendance</h3>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <form method="POST" action="#">
      <div class="form-group">
        <label for="inputName">Name</label>
        <select id="inputName" class="form-control" name="name" required>
          <option value="" selected disabled>-- Select Name --</option>
          <?php
          // Fetch names, IDs, and salaries from the monitoring table
          $query = "SELECT DISTINCT emp_id, name, salary FROM monitoring.employees ORDER BY name ASC";
          $result = mysqli_query($db, $query);

          // Loop through the result and create dropdown options
          while ($row = mysqli_fetch_assoc($result)) {
              // Pass the emp_id and salary as a data attribute for easy access in JavaScript
              echo '<option value="' . $row['name'] . '" data-empid="' . $row['emp_id'] . '" data-salary="' . $row['salary'] . '">' . $row['name'] . '</option>
';
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="employeeID">Employee ID</label>
        <input type="text" id="employeeID" class="form-control" name="employee_id" readonly>
      </div>
      <div class="form-group">
        <label for="employeeSalary">Salary</label>
        <input type="text" id="employeeSalary" class="form-control" name="salary" readonly>
      </div>

      <!-- Date Started -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="date" id="inputDateStart" class="form-control" name="date_started" required>
          <label for="inputDateStart">Date Started</label>
        </div>
      </div>

      <!-- Date Ended -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="date" id="inputDateEnd" class="form-control" name="date_ended" required>
          <label for="inputDateEnd">Date Ended</label>
        </div>
      </div>

      <!-- Days Present -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputDaysPresent" class="form-control" name="days_present" required>
          <label for="inputDaysPresent">Days Present</label>
        </div>
      </div>

      <!-- Overtime Hours -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputOvertime" class="form-control" name="overtime_hours">
          <label for="inputOvertime">Overtime (Hours)</label>
        </div>
      </div>

      <!-- Undertime Hours -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputUndertime" class="form-control" name="undertime_hours">
          <label for="inputUndertime">Undertime (Hours)</label>
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


           
                </table>
              </div>
              </div>
   

<?php
if (isset($_POST['submit'])) {
    // Retrieve form data
    $emp_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $salary = $_POST['salary'];
    $date_started = $_POST['date_started'];
    $date_ended = $_POST['date_ended'];
    $days_present = $_POST['days_present'];
    $overtime_hours = $_POST['overtime_hours'] ?? 0; // Default to 0 if not provided
    $undertime_hours = $_POST['undertime_hours'] ?? 0; // Default to 0 if not provided

    // Insert data into the attendance table
    $query = "INSERT INTO attendance (emp_id, name, salary, date_started, date_ended, days_present, overtime_hours, undertime_hours) 
              VALUES ('$emp_id', '$name', '$salary', '$date_started', '$date_ended', '$days_present', '$overtime_hours', '$undertime_hours')";
  
  mysqli_query($db, $query) or die(mysqli_error($db));
  
  mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

  ?>
  <script type="text/javascript">
    alert("New Employee Added Successfully!.");
    window.location = "attendance.php";
  </script>
    <?php
}

              include('include/scripts.php');
       
       
        ?>
<script>
document.getElementById("inputName").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const empID = selectedOption.getAttribute("data-empid"); // Fetch emp_id from data attribute
    const salary = selectedOption.getAttribute("data-salary"); // Fetch salary from data attribute

    // Populate the respective fields
    document.getElementById("employeeID").value = empID;
    document.getElementById("employeeSalary").value = salary;
});

</script>