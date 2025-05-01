<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logo</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            background-image: url("bg1.jpg");
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background: transparent; /* semi-transparent backtrground */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px gray;
            width: 300px;
            text-align: center;
        }

        .nav-buttons {
            margin-top: 20px;
        }

        .nav-buttons button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: white;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }

        .nav-buttons button:hover {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div style="font-weight: bold; font-size: 32px;">CALENDAR CHOCHO</div>
    <div class="nav-buttons">
        <button onclick="window.location.href='login.php'">Login</button>
        <button onclick="window.location.href='signup.php'">Signup</button>
    </div>
</div>

</body>
</html>
