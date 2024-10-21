<?php
include_once('../db/db.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['vcf_file'])) {
    $file = $_FILES['vcf_file']['tmp_name'];

    // Read the VCF file
    $vcfData = file_get_contents($file);

    // Parse the VCF data
    $lines = explode("\n", $vcfData);
    $currentContact = [];
    $user_id = $_SESSION['user_id'];

    foreach ($lines as $line) {
        $line = trim($line);

        if (strpos($line, 'BEGIN:VCARD') !== false) {
            $currentContact = [];
        } elseif (strpos($line, 'FN:') !== false) {
            $currentContact['name'] = substr($line, 3);
        } elseif (strpos($line, 'TEL;') !== false) {
            $currentContact['phone'] = substr($line, strpos($line, ':') + 1);
        } elseif (strpos($line, 'EMAIL:') !== false) {
            $currentContact['email'] = substr($line, 6);
        } elseif (strpos($line, 'NOTE:Tags=') !== false) {
            $currentContact['tags'] = substr($line, 10); // Extract tags from the note field
        } elseif (strpos($line, 'END:VCARD') !== false) {
            // Insert contact into the database
            $stmt = $conn->prepare('INSERT INTO contacts (user_id, name, phone, email, tags) VALUES (:user_id, :name, :phone, :email, :tags)');
            $stmt->execute([
                ':user_id' => $user_id,
                ':name' => $currentContact['name'] ?? '',
                ':phone' => $currentContact['phone'] ?? '',
                ':email' => $currentContact['email'] ?? '',
                ':tags' => $currentContact['tags'] ?? ''
            ]);
        }
    }

    // Redirect back to the contacts page
    header('Location: ../views/contacts.php?message=Contacts imported successfully');
    exit;
}
?>
