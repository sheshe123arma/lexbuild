<?php
      
include('include/connect.php');
       include('include/header.php');
include('include/sidebar1.php')
        ?>
      
        
          <!-- DataTables Example -->
              <!-- DataTables Example -->
  
        <div class="card-body" style="width: 220px;">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Add New Borrowed Equipment</a>
            </li>
            <li class="breadcrumb-item active">List of Borrowed Equipment(s)</li>
          </ol>
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Equipment Name</th>
                      <th>Latitude</th>
                      <th>Longitude</th>
                      <th>Date</th>
                      <th>IsVisible</th>
                      <th>Employee</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                        <?php                  
                $query = 'SELECT m.map_id,e.name as "ename",m.lat,m.lng,m.date,m.isVisible,emp.name as "empname" FROM equip_mapping m,equipments e,employees emp where e.equip_id = m.equip_id and emp.emp_id = m.emp_id';
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                  
                        while ($row = mysqli_fetch_assoc($result)) {
                                             
                            echo '<tr>';
                            echo '<td>'. $row['ename'].'</td>';
                            echo '<td>'. $row['lat'].'</td>';
                            echo '<td>'. $row['lng'].'</td>';
                            echo '<td>'. $row['date'].'</td>';
                            echo '<td>'. $row['isVisible'].'</td>';
                            echo '<td>'. $row['empname'].'</td>';
                            echo '<td> <a  type="button" class="btn btn-xs btn-success fas fa-eye" href="view.php?& id='.$row['map_id'].'&date='.$row['date'] .'"></a>';
                            echo '<a  type="button" class="btn btn-xs btn-warning fas fa-pencil-alt" href="event_del.php?action=edit & id=' . $row['map_id'] . '"></a></td>';
                            echo '</tr> ';
                }
            ?>
                  <tbody>
                  </tbody>
                </table>
              </div>
                    </div>
                  

  <div class="card-body">
  <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Add Equipment Location Below</a>
            </li>
            <li class="breadcrumb-item active">Mapping</li>
          </ol>
               <?php

include('view-map.php');
?>         
                    </div>
                  

               
<?php
include('include/scripts.php');
?>
  </body>

</html>
