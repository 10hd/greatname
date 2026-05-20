<?php
session_start();
include 'db.php';

if (!isset($_SESSION["username"])) {
    header("Location: /");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION["username"];
    $input = isset($_POST['assigned_email']) ? trim($_POST['assigned_email']) : '';

    $email = NULL;

    if ($input !== '') {
        $cleanEmail = filter_var($input, FILTER_SANITIZE_EMAIL);

        if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            header("Location: /profile?error=invalid_email");
            exit();
        } else {
            $email = $cleanEmail;
        }
    }

    $query = "UPDATE accounts SET email = $1 WHERE name = $2";
    pg_query_params($dbconn, $query, [$email, $username]);

    header("Location: /profile"); 
    exit();
}