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
        #content-wrapper {
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

<?php
       include('include/connect.php');
       include('include/header.php');
       include('include/sidebar1.php');
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <h2>History Log</h2> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Logs</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = "SELECT * FROM logs";
                        $result = mysqli_query($db, $query) or die (mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>'. $row['action'].' --- '.$row['date_time'].'</td>';
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    include('include/scripts.php');
    include('include/footer.php');
?>

<script src="path/to/bootstrap.js"></script>
<script src="path/to/font-awesome.js"></script>

</body>
</html>
