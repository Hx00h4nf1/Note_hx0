<?php
session_start();
include("connection.php");

$note_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($note_id) {
    // Delete the note from the database
    $query = "DELETE FROM notes WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $note_id);
    if ($stmt->execute()) {
        header("Location: notes.php");
        exit();
    } else {
        echo "Error occurred while deleting the note.";
    }
} else {
    echo "Invalid note ID.";
}
?>
