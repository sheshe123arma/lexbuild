<?php
include "include/header.php";
include "include/sidebar1.php";
?>
                  <!-- Date range -->
                  <span id="divToPrint" style="width: 100%;">
                  
                    
                    <center>
                    <h5><b>Tools and Equipments Monitoring System</b> </h5>  
                  <h6>List of Borrowed Tools</h6>
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

    $query = "SELECT borrowed_tools.borrow_id,tools.name,employees.name as 'Employee',borrowed_tools.borrowed_date,borrowed_tools.borrowed_time,borrowed_tools.due_date,borrowed_tools.date_returned,borrowed_tools.status,borrowed_tools.qty,location.location_address, borrowed_tools.location_id, borrowed_tools.emp_id, borrowed_tools.tool_id, tools.tool_type FROM borrowed_tools left join tools on tools.tool_id = borrowed_tools.tool_id left join employees on employees.emp_id = borrowed_tools.emp_id left join location on location.location_id = borrowed_tools.location_id WHERE borrowed_tools.borrow_id = '".$_GET['borrow_id']."'";
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
                        <th><h4>CODE #: <?php echo $row['borrow_id'];?> </h4></th>
                      </tr>    
                      <tr>
                        <th><i>Assigned Employee:</i></th>
                        <th style="color: red"><?php echo $row['Employee'];?></th>
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
                        <th style="color: red"><?php echo $row['borrowed_date'].''.$row['borrowed_time'];?></th>
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