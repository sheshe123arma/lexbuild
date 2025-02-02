<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>
<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Locations(s)<a href="#" data-toggle="modal" data-target="#AddLocation" class="btn btn-sm btn-info">Add New</a></h2> 
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Location Address</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT * FROM location";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                               echo '<td>'. $row['location_address'].'</td>';
                               echo '<td><a type="button" class="btn btn-sm btn-warning fa fa-edit fw-fa" href="#" data-toggle="modal" data-target="#UpdateLocation'.$row['location_id'].'">Edit</a></td>';
                               ?>

                               <div id="UpdateLocation<?php echo $row['location_id']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Modify Location</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="hidden" name="id" value="<?php echo $row['location_id']; ?>">
                            <textarea type="text" id="inputName" class="form-control" placeholder="Location Address" name="address" autofocus="autofocus" required><?php echo $row['location_address']; ?></textarea>
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
  $address = $_POST['address'];
  $id = $_POST['id'];

date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

 $remarks="location $address was updated"; 
  $query = "UPDATE location SET location_address = '$address' WHERE location_id = '$id'";
                mysqli_query($db,$query)or die (mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));
                ?>
                <script type="text/javascript">
      alert("Location Has Been Updated Successfully!.");
      window.location = "locations.php";
    </script>
    <?php
}

                            echo '</tr> ';
                        }
                        
            ?> 
           
                </table>
              </div>
              </div>
              <div id="AddLocation" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Add New Location</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <div class="form-group">
                            <div class="form-label-group">
                            <textarea type="text" id="inputName" class="form-control" placeholder="Location Address" name="address" autofocus="autofocus" required></textarea>
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
  $address = $_POST['address'];
 date_default_timezone_set("Asia/Manila"); 
  $date1 = date("Y-m-d H:i:s");

 $remarks="location $address was Added"; 


  $query = "INSERT INTO location(location_address)
                VALUES ('".$address."')";
                mysqli_query($db,$query)or die (mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));
                ?>
                <script type="text/javascript">
      alert("New Location Has Been Added Successfully!.");
      window.location = "locations.php";
    </script>
    <?php
}


              include('include/scripts.php');
       include('include/footer.php');
       
        ?>