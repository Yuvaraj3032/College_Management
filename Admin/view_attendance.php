<?php
require_once 'config.php';
include 'includes/header.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fetch all departments
$departments = [
    "B.Sc (Computer Science)",
    "Mathematics",
    "Physics",
    "Chemistry",
    "Zoology",
    "B.A Tamil",
    "B.A English"
];

// Filter by department, semester, and date if selected
$departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
$semesterFilter = isset($_GET['semester']) ? $_GET['semester'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Build the base query
$query = "SELECT a.*, s.student_id, s.first_name, s.last_name, s.department, s.semester 
          FROM attendance a 
          JOIN students s ON a.student_id = s.id";

// Add WHERE clause conditions based on filters
$conditions = [];
$params = [];
if ($departmentFilter) {
    $conditions[] = "s.department = :dept";
    $params[':dept'] = $departmentFilter;
}
if ($semesterFilter) {
    $conditions[] = "s.semester = :sem";
    $params[':sem'] = $semesterFilter;
}
if ($dateFilter) {
    $conditions[] = "a.date = :date";
    $params[':date'] = $dateFilter;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}
$query .= " ORDER BY a.date DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->execute($params);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head >
    <title>View Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container"  style="margin-top: 180px;">
       

        <div class="form-container">
            <h2>View Attendance</h2>
            <form method="GET" id="filterForm">
                <div class="form-grid">
                    <select name="department" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept; ?>" <?php if ($dept == $departmentFilter) echo 'selected'; ?>>
                                <?php echo $dept; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="semester" placeholder="Semester" min="1" max="8" value="<?php echo $semesterFilter; ?>" onchange="this.form.submit()">
                    <input type="date" name="date" value="<?php echo $dateFilter; ?>" onchange="this.form.submit()">
                </div>
            </form>
        </div>

        <div class="attendance-list">
            <h2>Attendance Records</h2>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($attendance as $record): ?>
                <tr>
                    <td><?php echo $record['student_id']; ?></td>
                    <td><?php echo $record['first_name'] . ' ' . $record['last_name']; ?></td>
                    <td><?php echo $record['department']; ?></td>
                    <td><?php echo $record['semester']; ?></td>
                    <td><?php echo $record['date']; ?></td>
                    <td><?php echo $record['status']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>