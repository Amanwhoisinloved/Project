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
        .day.today {
    background-color: #d63384;
    color: white;
    font-weight: bold;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);

    
}
.holiday {
    font-size: 0.75em;
    font-weight: bold;
    color: #e63946;
    background-color: #fff3f3;
    padding: 4px 6px;
    border-left: 4px solid #e63946;
    border-radius: 5px;
    margin-top: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.day.holiday-day {
    background-color: #ffe6e6;
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
    position: absolute;
    display: none;
    background: #fff0f5;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    max-width: 300px;
    width: 90%;
    z-index: 1000;
    transition: all 0.3s ease;
    border: 2px solid #f8c8dc;
    font-family: 'Nunito', sans-serif;
}

.note-form h3 {
    margin: 0 0 10px;
    color: #d63384;
    font-size: 1.2em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.note-form textarea {
    width: 100%;
    height: 100px;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    resize: vertical;
    font-size: 0.95em;
    background: #fff;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s ease;
}

.note-form textarea:focus {
    border-color: #d63384;
}

.note-form button {
    margin-top: 12px;
    padding: 10px;
    background: #d63384;
    color: white;
    border: none;
    border-radius: 30px;
    font-weight: bold;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 1em;
}

.note-form button:hover {
    background-color: #b42b6c;
}

.note-form.show {
    animation: fadeIn 0.3s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
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
    <p>No new reminders for now.</p>
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

    $holidays = [
        "$current_year-01-01" => "New Year's Day",
        "$current_year-04-09" => "Araw ng Kagitingan",
        "$current_year-05-01" => "Labor Day",
        "$current_year-06-12" => "Independence Day",
        "$current_year-08-21" => "Ninoy Aquino Day",
        "$current_year-11-01" => "All Saints' Day",
        "$current_year-11-30" => "Bonifacio Day",
        "$current_year-12-25" => "Christmas Day",
        "$current_year-12-30" => "Rizal Day",
        // You can add movable holidays manually or dynamically
    ];
    
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

    $note_preview = isset($notes[$current_day]) 
        ? "<div class='note'>" . htmlspecialchars(substr($notes[$current_day], 0, 20)) . "...</div>" 
        : "";

    $holiday_label = isset($holidays[$current_day]) 
        ? "<div class='holiday'>" . htmlspecialchars($holidays[$current_day]) . "</div>" 
        : "";

    $today_class = $current_day == date('Y-m-d') ? 'today' : '';

    echo "<div class='day $today_class' onclick='showNoteForm(\"$current_day\")'>
            $day
            $holiday_label
            $note_preview
          </div>";
}

        ?>
    </div>

    <!-- Note Form -->
    <<div class="note-form" id="note-form">
    <h3><i class="fas fa-sticky-note"></i> Note for <span id="note-date"></span></h3>
    <input type="hidden" id="note-date-input">
    <textarea id="note-text" placeholder="Write your note here..."></textarea>
    <button onclick="saveNote()"><i class="fas fa-save"></i> Save Note</button>
</div>



<script>
function showNoteForm(date) {
    const form = document.getElementById('note-form');
    const dayElements = document.querySelectorAll('.day');

    const clickedDay = Array.from(dayElements).find(el => el.textContent.trim().startsWith(date.split('-')[2].replace(/^0/, '')));
    if (!clickedDay) return;

    document.getElementById('note-date').innerText = date;
    document.getElementById('note-date-input').value = date;

    const rect = clickedDay.getBoundingClientRect();
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const scrollLeft = window.scrollX || document.documentElement.scrollLeft;

    form.style.top = (rect.top + scrollTop + 10) + 'px';
    form.style.left = (rect.left + scrollLeft + 10) + 'px';

    form.classList.add('show');
    form.style.display = 'block';
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

window.addEventListener('click', function(e) {
    const form = document.getElementById('note-form');
    if (!form.contains(e.target) && !e.target.classList.contains('day')) {
        form.style.display = 'none';
    }
});

</script>

</body>
</html>

<?php $conn->close(); ?>
