<?php
      
include('include/connect.php');
       include('include/header.php');
include('include/sidebar1.php')
        ?>
      
        
          <!-- DataTables Example -->
              <!-- DataTables Example -->
              <div id="content-wrapper">

        <div class="container-fluid">
           <h2>List of Borrowed Tool(s)<a href="#" data-toggle="modal" data-target="#borrowedtool"class="btn btn-sm btn-success" >Add New</a></h2>
           <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Tools</th>
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
                $query = "SELECT borrowed_tools.borrow_id,tools.name,employees.name as 'Employee',borrowed_tools.borrowed_date,borrowed_tools.borrowed_time,borrowed_tools.due_date,borrowed_tools.date_returned,borrowed_tools.status,borrowed_tools.qty,location.location_address, borrowed_tools.location_id, borrowed_tools.emp_id, borrowed_tools.tool_id, tools.tool_type FROM borrowed_tools left join tools on tools.tool_id = borrowed_tools.tool_id left join employees on employees.emp_id = borrowed_tools.emp_id left join location on location.location_id = borrowed_tools.location_id";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                            echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['qty'].'</td>';
                              //echo '<td>'. $row['remaining_qty'].'</td>';
                               echo '<td>'. $row['Employee'].'</td>';
                               echo '<td>'. $row['location_address'].'</td>';
                               echo '<td>'. $row['borrowed_date'].' '.$row['borrowed_time'].'</td>';
                              echo '<td>'. $row['due_date'].'</td>';
                               echo '<td>'. $row['date_returned'].'</td>';
                               echo '<td>'. $row['status'].'</td>';
                           if($row['status']=='RETURNED'){
                                echo '<td class="bg-success"><i>Returned Successfully!</i>
                                ';
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-warning fas fa-print" href="printtool.php?borrow_id='.$row['borrow_id'].'">Print</a>';
                               '</td>';
                               }else if($row['tool_type']=='CONSUMABLE'){
                                echo '<td class="bg-info"><i>This is a Consumable Tool</i></td>';
                               }else{
                                echo "<td>";
                               //echo '<td><a type="button" class="btn btn-sm btn-warning fas fa-pencil-alt" href="tools.php?action=add & id='.$row['borrow_id'] . '">Edit</a>';
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-success fas fa-history" href="#" data-toggle="modal" data-target="#returnConfirm">Return</a>';
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-warning fas fa-print" href="printtool.php?borrow_id='.$row['borrow_id'].'">Print</a>';
                                ?>
                             <div class="modal fade" id="returnConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Are You Sure You Want To Return This Tool(s)</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Return" below if you want to confirm.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-success" href="returnTool.php?action=add &id=<?php echo $row['borrow_id'];?>">Return</a>
          </div>
        </div>
      </div>
    </div>
                            <?php
                               echo " ";
                               echo '</td>';
                             }
                            echo '</tr> ';
                        }
                        
            ?> 
                  <tbody>
                  </tbody>
                </table>
              </div>
                   </div>
<?php
$query = "SELECT * FROM tools";

$result = mysqli_query($db,$query);

$query1 = "SELECT * FROM employees";

$result1 = mysqli_query($db,$query1);
?>
<!--
<div id="borrowedtool" class="modal fade" role="dialog">
              <div class="modal-dialog">

               
                <div class="modal-content" style="width: 130%">
                  <div class="modal-header"><h3>Add New Equipment</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <h3>Tool:</h3>
                            <div class="form-group">
                              
                              <select  name="type" class="form-control">
                                <option>---Select Tool---</option>
                              <?php// while($row = mysqli_fetch_array($result)):; ?>
                              <option value="<?php // echo $row[0]; ?>"><?php //echo $row[1]; ?></option>
                              <?php// endwhile; ?>
                              </select>
                            </div>
                            <h3>Employee:</h3>
                            <div class="form-group">
                              
                              <select  name="type" class="form-control">
                                <option>---Select Employee---</option>
                              <?php// while($row1 = mysqli_fetch_array($result1)):; ?>
                              <option value="<?php// echo $row1[0]; ?>"><?php// echo $row1[1]; ?></option>
                              <?php //endwhile; ?>
                              </select>
                            </div><br>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="date" id="inputDate" class="form-control" placeholder="Quantity" name="qty" required>
                            <label for="inputDate">Date</label>
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
            </div> -->


            <div id="borrowedtool" class="modal fade" role="dialog">
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
                $query4=mysqli_query($db,"select * from auto where description = 'BORROWED-TOOL'")or die(mysqli_error());
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

  $querys=mysqli_query($db,"select * from temp1 where emp_id = '".$emp."'")or die(mysqli_error());
    $rows=mysqli_fetch_array($querys);
if($rows==0){

mysqli_query($db,"UPDATE auto SET auto_end = auto_end+1 where description='BORROWED-TOOL'") or die(mysqli_error($db));
?>
<script type="text/javascript">
      window.location = "borrowedtool.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
}else{
                ?>
                <script type="text/javascript">
      window.location = "borrowedtool.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $rows['unique_id']; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
  }
}
include('include/scripts.php');
include('include/footer.php');
?>
  </body>

</html>
