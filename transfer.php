<?php 	

include('include/connect.php');
	$assign = $_POST['assign'];
	$map_id = $_POST['map_id'];
	$equipment = $_POST['equipment'];
	$employee = $_POST['employee'];
	$location_from = $_POST['location_from'];
	$location_to = $_POST['location_to'];
	$due = $_POST['due'];
	$querys=mysqli_query($db,"select current_date() as 'date',CURRENT_TIME as 'time', TIME_FORMAT(CURRENT_TIME(), '%p') as 'amorpm'")or die(mysqli_error());
	$rows=mysqli_fetch_array($querys);
		$date = $rows['date'];
		$time = $rows['time'].' '.$rows['amorpm'];
			
		date_default_timezone_set("Asia/Manila"); 
   $date1 = date("Y-m-d H:i:s");

 $remarks="transfered equipment id $equipment from $location_from to $location_to by employee $employee";
			mysqli_query($db,"INSERT INTO transfered_equipment(`map_id`, `equip_id`, `emp_id_from`,`emp_id_to`, `date_transfered`, `time_transfered`, `location_id_from`, `location_id_to`,`status`) VALUES('$map_id','$equipment','$employee','$assign','$date','$time','$location_from','$location_to','TRANSFERED')")or die(mysqli_error($db));
		    mysqli_query($db,"INSERT INTO logs(action,date_time) VALUES('$remarks','$date1')")or die(mysqli_error($db));

		mysqli_query($db,"UPDATE equip_mapping SET status = 'TRANSFERED' where map_id='$map_id'") or die(mysqli_error($db));

	?>
		<script type="text/javascript">
			alert('Equipment Transfered Successfully!');
      window.location = "transfered_equip.php";
    </script>
    <?php
	
?>