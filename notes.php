<?php
session_start();
include("connection.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Retrieve user's notes from the database
$query = "SELECT id, note_text FROM notes WHERE user_id=(SELECT id FROM users WHERE username=?)";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $username; ?></h2>
        <h3>Your Notes:</h3>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <?php echo $row['note_text']; ?>
                    <a href="edit_note.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_note.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="add_note.php">Add Note</a>
        <br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
