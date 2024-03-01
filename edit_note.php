<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note_id = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
    $note_text = filter_input(INPUT_POST, 'note_text', FILTER_SANITIZE_STRING);

    // Update the note in the database
    $query = "UPDATE notes SET note_text=? WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $note_text, $note_id);
    if ($stmt->execute()) {
        header("Location: notes.php");
        exit();
    } else {
        echo "Error occurred while updating the note.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Note</h2>
        <?php
        $note_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($note_id) {
            // Retrieve the note from the database
            $query = "SELECT note_text FROM notes WHERE id=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $note_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $note_text = $row['note_text'];
            ?>
            <form method="post" action="">
                <input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
                <textarea name="note_text" rows="4" cols="50"><?php echo $note_text; ?></textarea>
                <br>
                <button type="submit">Save Changes</button>
            </form>
            <?php
        } else {
            echo "Invalid note ID.";
        }
        ?>
        <br>
        <a href="notes.php">Back to Notes</a>
    </div>
</body>
</html>
