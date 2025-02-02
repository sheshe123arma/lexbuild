 <?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>
<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Borrowed Equipment(s)<a href="#" data-toggle="modal" data-target="#AddBorrowed" class="btn btn-sm btn-success">Add New</a></h2> 
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Equipment</th>
                      <th>Qty</th>
                      <th>Assigned Employee</th>
                      <th>Location</th>
                      <th>Date/Time Borrowed</th>
                      <th>Due Date</th>
                      <th>Date Returned</th>
                      <th>Status</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT equip_mapping.map_id,equipments.name,equipments.uniquec,employees.name as 'Employee',employees.emp_id,equip_mapping.date_borrowed,equip_mapping.time_borrowed,equip_mapping.due_date,equip_mapping.date_returned,equip_mapping.status,equip_mapping.qty,location.location_address, equip_mapping.location_id, equip_mapping.emp_id, equip_mapping.equip_id FROM equip_mapping left join equipments on equipments.equip_id = equip_mapping.equip_id left join employees on employees.emp_id = equip_mapping.emp_id left join location on location.location_id = equip_mapping.location_id";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                            echo '<td>'. $row['uniquec'].'</td>';
                             echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['qty'].'</td>';
                              //echo '<td>'. $row['remaining_qty'].'</td>';
                               echo '<td>'. $row['Employee'].'</td>';
                               echo '<td>'. $row['location_address'].'</td>';
                               echo '<td>'. $row['date_borrowed'].' '.$row['time_borrowed'].'</td>';
                              echo '<td>'. $row['due_date'].'</td>';
                               echo '<td>'. $row['date_returned'].'</td>';
                               echo '<td>'. $row['status'].'</td>';
                               if($row['status']=='RETURNED'){
                                echo '<td class="bg-success"><i>Returned Successfully!</i>
                                ';
                                echo " ";
                               echo '<a type="button" class="btn btn-sm btn-warning fas fa-print" href="print.php?map_id='.$row['map_id'].'">Print</a>';
                               echo '</td>';
                               }else if($row['status']=='TRANSFERED'){
                                echo '<td class="bg-success"><i>Transfered Successfully!</i>';
                                echo " ";
                               echo '<a type="button" class="btn btn-sm btn-warning fas fa-print" href="print.php?map_id='.$row['map_id'].'">Print</a>';
                               echo '</td>';
                               }else{
                                echo "<td>";
                               //echo '<td><a type="button" class="btn btn-sm btn-warning fas fa-pencil-alt" href="equipments.php?action=add & id='.$row['map_id'] . '">Edit</a>';
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-success fas fa-history" href="#" data-toggle="modal" data-target="#returnConfirm'.$row['map_id'].'">Return</a>';
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-warning fas fa-print" href="print.php?map_id='.$row['map_id'].'">Print</a>';
                                ?>
                             <div class="modal fade" id="returnConfirm<?php echo $row['map_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Are You Sure You Want To Return This Equipment(s)</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Return" below if you want to confirm.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-success" href="returnE.php?action=add &id=<?php echo $row['map_id'];?>">Return</a>
          </div>
        </div>
      </div>
    </div>
                            <?php
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-info fas fa-arrow-right" href="#" data-toggle="modal" data-target="#transfer">Transfer</a>
                               </td>';
                               ?>
                               <div id="transfer" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Transfer Equipment</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="transfer.php">
                              <input type="hidden" class="form-control" value="<?php echo $row['map_id']; ?>" name="map_id">
                              <input type="hidden" class="form-control" value="<?php echo $row['equip_id']; ?>" name="equipment">
                              <input type="hidden" class="form-control" value="<?php echo $row['emp_id']; ?>" name="employee">
                              <input type="hidden" class="form-control" value="<?php echo $row['location_id']; ?>" name="location_from">
                              <label>Transfer To</label>
                            <div class="form-group">
                            <div class="form-label-group">
                             <select class="form-control" name="location_to" tabindex="1" autofocus required>
                <?php
                   $querys2=mysqli_query($db,"select * from location")or die(mysqli_error());
                      while($rows2=mysqli_fetch_array($querys2)){
                ?>
                    <option value="<?php echo $rows2['location_id'];?>"><?php echo $rows2['location_address'];?></option>
                  <?php }?>
                </select>
                          </div>
                        </div>
                        <label>Assign Employee</label>
                            <div class="form-group">
                            <div class="form-label-group">
                             <select class="form-control" name="assign" tabindex="1" autofocus required>
                <?php
                   $querys3=mysqli_query($db,"select * from employees")or die(mysqli_error());
                      while($rows3=mysqli_fetch_array($querys3)){
                ?>
                    <option value="<?php echo $rows3['emp_id'];?>"><?php echo $rows3['name'];?></option>
                  <?php }?>
                </select>
                          </div>
                        </div>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="date" id="inputQty" class="form-control" placeholder="Due" name="due" required>
                            <label for="inputQty">Due Date</label>
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
                             }
                            echo '</tr> ';
                        }
                        
            ?> 
         
                </table>
              </div>
              </div>
              <div id="AddBorrowed" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Assigned Employee & Location</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
            <div class="form-group">
              <label for="date">Code</label>
                <?php
                $query4=mysqli_query($db,"select * from auto where description = 'BORROWED'")or die(mysqli_error());
                $row4=mysqli_fetch_array($query4);
                ?>
                <input type="text" class="form-control" readonly name="unique" value="<?php echo $row4['description'].'-'.($row4['auto_start']+$row4['auto_end']); ?>">
              </div>
                            <div class="form-group">
              <label for="date">Select Employee</label>
               
                <select class="form-control" name="emp" tabindex="1" autofocus required>
                <?php
                   $query2=mysqli_query($db,"select * from employees")or die(mysqli_error());
                      while($row=mysqli_fetch_array($query2)){
                ?>
                    <option value="<?php echo $row['emp_id'];?>"><?php echo $row['name'];?></option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
              <label for="date">Select Location</label>
               
                <select class="form-control" name="loc" tabindex="1" autofocus required>
                <?php
                   $query3=mysqli_query($db,"select * from location")or die(mysqli_error());
                      while($row3=mysqli_fetch_array($query3)){
                ?>
                    <option value="<?php echo $row3['location_id'];?>"><?php echo $row3['location_address'];?></option>
                  <?php }?>
                </select>
              </div>
              <div class="form-group">
                <label for="date">Due Date</label>
                <input type="date" class="form-control" required placeholder="Due Date" name="due">
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
  $emp = $_POST['emp'];
  $loc = $_POST['loc'];
  $code = $_POST['unique'];
  $due = $_POST['due'];
//   date_default_timezone_set("Asia/Manila"); 
//   $date = date("Y-m-d H:i:s");

// $remarks="";  
  $querys=mysqli_query($db,"select * from temp where emp_id = '".$emp."'")or die(mysqli_error());
    $rows=mysqli_fetch_array($querys);
if($rows==0){

mysqli_query($db,"UPDATE auto SET auto_end = auto_end+1 where description='BORROWED'") or die(mysqli_error($db));
// mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date')")or die(mysqli_error($db));
?>
<script type="text/javascript">
      window.location = "borrowedequip.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
}else{
                ?>
                <script type="text/javascript">
      window.location = "borrowedequip.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $rows['unique_id']; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
  }
}
              include('include/scripts.php');
       include('include/footer.php');
       
        ?>