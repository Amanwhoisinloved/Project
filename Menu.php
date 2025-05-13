<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            display: flex;
            min-height: 100vh;
            background: #fdf6f0 url("bg3.jpg") no-repeat center center/cover;
            overflow-x: hidden;
        }
        .sidebar-left {
            width: 240px;
            background-color: transparent;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar-left h2 {
            color: #333;
            margin-bottom: 40px;
        }

        .sidebar-left ul {
            list-style: none;
            padding: 0;
        }

        .sidebar-left ul li {
            margin-bottom: 20px;
        }

        .sidebar-left ul li a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar-left ul li a:hover,
        .sidebar-left ul li a.active {
            background-color: #ffeef4;
            color: #d63384;
        }

        .sidebar-left ul li i {
            margin-right: 10px;
        }

        .main {
            margin-left: 260px;
            margin-right: 240px;
            padding: 40px;
            flex: 1;
        }

        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>

<div class="sidebar-left">
    <h2>My Calendar</h2>
    <ul>
        <li><a href="dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="Calendar.php" class="<?= ($currentPage == 'Calendar.php') ? 'active' : '' ?>"><i class="fas fa-calendar"></i> Calendar</a></li>
        <li><a href="menu.php" class="<?= ($currentPage == 'menu.php') ? 'active' : '' ?>"><i class="fas fa-bars"></i> Menu</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main">
    <h1>This too shall pass</h1>
</div>

</body>
</html>
