<?php
// Start the session
session_start();

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'calendardb');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("images/bg3.jpg");
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px gray;
            margin-top: 20px;
            width: 300px;
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
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background: #1976D2;
        }
    </style>
</head>
<body>

    <!-- Logo Panel placeholder -->
    <div id="logo-panel"></div>

    <div class="form-container">
        <h2>Signup</h2>
        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Sign Up</button>
        </form>

        <p style="margin-top: 10px;">Already have an account? <a href="login.php">Login</a></p>

        <!-- PHP response messages -->
        <div style="color:red; margin-top:10px;">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                if (empty($username) || empty($password)) {
                    echo "Please fill in all fields.";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("ss", $username, $passwordHash);

                        if ($stmt->execute()) {
                            echo "<span style='color:green;'>Signup successful! <a href='login.php'>Login here</a>.</span>";
                        } else {
                            echo "Error: " . htmlspecialchars($stmt->error);
                        }

                        $stmt->close();
                    } else {
                        echo "Error preparing statement.";
                    }
                }
            }
            ?>
        </div>

    </div>

    
</body>
</html>

<?php
$conn->close();
?>
