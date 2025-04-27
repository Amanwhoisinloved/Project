<?php
session_start();

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'simple_auth');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Query to fetch user from the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Check if password matches
        if (password_verify($password, $user['password'])) {
            // Store user data in session (so they can be accessed later)
            $_SESSION['username'] = $user['username'];
            
            // Redirect to Main.php after successful login
            header("Location: Main.php");
            exit(); // Important to stop the script after redirect
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
           
            font-family: Arial, sans-serif;
            background-image: url("bg.jpg");
            background-size: cover;
            background-position: center;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        .form-container {
            background: Semi-transparent;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px gray;
            margin-top: 20px;
            width: 300px;
            height: 500px;
            position: relative;
            top: 70px;
            left: 700px;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    

    <!-- Logo Panel placeholder -->
    <div id="logo-panel"></div>

    <div class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <br>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>

    

</body>
</html>
