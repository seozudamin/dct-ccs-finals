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

//LOGIN

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


    //DASHBOARD FUCNTION
function countTotalSubjects()
{
    try {
        // Get the database connection
        $conn = dbConnect();

        // SQL query to count all subjects
        $sql = "SELECT COUNT(*) AS total_subjects FROM subjects";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch and return the result
        $result = $stmt->fetch();
        return $result['total_subjects'] ?? 0;
    } catch (PDOException $e) {
        // Log the error message and return 0
        error_log("Error in countTotalSubjects: " . $e->getMessage());
        return 0;
    }
}

function countTotalStudents()
{
    try {
        // Get the database connection
        $conn = dbConnect();

        // SQL query to count all students
        $sql = "SELECT COUNT(*) AS total_students FROM students";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch and return the result
        $result = $stmt->fetch();
        return $result['total_students'] ?? 0;
    } catch (PDOException $e) {
        // Log the error message and return 0
        error_log("Error in countTotalStudents: " . $e->getMessage());
        return 0;
    }
}

function calculatePassedAndFailedStudents()
{
    try {
        // Get the database connection
        $conn = dbConnect();

        // SQL query to calculate total grades and count of subjects for each student
        $sql = "SELECT student_id, SUM(grade) AS total_grades, COUNT(subject_id) AS subject_count 
                FROM students_subjects 
                GROUP BY student_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $students = $stmt->fetchAll();

        // Initialize counters for passed and failed students
        $passedCount = 0;
        $failedCount = 0;

        // Process each student's grades
        foreach ($students as $student) {
            $averageGrade = $student['subject_count'] > 0
                ? $student['total_grades'] / $student['subject_count']
                : 0;

            if ($averageGrade >= 75) {
                $passedCount++;
            } else {
                $failedCount++;
            }
        }

        // Return the total counts
        return [
            'passed' => $passedCount,
            'failed' => $failedCount
        ];
    } catch (PDOException $e) {
        // Log the error and return empty result
        error_log("Error in calculatePassedAndFailedStudents: " . $e->getMessage());
        return [
            'passed' => 0,
            'failed' => 0
        ];
    }
}?>