<?php
include('include/connect.php');
$loc = $_POST['loc'];
$id = $_REQUEST['id'];
	$emp = $_POST['emp'];
	$code = $_POST['code'];
	$due = $_POST['due'];
	$qty = $_POST['qty'];
	$toolname = $_POST['toolname'];
$result=mysqli_query($db,"DELETE FROM temp1 WHERE temp1_id ='$id'")
	or die(mysqli_error());

	mysqli_query($db,"UPDATE tools SET available_quantity = available_quantity +'$qty' where tool_id ='$toolname'") or die(mysqli_error($db));
	?>
		<script type="text/javascript">
      window.location = "borrowedtool.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
	?>