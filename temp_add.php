<?php 	

include('include/connect.php');
if (isset($_POST['add'])) {

	$equipname = $_POST['equipname'];
	$qty = $_POST['qty'];
	$loc = $_POST['loc'];
	$emp = $_POST['emp'];
	$code = $_POST['code'];
	$due = $_POST['due'];
		
			
		$query=mysqli_query($db,"select * from equipments where equip_id='".$equipname."'")or die(mysqli_error());
		$row=mysqli_fetch_array($query);
		$equip_qty=$row['available_quantity'];
		
		if ($equip_qty < $qty){
?>
			<script type="text/javascript">
				alert('Not Enough Stock!');
      window.location = "borrowedequip.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
	<?php
		}
		else{
			mysqli_query($db,"INSERT INTO temp(`equip_id`, `qty`, `emp_id`, `location_id`, `unique_id`) VALUES('$equipname','$qty','$emp','$loc','$code')")or die(mysqli_error($con));

			mysqli_query($db,"UPDATE equipments SET status='UNAVAILABLE' where equip_id ='$equipname' ") or die(mysqli_error($db));
		}

	?>
		<script type="text/javascript">
      window.location = "borrowedequip.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
	}
?>