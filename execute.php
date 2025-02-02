<?php 	

include('include/connect.php');

	$code = $_POST['code'];
	$due = $_POST['due'];
	$querys=mysqli_query($db,"select current_date() as 'date',CURRENT_TIME as 'time', TIME_FORMAT(CURRENT_TIME(), '%p') as 'amorpm'")or die(mysqli_error());
	$rows=mysqli_fetch_array($querys);
		$date = $rows['date'];
		$time = $rows['time'].' '.$rows['amorpm'];
			
		$query=mysqli_query($db,"select * from temp where unique_id='".$code."'")or die(mysqli_error());
		while($row=mysqli_fetch_array($query)){
		$equip_id=$row['equip_id'];
		$qty=$row['qty'];
		$emp_id=$row['emp_id'];
		$location_id=$row['location_id'];
		$status = $row['status'];
		$company = $row['company_name'];
		
		if($status=='OUTSOURCED'){
			mysqli_query($db,"INSERT INTO outsourcing(`company_name`, `equip_id`, `qty`, `date_outsourced`, `source_code`) VALUES('$company','$equip_id','$qty','$date','$code')")or die(mysqli_error($db));
		}
		
			mysqli_query($db,"INSERT INTO equip_mapping(`equip_id`, `qty`, `location_id`, `date_borrowed`, `time_borrowed`, `due_date`,`status`, `emp_id`) VALUES('$equip_id','$qty','$location_id','$date','$time','$due','BORROWED','$emp_id')")or die(mysqli_error($db));
		}

		mysqli_query($db,"DELETE FROM temp where unique_id='$code'") or die(mysqli_error($db));

	?>
		<script type="text/javascript">
			alert('Borrowed Successfully!');
      window.location = "borrowed_equip.php";
    </script>
    <?php
	
?>