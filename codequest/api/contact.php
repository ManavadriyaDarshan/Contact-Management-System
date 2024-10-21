<?php
session_start();
include_once('../db/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$name = '';
$email = '';
$phone = '';
$tags = '';
$contactId = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $tags = $_POST['tags'];
    $contactId = isset($_POST['id']) ? $_POST['id'] : null;

    // Validate phone number
    if (empty($phone)) {
        $_SESSION['message'] = 'Please enter a phone number.';
        header('Location: ' . $_SERVER['PHP_SELF']); // Redirect back to the form
        exit();
    }

    if ($contactId) {
        // Update contact
        $stmt = $conn->prepare('UPDATE contacts SET name = ?, email = ?, phone = ?, tags = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$name, $email, $phone, $tags, $contactId, $user_id]);
    } else {
        // Add contact
        $stmt = $conn->prepare('INSERT INTO contacts (name, email, phone, tags, user_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, $tags, $user_id]);
    }
    header('Location: ../views/contacts.php');
    exit();
}

// Handle contact deletion
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $stmt = $conn->prepare('DELETE FROM contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['delete'], $user_id]);
    header('Location: ../views/contacts.php');
    exit();
}

// If editing, fetch existing contact data
if (isset($_GET['id'])) {
    $contactId = $_GET['id'];
    $stmt = $conn->prepare('SELECT name, email, phone, tags FROM contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$contactId, $user_id]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contact) {
        $name = $contact['name'];
        $email = $contact['email'];
        $phone = $contact['phone'];
        $tags = $contact['tags'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $contactId ? 'Edit Contact' : 'Add Contact' ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            const phone = document.getElementById("phone").value;
            if (!phone) {
                alert("Please enter a phone number.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><?= $contactId ? 'Edit Contact' : 'Add Contact' ?></h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($phone); ?>" required>
            </div>
            <div class="form-group">
                <label for="tags">Tags:</label>
                <input type="text" class="form-control" id="tags" name="tags" value="<?= htmlspecialchars($tags); ?>">
            </div>
            <input type="hidden" name="id" value="<?= htmlspecialchars($contactId); ?>">
            <button type="submit" class="btn btn-primary">Save Contact</button>
            <a href="../views/contacts.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
