<?php
include 'config/db.php';


// Generate unique client code
function generateClientCode($name, $pdo) {
    $alpha = strtoupper(substr(preg_replace("/[^A-Za-z]/", '', $name), 0, 3));
    $alpha = str_pad($alpha, 3, 'A');
    $numeric = str_pad(1, 3, '0', STR_PAD_LEFT);

    $query = "SELECT client_code FROM clients WHERE client_code LIKE ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$alpha . '%']);
    $existingCodes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($existingCodes)) {
        $maxNumeric = max($existingCodes) + 1;
        $numeric = str_pad($maxNumeric, 3, '0', STR_PAD_LEFT);
    }

    return $alpha . $numeric;
}


// Create new client
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_client'])) {
    $name = $_POST['name'];
    $client_code = generateClientCode($name);

    $stmt = $dsn->prepare("INSERT INTO clients (name, client_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $client_code);
    $stmt->execute();
    $stmt->close();
}

// List clients
$clients = [];
$query = "SELECT clients.id, clients.name, clients.client_code, COUNT(client_contact.contact_id) AS contact_count
          FROM clients
          LEFT JOIN client_contact ON clients.id = client_contact.client_id
          GROUP BY clients.id
          ORDER BY clients.name ASC";

$stmt = $pdo->query($query);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clients</title>
</head>
<body>
    <h1>Clients</h1>
    <form action="addClient.php" method="POST">
        <input type="text" name="name" placeholder="Client Name" required>
        <button type="submit" name="create_client">Create Client</button>
    </form>
    <?php if (empty($clients)): ?>
        <p>No client(s) found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Client Code</th>
                    <th>No. of Linked Contacts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['name'] ?></td>
                        <td><?= $client['client_code'] ?></td>
                        <td style="text-align:center"><?= $client['contact_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
