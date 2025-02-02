<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php')
       
        ?>

<div id="content-wrapper">
    <div class="container-fluid">
        <h2>List of Cash Advance(s)
            <a href="#" data-toggle="modal" data-target="#AddEmployee" class="btn btn-sm btn-info">Add Cashadvanced</a>
            <a href="#" data-toggle="modal" data-target="#AddPayment" class="btn btn-sm btn-info">Add Payment</a>

        </h2>
        
 <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Repayment Date</th>
                            <th>Date Added</th>
                            <th>Total Payments</th>
                            <th>Last Payment Date</th>
                            <th>Balance</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch data from the 'cash_advance' table and join with the 'payments' table
                        $query = "
                            SELECT 
                                ca.id, 
                                ca.name, 
                                ca.salary, 
                                ca.amount, 
                                ca.reason, 
                                ca.repayment_date, 
                                ca.date_added,
                                COALESCE(SUM(p.payment_amount), 0) AS total_payments, 
                                COALESCE(MAX(p.payment_date), '') AS last_payment_date
                            FROM 
                                cash_advance ca
                            LEFT JOIN 
                                payments p ON ca.id = p.cash_advance_id
                            GROUP BY 
                                ca.id
                        ";
                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        // Loop through and display each row
                        while ($row = mysqli_fetch_assoc($result)) { 
                            // Calculate balance
                            $balance = $row['amount'] - $row['total_payments'];
                        ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['salary']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['reason']; ?></td>
                                <td><?php echo $row['repayment_date']; ?></td>
                                <td><?php echo $row['date_added']; ?></td>
                                <td><?php echo $row['total_payments']; ?></td>
                                <td><?php echo $row['last_payment_date']; ?></td>
                                <td><?php echo $balance; ?></td>
                                <td>
                                    <!-- Action button to delete the cash advance record -->
                                    <a type="button" class="btn btn-sm btn-danger" href="#" 
                                       data-toggle="modal" data-target="#DeleteCashAdvance<?php echo $row['id']; ?>">Delete</a>
                                </td>
                            </tr>
                            
                            <!-- Delete Modal for Cash Advance -->
                            <div id="DeleteCashAdvance<?php echo $row['id']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete Cash Advance</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the cash advance for <strong><?php echo $row['name']; ?></strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- Form to delete the cash advance record -->
                                            <form method="POST" action="">
                                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <input type="submit" name="delete" value="Delete" class="btn btn-danger">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        if (isset($_POST['delete'])) {
            // Retrieve the ID of the cash advance to be deleted
            $delete_id = $_POST['delete_id'];

            // Delete the record from the cash_advance table
            $query = "DELETE FROM cash_advance WHERE id = '$delete_id'";
            $result = mysqli_query($db, $query);

            if ($result) {
                // Redirect or show success message
                echo "<script>alert('Cash Advance Deleted Successfully'); window.location.href = 'cashadvance.php';</script>";
            } else {
                // Error message if deletion fails
                echo "<script>alert('Error deleting cash advance');</script>";
            }
        }
        ?>

<!-- Add Payment Modal -->
<div id="AddPayment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="cash_advance_id">Cash Advance:</label>
                        <select class="form-control" id="cash_advance_id" name="cash_advance_id" required onchange="updateBreakdown()">
                            <option value="">Select Cash Advance</option>
                            <?php
                            // Fetch the available cash advances from the database
                            $query = "SELECT id, name FROM cash_advance";
                            $result = mysqli_query($db, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='".$row['id']."'>".$row['id']." - ".$row['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Hidden input to store the selected payment ID -->
                    <div class="form-group">
                        <label for="payment_id">Payment ID:</label>
                        <input type="text" class="form-control" id="payment_id" name="payment_id" readonly>
                    </div>

                    <!-- Readonly input to display the Cash Advance Name -->
                    <div class="form-group">
                        <label for="cash_advance_name">Cash Advance Name:</label>
                        <input type="text" class="form-control" id="cash_advance_name" name="cash_advance_name" readonly>
                    </div>

                    <div class="form-group">
                        <label for="payment_amount">Payment Amount:</label>
                        <input type="number" class="form-control" id="payment_amount" name="payment_amount" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_date">Payment Date:</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                        </select>
                    </div>

                    <button type="submit" name="add_payment" class="btn btn-primary">Add Payment</button>
                </form>
            </div>
        </div>
    </div>
</div> 
<div id="AddEmployee" class="modal fade" role="dialog">
              <div class="modal-dialog">

<!-- Modal content -->
<div class="modal-content" style="width: auto;">
  <div class="modal-header">
    <h3>Add Cash Advance</h3>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <form method="POST" action="#">
      <!-- Name Dropdown -->
      <div class="form-group">
        <label for="inputName">Name</label>
        <select id="inputName" class="form-control" name="name" required>
          <option value="" selected disabled>-- Select Name --</option>
          <?php
          // Fetch names, IDs, and salaries from the monitoring table
          $query = "SELECT DISTINCT emp_id, name, salary FROM monitoring.employees ORDER BY name ASC";
          $result = mysqli_query($db, $query);

          // Loop through the result and create dropdown options
          while ($row = mysqli_fetch_assoc($result)) {
              // Pass the emp_id and salary as a data attribute for easy access in JavaScript
              echo '<option value="' . $row['name'] . '" data-empid="' . $row['emp_id'] . '" data-salary="' . $row['salary'] . '">' . $row['name'] . '</option>';
          }
          ?>
        </select>
      </div>

      <!-- Employee ID -->
      <div class="form-group">
        <label for="employeeID">Employee ID</label>
        <input type="text" id="employeeID" class="form-control" name="employee_id" readonly>
      </div>

      <!-- Employee Salary -->
      <div class="form-group">
        <label for="employeeSalary">Salary</label>
        <input type="text" id="employeeSalary" class="form-control" name="salary" readonly>
      </div>

      <!-- Cash Advance Amount -->
      <div class="form-group">
        <label for="cashAdvanceAmount">Amount</label>
        <input type="number" id="cashAdvanceAmount" class="form-control" name="amount" step="0.01" min="0" required>
      </div>

      <!-- Reason for Cash Advance -->
      <div class="form-group">
        <label for="cashAdvanceReason">Reason</label>
        <textarea id="cashAdvanceReason" class="form-control" name="reason" rows="3" required></textarea>
      </div>

      <!-- Repayment Date -->
      <div class="form-group">
        <label for="repaymentDate">Repayment Date</label>
        <input type="date" id="repaymentDate" class="form-control" name="repayment_date" required>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
          <span class="glyphicon glyphicon-remove-sign"></span>
        </button>
        <input type="submit" name="submit" value="Save" class="btn btn-success">
      </div>
    </form>
  </div>
</div>


           
                </table>
              </div>
              </div>

   

<?php
if (isset($_POST['add_payment'])) {
    // Retrieve form data
    $cash_advance_id = $_POST['cash_advance_id']; // ID of the selected cash advance
    $payment_id = $_POST['payment_id'];          // Payment ID (same as cash_advance_id)
    $payment_amount = $_POST['payment_amount'];  // Payment amount
    $payment_date = $_POST['payment_date'];      // Date of payment
    $payment_method = $_POST['payment_method'];  // Payment method (cash, bank transfer, check)

    // Fetch cash advance name
    $queryName = "SELECT name FROM cash_advance WHERE id = '$cash_advance_id'";
    $resultName = mysqli_query($db, $queryName);

    if (mysqli_num_rows($resultName) > 0) {
        $cashAdvanceRow = mysqli_fetch_assoc($resultName);
        $cash_advance_name = $cashAdvanceRow['name']; // Retrieve the name of the cash advance

        // Insert payment data into the payments table
        $query = "INSERT INTO payments (payment_id, cash_advance_id, payment_amount, payment_date, payment_method)
                  VALUES ('$payment_id', '$cash_advance_id', '$payment_amount', '$payment_date', '$payment_method')";

        $result = mysqli_query($db, $query);

        if ($result) {
            // Optional: Update the cash_advance table to reflect the total paid amount
            $updateQuery = "UPDATE cash_advance 
                            SET total_paid = total_paid + $payment_amount
                            WHERE id = '$cash_advance_id'";
            mysqli_query($db, $updateQuery);

            // Display success message with cash advance name
            echo "<script>
                    alert('Payment for \"$cash_advance_name\" Added Successfully!');
                    window.location.href = 'cashadvance.php';
                  </script>";
        } else {
            echo "<script>alert('Error adding payment.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Cash Advance ID.');</script>";
    }
}

if (isset($_POST['submit'])) {
    // Retrieve form data
    $emp_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $salary = $_POST['salary'];
    $amount = $_POST['amount']; // Cash Advance Amount
    $reason = $_POST['reason']; // Reason for Cash Advance
    $repayment_date = $_POST['repayment_date']; // Repayment Date

    // Insert data into the cash_advance table
    $query = "INSERT INTO cash_advance (emp_id, name, salary, amount, reason, repayment_date) 
              VALUES ('$emp_id', '$name', '$salary', '$amount', '$reason', '$repayment_date')";
    
    if (mysqli_query($db, $query)) {
        // Log the action
        $remarks = "Added cash advance for $name ($emp_id)";
        $date1 = date("Y-m-d H:i:s");
        mysqli_query($db, "INSERT INTO logs(action, date_time) VALUES('$remarks', '$date1')") or die(mysqli_error($db));
        
        // Success message
        echo "<script type='text/javascript'>
                alert('Cash Advance successfully added!');
                window.location = 'cashadvance.php'; // Redirect to a page showing the list of cash advances
              </script>";
    } else {
        echo "<script type='text/javascript'>
                alert('Error inserting cash advance data!');
              </script>";
    }
}
?>

    <?php


              include('include/scripts.php');
       
       
        ?>
<script>
document.getElementById("inputName").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const empID = selectedOption.getAttribute("data-empid"); // Fetch emp_id from data attribute
    const salary = selectedOption.getAttribute("data-salary"); // Fetch salary from data attribute

    // Populate the respective fields
    document.getElementById("employeeID").value = empID;
    document.getElementById("employeeSalary").value = salary;
});
    function updatePaymentId() {
        var cashAdvanceId = document.getElementById("cash_advance_id").value;
        var paymentIdField = document.getElementById("payment_id");

        if (cashAdvanceId) {
            // Set the payment_id to the same value as the cash_advance_id
            paymentIdField.value = cashAdvanceId;
        } else {
            paymentIdField.value = ''; // Clear the payment ID field if no cash advance is selected
        }
    }

function updateBreakdown() {
    // Get the selected cash advance ID
    const cashAdvanceSelect = document.getElementById('cash_advance_id');
    const selectedOption = cashAdvanceSelect.options[cashAdvanceSelect.selectedIndex];

    if (selectedOption.value) {
        // Split the option text to extract ID and name
        const [id, name] = selectedOption.text.split(' - ');

        // Update the payment_id and cash_advance_name inputs
        document.getElementById('payment_id').value = id.trim();
        document.getElementById('cash_advance_name').value = name.trim();
    } else {
        // Clear the inputs if no cash advance is selected
        document.getElementById('payment_id').value = '';
        document.getElementById('cash_advance_name').value = '';
    }
}
</script>
