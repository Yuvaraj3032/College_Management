<?php
include 'includes/header.php';
require_once 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get filter values from the form
$department_filter = isset($_GET['department']) ? $_GET['department'] : '';
$semester_filter = isset($_GET['semester']) ? $_GET['semester'] : '';
$gender_filter = isset($_GET['gender']) ? $_GET['gender'] : '';

// Build the SQL query with filters
$sql = "SELECT * FROM students WHERE 1=1";
if (!empty($department_filter)) {
    $sql .= " AND department = :department";
}
if (!empty($semester_filter)) {
    $sql .= " AND semester = :semester";
}
if (!empty($gender_filter)) {
    $sql .= " AND gender = :gender";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($department_filter)) {
    $stmt->bindParam(':department', $department_filter);
}
if (!empty($semester_filter)) {
    $stmt->bindParam(':semester', $semester_filter);
}
if (!empty($gender_filter)) {
    $stmt->bindParam(':gender', $gender_filter);
}
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check for error message in session
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>College Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .footer {
    width: 100%;
    text-align: center;
    padding: 30px 0;
    background: #222;
    color: #fff;
    margin-top: 50px;
}
    </style>
</head>
<body>
    <div class="container" style="margin-top: 180px;">
        

        <div class="students-list">
            <div class="list-header">
                
                <h1>Students List</h1>
                <button id="add-student-btn" class="action-btn edit-btn">Add Student</button>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="dashboard.php" class="filter-form">
    <select name="department">
        <option value="">All Departments</option>
        <option value="B.Sc (Computer Science)" <?php echo ($department_filter == 'B.Sc (Computer Science)') ? 'selected' : ''; ?>>B.Sc (CS)</option>
        <option value="Mathematics" <?php echo ($department_filter == 'Mathematics') ? 'selected' : ''; ?>>Mathematics</option>
        <option value="Physics" <?php echo ($department_filter == 'Physics') ? 'selected' : ''; ?>>Physics</option>
        <option value="Chemistry" <?php echo ($department_filter == 'Chemistry') ? 'selected' : ''; ?>>Chemistry</option>
        <option value="Zoology" <?php echo ($department_filter == 'Zoology') ? 'selected' : ''; ?>>Zoology</option>
        <option value="B.A Tamil" <?php echo ($department_filter == 'B.A Tamil') ? 'selected' : ''; ?>>B.A Tamil</option>
        <option value="B.A English" <?php echo ($department_filter == 'B.A English') ? 'selected' : ''; ?>>B.A English</option>
    </select>

    <select name="semester">
        <option value="">All Semesters</option>
        <?php for ($i = 1; $i <= 8; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo ($semester_filter == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
        <?php endfor; ?>
    </select>

    <select name="gender">
        <option value="">All Genders</option>
        <option value="Male" <?php echo ($gender_filter == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($gender_filter == 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo ($gender_filter == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>

    <button type="submit" class="action-btn">Filter</button>
    <a href="dashboard.php" class="action-btn">Clear</a>
</form>

            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <div id="add-student-form" class="form-container" style="display: none;">
                <h2>Add New Student</h2>
                <form method="POST" action="add_student.php">
                    <div class="form-grid">
                        <input type="text" name="student_id" placeholder="Student ID" required>
                        <input type="text" name="first_name" placeholder="First Name" required>
                        <input type="text" name="last_name" placeholder="Last Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="tel" name="phone" placeholder="Phone">
                        <input type="text" name="aadhaar" placeholder="Aadhaar Number" pattern="[0-9]{12}" title="Enter 12-digit Aadhaar number" required>
                        <select name="blood_group" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="date" name="admission_date" required>
                        <select name="department" required>
                            <option value="">Select Department</option>
                            <option value="B.Sc (Computer Science)">B.Sc (Computer Science)</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Physics">Physics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Zoology">Zoology</option>
                            <option value="B.A Tamil">B.A Tamil</option>
                            <option value="B.A English">B.A English</option>
                        </select>
                        <input type="number" name="semester" placeholder="Semester" min="1" max="8">
                    </div>
                    <button type="submit">Add Student</button>
                </form>
            </div>

            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Aadhaar</th>
                    <th>Blood Group</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['student_id']; ?></td>
                        <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td><?php echo $student['department']; ?></td>
                        <td><?php echo $student['semester']; ?></td>
                        <td><?php echo $student['aadhaar']; ?></td>
                        <td><?php echo $student['blood_group']; ?></td>
                        <td><?php echo $student['gender']; ?></td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="action-btn edit-btn">Edit</a>
                            <a href="delete_student.php?id=<?php echo $student['id']; ?>"
                                class="action-btn delete-btn"
                                onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <section class="footer">
    <h4>About Us</h4>
    <p>The JOY College at Chennai provide one of the earliest form of technical studies,
        which has been vital in setting up the standard of brilliance.</p>
    <p id="copyright">Made By &#10084; Yuvaraj</p>
</section>
    <script>
        document.getElementById('add-student-btn').addEventListener('click', function() {
            const form = document.getElementById('add-student-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                this.textContent = 'Hide Form';
            } else {
                form.style.display = 'none';
                this.textContent = 'Add Student';
            }
        });
    </script>
</body>
</html>