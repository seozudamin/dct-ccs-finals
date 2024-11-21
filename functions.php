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
}

//
function addCourse($course_code, $course_name) {
    $validateCourseData = validateCourseData($course_code, $course_name);

    $checkDuplicateCourse = checkDuplicateCourseData($course_code, $course_name);

    if(count($validateCourseData) > 0 ){
        echo displayErrors($validateCourseData);
        return;
    }

    if(count($checkDuplicateCourse) == 1 ){
        echo displayErrors($checkDuplicateCourse);
        return;
    }

    // Get database connection
    $dbConn = dbConnect();

    try {
        // Prepare SQL query to insert course into the database
        $query = "INSERT INTO courses (course_code, course_name) VALUES (:course_code, :course_name)";
        $stmt = $dbConn->prepare($query);

        // Bind parameters to the SQL query
        $stmt->bindParam(':course_code', $course_code);
        $stmt->bindParam(':course_name', $course_name);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Course successfully added
        } else {
            return "Failed to add course."; // Query execution failed
        }
    } catch (PDOException $e) {
        // Return error message if the query fails
        return "Error: " . $e->getMessage();
    }
}

function validateCourseData($course_code, $course_name) {
    $errors = [];

    // Check if course_code is empty
    if (empty($course_code)) {
        $errors[] = "Course code is required.";
    }

    // Check if course_name is empty
    if (empty($course_name)) {
        $errors[] = "Course name is required.";
    }

    return $errors;
}

// Function to check if the course already exists in the database (duplicate check)
function checkDuplicateCourseData($course_code, $course_name) {
    // Get database connection
    $dbConn = dbConnect();

    // Query to check if the course_code already exists in the database
    $query = "SELECT * FROM courses WHERE course_code = :course_code OR course_name = :course_name";
    $stmt = $dbConn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':course_code', $course_code);
    $stmt->bindParam(':course_name', $course_name);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $existing_course = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a course exists with the same code or name, return an error
    if ($existing_course) {
        return ["Duplicate course found: The course code or name already exists."];
    }

    return [];
}

// Function to check if the course already exists in the database for editing (duplicate check)
function checkDuplicateCourseForEdit($course_name) {
    // Get database connection
    $dbConn = dbConnect();

    // Query to check if the course_name already exists in the database
    $query = "SELECT * FROM courses WHERE course_name = :course_name";
    $stmt = $dbConn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':course_name', $course_name);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $existing_course = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a course exists with the same name, return an error
    if ($existing_course) {
        return ["Duplicate course found: The course name already exists."];
    }

    return [];
}

function fetchCourses() {
    // Get the database connection
    $dbConn = dbConnect();

    try {
        // Prepare SQL query to fetch all courses
        $query = "SELECT * FROM courses";
        $stmt = $dbConn->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch all courses as an associative array
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the list of courses
        return $courses;
    } catch (PDOException $e) {
        // Return an empty array in case of error
        return [];
    }
}

//Display Error

function displayErrors($errors) {
    if (empty($errors)) return "";

    $errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>System Alerts</strong><ul>';

    // Make sure each error is a string
    foreach ($errors as $error) {
        // Check if $error is an array or not
        if (is_array($error)) {
            // If it's an array, convert it to a string (you could adjust this to fit your needs)
            $errorHtml .= '<li>' . implode(", ", $error) . '</li>';
        } else {
            $errorHtml .= '<li>' . htmlspecialchars($error) . '</li>';
        }
    }

    $errorHtml .= '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

    return $errorHtml;
}

//Get Course 

