<?php
session_start();
include "include/connect.php";

$Message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user']) && isset($_POST['pass'])) {
    $username = trim($_POST['user']);
    $password = trim($_POST['pass']);

    if (empty($username) || empty($password)) {
        $Message = "Please fill in all fields.";
    } else {
        // Prepare and execute the query to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM `admin` WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION["name"] = $row['name'];
                $name = $row['name'];
                
                // Log user login
                date_default_timezone_set("Asia/Manila");
                $date1 = date("Y-m-d H:i:s");
                $remarks = "User $name logged in";
                $logStmt = $db->prepare("INSERT INTO logs (action, date_time) VALUES (?, ?)");
                $logStmt->bind_param("ss", $remarks, $date1);
                $logStmt->execute();

                // Redirect to the homepage
                echo '<script>
                    alert("Login Successful.");
                    window.location = "index.php";
                </script>';
                exit;
            } else {
                $Message = "Invalid username or password.";
            }
        } else {
            $Message = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="message">
            <?php echo htmlspecialchars($Message); ?>
        </div>
    </div>
</body>
</html>
