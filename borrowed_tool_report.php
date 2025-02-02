 <?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">

        <div class="container-fluid">
               <div style="margin-bottom: 30px">
             <center><h2>Tools and Equipments Monitoring System</h2><br><h5>List of Borrowed Tool(s)</h5><a href="#" data-toggle="modal" data-target="#BarrowedDate" class="btn btn-sm btn-primary">Barrowed Date</a></center>
             <br>
             <center>
               <form method="post" style="margin-left: 40%" action="#">
              <div class="row">
             <div class="col-md-4">
              <div class="form-group">
             <label for="date">Select Employee</label>
               
                <select class="form-control select2" name="employee" tabindex="1" autofocus required>
                <?php
                   $query2=mysqli_query($db,"select * from employees")or die(mysqli_error());
                      while($row=mysqli_fetch_array($query2)){
                ?>
                    <option value="<?php echo $row['emp_id'];?>"><?php echo $row['name'];?></option>
                  <?php }?>
                </select>
              </div>
            </div>
            <!-- <div class=" col-md-0">
            <div class="form-group">
              <div class="input-group">
              </div>
            </div>
           </div> -->
            <div class="col-md-2" style="margin-top: 8px">
            <div class="form-group">
              <label for="date"></label>
              <div class="input-group">
                <button class="btn btn-md btn-primary fas fa-search" type="submit" name="search"></button>
              </div>
            </div>
          </div>
        </div>
        </form>
      </center>
         </div>
         <?php
         if(isset($_POST['submit'])){
         ?>
         <span id="divToPrint" style="width: 100%;">
         <center><h1>Start Date: <?php echo $_POST['start']; ?><br>End Date: <?php echo $_POST['end']; ?></h1></center><br>
                <table width="100%" cellspacing="0">
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
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT borrowed_tools.borrow_id,tools.name,employees.name as 'Employee',borrowed_tools.borrowed_date,borrowed_tools.borrowed_time,borrowed_tools.due_date,borrowed_tools.date_returned,borrowed_tools.status,borrowed_tools.qty,location.location_address FROM borrowed_tools left join tools on tools.tool_id = borrowed_tools.tool_id left join employees on employees.emp_id = borrowed_tools.emp_id left join location on location.location_id = borrowed_tools.location_id WHERE borrowed_date BETWEEN '".$_POST['start']."' AND '".$_POST['end']."'";
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
                            echo '</tr> ';
                     ?>  




                   <?php }
                        
            ?> 
         
                </table>
                <?php
              }elseif(isset($_POST['search'])){?>
                <span id="divToPrint" style="width: 100%;">
         <center><h1>Assigned Employee: <?php echo $_POST['employee']; ?></h1></center><br>
                <table width="100%" cellspacing="0">
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
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT borrowed_tools.borrow_id,tools.name,employees.name as 'Employee',borrowed_tools.borrowed_date,borrowed_tools.borrowed_time,borrowed_tools.due_date,borrowed_tools.date_returned,borrowed_tools.status,borrowed_tools.qty,location.location_address FROM borrowed_tools left join tools on tools.tool_id = borrowed_tools.tool_id left join employees on employees.emp_id = borrowed_tools.emp_id left join location on location.location_id = borrowed_tools.location_id WHERE employees.emp_id = '".$_POST['employee']."'";
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
                            echo '</tr> ';
                     ?>  




                   <?php }
                        
            ?> 
         
                </table>
                <?php
              }else{
                ?>
                <table width="100%" cellspacing="0">
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
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT borrowed_tools.borrow_id,tools.name,employees.name as 'Employee',borrowed_tools.borrowed_date,borrowed_tools.borrowed_time,borrowed_tools.due_date,borrowed_tools.date_returned,borrowed_tools.status,borrowed_tools.qty,location.location_address FROM borrowed_tools left join tools on tools.tool_id = borrowed_tools.tool_id left join employees on employees.emp_id = borrowed_tools.emp_id left join location on location.location_id = borrowed_tools.location_id";
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
                            echo '</tr> ';
                     ?>  




                   <?php }
                        
            ?> 
         
                </table>
                <?php
              }
                ?>
              </span>
              <br>
              <center>
                <div style="float: center;">    
               <a href="#" type="button" class="btn btn-xs btn-info fas fa-print" value="print" onclick="PrintDiv();"></a>
             </div> 
             </center>   

             <div id="BarrowedDate" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header"><h3>Barrowed Date</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                      
                        
                          <form method="POST" action="#">
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="date" id="inputName" class="form-control" placeholder="Name" name="start" autofocus="autofocus" required>
                            <label for="inputName">Start Date</label>
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="form-label-group">
                            <input type="date" id="inputAge" class="form-control" placeholder="Age" name="end" required>
                            <label for="inputAge">End  Date</label>
                            </div>
                            </div>
                            
                          
                 
                  <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">
                      Close
                      <span class="glyphicon glyphicon-remove-sign"></span>
                    </button> -->
                    <input type="submit" name="submit" value="Submit" class="btn btn-success">
                  </div>
                  </form>
               
</div>
</div>
              </div>
            </div>      
        <script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=800,height=800');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
              </div>
            </div>
            
       
              <?php
include('include/scripts.php');
       include('include/footer.php');
              ?>
