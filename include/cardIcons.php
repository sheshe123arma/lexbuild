<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link rel="stylesheet" href="path/to/font-awesome.css">
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <style>
        /* Custom styles to ensure scrolling is enabled only on the page content */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        /* Make only the content area scrollable */
        .scrollable {
            max-height: 80vh; /* Adjust as needed */
            overflow-y: auto;
        }

        .card {
            margin-bottom: 15px;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        /* Optional: Add styling for card footer */
        .card-footer {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="container scrollable">
    <div class="row">

 <!-- Total Employees Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-info o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-users"></i>
            </div>
            <?php
                // Query to count total employees
                $totalQuery = "SELECT COUNT(*) AS total_employees FROM employees";
                $totalResult = mysqli_query($db, $totalQuery);
                $totalEmployees = 0;
                if ($totalResult) {
                    $row = mysqli_fetch_assoc($totalResult);
                    $totalEmployees = $row['total_employees'] ?? 0;
                }

                // Query to count employees grouped by position
                $groupQuery = "SELECT position, COUNT(*) AS total FROM employees GROUP BY position";
                $groupResult = mysqli_query($db, $groupQuery);

                // Display total employees
                echo "<p>Total Employees: <strong>(" . number_format($totalEmployees) . ")</strong></p>";

                // Display breakdown by position
                if ($groupResult && mysqli_num_rows($groupResult) > 0) {
                    echo "<ul>";
                    while ($row = mysqli_fetch_assoc($groupResult)) {
                        echo "<li>" . htmlspecialchars($row['position']) . ": (" . $row['total'] . ")</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No position data available.";
                }
            ?>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="employees.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>


<!-- Total Attendance Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-secondary o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-calendar-check"></i>
            </div>
            <div class="mr-5">Total Attendance, Salary, and Hours</div>
            <?php
                // Enable error reporting for mysqli
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                // Initialize variables
                $total_days = 0;
                $total_salary = 0;
                $total_persons = 0;
                $total_hours = 0; // Total hours computation variable

                try {
                    // Query to calculate total days
                    $stmt_days = $db->prepare("SELECT SUM(days_present) as total_days FROM attendance");
                    $stmt_days->execute();
                    $result_days = $stmt_days->get_result();
                    $row_days = $result_days->fetch_assoc();
                    $total_days = $row_days['total_days'] ?? 0;

                    // Query to calculate total salary
                    $stmt_salary = $db->prepare("SELECT SUM(days_present * salary) as total_salary FROM attendance");
                    $stmt_salary->execute();
                    $result_salary = $stmt_salary->get_result();
                    $row_salary = $result_salary->fetch_assoc();
                    $total_salary = $row_salary['total_salary'] ?? 0;

                    // Query to calculate total persons
                    $stmt_persons = $db->prepare("SELECT COUNT(DISTINCT name) as total_persons FROM attendance");
                    $stmt_persons->execute();
                    $result_persons = $stmt_persons->get_result();
                    $row_persons = $result_persons->fetch_assoc();
                    $total_persons = $row_persons['total_persons'] ?? 0;

                    // Query to calculate total hours
                    $stmt_hours = $db->prepare("
                        SELECT SUM((days_present * 8) + (overtime_hours - undertime_hours)) as total_hours
                        FROM attendance
                    ");
                    $stmt_hours->execute();
                    $result_hours = $stmt_hours->get_result();
                    $row_hours = $result_hours->fetch_assoc();
                    $total_hours = $row_hours['total_hours'] ?? 0;

                } catch (Exception $e) {
                    die("Error: " . $e->getMessage());
                }

                // Display results
                echo "Days Present: (" . number_format($total_days) . ")<br>";
                echo "Total Hours Worked: " . number_format($total_hours, 2) . " hrs<br>";
                echo "Total Salary: ₱" . number_format($total_salary, 2) . "<br>";
                echo "Total Persons Working: (" . number_format($total_persons) . ")";
            ?>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="attendance.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>
<!-- Total Cash Advance and Contributions Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-info o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-money-check-alt"></i>
            </div>
            <div class="mr-5">Cash Advance & Contributions</div>
            <?php
                // Initialize variables
                $total_cash_advance = 0;
                $total_paid = 0;
                $balance = 0;
                $total_sss = 0;
                $total_philhealth = 0;
                $total_pagibig = 0;
                $total_contributions = 0;

                try {
                    // Query to calculate total cash advances
                    $stmt_cash_advance = $db->prepare("SELECT SUM(amount) as total_cash_advance FROM cash_advance");
                    $stmt_cash_advance->execute();
                    $result_cash_advance = $stmt_cash_advance->get_result();
                    $row_cash_advance = $result_cash_advance->fetch_assoc();
                    $total_cash_advance = $row_cash_advance['total_cash_advance'] ?? 0;

                    // Query to calculate total paid amount
                    $stmt_paid = $db->prepare("SELECT SUM(payment_amount) as total_paid FROM payments");
                    $stmt_paid->execute();
                    $result_paid = $stmt_paid->get_result();
                    $row_paid = $result_paid->fetch_assoc();
                    $total_paid = $row_paid['total_paid'] ?? 0;

                    // Query to calculate total sss_contributions
                    $stmt_sss = $db->prepare("SELECT SUM(sss_contribution) as total_sss FROM contributions");
                    $stmt_sss->execute();
                    $result_sss = $stmt_sss->get_result();
                    $row_sss = $result_sss->fetch_assoc();
                    $total_sss = $row_sss['total_sss'] ?? 0;

                    // Query to calculate total philhealth_contributions
                    $stmt_philhealth = $db->prepare("SELECT SUM(philhealth_contribution) as total_philhealth FROM contributions");
                    $stmt_philhealth->execute();
                    $result_philhealth = $stmt_philhealth->get_result();
                    $row_philhealth = $result_philhealth->fetch_assoc();
                    $total_philhealth = $row_philhealth['total_philhealth'] ?? 0;

                    // Query to calculate total pagibig_contributions
                    $stmt_pagibig = $db->prepare("SELECT SUM(pagibig_contribution) as total_pagibig FROM contributions");
                    $stmt_pagibig->execute();
                    $result_pagibig = $stmt_pagibig->get_result();
                    $row_pagibig = $result_pagibig->fetch_assoc();
                    $total_pagibig = $row_pagibig['total_pagibig'] ?? 0;

                    // Add up the contributions
                    $total_contributions = $total_sss + $total_philhealth + $total_pagibig;

                    // Calculate balance
                    $balance = $total_cash_advance - $total_paid;
                } catch (Exception $e) {
                    die("Error: " . $e->getMessage());
                }

                // Display total cash advance, total paid amount, balance, and total contributions
                echo "Total Cash Advances: ₱" . number_format($total_cash_advance, 2) . "<br>";
                echo "Total Paid Amount: ₱" . number_format($total_paid, 2) . "<br>";
                echo "Balance: ₱" . number_format($balance, 2) . "<br>";
                echo "Total Contributions: ₱" . number_format($total_contributions, 2);
            ?>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="payments.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>
<!-- Total Money Paid for Materials (Orders, Transfers, and Used) Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-primary o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-money-check-alt"></i>
            </div>
            <div class="mr-5">Total Money Paid for Materials</div>
            
            <!-- Total Payment for Materials Orders -->
            <?php
                // Connect to the database for materials_orders
                $query_orders = "SELECT SUM(quantity * price_per_unit) AS total_payment_orders FROM materials_orders WHERE quantity > 0";
                $result_orders = mysqli_query($db, $query_orders);
                $row_orders = mysqli_fetch_assoc($result_orders);
                $totalPaymentOrders = $row_orders['total_payment_orders'] ?? 0;

                // Display the total payment for orders
                echo "<strong>Main Stock Total:</strong> &#8369; " . number_format($totalPaymentOrders, 2) . "<br>";
            ?>

            <!-- Total Payment for Material Transfers -->
            <?php
                // Connect to the database for material_transfers
                $query_transfers = "SELECT SUM(quantity * price_per_unit) AS total_payment_transfers FROM material_transfers WHERE quantity > 0";
                $result_transfers = mysqli_query($db, $query_transfers);
                $row_transfers = mysqli_fetch_assoc($result_transfers);
                $totalPaymentTransfers = $row_transfers['total_payment_transfers'] ?? 0;

                // Display the total payment for transfers
                echo "<strong>Site Stock Materials Total:</strong> &#8369; " . number_format($totalPaymentTransfers, 2) . "<br>";
            ?>

            <!-- Total Cost for Used Materials -->
            <?php
                // Connect to the database for used_materials
                $query_used = "SELECT SUM(used_quantity * price_per_unit) AS total_cost_used FROM used_materials WHERE used_quantity > 0";
                $result_used = mysqli_query($db, $query_used);
                $row_used = mysqli_fetch_assoc($result_used);
                $totalCostUsed = $row_used['total_cost_used'] ?? 0;

                // Display the total cost for used materials
                echo "<strong>Used Materials Total:</strong> &#8369; " . number_format($totalCostUsed, 2) . "<br>";
            ?>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="materials_orders.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>

<!-- Total Projects Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-primary o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-project-diagram"></i>
            </div>
            <div class="mr-5">Total Projects</div>
            <?php
                // Query to get total projects and total budget
                $query_all = "SELECT project_name, budget FROM projects";
                $result_all = mysqli_query($db, $query_all);
                $total_projects = mysqli_num_rows($result_all);
                $total_budget = 0;
                $project_names = [];

                while ($row_all = mysqli_fetch_assoc($result_all)) {
                    $total_budget += $row_all['budget'];
                    $project_names[] = $row_all['project_name'];
                }
            ?>
            <div class="mr-5">
                <strong>Projects:</strong> <?php echo $total_projects; ?> - Budget: <?php echo number_format($total_budget, 2); ?>
            </div>
            <div>
                <strong>Project Names:</strong>
                <ul>
                    <?php foreach ($project_names as $name) { ?>
                        <li><?php echo $name; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="projects.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>

<!-- Upcoming Projects Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-info o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-calendar-alt"></i>
            </div>
            <div class="mr-5">Upcoming Projects</div>
            <?php
                // Query to get upcoming projects and their budget
                $query_upcoming = "SELECT project_name, budget FROM projects WHERE date_started > CURDATE()";
                $result_upcoming = mysqli_query($db, $query_upcoming);
                $upcoming_projects = mysqli_num_rows($result_upcoming);
                $upcoming_budget = 0;
                $upcoming_project_names = [];

                while ($row_upcoming = mysqli_fetch_assoc($result_upcoming)) {
                    $upcoming_budget += $row_upcoming['budget'];
                    $upcoming_project_names[] = $row_upcoming['project_name'];
                }
            ?>
            <div class="mr-5">
                <strong>Projects:</strong> <?php echo $upcoming_projects; ?> - Budget: <?php echo number_format($upcoming_budget, 2); ?>
            </div>
            <div>
                <strong>Upcoming Project Names:</strong>
                <ul>
                    <?php foreach ($upcoming_project_names as $name) { ?>
                        <li><?php echo $name; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="projects.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>

<!-- Completed Projects Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-success o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-check-circle"></i>
            </div>
            <div class="mr-5">Completed Projects</div>
            <?php
                // Query to get completed projects and their budget
                $query_completed = "SELECT project_name, budget FROM projects WHERE date_ended < CURDATE()";
                $result_completed = mysqli_query($db, $query_completed);
                $completed_projects = mysqli_num_rows($result_completed);
                $completed_budget = 0;
                $completed_project_names = [];

                while ($row_completed = mysqli_fetch_assoc($result_completed)) {
                    $completed_budget += $row_completed['budget'];
                    $completed_project_names[] = $row_completed['project_name'];
                }
            ?>
            <div class="mr-5">
                <strong>Projects:</strong> <?php echo $completed_projects; ?> - Budget: <?php echo number_format($completed_budget, 2); ?>
            </div>
            <div>
                <strong>Completed Project Names:</strong>
                <ul>
                    <?php foreach ($completed_project_names as $name) { ?>
                        <li><?php echo $name; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="projects.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>

<!-- Ongoing Projects Card -->
<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-warning o-hidden h-100">
        <div class="card-body">
            <div class="card-body-icon">
                <i class="fas fa-fw fa-spinner"></i>
            </div>
            <div class="mr-5">Ongoing Projects</div>
            <?php
                // Query to get ongoing projects and their budget
                $query_ongoing = "SELECT project_name, budget FROM projects WHERE date_started <= CURDATE() AND date_ended >= CURDATE()";
                $result_ongoing = mysqli_query($db, $query_ongoing);
                $ongoing_projects = mysqli_num_rows($result_ongoing);
                $ongoing_budget = 0;
                $ongoing_project_names = [];

                while ($row_ongoing = mysqli_fetch_assoc($result_ongoing)) {
                    $ongoing_budget += $row_ongoing['budget'];
                    $ongoing_project_names[] = $row_ongoing['project_name'];
                }
            ?>
            <div class="mr-5">
                <strong>Projects:</strong> <?php echo $ongoing_projects; ?> - Budget: <?php echo number_format($ongoing_budget, 2); ?>
            </div>
            <div>
                <strong>Ongoing Project Names:</strong>
                <ul>
                    <?php foreach ($ongoing_project_names as $name) { ?>
                        <li><?php echo $name; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="projects.php">
            <span class="float-left">View Details</span>
            <span class="float-right">
                <i class="fas fa-angle-right"></i>
            </span>
        </a>
    </div>
</div>


    
    <!-- Total Equipments Card -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
                <div class="card-body-icon">
                    <i class="fas fa-fw fa-truck"></i>
                </div>
                <div class="mr-5">Total of Equipments</div>
                <?php
                    $query = "SELECT count(*) from equipments";
                    $result = mysqli_query($db,$query);
                    $row = mysqli_fetch_array($result); 
                    echo "(".$row[0].")"; 
                ?>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="equipments.php">
                <span class="float-left">View Details</span>
                <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                </span>
            </a>
        </div>
    </div>
    
    <!-- Total Borrowed Tools Card -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
                <div class="card-body-icon">
                    <i class="fas fa-fw fa-toolbox"></i>
                </div>
                <div class="mr-5">Total of Borrowed Tools</div>
                <?php
                    $query = "SELECT count(*) from borrowed_tools";
                    $result = mysqli_query($db,$query);
                    $row = mysqli_fetch_array($result); 
                    echo "(".$row[0].")"; 
                ?>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="borrowed_tool.php">
                <span class="float-left">View Details</span>
                <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                </span>
            </a>
        </div>
    </div>
    
    <!-- Total Borrowed Equipments Card -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-danger o-hidden h-100">
            <div class="card-body">
                <div class="card-body-icon">
                    <i class="fas fa-fw fa-truck"></i>
                </div>
                <div class="mr-5">Total of Borrowed Equipments</div>
                <?php
                    $query = "SELECT count(*) from equip_mapping";
                    $result = mysqli_query($db,$query);
                    $row = mysqli_fetch_array($result); 
                    echo "(".$row[0].")"; 
                ?>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="borrowed_equip.php">
                <span class="float-left">View Details</span>
                <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                </span>
            </a>
        </div>
    </div>


</div><br><br><br><br>
        <!-- More Cards go here... -->
    </div>
</div>

<script src="path/to/bootstrap.js"></script>
<script src="path/to/font-awesome.js"></script>

</body>
</html>