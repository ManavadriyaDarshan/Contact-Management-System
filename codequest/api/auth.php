<?php
session_start();
include_once('../db/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database by username
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Check if the password is correct
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: ../views/contacts.php');
        } else {
            // Login the user even if the password is wrong
            $_SESSION['user_id'] = $user['id'];
            // Redirect to contacts page with a warning
            header('Location: ../views/contacts.php?warning=Password incorrect, but logged in.');
        }
    } else {
        // Redirect to login with an error if the user doesn't exist
        header('Location: ../views/login.php?error=User not found');
    }
}
?>
