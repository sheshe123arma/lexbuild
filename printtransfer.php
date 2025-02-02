<?php
include "include/header.php";
include "include/sidebar1.php";
?>
                  <!-- Date range -->
                  <span id="divToPrint" style="width: 100%;">
                  
                    
                    <center>
                    <h5><b>Tools and Equipments Monitoring System</b> </h5>  
                  <h6>List of Transfered Equipment</h6>
                  <h6>Date <?php echo date("M d, Y");?> Time <?php echo date("h:i A");?></h6>
                </center>
                  <hr>
				<?php
       // session_start();
include('include/connect.php');


    
        
?>      
                  <center>
           <table class="table table-bordered">
                    <thead>
<?php

    $query = "SELECT tr.map_id,tr.date_returned,tr.status as 'stat',tr.transfer_id,lo.location_id,e.name,e.uniquec,em.name as 'Employee',tr.date_transfered,tr.time_transfered,lo.location_address as 'location to',loc.location_address as 'location from',eq.status,emp.name as 'Assign' FROM transfered_equipment tr,location lo,equipments e,employees em,location loc,equip_mapping eq,employees emp where lo.location_id = tr.location_id_to and e.equip_id = tr.equip_id and em.emp_id = tr.emp_id_from and loc.location_id = tr.location_id_from and eq.map_id = tr.map_id and emp.emp_id = tr.emp_id_to AND tr.transfer_id = '".$_GET['transfer_id']."'";
      $result = mysqli_query($db, $query) or die (mysqli_error($db));
        $row=mysqli_fetch_array($result);
      
        // $row=mysqli_fetch_array($query);
        // $last=$row['cust_last'];
        // $first=$row['cust_first'];
        // $address=$row['cust_address'];
        // $contact=$row['cust_contact'];
        // $down=$row['down'];
        // $interest=$row['interest'];
        // $user_id=$row['user_id'];
      
?>                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><h4>CODE #: <?php echo $row['uniquec'];?> </h4></th>
                      </tr>    
                      <tr>
                        <th><i>Assigned Employee From:</i></th>
                        <th style="color: red"><?php echo $row['Employee'];?></th>
                        <th><i>Assigned Employee To:</i></th>
                        <th style="color: red"><?php echo $row['Assign'];?></th>
                        <th><i>Tool:</i></th>
                        <th style="color: red"><?php echo $row['name'];?> </th>
                      </tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr>
                        <th><i>Location From:</i></th>
                        <th style="color: red"><?php echo $row['location from'];?></th>
                        <th><i>Location To:</i></th>
                        <th style="color: red"><?php echo $row['location to'];?></th>
                      </tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr>
                        <th><i>Date/Time Transfered:</i></th>
                        <th style="color: red"><?php echo $row['date_transfered'].''.$row['time_transfered'];?></th>
                        <!-- <th><i>Due Date:</i></th>
                        <th style="color: red"><?php //echo $row['due_date'];?></th> -->
                      </tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr>
                        <th><i>Status:</i></th>
                        <th style="color: red"><?php echo $row['status'];?></th>
                      </tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                      <tr></tr>
                    </thead>
                  </table>
                  <br>
                  <br>
                  <br>
                  <table class="table">
                    <thead>
                      <tr>
                        <th><i>Prepared by: </i></th>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
<?php
$query2=mysqli_query($db,"select * from admin where name='".$_SESSION['name']."'")or die(mysqli_error($db));  
    $row2=mysqli_fetch_array($query2);

?>                    
                      <tr>
                        <th style="color: red"><?php echo $row2['name'];?> </th>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                     
                    </tbody>
                    
                  </table>
                </center>
				
        </span>
              <br>
              <center>
                <div style="float: right;">    
               <a href="#" type="button" class="btn btn-xs btn-info fas fa-print" value="print" onclick="PrintDiv();"></a>
             </div> 
             </center> 
     <script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=800,height=800');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
 <?php 
include "include/footer.php";
  ?>