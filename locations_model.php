<?php
require("db.php");

// Gets data from URL parameters.
if(isset($_GET['add_location'])) {
    add_location();
}
if(isset($_GET['confirm_location'])) {
    confirm_location();
}



function add_location(){
    $con=mysqli_connect ("localhost", 'root', '','monitoring_db');
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $id =$_GET['description'];
    $eid =$_GET['emp'];
    // Inserts new row with place data.
    $query2=mysqli_query($con,"SELECT current_date as 'date'")or die(mysqli_error());
        $row2=mysqli_fetch_array($query2);
        $date=$row2['date'];
    $query1=mysqli_query($con,"select name from equipments where equip_id='$id'")or die(mysqli_error());
        $row1=mysqli_fetch_array($query1);
        $name=$row1['name'];
    $query = sprintf("INSERT INTO equip_mapping " .
        " (map_id, equip_id,name,lat,lng,date,unique_id,status,isVisible,emp_id) " .
        " VALUES (NULL, '%s', '%s', '%s','%s', '%s', '%s','1','YES','%s');",
        mysqli_real_escape_string($con,$id),
        mysqli_real_escape_string($con,$name),
        mysqli_real_escape_string($con,$lat),
        mysqli_real_escape_string($con,$lng),
        mysqli_real_escape_string($con,$date),
        mysqli_real_escape_string($con,$date),
        mysqli_real_escape_string($con,$eid));

    $result = mysqli_query($con,$query);
    echo"Inserted Successfully";
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
function confirm_location(){
    $con=mysqli_connect ("localhost", 'root', '','monitoring_db');
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    $id =$_GET['id'];
    $confirmed =$_GET['confirmed'];
    // update location with confirm if admin confirm.
    $query = "update equip_mapping set status = $confirmed WHERE map_id = $id ";
    $result = mysqli_query($con,$query);
    echo "Inserted Successfully";
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
function get_confirmed_locations(){
    $con=mysqli_connect ("localhost", 'root', '','monitoring_db');
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con,"
select m.map_id, m.equip_id,m.name,m.lat,m.lng,m.date,m.unique_id,m.status as isconfirmed,e.name as emp from equip_mapping m,employees e WHERE e.emp_id = m.emp_id and m.status = 1 and m.isVisible = 'YES'
  ");

    $rows = array();

    while($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;

    }

    $indexed = array_map('array_values', $rows);
    //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function getEquipment(){
    $con=mysqli_connect ("localhost", 'root', '','monitoring_db');
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
$query1 = "select * from equipments where category = 'heavy'";

    $result1 = mysqli_query($con,$query1);

    while($row = mysqli_fetch_array($result1)){
$name = $row[1];

    

   
    //  $array = array_filter($indexed);
}
}
function get_all_locations(){
    $con=mysqli_connect ("localhost", 'root', '','monitoring_db');
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con,"
select map_id, equip_id,name,lat,lng,date,unique_id,status as isconfirmed
from equip_mapping
  ");

    $rows = array();
    while($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;

    }
  $indexed = array_map('array_values', $rows);
  //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function array_flatten($array) {
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        }
        else {
            $result[$key] = $value;
        }
    }
    return $result;
}

?>