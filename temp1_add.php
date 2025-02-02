<?php 	

include('include/connect.php');
if (isset($_POST['add'])) {

	$toolname = $_POST['toolname'];
	$qty = $_POST['qty'];
	$loc = $_POST['loc'];
	$emp = $_POST['emp'];
	$code = $_POST['code'];
	$due = $_POST['due'];
		
			
		$query=mysqli_query($db,"select * from tools where tool_id='".$toolname."'")or die(mysqli_error());
		$row=mysqli_fetch_array($query);
		$tool_qty=$row['available_quantity'];
		
		if ($tool_qty < $qty){
?>
			<script type="text/javascript">
				alert('Not Enough Stock!');
      window.location = "borrowedtool.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
	<?php
		}
		else{
			mysqli_query($db,"INSERT INTO temp1(`tool_id`, `qty`, `emp_id`, `location_id`, `unique_id`) VALUES('$toolname','$qty','$emp','$loc','$code')")or die(mysqli_error($con));

			mysqli_query($db,"UPDATE tools SET available_quantity = available_quantity-'$qty' where tool_id ='$toolname' ") or die(mysqli_error($db));
		}

	?>
		<script type="text/javascript">
      window.location = "borrowedtool.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
	}
?>