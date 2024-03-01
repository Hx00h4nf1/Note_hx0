<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note_text = filter_input(INPUT_POST, 'note_text', FILTER_SANITIZE_STRING);
    $username = $_SESSION['username'];

    // Retrieve user ID from the database
    $query = "SELECT id FROM users WHERE username=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_row = $result->fetch_assoc();
    $user_id = $user_row['id'];

    // Insert the note into the database
    $query = "INSERT INTO notes (user_id, note_text) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $user_id, $note_text);
    if ($stmt->execute()) {
        header("Location: notes.php");
        exit();
    } else {
        echo "Error occurred while adding the note.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Note</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
    <h2>Add Note</h2>
    <form method="post" action="">
        <textarea name="note_text" rows="4" cols="50" placeholder="Enter your note"></textarea>
        <br>
        <input type="submit" value="Add Note">
    </form>
    <br>
    <a href="notes.php">Back to Notes</a>
</body>
</html>
