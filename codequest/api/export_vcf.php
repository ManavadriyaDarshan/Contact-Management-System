<?php
include_once('../db/db.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all contacts for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM contacts WHERE user_id = :user_id');
$stmt->execute([':user_id' => $user_id]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Start generating the vCard content
$vcfContent = '';

foreach ($contacts as $contact) {
    $vcfContent .= "BEGIN:VCARD\r\n";
    $vcfContent .= "VERSION:3.0\r\n";
    $vcfContent .= "FN:" . $contact['name'] . "\r\n";
    if (!empty($contact['phone'])) {
        $vcfContent .= "TEL;TYPE=CELL:" . $contact['phone'] . "\r\n";
    }
    if (!empty($contact['email'])) {
        $vcfContent .= "EMAIL:" . $contact['email'] . "\r\n";
    }
    if (!empty($contact['tags'])) {
        $vcfContent .= "NOTE:Tags=" . $contact['tags'] . "\r\n"; // Storing tags as a note field in vCard
    }
    $vcfContent .= "END:VCARD\r\n";
}

// Set the headers to force a file download
header('Content-Type: text/vcard');
header('Content-Disposition: attachment; filename="contacts.vcf"');

// Output the vCard content
echo $vcfContent;
exit;
?>
