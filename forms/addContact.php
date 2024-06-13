<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_contact'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];

    // Check for duplicate email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contacts WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        echo "<p style='color: red;'>Email already exists. Please use a different email.</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, surname, email) VALUES (?, ?, ?)");
        $stmt->execute([$name, $surname, $email]);

        // Fetch the newly created contact's ID
        $contact_id = $pdo->lastInsertId();

        // Redirect to this page with the contact_id as a parameter
        header("Location: addContact.php?contact_id=$contact_id");
        exit;
    }
}

// Check if contact_id is set for linking
$contact_id = $_GET['contact_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Contact</title>
</head>
<body>
    <h1>Add New Contact</h1>
    <?php if (!$contact_id): ?>
        <form method="POST">
            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="surname">Last Name:</label>
            <input type="text" id="surname" name="surname" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <button type="submit" name="create_contact">Add Contact</button>
        </form>
    <?php else: ?>
        <p>Contact has been added. Contact ID: <?= htmlspecialchars($contact_id) ?></p>
        <h2>Link Contact to Existing Clients</h2>
        <form method="POST" action="linkClientContact.php">
            <input type="hidden" name="contact_id" value="<?= htmlspecialchars($contact_id) ?>">
            <label for="client_id">Select Client:</label>
            <select id="client_id" name="client_id">
                <?php
                $stmt = $pdo->query("SELECT id, name, client_code FROM clients ORDER BY name ASC");
                $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($clients as $client) {
                    echo '<option value="' . htmlspecialchars($client['id']) . '">' . htmlspecialchars($client['name'] . ' (' . $client['client_code'] . ')') . '</option>';
                }
                ?>
            </select><br>
            <button type="submit" name="link_client">Link Client</button>
        </form>
    <?php endif; ?>
    <br>
    <a href="../views/contacts_view.php">Back to Contact List</a>
</body>
</html>
