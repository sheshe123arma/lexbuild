<?php
require 'vendor/autoload.php';  // Autoload Composer dependencies

use PhpOffice\PhpSpreadsheet\IOFactory;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileType = $_FILES['file']['type'];

    if ($fileType == 'application/vnd.oasis.opendocument.spreadsheet') {
        try {
            // Load the ODS file
            $spreadsheet = IOFactory::load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            foreach ($rows as $row) {
                // Assuming each row contains:
                // [Name, Salary, SSS, PhilHealth, Pag-IBIG, Cash Advance, Deduction, Days Present, Start Date, End Date, Overtime, Undertime]
                $name = $row[0];  
                $salary = $row[1];  
                $sss = $row[2];     
                $philhealth = $row[3];  
                $pagibig = $row[4];     
                $cash_advance = $row[5]; 
                $deduction = $row[6];   
                $days_present = isset($row[7]) ? (int) $row[7] : 0;  // Days Present
                $start_date = isset($row[8]) ? date('Y-m-d', strtotime($row[8])) : null;  // Start Date
                $end_date = isset($row[9]) ? date('Y-m-d', strtotime($row[9])) : null;  // End Date
                $overtime = isset($row[10]) ? (float) $row[10] : 0.0;  // Overtime in hours
                $undertime = isset($row[11]) ? (float) $row[11] : 0.0;  // Undertime in hours

                $stmt = $conn->prepare("INSERT INTO payrolls (name, salary, sss, philhealth, pagibig, cash_advance, deduction, days_present, start_date, end_date, overtime, undertime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sddddddissdd", $name, $salary, $sss, $philhealth, $pagibig, $cash_advance, $deduction, $days_present, $start_date, $end_date, $overtime, $undertime);
                $stmt->execute();
                $stmt->close();
            }

            echo "File uploaded and payroll data stored successfully!";
        } catch (Exception $e) {
            echo "Error parsing file: " . $e->getMessage();
        }
    } else {
        echo "Please upload a valid ODS file.";
    }
}

$conn->close();
?>
