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

// Get the current month and year
$current_month = date('m');
$current_year = date('Y');
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
        /* Basic calendar styling */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 10px;
            margin-top: 20px;
        }
        .calendar div {
            padding: 10px;
            background-color: #f9f9f9;
            text-align: center;
            border: 1px solid #ccc;
        }
        .calendar-header {
            font-weight: bold;
            background-color: #4CAF50;
            color: white;
        }
        .calendar .day {
            cursor: pointer;
        }
        .note-form {
            margin-top: 20px;
        }
        .note-form input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
        }
        .note-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .note-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <h2>Calendar for <?php echo date('F Y', strtotime("$current_year-$current_month-01")); ?></h2>

    <!-- Calendar -->
    <div class="calendar">
        <div class="calendar-header">Sun</div>
        <div class="calendar-header">Mon</div>
        <div class="calendar-header">Tue</div>
        <div class="calendar-header">Wed</div>
        <div class="calendar-header">Thu</div>
        <div class="calendar-header">Fri</div>
        <div class="calendar-header">Sat</div>

        <?php
        // Display the days in the current month
        for ($day = 1; $day <= $days_in_month; $day++) {
            $current_day = date('Y-m-d', strtotime("$current_year-$current_month-$day"));
            echo "<div class='day' id='day-$day' onclick='showNoteForm(\"$current_day\")'>$day</div>";
        }
        ?>
    </div>

    <!-- Note Form -->
    <div class="note-form" id="note-form" style="display:none;">
        <h3>Enter your note for <span id="note-date"></span></h3>
        <form method="POST">
            <input type="hidden" name="note_date" id="note-date-input">
            <textarea name="note" placeholder="Write your note here..." required></textarea><br>
            <button type="submit" name="save_note">Save Note</button>
        </form>
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
