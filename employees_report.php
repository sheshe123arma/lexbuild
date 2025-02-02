 <?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">

        <div class="container-fluid">
    <span id="divToPrint" style="width: 100%;">
               <div style="margin-bottom: 30px">
             <center><h2>Tools and Equipments Monitoring System</h2><br><h5>List of Employee(s)</h5></center>
         </div>
                <table width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Age</th>
                      <th>Address</th>
                      <th>Contact Number</th>
                    </tr>
                  </thead>
                  
              <?php

$query = "SELECT * FROM employees";
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                             echo '<td>'. $row['name'].'</td>';
                             echo '<td>'. $row['age'].'</td>';
                              echo '<td>'. $row['address'].'</td>';
                               echo '<td>'. $row['contact_number'].'</td>';
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
