<?php
include('include/connect.php');
$querys=mysqli_query($db,"select current_date() as 'date'")or die(mysqli_error());
    $rows=mysqli_fetch_array($querys);
    $date = $rows['date'];

mysqli_query($db,"UPDATE `equip_mapping` SET date_returned = '".$date."', status  = 'RETURNED' WHERE map_id = '".$_GET['id']."' ") or die(mysqli_error($db));

$queryss=mysqli_query($db,"select * from equip_mapping where map_id = '".$_GET['id']."' ")or die(mysqli_error());
    $rowss=mysqli_fetch_array($queryss);
    $equip = $rowss['equip_id'];
    $qty = $rowss['qty'];
    //$equip = $rowss['date'];
date_default_timezone_set("Asia/Manila"); 
   $date1 = date("Y-m-d H:i:s");

 $remarks="returned equipment id $equip";
mysqli_query($db,"UPDATE `equipments` SET status = '' where equip_id = '".$equip."'") or die(mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));
?>
<script type="text/javascript">
	  alert('Successfully Returned!');
      window.location = "borrowed_equip.php";
</script>