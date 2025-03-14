<?php
require_once 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$error = ''; // Variable to store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if student_id already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE student_id = :sid");
    $checkStmt->execute([':sid' => $_POST['student_id']]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        // Student ID already exists
        $error = "Error: Student ID '{$_POST['student_id']}' already exists. Please use a unique Student ID.";
    } else {
        // Proceed with insertion
        try {
            $stmt = $conn->prepare("INSERT INTO students (student_id, first_name, last_name, email, phone, aadhaar, blood_group, admission_date, department, semester) 
                                   VALUES (:sid, :fname, :lname, :email, :phone, :aadhaar, :blood_group, :adate, :dept, :sem)");
            $stmt->execute([
                ':sid' => $_POST['student_id'],
                ':fname' => $_POST['first_name'],
                ':lname' => $_POST['last_name'],
                ':email' => $_POST['email'],
                ':phone' => $_POST['phone'],
                ':aadhaar' => $_POST['aadhaar'],
                ':blood_group' => $_POST['blood_group'],
                ':adate' => $_POST['admission_date'],
                ':dept' => $_POST['department'],
                ':sem' => $_POST['semester']
            ]);
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// If there's an error, redirect back to dashboard with error message in session
if ($error) {
    session_start();
    $_SESSION['error'] = $error;
    header("Location: dashboard.php");
    exit();
}
?>