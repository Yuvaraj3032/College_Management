<?php
include 'includes/header.php';

require_once 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_attendance'])) {
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (:sid, :date, :status)");
    foreach ($_POST['attendance'] as $student_id => $status) {
        $stmt->execute([
            ':sid' => $student_id,
            ':date' => $_POST['date'],
            ':status' => $status
        ]);
    }
    header("Location: attendance.php?success=Attendance recorded successfully");
    exit();
}

// Fetch all departments for the dropdown
$departments = [
    "B.Sc (Computer Science)",
    "Mathematics",
    "Physics",
    "Chemistry",
    "Zoology",
    "B.A Tamil",
    "B.A English"
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <div class="container"  style="margin-top: 160px;">
       

        <div class="form-container">
            <h2>Record Attendance</h2>
            <?php if (isset($_GET['success'])): ?>
                <p style="color: green;"><?php echo $_GET['success']; ?></p>
            <?php endif; ?>
            <form method="POST" id="attendanceForm">
                <div class="form-grid">
                    <select name="department" id="department" required onchange="filterStudents()">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="semester" id="semester" placeholder="Semester" min="1" max="8" required onchange="filterStudents()">
                    <input type="date" name="date" id="date" required>
                </div>

                <div id="students-list" style="margin-top: 20px; display: none;">
                    <h3>Students</h3>
                    <table id="students-table">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Attendance</th>
                        </tr>
                        <?php
                        $students = $conn->query("SELECT id, student_id, first_name, last_name, department, semester FROM students")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($students as $student): ?>
                            <tr class="student-row" data-department="<?php echo $student['department']; ?>" data-semester="<?php echo $student['semester']; ?>" style="display: none;">
                                <td><?php echo $student['student_id']; ?></td>
                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                <td>
                                    <select name="attendance[<?php echo $student['id']; ?>]" required>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <button type="submit" name="submit_attendance" style="margin-top: 20px;">Record Attendance</button>
                </div>
            </form>
        </div>
    </div>
   

    <script>
        function filterStudents() {
            const department = document.getElementById('department').value;
            const semester = document.getElementById('semester').value;
            const studentsList = document.getElementById('students-list');
            const rows = document.getElementsByClassName('student-row');

            // Hide the students list if either department or semester is not selected
            if (department === '' || semester === '') {
                studentsList.style.display = 'none';
                return;
            }

            studentsList.style.display = 'block';
            for (let row of rows) {
                const studentDept = row.getAttribute('data-department');
                const studentSem = row.getAttribute('data-semester');
                row.style.display = (studentDept === department && studentSem === semester) ? 'table-row' : 'none';
            }
        }
    </script>
</body>
</html>