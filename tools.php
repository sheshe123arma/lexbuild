<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>
<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Tool(s)<a href="#"  data-toggle="modal" data-target="#Addtool" class="btn btn-sm btn-info">Add New</a></h2> 
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Tool Type</th>
                      <th>Quantity</th>
                      <th>Available Quantity</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT * FROM tools";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                             echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['tool_type'].'</td>';
                              echo '<td>'. $row['quantity'].'</td>';
                               echo '<td>'. $row['available_quantity'].'</td>';
                               echo '<td><a type="button" class="btn btn-sm btn-warning fa fa-edit fw-fa" href="#" data-toggle="modal" data-target="#UpdateTool'.$row['tool_id'].'">Edit</a></td>';
                               ?>

                               <div id="UpdateTool<?php echo $row['tool_id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Modify Tool</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <div class="form-group">
                            <div class="form-label-group">
                              <input type="hidden" id="inputName1" class="form-control" value="<?php echo $row['tool_id']; ?>" name="id" autofocus="autofocus" required>
                            <input type="text" id="inputName1" class="form-control" value="<?php echo $row['name']; ?>" placeholder="Name" name="name" autofocus="autofocus" required>
                            <label for="inputName1">Name</label>
                            </div>
                            </div>
                            <input type="text" readonly="" class="form-control" value="NON CONSUMABLE" placeholder="Name" name="type" autofocus="autofocus" required><br>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" id="inputQty1" class="form-control" value="<?php echo $row['quantity']; ?>" placeholder="Quantity" name="qty" required>
                            <label for="inputQty1">Quantity</label>
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" id="inputQty1" class="form-control" value="<?php echo $row['available_quantity']; ?>" placeholder="Available Quantity" name="avail_qty" required>
                            <label for="inputQty1">Available Quantity</label>
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
            <?php
if(isset($_POST['update'])){
  $name = $_POST['name'];
  $type = $_POST['type'];
  $qty = $_POST['qty'];
  $id = $_POST['id'];
  $avail_qty = $_POST['avail_qty'];

date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

 $remarks="tool $name was updated";  
  $query = "UPDATE `tools` SET `name`='$name',`quantity`='$qty',`available_quantity`='$avail_qty',`tool_type`='$type',`status`='' WHERE `tool_id`='$id'";
                mysqli_query($db,$query)or die (mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));

                ?>
                <script type="text/javascript">
      alert("Tool Updated Successfully!.");
      window.location = "tools.php";
    </script>
    <?php
}

                            echo '</tr> ';
                        }
                        
            ?> 
           
                </table>
              </div>
              </div>
              <div id="Addtool" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Add New Tool</h3>
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
                            <input type="text" readonly="" class="form-control" value="NON CONSUMABLE" placeholder="Name" name="type" autofocus="autofocus" required><br>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" id="inputQty" class="form-control" placeholder="Quantity" name="qty" required>
                            <label for="inputQty">Quantity</label>
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
              </div>
            </div>
            <?php
if(isset($_POST['submit'])){
  $name = $_POST['name'];
  $type = $_POST['type'];
  $qty = $_POST['qty'];


date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

 $remarks="tool $name was Added"; 
  $query = "INSERT INTO tools(name,quantity,available_quantity,tool_type)
                VALUES ('".$name."','".$qty."','".$qty."','".$type."')";
                mysqli_query($db,$query)or die (mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));

                ?>
                <script type="text/javascript">
      alert("New Tool Added Successfully!.");
      window.location = "tools.php";
    </script>
    <?php
}

              include('include/scripts.php');
       include('include/footer.php');
       
        ?>