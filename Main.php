<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'simple_auth');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$current_month = $_GET['month'] ?? date('m');
$current_year = $_GET['year'] ?? date('Y');
$first_day_of_month = date('Y-m-01', strtotime("$current_year-$current_month-01"));
$last_day_of_month = date('Y-m-t', strtotime("$current_year-$current_month-01"));
$days_in_month = date('t', strtotime("$current_year-$current_month-01"));
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
            background: #fdf6f0 url("bg1.jpg") no-repeat center center/cover;
            overflow-x: hidden;
        }

        /* Sidebar styles */
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

        /* Right sidebar */
        .sidebar-right {
            width: 220px;
            background-color: #ffeef4;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            box-shadow: -2px 0 10px rgba(0,0,0,0.05);
            color: #333;
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            margin-right: 240px;
            padding: 40px;
            flex: 1;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        /* Calendar Styles */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            max-width: 1000px;
            margin: 20px auto;
            background: transparent;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .calendar-header {
            font-weight: bold;
            padding: 12px;
            text-align: center;
            background: #f8c8dc;
            border-radius: 8px;
            color: #333;
        }

        .day {
            min-height: 100px;
            background-color: #fffdfd;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .day:hover {
            background-color: #ffeef4;
        }

        .note {
            font-size: 0.8em;
            color: #555;
            background: #fff0f5;
            border-left: 4px solid #d63384;
            padding: 5px 8px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Note form */
        .note-form {
            max-width: 400px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .note-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            resize: vertical;
        }

        .note-form button {
            margin-top: 10px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            width: 100%;
        }

        .note-form button:hover {
            background-color: #45a049;

            
        }
        .nav-buttons {
    text-align: center;
    margin: 30px 0;
}

.nav-buttons a {
    display: inline-block;
    background-color: #f8c8dc;
    color: #333;
    padding: 10px 25px;
    margin: 0 10px;
    font-size: 1.1em;
    font-weight: bold;
    border-radius: 30px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.nav-buttons a:hover {
    background-color: #d63384;
    color: #fff;
    transform: translateY(-2px);
}


        @media (max-width: 768px) {
            .calendar {
                grid-template-columns: repeat(2, 1fr);
            }
            .calendar-header {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="sidebar-left">
    <h2>My Calendar</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="menu.php"><i class="fas fa-bars"></i> Menu</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="sidebar-right">
    <h3>Reminders</h3>
    <p>No new reminders.</p>
</div>

<div class="main-content">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2><?= date('F Y', strtotime("$current_year-$current_month-01")); ?></h2>

    <?php
    $prev_month_ts = strtotime("-1 month", strtotime("$current_year-$current_month-01"));
    $next_month_ts = strtotime("+1 month", strtotime("$current_year-$current_month-01"));

    $prev_month = date('m', $prev_month_ts);
    $prev_year = date('Y', $prev_month_ts);
    $next_month = date('m', $next_month_ts);
    $next_year = date('Y', $next_month_ts);
    ?>

<div class="nav-buttons">
    <a href="?month=<?= $prev_month ?>&year=<?= $prev_year ?>">← Previous</a>
    <a href="?month=<?= $next_month ?>&year=<?= $next_year ?>">Next →</a>
</div>


    <div class="calendar">
        <?php
        echo '<div class="calendar-header">Sun</div>';
        echo '<div class="calendar-header">Mon</div>';
        echo '<div class="calendar-header">Tue</div>';
        echo '<div class="calendar-header">Wed</div>';
        echo '<div class="calendar-header">Thu</div>';
        echo '<div class="calendar-header">Fri</div>';
        echo '<div class="calendar-header">Sat</div>';

        $first_day_weekday = date('w', strtotime($first_day_of_month));

        for ($i = 0; $i < $first_day_weekday; $i++) echo "<div></div>";

        $user_result = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'");
        $user_id = $user_result->fetch_assoc()['id'];

        $notes_query = $conn->query("SELECT note_date, note FROM calendar_notes WHERE user_id = $user_id AND note_date BETWEEN '$first_day_of_month' AND '$last_day_of_month'");
        $notes = [];
        while ($row = $notes_query->fetch_assoc()) {
            $notes[$row['note_date']] = $row['note'];
        }

        for ($day = 1; $day <= $days_in_month; $day++) {
            $current_day = date('Y-m-d', strtotime("$current_year-$current_month-$day"));
            $note_preview = isset($notes[$current_day]) ? "<div class='note'>" . htmlspecialchars(substr($notes[$current_day], 0, 20)) . "...</div>" : "";
            echo "<div class='day' onclick='showNoteForm(\"$current_day\")'>$day $note_preview</div>";
        }
        ?>
    </div>

    <!-- Note Form -->
    <div class="note-form" id="note-form" style="display:none;">
        <h3>Enter your note for <span id="note-date"></span></h3>
        <input type="hidden" id="note-date-input">
        <textarea id="note-text" placeholder="Write your note here..."></textarea><br>
        <button onclick="saveNote()">Save Note</button>
    </div>
</div>

<script>
function showNoteForm(date) {
    document.getElementById('note-date').innerText = date;
    document.getElementById('note-date-input').value = date;
    document.getElementById('note-form').style.display = 'block';
}

function saveNote() {
    const noteDate = document.getElementById('note-date-input').value;
    const noteText = document.getElementById('note-text').value;

    if (noteText.trim() === "") {
        alert("Please enter a note.");
        return;
    }

    const formData = new FormData();
    formData.append('note_date', noteDate);
    formData.append('note', noteText);

    fetch('save_note.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        window.location.reload();
    })
    .catch(error => {
        console.error("Error:", error);
    });
}
</script>

</body>
</html>

<?php $conn->close(); ?>
