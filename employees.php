<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Employee(s)<a href="#" data-toggle="modal" data-target="#AddEmployee" class="btn btn-sm btn-info">Add New</a></h2> 
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Age</th>
                      <th>Address</th>
                      <th>Contact Number</th>
                      <th>Position</th>
                      <th>Salary</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT * FROM employees";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                             echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['age'].'</td>';
                              echo '<td>'. $row['address'].'</td>';
                               echo '<td>'. $row['contact_number'].'</td>';
                               echo '<td>'. $row['position'].'</td>';
                               echo '<td>'. $row['salary'].'</td>';
                               echo " ";
                               echo '<td><a type="button" class="btn btn-sm btn-warning fa fa-edit fw-fa" href="#" data-toggle="modal" data-target="#UpdateEmployee'.$row['emp_id'].'">Edit</a>';
                                echo '<a type="button" class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#DeleteEmployee' . $row['emp_id'] . '">Delete</a>';
                        
                               ?>
<div id="DeleteEmployee<?php echo $row['emp_id'];?>" class="modal fade" role="dialog">
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
                <form method="POST" action="#">
                    <input type="hidden" name="delete_id" value="<?php echo $row['emp_id']; ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                </form>
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
    $result = mysqli_query($db, "SELECT name FROM employees WHERE emp_id = '$id'");
    $employee = mysqli_fetch_assoc($result);
    $name = $employee['name'];

    // Delete the employee record
    $query = "DELETE FROM employees WHERE emp_id = '$id'";
    mysqli_query($db, $query) or die(mysqli_error($db));

    // Log the deletion
    $remarks = "Employee $name was deleted";
    mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

    echo '<script type="text/javascript">
            alert("Employee Deleted Successfully!");
            window.location = "employees.php";
          </script>';
}
?>
<div id="UpdateEmployee<?php echo $row['emp_id'];?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="width: auto">
      <div class="modal-header">
        <h3>Modify Employee</h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form method="POST" action="#">
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="inputName1" class="form-control" placeholder="Name" name="name" value="<?php echo $row['name']; ?>" autofocus="autofocus" required>
              <input type="hidden" id="inputName1" class="form-control" name="id" value="<?php echo $row['emp_id']; ?>" required>
              <label for="inputName1">Name</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="number" id="inputAge1" class="form-control" placeholder="Age" name="age" value="<?php echo $row['age']; ?>" required>
              <label for="inputAge1">Age</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="inputAddress1" class="form-control" placeholder="Address" value="<?php echo $row['address']; ?>" name="add" required>
              <label for="inputAddress1">Address</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="inputContact1" class="form-control" placeholder="Contact Number" value="<?php echo $row['contact_number']; ?>" name="contact" required>
              <label for="inputContact1">Contact Number</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="position" class="form-control" placeholder="Position" value="<?php echo $row['position']; ?>" name="position" required>
              <label for="position">Position</label>
            </div>
          </div>
          <!-- Add Salary Field -->
          <div class="form-group">
            <div class="form-label-group">
              <input type="number" id="inputSalary1" class="form-control" placeholder="Salary" name="salary" value="<?php echo $row['salary']; ?>" required>
              <label for="inputSalary">Salary</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
              <span class="glyphicon glyphicon-remove-sign"></span>
            </button>
            <input type="submit" name="update" value="Update" class="btn btn-success">
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

<?php
if(isset($_POST['update'])){
  $id = $_POST['id'];
  $name = $_POST['name'];
  $salary = $_POST['salary']; // Capture the salary value
  $age = $_POST['age'];
  $add = $_POST['add'];
  $contact = $_POST['contact'];
  $position = $_POST['position'];
  

  // Set timezone and get the current timestamp
  date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

  $remarks = "employee $name was updated";  

  // Update query to include salary
  $query = "UPDATE `employees` SET `name`='$name', `salary`='$salary', `age`='$age', `address`='$add', `contact_number`='$contact', `position`='$position'  WHERE `emp_id`='$id'";
  
  // Execute the query
  mysqli_query($db, $query) or die(mysqli_error($db));

  // Insert log entry
  mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

  // Provide feedback and redirect
  ?>
                <script type="text/javascript">
      alert("Employee Updated Successfully!.");
      window.location = "employees.php";
    </script>
    <?php
}
                            echo '</td> ';
                            echo '</tr> ';

                               
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
