<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'simple_auth');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$current_month = isset($_GET['month']) ? $_GET['month'] : date('m');
$current_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$first_day_of_month = date('Y-m-01', strtotime("$current_year-$current_month-01"));
$last_day_of_month = date('Y-m-t', strtotime("$current_year-$current_month-01"));

// Get the days of the month
$days_in_month = date('t', strtotime("$current_year-$current_month-01"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calendar</title>
    <style>
.note {
    font-size: 0.75em;
    color: #333;
    margin-top: 5px;
    background-color: #fffdd0;
    border-radius: 5px;
    padding: 2px 4px;
    max-height: 50px;
    overflow: hidden;
}

body {
    background-color: #ffe4e1;
    font-family: Arial, sans-serif;
    padding: 20px;
    margin: 0;
}

h1, h2 {
    text-align: center;
}

.calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
    max-width: 900px;
    margin: 20px auto;
}

.calendar-header {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    padding: 10px;
    text-align: center;
}

.calendar .day {
    min-height: 80px;
    padding: 5px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    text-align: left;
    vertical-align: top;
    position: relative;
    cursor: pointer;
}

.calendar .day:hover {
    background-color: #e0ffe0;
}

.note {
    font-size: 0.75em;
    margin-top: 5px;
    background: #fffdd0;
    border-radius: 4px;
    padding: 3px 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

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
    cursor: pointer;
    width: 100%;
}

.note-form button:hover {
    background-color: #45a049;
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

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <h2>Calendar for <?php echo date('F Y', strtotime("$current_year-$current_month-01")); ?></h2>
    <?php
// Calculate previous and next month
$prev_month_ts = strtotime("-1 month", strtotime("$current_year-$current_month-01"));
$next_month_ts = strtotime("+1 month", strtotime("$current_year-$current_month-01"));

$prev_month = date('m', $prev_month_ts);
$prev_year = date('Y', $prev_month_ts);
$next_month = date('m', $next_month_ts);
$next_year = date('Y', $next_month_ts);
?>

<div style="text-align: center; margin: 20px 0;">
    <a href="?month=<?=$prev_month?>&year=<?=$prev_year?>" style="margin-right: 30px; font-size: 1.2em;">← Previous</a>
    <a href="?month=<?=$next_month?>&year=<?=$next_year?>" style="font-size: 1.2em;">Next →</a>
</div>


    <!-- Calendar -->
    <div class="calendar">
    <h2>Calendar for <?php echo date('F Y', strtotime("$current_year-$current_month-01")); ?></h2>

        <div class="calendar-header">Sun</div>
        <div class="calendar-header">Mon</div>
        <div class="calendar-header">Tue</div>
        <div class="calendar-header">Wed</div>
        <div class="calendar-header">Thu</div>
        <div class="calendar-header">Fri</div>
        <div class="calendar-header">Sat</div>

        <?php
$first_day_weekday = date('w', strtotime($first_day_of_month)); // 0 (Sun) to 6 (Sat)

// Fill empty cells before the first day
for ($i = 0; $i < $first_day_weekday; $i++) {
    echo "<div></div>";
}

// Fetch notes in current month
$user_result = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'");
$user = $user_result->fetch_assoc();
$user_id = $user['id'];

$notes_query = $conn->query("SELECT note_date, note FROM calendar_notes WHERE user_id = $user_id AND note_date BETWEEN '$first_day_of_month' AND '$last_day_of_month'");
$notes = [];
while ($row = $notes_query->fetch_assoc()) {
    $notes[$row['note_date']] = $row['note'];
}

// Fill actual days
for ($day = 1; $day <= $days_in_month; $day++) {
    $current_day = date('Y-m-d', strtotime("$current_year-$current_month-$day"));
    $note_preview = isset($notes[$current_day]) ? "<div class='note'>" . htmlspecialchars(substr($notes[$current_day], 0, 20)) . "...</div>" : "";
    echo "<div class='day' onclick='showNoteForm(\"$current_day\")'>$day $note_preview</div>";
}
?>

    

    <!-- Note Form -->
    <div class="note-form" id="note-form" style="display:none;">
    <h3>Enter your note for <span id="note-date"></span></h3>
    <input type="hidden" id="note-date-input">
    <textarea id="note-text" placeholder="Write your note here..." required></textarea><br>
    <button onclick="saveNote()">Save Note</button>
</div>


    <script>
        // Show the note input form when a day is clicked
        function showNoteForm(date) {
            document.getElementById('note-date').innerText = date;
            document.getElementById('note-date-input').value = date;
            document.getElementById('note-form').style.display = 'block';
        }
    </script>

    <?php
    // Handle note saving
    if (isset($_POST['save_note'])) {
        $note_date = $_POST['note_date'];
        $note = $_POST['note'];
        $user_id = $_SESSION['username']; // Use username or user ID for association

        // Get user ID
        $user_result = $conn->query("SELECT id FROM users WHERE username = '$user_id'");
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];

        // Insert the note into the database
        $stmt = $conn->prepare("INSERT INTO calendar_notes (user_id, note_date, note) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $note_date, $note);

        if ($stmt->execute()) {
            echo "<p>Note saved successfully for $note_date!</p>";
        } else {
            echo "<p>Error saving note.</p>";
        }
    }
    ?>

</body>
</html>

<?php
$conn->close();
?>
