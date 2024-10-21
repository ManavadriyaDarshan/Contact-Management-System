<?php
session_start();
include_once('../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mergeOption = $_POST['merge_option'];

    if ($mergeOption === 'name') {
        // Fetch duplicates based on name
        $stmt = $conn->prepare('SELECT name, GROUP_CONCAT(id) AS ids, GROUP_CONCAT(COALESCE(email, \'\')) AS emails, GROUP_CONCAT(phone) AS phones, GROUP_CONCAT(tags) AS tags
                                FROM contacts WHERE user_id = ? 
                                GROUP BY name HAVING COUNT(*) > 1');
        $stmt->execute([$_SESSION['user_id']]);
        $duplicates = $stmt->fetchAll();
    } elseif ($mergeOption === 'number') {
        // Fetch duplicates based on phone number
        $stmt = $conn->prepare('SELECT phone, GROUP_CONCAT(id) AS ids, GROUP_CONCAT(name) AS names, GROUP_CONCAT(COALESCE(email, \'\')) AS emails, GROUP_CONCAT(tags) AS tags
                                FROM contacts WHERE user_id = ? 
                                GROUP BY phone HAVING COUNT(*) > 1');
        $stmt->execute([$_SESSION['user_id']]);
        $duplicates = $stmt->fetchAll();
    } elseif ($mergeOption === 'email') {
        // Fetch duplicates based on email
        $stmt = $conn->prepare('SELECT email, GROUP_CONCAT(id) AS ids, GROUP_CONCAT(name) AS names, GROUP_CONCAT(phone) AS phones, GROUP_CONCAT(tags) AS tags
                                FROM contacts WHERE user_id = ? 
                                GROUP BY email HAVING COUNT(*) > 1');
        $stmt->execute([$_SESSION['user_id']]);
        $duplicates = $stmt->fetchAll();
    } else {
        $_SESSION['message'] = 'Invalid merge option selected!';
        $_SESSION['message_type'] = 'warning'; // Set message type
        header('Location: ../views/contacts.php');
        exit();
    }

    if ($duplicates) {
        foreach ($duplicates as $duplicate) {
            // Get the IDs of the duplicate contacts
            $ids = explode(',', $duplicate['ids']);
            
            // Use the first contact's details as the main record
            $mainId = array_shift($ids); // Get the ID to keep
            
            // Get all emails, filtering out empty values
            $emailList = array_filter(explode(',', $duplicate['emails']), function ($email) {
                return !empty($email); // Keep only non-empty emails
            });

            // Log the emails retrieved for debugging
            error_log("Emails Retrieved: " . json_encode($emailList));
            
            // Get the first non-empty email
            $mainEmail = !empty($emailList) ? reset($emailList) : null; // Get the first non-empty email
            
            // Log the selected main email
            error_log("Main Email Selected: " . ($mainEmail !== null ? $mainEmail : 'null'));
            
            // Get all phone numbers and filter out empty values
            $phoneNumbers = array_filter(explode(',', $duplicate['phones']));
            $mainPhone = !empty($phoneNumbers) ? reset($phoneNumbers) : null; // Get the first non-empty phone
            
            // Get the tags and remove duplicates
            $mainTags = explode(',', $duplicate['tags']);
            $mainTags = array_unique($mainTags);
            $mainTagsString = implode(',', $mainTags);

            // Debugging: Print the values before update
            error_log("Merging: Main ID = $mainId, Email = $mainEmail, Phone = $mainPhone, Tags = $mainTagsString");

            // Update the main contact
            $stmtUpdate = $conn->prepare('UPDATE contacts SET email = ?, phone = ?, tags = ? WHERE id = ?');
            $stmtUpdate->execute([$mainEmail, $mainPhone, $mainTagsString, $mainId]);

            // Delete the remaining duplicate contacts
            if (!empty($ids)) {
                $stmtDelete = $conn->prepare('DELETE FROM contacts WHERE id IN (' . implode(',', $ids) . ')');
                $stmtDelete->execute();
            }
        }
        $_SESSION['message'] = 'Duplicates merged successfully!';
        $_SESSION['message_type'] = 'success'; // Set message type
    } else {
        $_SESSION['message'] = 'No duplicates found!';
        $_SESSION['message_type'] = 'info'; // Set message type
    }
} else {
    $_SESSION['message'] = 'Invalid request method!';
    $_SESSION['message_type'] = 'warning'; // Set message type
}

header('Location: ../views/contacts.php');
exit();
?>
