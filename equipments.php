<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>
<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Equipment(s)<a href="#" data-toggle="modal" data-target="#AddEquipment" class="btn btn-sm btn-info">Add New</a></h2> 
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Category</th>
                      <!-- <th>Quantity</th>
                      <th>Available Quantity</th> -->
                      <th>Status</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT * FROM equipments";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                             echo '<td>'. $row['uniquec'].'</td>';
                             echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['category'].'</td>';
                              // echo '<td>'. $row['quantity'].'</td>';
                              //  echo '<td>'. $row['available_quantity'].'</td>';
                               echo '<td>'. $row['status'].'</td>';
                               echo '<td><a type="button" class="btn btn-sm btn-warning fa fa-edit fw-fa" href="#" data-toggle="modal" data-target="#UpdateEquipment'.$row['equip_id'].'">Edit</a></td>';

                               ?>
                               <div id="UpdateEquipment<?php echo $row['equip_id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Modify Equipment</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <div class="form-group">
                            <div class="form-label-group">
                              <input type="hidden" id="inputName1" class="form-control" value="<?php echo $row['equip_id']; ?>" name="id" autofocus="autofocus" required>
                            <input type="text" id="inputName1" class="form-control" placeholder="Name" value="<?php echo $row['name']; ?>" name="name" autofocus="autofocus" required>
                            <label for="inputName1">Name</label>
                            </div>
                            </div>
                            <select name="category" class="form-control" id="selectType">
                            <option disabled>--Select Category--</option>
                            <option value="<?php echo $row['category']; ?>">HEAVY</option>
                            <option value="<?php echo $row['category']; ?>">NOT HEAVY</option>
                            </select><br>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" hidden id="inputQty1" class="form-control" placeholder="Quantity" name="qty" value="<?php echo $row['quantity']; ?>" required>
                            <!-- <label for="inputQty1">Quantity</label> -->
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" hidden id="inputQty1" class="form-control" name="avail_qty" value="<?php echo $row['available_quantity']; ?>" required>
                            <!-- <label for="inputQty1">Available Quantity</label> -->
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" id="inputQty1" class="form-control" name="status" value="<?php echo $row['status']; ?>">
                            <label for="inputQty1">Status</label>
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
  $category = $_POST['category'];
  $qty = $_POST['qty'];
  $id = $_POST['id'];
  $avail_qty = $_POST['avail_qty'];
  $status = $_POST['status'];

 date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

 $remarks="equipment $name was updated";  
  $query = "UPDATE `equipments` SET `name`='$name',`category`='$category',`quantity`='$qty',`available_quantity`='$avail_qty',`status`='$status' WHERE `equip_id`='$id'";
                mysqli_query($db,$query)or die (mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));

                ?>
                <script type="text/javascript">
      alert("Equipment Updated Successfully!.");
      window.location = "equipments.php";
    </script>
    <?php
}
                            echo '</tr> ';
                        }
                        
            ?> 
           
                </table>
              </div>
              </div>

              <div id="AddEquipment" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Add New Equipment</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="text" id="inputNamee" class="form-control" placeholder="Code" name="code" autofocus="autofocus" required>
                            <label for="inputNamee">Code</label>
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="text" id="inputName" class="form-control" placeholder="Name" name="name" autofocus="autofocus" required>
                            <label for="inputName">Name</label>
                            </div>
                            </div>
                            <select name="category" class="form-control" id="selectType">
                            <option disabled>--Select Category--</option>
                            <option value="HEAVY">HEAVY</option>
                            <option value="NOT HEAVY">NOT HEAVY</option>
                            </select><br>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="number" id="inputQty" value="1" hidden class="form-control" placeholder="Quantity" name="qty" required>
                           <!--  <label for="inputQty">Quantity</label> -->
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

$query = "SELECT * FROM equipments where uniquec ='".$_POST['code']."' ";
$result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
$row = mysqli_fetch_assoc($result);
if ($row>0) {
   ?>
      <script type="text/javascript">
      alert("Unique Code Already Exist!.");
      window.location = "equipments.php";
    </script>
    <?php
}else{

  $code = $_POST['code'];
  $name = $_POST['name'];
  $category = $_POST['category'];
  $qty = $_POST['qty'];

date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

 $remarks="equipment $name was Added"; 
  $query = "INSERT INTO equipments(uniquec,name,category,quantity,available_quantity)
                VALUES ('".$code."','".$name."','".$category."','".$qty."','".$qty."')";
                mysqli_query($db,$query)or die (mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));
                ?>
                <script type="text/javascript">
      alert("New Equipment Added Successfully!.");
      window.location = "equipments.php";
    </script>
    <?php
}
}
              include('include/scripts.php');
       include('include/footer.php');
       
        ?>