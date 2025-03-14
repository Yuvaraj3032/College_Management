<?php
require_once 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$student = $conn->query("SELECT * FROM students WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE students SET student_id = :sid, first_name = :fname, last_name = :lname, 
                           email = :email, phone = :phone, aadhaar = :aadhaar, blood_group = :blood_group, 
                           gender = :gender, admission_date = :adate, department = :dept, semester = :sem WHERE id = :id");
    $stmt->execute([
        ':sid' => $_POST['student_id'],
        ':fname' => $_POST['first_name'],
        ':lname' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':phone' => $_POST['phone'],
        ':aadhaar' => $_POST['aadhaar'],
        ':blood_group' => $_POST['blood_group'],
        ':gender' => $_POST['gender'], // Added gender
        ':adate' => $_POST['admission_date'],
        ':dept' => $_POST['department'],
        ':sem' => $_POST['semester'],
        ':id' => $id
    ]);
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>
        <form method="POST">
            <div class="form-grid">
                <input type="text" name="student_id" value="<?php echo $student['student_id']; ?>" required>
                <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required>
                <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required>
                <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
                <input type="tel" name="phone" value="<?php echo $student['phone']; ?>">
                <input type="text" name="aadhaar" value="<?php echo $student['aadhaar']; ?>" pattern="[0-9]{12}" title="Enter 12-digit Aadhaar number" required>
                <select name="blood_group" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+" <?php if($student['blood_group'] == 'A+') echo 'selected'; ?>>A+</option>
                    <option value="A-" <?php if($student['blood_group'] == 'A-') echo 'selected'; ?>>A-</option>
                    <option value="B+" <?php if($student['blood_group'] == 'B+') echo 'selected'; ?>>B+</option>
                    <option value="B-" <?php if($student['blood_group'] == 'B-') echo 'selected'; ?>>B-</option>
                    <option value="AB+" <?php if($student['blood_group'] == 'AB+') echo 'selected'; ?>>AB+</option>
                    <option value="AB-" <?php if($student['blood_group'] == 'AB-') echo 'selected'; ?>>AB-</option>
                    <option value="O+" <?php if($student['blood_group'] == 'O+') echo 'selected'; ?>>O+</option>
                    <option value="O-" <?php if($student['blood_group'] == 'O-') echo 'selected'; ?>>O-</option>
                </select>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?php if($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if($student['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                </select>
                <input type="date" name="admission_date" value="<?php echo $student['admission_date']; ?>" required>
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="B.Sc (Computer Science)" <?php if($student['department'] == 'B.Sc (Computer Science)') echo 'selected'; ?>>B.Sc (Computer Science)</option>
                    <option value="Mathematics" <?php if($student['department'] == 'Mathematics') echo 'selected'; ?>>Mathematics</option>
                    <option value="Physics" <?php if($student['department'] == 'Physics') echo 'selected'; ?>>Physics</option>
                    <option value="Chemistry" <?php if($student['department'] == 'Chemistry') echo 'selected'; ?>>Chemistry</option>
                    <option value="Zoology" <?php if($student['department'] == 'Zoology') echo 'selected'; ?>>Zoology</option>
                    <option value="B.A Tamil" <?php if($student['department'] == 'B.A Tamil') echo 'selected'; ?>>B.A Tamil</option>
                    <option value="B.A English" <?php if($student['department'] == 'B.A English') echo 'selected'; ?>>B.A English</option>
                </select>
                <input type="number" name="semester" value="<?php echo $student['semester']; ?>" min="1" max="8">
            </div>
            <button type="submit">Update Student</button>
        </form>
    </div>
</body>
</html>