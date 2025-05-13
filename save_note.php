<?php
include 'db.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $note_date = $_POST['note_date'];
    $note = $_POST['note'];
    $note_image = $_FILES['note_image'];

    // Handle the image upload
    $image_path = null;
    if ($note_image['error'] == 0) {
        $target_dir = "uploads/"; // The directory to store the uploaded images
        $image_name = basename($note_image["name"]);
        $image_path = $target_dir . $image_name;

        // Check if the directory exists, if not create it
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($note_image["tmp_name"], $image_path)) {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Get the user ID
    $user_result = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'");
    $user_id = $user_result->fetch_assoc()['id'];

    // Save the note and image path to the database
    $stmt = $conn->prepare("INSERT INTO calendar_notes (user_id, note_date, note, note_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $note_date, $note, $image_path);
    $stmt->execute();

    echo "Note and image saved successfully!";
    $stmt->close();
}

$conn->close();
?>
