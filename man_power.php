<?php
include('include/connect.php');
include('include/header.php');
include('include/sidebar1.php');

// Query to fetch employee details along with attendance data for salary calculation
$query = "
    SELECT e.emp_id, e.name AS employee_name, e.salary AS base_salary,
           SUM(a.days_present) AS total_days_present, 
           SUM(a.overtime_hours) AS total_overtime_hours,
           SUM(a.undertime_hours) AS total_undertime_hours
    FROM employees e
    LEFT JOIN attendance a ON e.emp_id = a.emp_id
    GROUP BY e.emp_id, e.name, e.salary
";

$result = mysqli_query($db, $query);

if (!$result) {
    die('Error executing salary query: ' . mysqli_error($db));
}

// Query to fetch cash advance data for each employee
$cash_advance_query = "
    SELECT e.emp_id, e.name AS employee_name, 
           SUM(ca.amount) AS total_cash_advance
    FROM employees e
    LEFT JOIN cash_advance ca ON e.emp_id = ca.emp_id
    GROUP BY e.emp_id, e.name
";

$cash_advance_result = mysqli_query($db, $cash_advance_query);

if (!$cash_advance_result) {
    die('Error executing cash advance query: ' . mysqli_error($db));
}

// Query to fetch payment data based on cash_advance_id linking to cash_advance
$payment_query = "
    SELECT e.emp_id, e.name AS employee_name, 
           SUM(p.payment_amount) AS total_payments
    FROM employees e
    LEFT JOIN cash_advance ca ON e.emp_id = ca.emp_id
    LEFT JOIN payments p ON ca.id = p.cash_advance_id
    GROUP BY e.emp_id, e.name
";

$payment_result = mysqli_query($db, $payment_query);

if (!$payment_result) {
    die('Error executing payment query: ' . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Salary Analysis</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Employee Salary Analysis</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Base Salary</th>
                    <th>Days Present</th>
                    <th>Overtime Hours</th>
                    <th>Overtime Pay</th>
                    <th>Undertime Hours</th>
                    <th>Undertime Deduction</th>
                    <th>Total Salary</th>
                    <th>Total Cash Advance</th>
                    <th>Total Payments</th>
                    <th>Net</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    // Calculate overtime and undertime
                    $overtime_pay = ($row['base_salary'] / 8) * $row['total_overtime_hours'];
                    $undertime_deduction = ($row['base_salary'] / 8) * $row['total_undertime_hours'];

                    // Calculate total salary
                    $total_salary = ($row['total_days_present'] * $row['base_salary']) + 
                                    $overtime_pay - $undertime_deduction;

                    // Get cash advance and payment data for the employee
                    $ca_row = mysqli_fetch_assoc($cash_advance_result);
                    $payment_row = mysqli_fetch_assoc($payment_result);

                    // Calculate Net (Total Salary + (Total Payments - Total Cash Advance))
                    $net = $total_salary + ($payment_row['total_payments'] - $ca_row['total_cash_advance']);

                    echo "<tr>
                        <td>{$row['employee_name']}</td>
                        <td>&#8369; " . number_format($row['base_salary'], 2) . "</td>
                        <td>{$row['total_days_present']}</td>
                        <td>" . number_format($row['total_overtime_hours'], 2) . " hrs</td>
                        <td>&#8369; " . number_format($overtime_pay, 2) . "</td>
                        <td>" . number_format($row['total_undertime_hours'], 2) . " hrs</td>
                        <td>&#8369; " . number_format($undertime_deduction, 2) . "</td>
                        <td>&#8369; " . number_format($total_salary, 2) . "</td>
                        <td>&#8369; " . number_format($ca_row['total_cash_advance'], 2) . "</td>
                        <td>&#8369; " . number_format($payment_row['total_payments'], 2) . "</td>
                        <td>&#8369; " . number_format($net, 2) . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
include('include/scripts.php');
?>
