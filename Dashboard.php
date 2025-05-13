<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'calendardb');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Get user ID
$user_result = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'");
$user_id = $user_result->fetch_assoc()['id'];

// Get all notes for this user
$notes_query = $conn->query("SELECT note_date, note, note_image FROM calendar_notes WHERE user_id = $user_id ORDER BY note_date DESC");
$notes = [];
while ($row = $notes_query->fetch_assoc()) {
    $notes[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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

        .sidebar-left ul li a:hover {
            background-color: #ffeef4;
            color: #d63384;
        }

        .sidebar-left ul li i {
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 40px;
            flex: 1;
            max-width: 900px;
        }

        h1, h2, h3 {
            color: #333;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .dashboard-card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .dashboard-card h3 {
            margin-top: 0;
            color: #d63384;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .note-card {
            background-color: #fff0f5;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #d63384;
        }
        
        .note-date {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .note-text {
            margin-bottom: 10px;
        }
        
        .note-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }
        
        .notes-container {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .summary-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }
        
        .pink-bg {
            background-color: #d63384;
        }
        
        .blue-bg {
            background-color: #0d6efd;
        }
        
        .green-bg {
            background-color: #198754;
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #888;
        }
        
        .empty-state i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* Gallery styling */
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .gallery-item {
            width: 48%;
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        .caption {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            border-radius: 4px;
        }

    </style>
</head>
<body>

<div class="sidebar-left">
    <h2>My Calendar</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="Calendar.php"><i class="fas fa-calendar"></i> Calendar</a></li>
        <li><a href="menu.php"><i class="fas fa-bars"></i> Menu</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3><i class="fas fa-chart-pie"></i> Summary</h3>
            
            <div class="summary-item">
                <div class="summary-icon pink-bg">
                    <i class="fas fa-clipboard"></i>
                </div>
                <div>
                    <h4>Total Notes</h4>
                    <p><?= count($notes) ?></p>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon blue-bg">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <h4>Upcoming Notes</h4>
                    <p>To be shown in calendar view</p>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon green-bg">
                    <i class="fas fa-image"></i>
                </div>
                <div>
                    <h4>With Images</h4>
                    <p><?php 
                        $with_images = 0;
                        foreach ($notes as $note) {
                            if (!empty($note['note_image'])) $with_images++;
                        }
                        echo $with_images;
                    ?></p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card" style="grid-column: span 2;">
            <h3><i class="fas fa-history"></i> Recent Notes</h3>
            <div class="notes-container">
                <?php if (count($notes) > 0): ?>
                    <?php 
                    // Display only the 5 most recent notes
                    $recent_notes = array_slice($notes, 0, 5);
                    foreach ($recent_notes as $note): 
                    ?>
                        <div class="note-card">
                            <div class="note-date"><?= date('F j, Y', strtotime($note['note_date'])) ?></div>
                            <div class="note-text"><?= nl2br(htmlspecialchars($note['note'])) ?></div>
                            <?php if (!empty($note['note_image'])): ?>
                                <img class="note-image" src="<?= htmlspecialchars($note['note_image']) ?>" alt="Note Image">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="far fa-sticky-note"></i>
                        <p>No notes found. Add notes from the calendar page.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-card">
            <h3><i class="fas fa-image"></i> Image Gallery</h3>
            <div class="gallery">
                <?php foreach ($notes as $note): ?>
                    <?php if (!empty($note['note_image'])): ?>
                        <div class="gallery-item">
                            <img src="<?= htmlspecialchars($note['note_image']) ?>" alt="Note Image">
                            <div class="caption"><?= nl2br(htmlspecialchars($note['note'])) ?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
