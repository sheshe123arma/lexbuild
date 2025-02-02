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

// Function to calculate normalized performance score based on the employee with highest days present
function calculate_normalized_performance_score($total_days_present, $max_days_present, $total_overtime_hours, $total_undertime_hours) {
    if ($total_days_present == 0) {
        return 0; // No present days, 0% performance
    }

    // Normalize based on the maximum number of days present
    $performance_score = ($total_days_present / $max_days_present) * 100;

    // Impact of Overtime (deduct if excessive overtime hours)
    if ($total_overtime_hours > 20) {
        $performance_score -= 15; // Excessive overtime, deduct 15 points
    } elseif ($total_overtime_hours > 10) {
        $performance_score -= 5; // Moderate overtime, deduct 5 points
    }

    // Impact of Undertime (deduct if excessive undertime hours)
    if ($total_undertime_hours > 5) {
        $performance_score -= 10; // Excessive undertime, deduct 10 points
    }

    // Ensure the score is between 0 and 100
    if ($performance_score < 0) {
        $performance_score = 0;
    } elseif ($performance_score > 100) {
        $performance_score = 100;
    }

    return $performance_score;
}

// Function to determine compliment based on performance score
function get_compliment($performance_score) {
    if ($performance_score >= 90) {
        return "Excellent";
    } elseif ($performance_score >= 75) {
        return "Good";
    } elseif ($performance_score >= 60) {
        return "Satisfactory";
    } else {
        return "Needs Improvement";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Salary Analysis</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS -->
    <style>
table {
    width: 100%; /* Ensures the table takes up the full width of the container */
    border-collapse: collapse; /* Removes space between borders */
    table-layout: fixed; /* Ensures columns are evenly spaced */
}

th, td {
    padding: 8px 12px; /* Adds space around text */
    text-align: left; /* Aligns text to the left */
    border: 1px solid #ddd; /* Adds border around cells */
    font-size: 12px; /* Reduces the font size for smaller text */
    word-wrap: break-word; /* Ensures text breaks to fit the cell */
    overflow-wrap: break-word; /* Alternative for word wrapping */
}

th {
    background-color: #f2f2f2; /* Light gray background for headers */
    font-weight: bold; /* Makes header text bold */
}

tr:nth-child(even) {
    background-color: #f9f9f9; /* Adds zebra striping for rows */
}

@media (max-width: 768px) {
    table {
        font-size: 10px; /* Further reduce font size for small screens */
    }

    th, td {
        padding: 6px 8px; /* Adjust padding for smaller screens */
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Performance Analysis</h1>
        <table>
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
                    <th>Performance Score</th>
                    <th>Compliment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Find the employee with the maximum days present
                $max_days_present = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['total_days_present'] > $max_days_present) {
                        $max_days_present = $row['total_days_present'];
                    }
                }

                // Reset the result pointer to the beginning
                mysqli_data_seek($result, 0);

                // Output each employee's data
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

                    // Calculate normalized performance score based on the employee with highest days present
                    $performance_score = calculate_normalized_performance_score(
                        $row['total_days_present'], 
                        $max_days_present, 
                        $row['total_overtime_hours'], 
                        $row['total_undertime_hours']
                    );

                    // Get the compliment based on the performance score
                    $compliment = get_compliment($performance_score);

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
                        <td>{$performance_score}%</td>
                        <td>{$compliment}</td>
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
