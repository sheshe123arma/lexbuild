 <?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">

        <div class="container-fluid">
               <div style="margin-bottom: 30px">
             <center><h2>Tools and Equipments Monitoring System</h2><br><h5>List of Transfered Equipment(s)</h5><a href="#" data-toggle="modal" data-target="#BarrowedDate" class="btn btn-sm btn-primary">Barrowed Date</a></center>
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
                <table width="100%" cellspacing="0">
                 <thead>
                    <tr>
                      <th>Code</th>
                      <th>Equipment</th>
                      <th>Assigned Employee From</th>
                      <th>Assigned Employee To</th>
                      <th>Date&Time Transfered</th>
                      <th>Location From</th>
                      <th>Location To</th>
                      <th>Date Returned</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT tr.map_id,tr.date_returned,tr.status as 'stat',tr.transfer_id,lo.location_id,e.name,e.uniquec,em.name as 'Employee',tr.date_transfered,tr.time_transfered,lo.location_address as 'location to',loc.location_address as 'location from',eq.status,emp.name as 'Assign' FROM transfered_equipment tr,location lo,equipments e,employees em,location loc,equip_mapping eq,employees emp where lo.location_id = tr.location_id_to and e.equip_id = tr.equip_id and em.emp_id = tr.emp_id_from and loc.location_id = tr.location_id_from and eq.map_id = tr.map_id and emp.emp_id = tr.emp_id_to AND date_transfered BETWEEN '".$_POST['start']."' AND '".$_POST['end']."'";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                               echo '<td>'. $row['uniquec'].'</td>';
                               echo '<td>'. $row['name'].'</td>';
                               echo '<td>'. $row['Employee'].'</td>';
                               echo '<td>'. $row['Assign'].'</td>';
                               echo '<td>'. $row['date_transfered'].' '.$row['time_transfered'].'</td>';
                               //echo '<td>'. $row['time_transfered'].'</td>';
                               echo '<td>'. $row['location from'].'</td>';
                               echo '<td>'. $row['location to'].'</td>';
                               echo '<td>'. $row['date_returned'].'</td>';
                               echo '<td>'. $row['stat'].'</td>';
                            echo '</tr> ';
                        }
                        
            ?> 
         
                </table>
                <?php
              }elseif(isset($_POST['search'])){
                ?>
                <span id="divToPrint" style="width: 100%;">
                <table width="100%" cellspacing="0">
                 <thead>
                    <tr>
                      <th>Code</th>
                      <th>Equipment</th>
                      <th>Assigned Employee From</th>
                      <th>Assigned Employee To</th>
                      <th>Date&Time Transfered</th>
                      <th>Location From</th>
                      <th>Location To</th>
                      <th>Date Returned</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT tr.map_id,tr.date_returned,tr.status as 'stat',tr.transfer_id,lo.location_id,e.name,e.uniquec,em.name as 'Employee',tr.date_transfered,tr.time_transfered,lo.location_address as 'location to',loc.location_address as 'location from',eq.status,emp.name as 'Assign' FROM transfered_equipment tr,location lo,equipments e,employees em,location loc,equip_mapping eq,employees emp where lo.location_id = tr.location_id_to and e.equip_id = tr.equip_id and em.emp_id = tr.emp_id_from and loc.location_id = tr.location_id_from and eq.map_id = tr.map_id and emp.emp_id = tr.emp_id_to AND emp.emp_id = '".$_POST['employee']."'";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                               echo '<td>'. $row['uniquec'].'</td>';
                               echo '<td>'. $row['name'].'</td>';
                               echo '<td>'. $row['Employee'].'</td>';
                               echo '<td>'. $row['Assign'].'</td>';
                               echo '<td>'. $row['date_transfered'].' '.$row['time_transfered'].'</td>';
                               //echo '<td>'. $row['time_transfered'].'</td>';
                               echo '<td>'. $row['location from'].'</td>';
                               echo '<td>'. $row['location to'].'</td>';
                               echo '<td>'. $row['date_returned'].'</td>';
                               echo '<td>'. $row['stat'].'</td>';
                            echo '</tr> ';
                        }
                        
            ?> 
         
                </table>
                <?php

              }else{
                ?>
                                <table width="100%" cellspacing="0">
                 <thead>
                    <tr>
                      <th>Code</th>
                      <th>Equipment</th>
                      <th>Assigned Employee From</th>
                      <th>Assigned Employee To</th>
                      <th>Date&Time Transfered</th>
                      <th>Location From</th>
                      <th>Location To</th>
                      <th>Date Returned</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT tr.map_id,tr.date_returned,tr.status as 'stat',tr.transfer_id,lo.location_id,e.name,e.uniquec,em.name as 'Employee',tr.date_transfered,tr.time_transfered,lo.location_address as 'location to',loc.location_address as 'location from',eq.status,emp.name as 'Assign' FROM transfered_equipment tr,location lo,equipments e,employees em,location loc,equip_mapping eq,employees emp where lo.location_id = tr.location_id_to and e.equip_id = tr.equip_id and em.emp_id = tr.emp_id_from and loc.location_id = tr.location_id_from and eq.map_id = tr.map_id and emp.emp_id = tr.emp_id_to";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                               echo '<td>'. $row['uniquec'].'</td>';
                               echo '<td>'. $row['name'].'</td>';
                               echo '<td>'. $row['Employee'].'</td>';
                               echo '<td>'. $row['Assign'].'</td>';
                               echo '<td>'. $row['date_transfered'].' '.$row['time_transfered'].'</td>';
                               //echo '<td>'. $row['time_transfered'].'</td>';
                               echo '<td>'. $row['location from'].'</td>';
                               echo '<td>'. $row['location to'].'</td>';
                               echo '<td>'. $row['date_returned'].'</td>';
                               echo '<td>'. $row['stat'].'</td>';
                            echo '</tr> ';
                        }
                        
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
