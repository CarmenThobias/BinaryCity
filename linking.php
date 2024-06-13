<?php
include '../config/db.php';

//Link Contact to Client

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['link_contact'])) {
    $client_id = $_POST['client_id'];
    $contact_id = $_POST['contact_id'];

    $stmt = $pdo->prepare("INSERT INTO client_contact (client_id, contact_id) VALUES (?, ?)");
    $stmt->execute([$client_id, $contact_id]);

    header("Location: views/clients_view.php?id=$client_id"); // Redirect to the client view
    exit();
}

//Unlink Contact from Client

if (isset($_GET['action']) && $_GET['action'] == 'unlink') {
    $client_id = $_GET['client_id'];
    $contact_id = $_GET['contact_id'];
    

    $stmt = $pdo->prepare("DELETE FROM client_contact WHERE client_id = ? AND contact_id = ?");
    $stmt->execute([$client_id, $contact_id]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}