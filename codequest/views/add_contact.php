<?php
session_start();
include_once('../db/db.php');

$contact = ['name' => '', 'email' => '', 'phone' => '', 'tags' => ''];
if (isset($_GET['id'])) {
    $stmt = $conn->prepare('SELECT * FROM contacts WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $contact = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title><?= isset($_GET['id']) ? 'Edit Contact' : 'Add Contact'; ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative; /* Positioning context for the title */
        }

        h1 {
            text-align: center;
            color: #333;
            position: absolute; /* Absolute positioning for overlap */
            left: 50%;
            transform: translateX(-50%); /* Center the title horizontally */
            z-index: 0; /* Position it behind the card */
            font-size: 2rem; /* Adjust font size as needed */
            opacity: 1; /* Make title semi-transparent for effect */
            top: 20px; /* Adjust this value to control how much of the title is visible */
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px; /* Maintain the width */
            transition: transform 0.2s;
            z-index: 1; /* Ensure card is on top of the title */
            position: relative; /* Position context for z-index */
            margin-top: 60px; /* Adjust the top margin to push card down */
            overflow: hidden; /* Hide overflow for a clean cut effect */
        }

        .card:hover {
            transform: scale(1.02); /* Slight hover effect */
        }

        form {
            margin: 0; /* Remove margin from the form */
        }

        form div {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #5cb85c; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-bottom: 10px; /* Space between buttons */
        }

        button:hover {
            background-color: #4cae4c; /* Darker shade on hover */
        }

        .cancel-button {
            background-color: #dc3545; /* Red color for cancel button */
        }

        .cancel-button:hover {
            background-color: #c82333; /* Darker red on hover */
        }
    </style>
    <script>
        function cancel() {
            window.location.href = 'contacts.php'; // Redirect to contacts.php
        }
    </script>
</head>
<body>
    <h1><?= isset($_GET['id']) ? 'Edit Contact' : 'Add Contact'; ?></h1>
    <div class="card">
        <form action="../api/contact.php" method="POST">
            <?php if (isset($_GET['id'])): ?>
            <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
            <?php endif; ?>
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($contact['name']); ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($contact['email']); ?>">
            </div>
            <div>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($contact['phone']); ?>">
            </div>
            <div>
                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" value="<?= htmlspecialchars($contact['tags']); ?>">
            </div>
            <button type="submit">Save</button>
            <button type="button" class="cancel-button" onclick="cancel()">Cancel</button> <!-- Cancel Button -->
        </form>
    </div>
</body>
</html>
