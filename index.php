<?php include 'config/db.php'; ?>


<!DOCTYPE html>
<html>
<head>
    <title>Client Management</title>
</head>
<body>
    <h1>Client Management System</h1>

    <h2>Add New Client</h2>
    <form method="POST" action="addClient.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="client_code">Client Code:</label>
        <input type="text" id="client_code" name="client_code" required><br>
        <button type="submit">Add Client</button>
    </form>

    <h2>Add New Contact</h2>
    <form method="POST" action="addContact.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <button type="submit">Add Contact</button>
    </form>

</body>
</html>
