<!DOCTYPE html>
<html>
<head>
    <title>Client Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: auto;
            text-align: center;
        }
        .button-container {
            margin-top: 50px;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Client Management System</h1>
        <div class="button-container">
            <a href="../forms/addClient.php">Add New Client</a>
            <a href="../forms/addContact.php">Add New Contact</a>
            <a href="../views/clients_view.php">View Clients</a>
            <a href="../views/contacts_view.php">View Contacts</a>
        </div>
    </div>
</body>
</html>