function getCourseByCode($course_code) {
    $dbConn = dbConnect();
    $query = "SELECT * FROM courses WHERE course_code = :course_code";
    $stmt = $dbConn->prepare($query);
    $stmt->execute([':course_code' => $course_code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//Update Course

function updateCourse($course_code, $course_name, $redirectPage) {
    $validateCourseData = validateCourseData($course_code, $course_name);

    $checkDuplicateCourse = checkDuplicateCourseForEdit($course_name);

    if(count($validateCourseData) > 0 ){
        echo displayErrors($validateCourseData);
        return;
    }

    if(count($checkDuplicateCourse) == 1 ){
        echo displayErrors($checkDuplicateCourse);
        return;
    }

    try {
        // Get the database connection
        $dbConn = dbConnect();

        // Prepare the SQL query for updating the course
        $query = "UPDATE courses SET course_name = :course_name WHERE course_code = :course_code";
        $stmt = $dbConn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.location.href = '$redirectPage';</script>";
        } else {
            return 'Failed to update course';
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

//Delete Course

function deleteCourse($course_code, $redirectPage) {
    try {
        // Get the database connection
        $dbConn = dbConnect();

        // Prepare the SQL query to delete the course
        $query = "DELETE FROM courses WHERE course_code = :course_code";
        $stmt = $dbConn->prepare($query);

        // Bind the parameter
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.location.href = '$redirectPage';</script>";
        } else {
            return "Failed to delete the course with code $course_code.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

//Students 

//Fetch the students

function fetchAllStudents() {
    // Get the database connection
    $dbConnection = dbConnect();

    try {
        // Prepare SQL query to fetch all students
        $query = "SELECT * FROM students";
        $stmt = $dbConnection->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch all students as an associative array
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the list of students
        return $students;
    } catch (PDOException $e) {
        // Return an empty array in case of error
        return [];
    }
}

//Add New Students

function addNewStudent($id, $firstName, $lastName) {
    $validationErrors = validateStudentInputs($id, $firstName, $lastName);
    $duplicateCheck = checkForDuplicateStudent($id);

    if (count($validationErrors) > 0) {
        echo displayErrors($validationErrors);
        return;
    }

    if (count($duplicateCheck) == 1) {
        echo displayErrors($duplicateCheck);
        return;
    }

    $dbConnection = dbConnect();

    try {
        // Prepare SQL query to insert a new student into the database
        $sql = "INSERT INTO students (student_id, first_name, last_name) VALUES (:student_id, :first_name, :last_name)";
        $stmt = $dbConnection->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':student_id', $id);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Student successfully added
        } else {
            return "Failed to add student."; // Query execution failed
        }
    } catch (PDOException $e) {
        // Return error message if the query fails
        return "Error: " . $e->getMessage();
    }
}

//Get Student ID

function getStudentById($id) {
    $dbConnection = dbConnect();
    $query = "SELECT * FROM students WHERE student_id = :student_id";
    $stmt = $dbConnection->prepare($query);
    $stmt->execute([':student_id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//Update Student Details

function updateStudentDetails($id, $firstName, $lastName, $redirectUrl) {
    $validationErrors = validateStudentInputs($id, $firstName, $lastName);

    if (count($validationErrors) > 0) {
        echo displayErrors($validationErrors);
        return;
    }

    try {
        // Get the database connection
        $dbConnection = dbConnect();

        // Prepare the SQL query to update student details
        $sql = "UPDATE students SET first_name = :first_name, last_name = :last_name WHERE student_id = :student_id";
        $stmt = $dbConnection->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':student_id', $id, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.location.href = '$redirectUrl';</script>";
        } else {
            return 'Failed to update student details.';
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Function to validate student inputs
function validateStudentInputs($id, $firstName, $lastName) {
    $errors = [];

    // Check if student_id is empty
    if (empty($id)) {
        $errors[] = "Student ID is required.";
    }

    // Check if first_name is empty
    if (empty($firstName)) {
        $errors[] = "Student first name is required.";
    }

    // Check if last_name is empty
    if (empty($lastName)) {
        $errors[] = "Student last name is required.";
    }

    return $errors;
}

//Check Duplicate student

function checkForDuplicateStudent($id) {
    $dbConnection = dbConnect();

    // Query to check if the student_id already exists in the database
    $sql = "SELECT * FROM students WHERE student_id = :student_id";
    $stmt = $dbConnection->prepare($sql);

    // Bind parameter
    $stmt->bindParam(':student_id', $id);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $existingStudent = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a student exists with the same ID, return an error
    if ($existingStudent) {
        return ["Duplicate student found: The student ID already exists."];
    }

    return [];
}


//Delete Student Record

function deleteStudentRecord($id, $redirectUrl) {
    try {
        // Get the database connection
        $dbConnection = dbConnect();

        // Prepare the SQL query to delete the student
        $sql = "DELETE FROM students WHERE student_id = :student_id";
        $stmt = $dbConnection->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':student_id', $id, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.location.href = '$redirectUrl';</script>";
        } else {
            return "Failed to delete the student with ID $id.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


//Available Subject Courses

function getAvailableSubjectsCheckboxes($student_id) {
    // Get database connection
    $connection = dbConnect();

    // SQL query to fetch subjects not assigned to the student
    $query = "SELECT subject_code, subject_name FROM subjects WHERE subject_code NOT IN (SELECT subject_id FROM students_subjects WHERE student_id = :student_id)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();

    // Fetch subjects
    $subjectsList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for checkboxes
    $checkboxHtml = '';
    foreach ($subjectsList as $subject) {
        $checkboxHtml .= '<div class="form-check">';
        $checkboxHtml .= '<input class="form-check-input" type="checkbox" name="subjects[]" value="' . htmlspecialchars($subject['subject_code']) . '" id="subject_' . htmlspecialchars($subject['subject_code']) . '">';
        $checkboxHtml .= '<label class="form-check-label" for="subject_' . htmlspecialchars($subject['subject_code']) . '">';
        $checkboxHtml .= htmlspecialchars($subject['subject_code']) . ' - ' . htmlspecialchars($subject['subject_name']);
        $checkboxHtml .= '</label>';
        $checkboxHtml .= '</div>';
    }

    // Return the HTML checkboxes
    return $checkboxHtml;
}

// Assigned Subjects

function getAssignedSubjects($student_id) {
    // Get the database connection
    $connection = dbConnect();

    try {
        // Prepare SQL query to fetch all assigned subjects
        $query = "SELECT assignment.subject_id, subjects.subject_name, assignment.grade FROM students_subjects assignment JOIN subjects ON assignment.subject_id = subjects.subject_code";
        $stmt = $connection->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch all subjects as an associative array
        $assignedSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the list of subjects
        return $assignedSubjects;
    } catch (PDOException $e) {
        // Return an empty array in case of error
        return [];
    }
}

// Assign Subjects to Student

function assignSubjectsToStudent($student_id, $subject_codes) {
    $studentId = intval($student_id); // Ensure student_id is an integer
    $subjectIds = [];

    // Convert subject codes to integers
    foreach ($subject_codes as $code) {
        $subjectIds[] = intval($code);
    }

    try {
        // Get the database connection
        $connection = dbConnect();

        // Loop through each subject_code
        foreach ($subjectIds as $subjectCode) {
            // Check if the subject is already assigned
            $checkQuery = "SELECT COUNT(*) FROM students_subjects WHERE student_id = :student_id AND subject_id = :subject_id";
            $checkStmt = $connection->prepare($checkQuery);

            // Bind parameters for the check query
            $checkStmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
            $checkStmt->bindParam(':subject_id', $subjectCode, PDO::PARAM_INT);
            $checkStmt->execute();

            // Fetch the result (count of matching records)
            $alreadyAssigned = $checkStmt->fetchColumn();

            // If not assigned already, proceed to insert
            if ($alreadyAssigned == 0) {
                $insertQuery = "INSERT INTO students_subjects (student_id, subject_id, grade) 
                                VALUES (:student_id, :subject_id, 0.00)";
                $insertStmt = $connection->prepare($insertQuery);

                // Bind parameters for the insert query
                $insertStmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
                $insertStmt->bindParam(':subject_id', $subjectCode, PDO::PARAM_INT);
                
                // Execute the query
                $insertStmt->execute();
            }
        }

        // Success message after assigning subjects
        echo "Subjects assigned successfully!";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Remove Subject from Student

function removeSubjectFromStudent($student_id, $subject_id, $redirectPage) {
    try {
        // Get the database connection
        $connection = dbConnect();

        // Prepare the SQL query to delete the subject
        $deleteQuery = "DELETE FROM students_subjects WHERE student_id = :student_id AND subject_id = :subject_id";
        $deleteStmt = $connection->prepare($deleteQuery);

        // Bind parameters
        $deleteStmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $deleteStmt->bindParam(':subject_id', $subject_id, PDO::PARAM_STR);

        // Execute the query
        if ($deleteStmt->execute()) {
            echo "<script>window.location.href = '$redirectPage';</script>";
        } else {
            return "Failed to delete the subject with ID $subject_id.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Update Student Subject Grade
function updateStudentSubjectGrade($student_id, $subject_id, $grade, $redirectPage) {
    try {
        // Get the database connection
        $connection = dbConnect();

        // SQL query to update the grade
        $updateQuery = "UPDATE students_subjects 
                        SET grade = :grade 
                        WHERE student_id = :student_id AND subject_id = :subject_id";
        $updateStmt = $connection->prepare($updateQuery);

        // Bind parameters
        $updateStmt->bindParam(':grade', $grade, PDO::PARAM_STR);
        $updateStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $updateStmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);

        // Execute the query
        $updateStmt->execute();

        // Redirect after success
        echo "<script>window.location.href = '$redirectPage';</script>";
    } catch (PDOException $e) {
        // Handle any errors
        return "Error: " . $e->getMessage();
    }
}

// Database Connection
function getRequestData($parameter) {
    return $_GET[$parameter];
}

// Database
function isFormSubmitted($field = null)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($field) {
            return isset($_POST[$field]) ? htmlspecialchars(trim($_POST[$field])) : null;
        }
        return true;
    }
    return false;
}


// User Authentication

function logOutUser($redirectPage) {
    // Unset the 'user_email' session variable
    unset($_SESSION['user_email']);

    // Destroy the session
    session_destroy();

    // Redirect to the specified page
    header("Location: $redirectPage");
    exit;
}
//isFormSubmitted
//fetchAllStudents

?>

