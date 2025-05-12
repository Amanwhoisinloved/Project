<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logo</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            background-image: url("images/bg3.jpg");
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        img.logo {
            width: 500px;
            margin-bottom: 20px;
        }

        .title {
            font-weight: bold;
            font-size: 36px;
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            font-size: 20px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            color: white;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #4CAF50;
        }

        
    </style>
</head>
<body>

    <img src="images/logo.png" alt="Logo" class="logo">
    <p>Memo. Memories. Nostalgia.</p>
    <div class="nav-links">
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>
    </div>

</body>
</html>
