<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dct-ccs-finals');
define('DB_USER', 'root');
define('DB_PASS', '');

// Function to create a database connection
function dbConnect()
{
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

// Function to validate user login
function loginUser($email, $password)
{
    $db = dbConnect();
    if ($db) {
        // Fetch the user by email
        $query = $db->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and verify MD5 password (for your current setup)
        if ($user && md5($password) === $user['password']) {
            // User authenticated successfully
            return $user;
        }
    }
    return null;
}
?>