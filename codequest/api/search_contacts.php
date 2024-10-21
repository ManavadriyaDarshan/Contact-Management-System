<?php
session_start();
include_once('../db/db.php');

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Prepare and execute the SQL statement to search for contacts
$stmt = $conn->prepare('SELECT * FROM contacts WHERE user_id = ? AND (name LIKE ? OR email LIKE ? OR phone LIKE ? OR tags LIKE ?) ORDER BY name');
$searchTerm = '%' . $query . '%'; // Prepare the search term for wildcard matching
$stmt->execute([$_SESSION['user_id'], $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
$contacts = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>
    <div class="container mt-4">
        <h1>Search Results for "<?= htmlspecialchars($query); ?>"</h1>
        
        <a href="../views/contacts.php" class="btn btn-secondary mb-3">Back to Contacts</a>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($contacts) > 0): ?>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['name']); ?></td>
                        <td><?= htmlspecialchars($contact['email']); ?></td>
                        <td><?= htmlspecialchars($contact['phone']); ?></td>
                        <td><?= htmlspecialchars($contact['tags']); ?></td>
                        <td>
                            <a href="add_contact.php?id=<?= $contact['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="../api/contact.php?delete=<?= $contact['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No contacts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
