<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "Not logged in.";
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'simple_auth');
if ($conn->connect_error) {
    echo "Database connection failed.";
    exit;
}

$note_date = $_POST['note_date'];
$note = $_POST['note'];

$username = $_SESSION['username'];
$user_result = $conn->query("SELECT id FROM users WHERE username = '$username'");
$user = $user_result->fetch_assoc();
$user_id = $user['id'];

// Check if a note already exists
$check = $conn->query("SELECT id FROM calendar_notes WHERE user_id = $user_id AND note_date = '$note_date'");

if ($check->num_rows > 0) {
    // Update note
    $stmt = $conn->prepare("UPDATE calendar_notes SET note = ? WHERE user_id = ? AND note_date = ?");
    $stmt->bind_param("sis", $note, $user_id, $note_date);
} else {
    // Insert new note
    $stmt = $conn->prepare("INSERT INTO calendar_notes (user_id, note_date, note) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $note_date, $note);
}

if ($stmt->execute()) {
    echo "Note saved!";
} else {
    echo "Failed to save note.";
}

$conn->close();
?>
