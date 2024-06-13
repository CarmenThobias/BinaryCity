<?php
include '../config/db.php';

// Fetch clients linked to a contact
function fetchClientsByContact($contact_id, $pdo) {
    $stmt = $pdo->prepare("
        SELECT clients.id, clients.name, clients.client_code 
        FROM clients 
        INNER JOIN client_contact ON clients.id = client_contact.client_id 
        WHERE client_contact.contact_id = ?
        ORDER BY clients.name ASC
    ");
    $stmt->execute([$contact_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all contacts
$stmt = $pdo->query("SELECT id, name, surname, email FROM contacts ORDER BY surname ASC, name ASC");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contacts</title>
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
    <h1>Contacts</h1>

    <?php
        // List contacts
        $contacts = [];
        $query = "SELECT id, name, surname, email FROM contacts ORDER BY surname ASC, name ASC";
        $stmt = $pdo->query($query);
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

    <a href="../forms/addContact.php" class="btn">Add New Contact</a>
    
    <?php if (empty($contacts)): ?>
            <p>No contact(s) found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                        <td><?php echo "{$contact['surname']}, {$contact['name']}"; ?></td>
                            <td><?php echo $contact['email']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2>Clients Linked to Contacts</h2>

        <?php
        // List clients linked to contacts
        $linkedClients = [];
        $query = "SELECT cl.id, cl.name, cl.client_code, c.name AS contact_name, c.surname, c.email
                  FROM clients cl
                  JOIN client_contact cc ON cl.id = cc.client_id
                  JOIN contacts c ON cc.contact_id = c.id
                  ORDER BY c.surname ASC, c.name ASC";
        $stmt = $pdo->query($query);
        $linkedClients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (empty($linkedClients)): ?>
            <p>No clients found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Client Code</th>
                        <th>Contact Full Name</th>
                        <th>Contact Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($linkedClients as $client): ?>
                        <tr>
                            <td><?= $client['name'] ?></td>
                            <td><?= $client['client_code'] ?></td>
                            <td><?= $client['surname'] . ' ' . $client['contact_name'] ?></td>
                            <td><?= $client['email'] ?></td>
                            <td class="action-links">
                                <a href="../forms/linkClientContact.php?<?php echo $contact['id']; ?>&client_id=">Unlink</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
