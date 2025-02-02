<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">
    <div class="container-fluid">
        <h2>List of Upcoming Project(s)
            <a href="#" data-toggle="modal" data-target="#AddEmployee" class="btn btn-sm btn-info">Add Projects</a>
        </h2>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Date Started</th>
                            <th>Date Ended</th>
                            <th>Budget</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
                        // Query to select only projects where date_ended is greater than today's date
                        $query = "SELECT * FROM projects WHERE date_started > CURDATE()";
                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['project_id']; ?></td>
                                <td><?php echo $row['project_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['date_started']; ?></td>
                                <td><?php echo $row['date_ended']; ?></td>
                                <td><?php echo $row['budget']; ?></td>
                                <td>
                                    <a type="button" class="btn btn-sm btn-danger" href="#" 
                                       data-toggle="modal" data-target="#DeleteProject<?php echo $row['project_id']; ?>">Delete</a>
                                </td>
                            </tr>
                            <!-- Delete Modal -->
                            <div id="DeleteProject<?php echo $row['project_id']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete Project</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the project <strong><?php echo $row['project_name']; ?></strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" action="">
                                                <input type="hidden" name="delete_id" value="<?php echo $row['project_id']; ?>">
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
    $project_id = $_POST['delete_id'];  // Use the correct hidden input name

    date_default_timezone_set("Asia/Manila");
    $date1 = date("Y-m-d H:i:s");

    // Fetch the name of the project being deleted (optional, for logs)
    $result = mysqli_query($db, "SELECT project_name FROM projects WHERE project_id = '$project_id'");
    $project = mysqli_fetch_assoc($result);
    $project_name = $project['project_name'];

    // Delete the project record
    $query = "DELETE FROM projects WHERE project_id = '$project_id'";
    mysqli_query($db, $query) or die(mysqli_error($db));

$remarks = "Project '$project_name' was deleted";

// Escape the remarks to handle special characters properly
$remarks = mysqli_real_escape_string($db, $remarks);

// Log the deletion
$log_query = "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')";
mysqli_query($db, $log_query) or die(mysqli_error($db));


    echo '<script type="text/javascript">
            alert("Project Deleted Successfully!");
            window.location = "upcoming_projects.php";
          </script>';
}
?>


 
<div id="AddEmployee" class="modal fade" role="dialog">
              <div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content" style="width: auto;">
  <div class="modal-header">
    <h3>Add Project</h3>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <form method="POST" action="#">
      <div class="form-group">
        <label for="inputProjectName">Project Name</label>
        <input type="text" id="inputProjectName" class="form-control" name="project_name" required>
      </div>

      <div class="form-group">
        <label for="projectDescription">Description</label>
        <textarea id="projectDescription" class="form-control" name="description" rows="4" required></textarea>
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

      <!-- Budget -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="number" id="inputBudget" class="form-control" name="budget" required>
          <label for="inputBudget">Project Budget</label>
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
    // Retrieve form data for the project
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $date_started = $_POST['date_started'];
    $date_ended = $_POST['date_ended'];
    $budget = $_POST['budget'];

    // Insert data into the projects table
    $query = "INSERT INTO projects (project_name, description, date_started, date_ended, budget) 
              VALUES ('$project_name', '$description', '$date_started', '$date_ended', '$budget')";
  
  mysqli_query($db, $query) or die(mysqli_error($db));
  
  mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));

  ?>
  <script type="text/javascript">
    alert("New Project Added Successfully!.");
    window.location = "ongoing_projects.php";
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