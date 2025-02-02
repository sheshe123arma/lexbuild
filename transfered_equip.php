<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>
<div id="content-wrapper">

        <div class="container-fluid">
 <h2>List of Transfered Equipment(s)</h2> 
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                      <th>Return</th>
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
                               if($row['stat']=='RETURNED'){
                                echo '<td class="bg-success"><i>Returned Successfully!</i></td>';
                               }else{
                               echo '<td><a type="button" class="btn btn-sm btn-success fa fa-return fw-fa" href="#" data-toggle="modal" data-target="#returnConfirm'.$row['map_id'].'">Return</a>';
                               echo " ";
                               echo '<a type="button" class="btn btn-sm btn-warning fa fa-print fw-fa" href="printtransfer.php?transfer_id='.$row['transfer_id'].'">Print</a>
                               </td>';
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
            <a class="btn btn-success" href="returnT.php?id=<?php echo $row['map_id'];?>">Return</a>
          </div>
        </div>
      </div>
    </div>
                            <?php
                          }
                            echo " ";
                            echo '</tr> ';
                        }
                        
            ?> 
           
                </table>
              </div>
              </div>
              <?php


              include('include/scripts.php');
       include('include/footer.php');
       
        ?>