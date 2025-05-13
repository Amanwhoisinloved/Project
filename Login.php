<?php
session_start();

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'calendardb');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// 🚫 Prevent already-logged-in users from viewing login.php again
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
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
            $_SESSION['user_id'] = $user['id']; // Add user_id to session
            
            // Redirect to dashboard.php after successful login
            header("Location: dashboard.php");
            exit(); // Important to stop the script after redirect
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
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
        @font-face {
            font-family: 'Retropix';
            src: url('fonts/Retropix.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: 'Retropix', sans-serif;
            background-image: url("images/samplebg.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .form-container {
            animation: popIn 0.6s ease-out;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.6);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            width: 300px;
            height: 80px;
            margin-bottom: 5px;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 26px;
            text-shadow: 0 0 0 rgba(0, 0, 0, 0); /* Fully transparent */
        }

        .error-message {
            color: #d9534f;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            font-family: 'Retropix', sans-serif;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: url('images/woodtexture.png');
            background-size: cover;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-family: 'Retropix', sans-serif;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 0 #5c3c2d;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #4e2f22;
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 30px 20px;
            }

            .form-container h2 {
                font-size: 22px;
            }

            button[type="submit"] {
                font-size: 16px;
            }

            .logo {
                width: 100px;
                height: 60px;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <img src="images/logo.png" alt="Logo" class="logo">
        <h2>Welcome back!</h2>
        <?php if(isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">LOGIN</button>
        </form>
    </div>

</body>
</html>