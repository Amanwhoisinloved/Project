<?php

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
      
      
        @font-face {
            font-family: 'Retropix';
            src: url('fonts/retropix.ttf') format('truetype');
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;

            background: url('images/bgwow.png') center/cover no-repeat;
            filter: blur(15px);
            z-index: -2;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .pixel-font {
            color: white;
            font-family: 'Retropix', sans-serif;
            stroke-width: 5%;
            stroke: black;
            stroke-opacity: 1; /* 100% opacity */
            text-shadow: 0px 4px 0px rgba(0, 0, 0, 0.8); /* Vertical shadow */
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
            margin-left:4%;
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
             background-color:rgb(255, 255, 255);
            color: #3399ff;
        }

        .sidebar-left ul li i {
            margin-right: 10px;
        }
        
.nav-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin: 30px 0;
}

.nav-buttons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    border-radius: 50%;
    background-color: transparent;
    transition: transform 0.3s ease;
}

.nav-buttons a:hover {
    transform: scale(1.15);
}

.nav-buttons img {
    height: 55px;
    width: 55px;
    transition: transform 0.3s ease, filter 0.3s ease;
    filter: drop-shadow(0 3px 5px rgba(0,0,0,0.1));
}

.nav-buttons a:hover img {
    transform: scale(1.25);
    filter: drop-shadow(0 5px 8px rgba(0,0,0,0.2));
}



        /* Main content */
     .main-content {
    padding: 40px;
    flex: 1;
    margin-left: 240px;
    justify-content: flex-start;
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow-y: auto;
    min-height: 100vh;
    box-sizing: border-box;
}



        h1, h2 {
            text-align: center;
            color: #333;
        }

        /* Calendar Styles */
      .calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 15px;
    width: 100%;
    max-width:  1200px;
    background: #e6f0ff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
       margin: 0 auto;
    
}




       .calendar-header {
    font-weight: bold;
    padding: 10px;
    text-align: center;
    background: #b3d7ff;
    border-radius: 6px;
    color: #004080;
}


   .day {
    min-height: 100px;
    height: 100px; /* Fix height */
    aspect-ratio: 1 / 1;
    background-color: #f8fbff;
    border: 1px solid #cce5ff;
    border-radius: 8px;
    padding: 10px;
    box-sizing: border-box;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    overflow: hidden; /* Prevent content from expanding box */
    position: relative;
}





        .day:hover {
    background-color: #cce5ff;
}

.day.today {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
}



    
.holiday {
    font-size: 0.75em;
    font-weight: bold;
    color: #1c7ed6; /* soft blue */
    background-color: #e0f2ff; /* very light blue */
    padding: 4px 6px;
    border-left: 4px solid #1c7ed6;
    border-radius: 5px;
    margin-top: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.day.holiday-day {
    background-color: #d0ebff;
}

.note, .holiday {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.note {
    font-size: 0.8em;
    color: #1c3d5a;
    background: #e8f6ff;
    border-left: 4px solid #339af0;
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
    background: #e6f4ff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    max-width: 300px;
    width: 90%;
    z-index: 1000;
    transition: all 0.3s ease;
    border: 2px solid #b3daff;
    font-family: 'Nunito', sans-serif;
}

.note-form h3 {
    margin: 0 0 10px;
    color: #339af0;
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
    border: 1px solid #b5d9f6;
    resize: vertical;
    font-size: 0.95em;
    background: #fff;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s ease;
}

.note-form textarea:focus {
    border-color: #339af0;
}

.note-form button {
    margin-top: 12px;
    padding: 10px;
    background: #339af0;
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
    background-color: #1c7ed6;
}

.note-form.show {
    animation: fadeIn 0.3s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}


      @media (max-width: 1024px) {
    .calendar {
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width: 768px) {
    .calendar {
        grid-template-columns: repeat(2, 1fr);
    }
    .sidebar-left,
    .sidebar-right {
        display: none;
    }
    .main-content {
        margin: 0;
        padding: 20px;
    }
}


    h2 {
            color: white;
            font-family: 'Retropix', sans-serif;
            stroke: black;
            font-size: 200%;
            stroke-opacity: 1; 
            text-shadow: 0px 4px 0px rgba(0, 0, 0, 0.8); 
            text-align: center;
            margin-bottom: 20px;
            background-image: url('images/calendarbg.png');
            padding: 10%;
            background-size: cover; 
            background-repeat: no-repeat;
            background-position: center;
            padding: 20px; 
            color: white;

}
    </style>
</head>
<body>

        <div class="sidebar-left">
            <img src="images/logo.png" alt="My Calendar" style="width: 180%; max-width: 250px; margin-bottom:40px; margin-top:20px; display: block;">
            <ul>
         <li><a href="dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="Calendar.php" class="<?= ($currentPage == 'Calendar.php') ? 'active' : '' ?>"><i class="fas fa-calendar"></i> Calendar</a></li>
        <li><a href="menu.php" class="<?= ($currentPage == 'menu.php') ? 'active' : '' ?>"><i class="fas fa-bars"></i> Menu</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>


<div class="main-content">
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
    <a href="?month=<?= $prev_month ?>&year=<?= $prev_year ?>">
        <img src="images/leftarrow.png" alt="Previous">
    </a>
    <a href="?month=<?= $next_month ?>&year=<?= $next_year ?>">
        <img src="images/rightarrow.png" alt="Next">
    </a>
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

$image_preview = isset($notes[$current_day]['note_image']) && !empty($notes[$current_day]['note_image']) 
    ? "<img src='" . htmlspecialchars($notes[$current_day]['note_image']) . "' alt='Note Image' style='width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-top: 8px;'>" 
    : "";

$holiday_label = isset($holidays[$current_day]) 
    ? "<div class='holiday'>" . htmlspecialchars($holidays[$current_day]) . "</div>" 
    : "";

$today_class = $current_day == date('Y-m-d') ? 'today' : '';

echo "<div class='day $today_class' onclick='showNoteForm(\"$current_day\")'>
        $day
        $holiday_label
        $note_preview
        $image_preview
      </div>";
}



        ?>
    </div>

    <!-- Note Form -->
   <div class="note-form" id="note-form">
    <h3><i class="fas fa-sticky-note"></i> Note for <span id="note-date"></span></h3>
    <input type="hidden" id="note-date-input">
    <textarea id="note-text" placeholder="Write your note here..."></textarea>
    <label for="note-image">Upload Image</label>
    <input type="file" id="note-image" accept="image/*">
    <button onclick="saveNote()">Save Note</button>
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
    const noteImage = document.getElementById('note-image').files[0];

    if (noteText.trim() === "" && !noteImage) {
        alert("Please enter a note or select an image.");
        return;
    }

    const formData = new FormData();
    formData.append('note_date', noteDate);
    formData.append('note', noteText);
    if (noteImage) {
        formData.append('note_image', noteImage);
    }

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
