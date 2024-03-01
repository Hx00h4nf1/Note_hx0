<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate username and password
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
    } else {
        // Check if username already exists
        $query = "SELECT * FROM users WHERE username=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists. Please choose a different username.";
        } else {
            // Insert user into database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                echo "Registration successful. <a href='login.php'>Login</a> to your account.";
            } else {
                echo "Error occurred. Please try again.";
            }
        }
    }
}
?>
