<?php 	

include('include/connect.php');
if (isset($_POST['submit'])) {

	$toolname = $_POST['toolname'];
	$qty = $_POST['qty'];
	$loc = $_POST['loc'];
	$emp = $_POST['emp'];
	$code = $_POST['code'];
	$due = $_POST['due'];
	$company = $_POST['company'];
		
			
			mysqli_query($db,"INSERT INTO temp1(`tool_id`, `qty`, `emp_id`, `location_id`, `unique_id`,`status`, `company_name`) VALUES('$toolname','$qty','$emp','$loc','$code','OUTSOURCED','$company')")or die(mysqli_error($con));

	?>
		<script type="text/javascript">
      window.location = "borrowedtool.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
	}
?>