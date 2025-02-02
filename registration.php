<?php
session_start();
$Message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['name'])) {
    include "include/connect.php";

    $username = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
    $password = $_POST['pass'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $user_type = 'User'; // Default user type, change as necessary.

    if (empty($username) || empty($password) || empty($name)) {
        $Message = "Please fill in all fields.";
    } else {
        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM `admin` WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $Message = "Username already exists. Please choose another.";
        } else {
            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $insertStmt = $db->prepare("INSERT INTO `admin` (name, username, password, user_type) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $name, $username, $hashedPassword, $user_type);

            if ($insertStmt->execute()) {
                $Message = "Registration successful! You can now log in.";
                echo '<script>
                    alert("Registration Successful. Redirecting to login page.");
                    window.location = "login1.php";
                </script>';
                exit;
            } else {
                $Message = "Error: Unable to register. Please try again.";
            }

            $insertStmt->close();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            margin-bottom: 20px;
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
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="message">
            <?php echo htmlspecialchars($Message); ?>
        </div>
    </div>
</body>
</html>
