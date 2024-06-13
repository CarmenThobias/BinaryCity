<?php
include '../config/db.php';

// Fetch clients
$clients = [];
$query = "SELECT clients.id, clients.name, clients.client_code, COUNT(client_contact.contact_id) AS contact_count
          FROM clients
          LEFT JOIN client_contact ON clients.id = client_contact.client_id
          GROUP BY clients.id
          ORDER BY clients.name ASC";
$stmt = $pdo->query($query);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch contacts linked to clients
$linkedContacts = [];
$query = "SELECT c.id AS contact_id, c.name, c.surname, c.email, cl.id AS client_id, cl.name AS client_name, cl.client_code
          FROM contacts c
          JOIN client_contact cc ON c.id = cc.contact_id
          JOIN clients cl ON cc.client_id = cl.id
          ORDER BY cl.name ASC, c.surname ASC, c.name ASC";
$stmt = $pdo->query($query);
$linkedContacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Clients</h1>

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
                        <td><?= htmlspecialchars($client['name']) ?></td>
                        <td><?= htmlspecialchars($client['client_code']) ?></td>
                        <td style="text-align:center"><?= htmlspecialchars($client['contact_count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Contacts Linked to Clients</h2>

    <?php if (empty($linkedContacts)): ?>
        <p>No contacts found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Client Name</th>
                    <th>Client Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($linkedContacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['surname'] . ', ' . $contact['name']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['client_name']) ?></td>
                        <td><?= htmlspecialchars($contact['client_code']) ?></td>
                        <td><a href="../forms/linkClientContact.php?unlink_contact&contact_id=<?= htmlspecialchars($contact['contact_id']) ?>&client_id=<?= htmlspecialchars($contact['client_id']) ?>">Unlink</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
