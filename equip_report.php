 <?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">

        <div class="container-fluid">
    <span id="divToPrint" style="width: 100%;">
               <div style="margin-bottom: 30px">
             <center><h2>Tools and Equipments Monitoring System</h2><br><h5>List of Equipment(s)</h5></center>
         </div>
                <table width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Quantity</th>
                      <th>Available Quantity</th>
                      <th>Status</th>
                    
                  </thead>
                  
              <?php

$query = "SELECT * FROM equipments";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                            echo '<td>'. $row['uniquec'].'</td>';
                             echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['category'].'</td>';
                              echo '<td>'. $row['quantity'].'</td>';
                               echo '<td>'. $row['available_quantity'].'</td>';
                               echo '<td>'. $row['status'].'</td>';
                            echo '</tr> ';
                        }
                        
            ?> 
         
                </table>
              </span>
              <br>
              <center>
                <div style="float: center;">    
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
              </div>
            </div>
            
       
              <?php
include('include/scripts.php');
       include('include/footer.php');
              ?>
