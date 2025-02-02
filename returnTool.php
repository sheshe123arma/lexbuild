<?php
include('include/connect.php');
$querys=mysqli_query($db,"select current_date() as 'date'")or die(mysqli_error());
    $rows=mysqli_fetch_array($querys);
    $date = $rows['date'];

mysqli_query($db,"UPDATE `borrowed_tools` SET date_returned = '".$date."', status  = 'RETURNED' WHERE borrow_id = '".$_GET['id']."' ") or die(mysqli_error($db));

$queryss=mysqli_query($db,"select * from borrowed_tools where borrow_id = '".$_GET['id']."' ")or die(mysqli_error());
    $rowss=mysqli_fetch_array($queryss);
    $tool = $rowss['tool_id'];
    $qty = $rowss['qty'];
    //$equip = $rowss['date'];
date_default_timezone_set("Asia/Manila"); 
   $date1 = date("Y-m-d H:i:s");

 $remarks="returned tool id $tool";
mysqli_query($db,"UPDATE `tools` SET available_quantity = available_quantity + '".$qty."' where tool_id = '".$tool."'") or die(mysqli_error($db));
mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));
?>
<script type="text/javascript">
	  alert('Successfully Returned!');
      window.location = "borrowed_tool.php";
</script>