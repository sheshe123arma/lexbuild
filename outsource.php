<?php 	

include('include/connect.php');
if (isset($_POST['submit'])) {

	$equipname = $_POST['equipname'];
	$qty = $_POST['qty'];
	$loc = $_POST['loc'];
	$emp = $_POST['emp'];
	//$ucode = $_POST['ucode'];
	$code = $_POST['code'];
	$due = $_POST['due'];
	$company = $_POST['company'];
		
			
			mysqli_query($db,"INSERT INTO temp(`equip_id`, `qty`, `emp_id`, `location_id`, `unique_id`,`status`, `company_name`) VALUES('$equipname','$qty','$emp','$loc','$code','OUTSOURCED','$company')")or die(mysqli_error($con));

	?>
		<script type="text/javascript">
      window.location = "borrowedequip.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
	}
?>