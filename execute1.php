<?php 	

include('include/connect.php');

	$code = $_POST['code'];
	$due = $_POST['due'];
	$querys=mysqli_query($db,"select current_date() as 'date',CURRENT_TIME as 'time', TIME_FORMAT(CURRENT_TIME(), '%p') as 'amorpm'")or die(mysqli_error());
	$rows=mysqli_fetch_array($querys);
		$date = $rows['date'];
		$time = $rows['time'].' '.$rows['amorpm'];
			
		$query=mysqli_query($db,"select * from temp1 where unique_id='".$code."'")or die(mysqli_error());
		while($row=mysqli_fetch_array($query)){
		$tool_id=$row['tool_id'];
		$qty=$row['qty'];
		$emp_id=$row['emp_id'];
		$location_id=$row['location_id'];
		$status = $row['status'];
		$company = $row['company_name'];
		
		if($status=='OUTSOURCED'){
			mysqli_query($db,"INSERT INTO outsourcing_tools(`company_name`, `tool_id`, `qty`, `date_outsourced`, `source_code`) VALUES('$company','$tool_id','$qty','$date','$code')")or die(mysqli_error($db));
		}
		
			mysqli_query($db,"INSERT INTO borrowed_tools(`tool_id`, `qty`, `location_id`, `borrowed_date`, `borrowed_time`, `due_date`,`status`, `emp_id`) VALUES('$tool_id','$qty','$location_id','$date','$time','$due','BORROWED','$emp_id')")or die(mysqli_error($db));
		}

		mysqli_query($db,"DELETE FROM temp1 where unique_id='$code'") or die(mysqli_error($db));

	?>
		<script type="text/javascript">
			alert('Borrowed Successfully!');
      window.location = "borrowed_tool.php";
    </script>
    <?php
	
?>