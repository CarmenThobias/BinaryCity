<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['link_contact'])) {
        $client_id = $_POST['client_id'];
        $contact_id = $_POST['contact_id'];

        // Check if the link already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM client_contact WHERE client_id = ? AND contact_id = ?");
        $stmt->execute([$client_id, $contact_id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "<p style='color: red;'>This contact is already linked to the client.</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO client_contact (client_id, contact_id) VALUES (?, ?)");
            $stmt->execute([$client_id, $contact_id]);
            echo "<p style='color: green;'>Contact linked successfully.</p>";
        }
    } elseif (isset($_POST['link_client'])) {
        $contact_id = $_POST['contact_id'];
        $client_id = $_POST['client_id'];

        // Check if the link already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM client_contact WHERE client_id = ? AND contact_id = ?");
        $stmt->execute([$client_id, $contact_id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "<p style='color: red;'>This client is already linked to the contact.</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO client_contact (client_id, contact_id) VALUES (?, ?)");
            $stmt->execute([$client_id, $contact_id]);
            echo "<p style='color: green;'>Client linked successfully.</p>";
        }
    }
} elseif (isset($_GET['unlink_contact'])) {
    $client_id = $_GET['client_id'];
    $contact_id = $_GET['contact_id'];

    $stmt = $pdo->prepare("DELETE FROM client_contact WHERE client_id = ? AND contact_id = ?");
    $stmt->execute([$client_id, $contact_id]);
    echo "<p style='color: green;'>Contact unlinked successfully.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Link/Unlink</title>
</head>
<body>
    <p><a href="../views/clients_view.php">Back to Clients View</a></p>
    <p><a href="../views/contacts_view.php">Back to Contacts View</a></p>
</body>
</html>
