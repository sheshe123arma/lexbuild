<?php
include('include/connect.php');
$loc = $_POST['loc'];
$id = $_REQUEST['id'];
	$emp = $_POST['emp'];
	$code = $_POST['code'];
	$due = $_POST['due'];
	$qty = $_POST['qty'];
	$equipname = $_POST['equipname'];
$result=mysqli_query($db,"DELETE FROM temp WHERE temp_id ='$id'")
	or die(mysqli_error());

	mysqli_query($db,"UPDATE equipments SET status = '' where equip_id ='$equipname'") or die(mysqli_error($db));
	?>
		<script type="text/javascript">
      window.location = "borrowedequip.php?emp_id=<?php echo $emp; ?>&location_id=<?php echo $loc; ?>&code=<?php echo $code; ?>&due=<?php echo $due; ?>";
    </script>
    <?php
	?>