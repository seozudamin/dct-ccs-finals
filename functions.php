<?php
// functions.php

// Database configuration
define('DB_HOST', 'localhost'); // Database host
define('DB_NAME', 'dct-ccs-finals'); // Database name
define('DB_USER', 'root'); // Database username
define('DB_PASS', ''); // Database password

// Function to create a database connection
function dbConnect()
{
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

// Function to validate user login
function loginUser($email, $password)
{
    $db = dbConnect();
    if ($db) {
        $query = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $password);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
?>
