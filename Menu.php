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
            background: url("images/bgwow.png") no-repeat center center/cover;
            overflow-x: hidden;
        }
       .sidebar-left {
    width: 240px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    padding: 30px 20px;
    background-color: rgba(255, 255, 255, 0.15); 
    backdrop-filter: blur(30px); 
    -webkit-backdrop-filter: blur(10px); 
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); 
    border-right: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar-left ul {
    list-style: none;
    padding: 0;
    margin-left: 4%;
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

.sidebar-left ul li a:hover {
    background-color: rgb(255, 255, 255);
    color: #3399ff;
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
    <img src="images/logo.png" alt="My Calendar" style="width: 180%; max-width: 250px; margin-bottom:40px; margin-top:20px; display: block;">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="Calendar.php"><i class="fas fa-calendar"></i> Calendar</a></li>
        <li><a href="menu.php"><i class="fas fa-bars"></i> Menu</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main">
    <h1>This too shall pass</h1>
</div>

</body>
</html>
