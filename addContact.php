<?php
include 'config/db.php';


// Create new contact
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_contact'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];

    $query = "INSERT INTO contacts (name, surname, email) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $surname, $email]);
}


// List contacts
$contacts = [];
$query = "SELECT id, name, surname, email FROM contacts ORDER BY surname ASC";

try {
    $stmt = $pdo->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $contacts[] = $row;
    }
} catch (PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contacts</title>
</head>
<body>
    <h1>Contacts</h1>
    <form action="addContact.php" method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="surname" placeholder="Surname" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" name="create_contact">Create Contact</button>
    </form>
    <?php if (empty($contacts)): ?>
        <p>No contacts found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= $contact['surname'] . ' ' . $contact['name'] ?></td>
                        <td><?= $contact['email'] ?></td>
                        <td><a href="unlink_contact.php?id=<?= $contact['id'] ?>">Unlink</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
