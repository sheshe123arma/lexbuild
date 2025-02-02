<?php
include "include/header.php";
include "include/sidebar1.php";
?>
                  <!-- Date range -->
                  <span id="divToPrint" style="width: 100%;">
                  
                    
                    <center>
                    <h5><b>Tools and Equipments Monitoring System</b> </h5>  
                  <h6>List of Borrowed Equipment</h6>
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

    $query = "SELECT equip_mapping.map_id,equipments.name,equipments.uniquec,employees.name as 'Employee',employees.emp_id,equip_mapping.date_borrowed,equip_mapping.time_borrowed,equip_mapping.due_date,equip_mapping.date_returned,equip_mapping.status,equip_mapping.qty,location.location_address, equip_mapping.location_id, equip_mapping.emp_id, equip_mapping.equip_id FROM equip_mapping left join equipments on equipments.equip_id = equip_mapping.equip_id left join employees on employees.emp_id = equip_mapping.emp_id left join location on location.location_id = equip_mapping.location_id WHERE equip_mapping.map_id = '".$_GET['map_id']."'";
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
                        <th><i>Assigned Employee:</i></th>
                        <th style="color: red"><?php echo $row['Employee'];?></th>
                        <th><i>Equipment:</i></th>
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
                        <th><i>Quantity:</i></th>
                        <th style="color: red"><?php echo $row['qty'];?></th>
                        <th><i>Location:</i></th>
                        <th style="color: red"><?php echo $row['location_address'];?></th>
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
                        <th><i>Date/Time Borrowed:</i></th>
                        <th style="color: red"><?php echo $row['date_borrowed'].''.$row['time_borrowed'];?></th>
                        <th><i>Due Date:</i></th>
                        <th style="color: red"><?php echo $row['due_date'];?></th>
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