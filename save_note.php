<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_POST['note_date']) || !isset($_POST['note'])) {
    http_response_code(400);
    echo "Invalid request.";
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'simple_auth');
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit();
}

$username = $_SESSION['username'];
$note_date = $_POST['note_date'];
$note = $_POST['note'];

$user_result = $conn->query("SELECT id FROM users WHERE username = '$username'");
$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// Check if note already exists for this date and user
$existing = $conn->query("SELECT id FROM calendar_notes WHERE user_id = $user_id AND note_date = '$note_date'");
if ($existing->num_rows > 0) {
    // Update existing note
    $stmt = $conn->prepare("UPDATE calendar_notes SET note = ? WHERE user_id = ? AND note_date = ?");
    $stmt->bind_param("sis", $note, $user_id, $note_date);
} else {
    // Insert new note
    $stmt = $conn->prepare("INSERT INTO calendar_notes (user_id, note_date, note) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $note_date, $note);
}

if ($stmt->execute()) {
    echo "Note saved successfully.";
} else {
    http_response_code(500);
    echo "Failed to save note.";
}

$conn->close();
?>
<script>
function showNoteForm(date) {
    document.getElementById('note-date').innerText = date;
    document.getElementById('note-date-input').value = date;
    document.getElementById('note-text').value = ''; // clear previous input
    document.getElementById('note-form').style.display = 'block';
}

function saveNote() {
    const date = document.getElementById('note-date-input').value;
    const note = document.getElementById('note-text').value;

    if (!note) {
        alert("Please enter a note.");
        return;
    }

    fetch('save_note.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `note_date=${encodeURIComponent(date)}&note=${encodeURIComponent(note)}`
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // reload to reflect updated notes
    })
    .catch(err => alert("Error: " + err));
}
</script>
