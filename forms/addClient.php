<?php
include '../config/db.php';

// Function to generate unique client code
function generateClientCode($name, $pdo) {
    $alpha = strtoupper(substr(preg_replace("/[^A-Za-z]/", '', $name), 0, 3));
    $alpha = str_pad($alpha, 3, 'A');
    $numeric = str_pad(1, 3, '0', STR_PAD_LEFT);

    $query = "SELECT client_code FROM clients WHERE client_code LIKE '$alpha%'";
    $stmt = $pdo->query($query);
    $existingCodes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    if ($existingCodes) {
        $existingNumbers = array_map(function($code) {
            return (int) substr($code, -3);
        }, $existingCodes);
        $maxNumeric = max($existingNumbers) + 1;
        $numeric = str_pad($maxNumeric, 3, '0', STR_PAD_LEFT);
    }

    return $alpha . $numeric;
}

// Create new client and then display linking options
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_client'])) {
    $name = $_POST['name'];
    $client_code = generateClientCode($name, $pdo);

    $stmt = $pdo->prepare("INSERT INTO clients (name, client_code) VALUES (?, ?)");
    $stmt->execute([$name, $client_code]);

    // Fetch the newly created client's ID
    $client_id = $pdo->lastInsertId();

    // Redirect to this page with the client_id as a parameter
    header("Location: addClient.php?client_id=$client_id");
    exit;
}

// Check if client_id is set for linking
$client_id = $_GET['client_id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Client</title>
</head>
<body>
    <h1>Add New Client</h1>
    <?php if (!$client_id): ?>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <button type="submit" name="create_client">Add Client</button>
        </form>
    <?php else: ?>
        <p>Client has been added. Client ID: <?= htmlspecialchars($client_id) ?></p>
        <h2>Link Client to Existing Contacts</h2>
        <form method="POST" action="linkClientContact.php">
            <input type="hidden" name="client_id" value="<?= htmlspecialchars($client_id) ?>">
            <label for="contact_id">Select Contact:</label>
            <select id="contact_id" name="contact_id">
                <?php
                $stmt = $pdo->query("SELECT id, name, surname FROM contacts ORDER BY surname ASC, name ASC");
                $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($contacts as $contact) {
                    echo '<option value="' . htmlspecialchars($contact['id']) . '">' . htmlspecialchars($contact['surname'] . ', ' . $contact['name']) . '</option>';
                }
                ?>
            </select><br>
            <button type="submit" name="link_contact">Link Contact</button>
        </form>
    <?php endif; ?>
    <br>
    <a href="../views/clients_view.php">Back to Client List</a>
</body>
</html>
