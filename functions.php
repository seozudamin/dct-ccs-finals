<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dct-ccs-finals');
define('DB_USER', 'root');
define('DB_PASS', '');
define('CHARSET', 'utf8mb4');

// Function to establish a database connection
function dbConnect()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
        exit;
    }
}

// Function to authenticate a user
function loginUser($email, $password)
{
    $db = dbConnect();

    // Validate email and password before querying the database
    $errors = validateLoginInputs($email, $password);
    if (!empty($errors)) {
        return ['errors' => $errors];
    }

    // Query the database for the user by email
    $query = $db->prepare("SELECT * FROM users WHERE email = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    $user = $query->fetch();

    // Check if user exists and validate password (consider upgrading from MD5 for better security)
    if ($user && md5($password) === $user['password']) {
        return $user;
    } else {
        return ['errors' => ['Invalid email or password.']];
    }
}

// Function to validate login inputs
function validateLoginInputs($email, $password)
{
    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    return $errors;
}
?>
