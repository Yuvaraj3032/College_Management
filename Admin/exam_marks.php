<?php
require_once 'config.php';
include 'includes/header.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Define subjects for each department (unchanged)
$subjects_by_dept = [
    "B.Sc (Computer Science)" => ["LA11A", "LZ11A", "PZ15A", "SE211", "SE21A"],
    "Mathematics" => ["SM3AA", "SY5AC", "LA12A", "LZ12A", "PZ15C"],
    "Physics" => ["SE211", "SE22A", "SG5AD", "SM3AE"],
    "Chemistry" => ["LA13A", "LZ13B", "SE231", "SE23A", "SP3AA"],
    "Zoology" => ["TS5EG", "ENV4B", "LA14A", "LZ14B", "NMU41"],
    "B.A Tamil" => ["SE241", "SE24A", "SP3A1", "SP3AB", "TSSEH"],
    "B.A English" => []
];

// Handle form submission (unchanged)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO exam_marks (student_id, exam_type, subject, marks, max_marks, exam_date) 
                           VALUES (:sid, :exam_type, :subject, :marks, :max, :date)");
    
    if (isset($_POST['student_id']) && is_array($_POST['student_id'])) {
        foreach ($_POST['student_id'] as $studentId => $sid) {
            if (!empty($_POST['marks'][$studentId]) && !empty($_POST['subject'][$studentId])) {
                try {
                    $stmt->execute([
                        ':sid' => $sid,
                        ':exam_type' => $_POST['exam_type'],
                        ':subject' => $_POST['subject'][$studentId],
                        ':marks' => $_POST['marks'][$studentId],
                        ':max' => $_POST['max_marks'][$studentId],
                        ':date' => $_POST['exam_date']
                    ]);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch existing marks (unchanged)
$marks = $conn->query("SELECT m.*, s.student_id, s.first_name, s.last_name, s.department 
                       FROM exam_marks m 
                       JOIN students s ON m.student_id = s.id 
                       ORDER BY m.exam_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$subjects_by_dept = [
    "B.Sc (Computer Science)" => ["LA11A", "LZ11A", "PZ15A", "SE211", "SE21A"],
    "Mathematics" => ["SM3AA", "SY5AC", "LA12A", "LZ12A", "PZ15C"],
    "Physics" => ["SE211", "SE22A", "SG5AD", "SM3AE"],
    "Chemistry" => ["LA13A", "LZ13B", "SE231", "SE23A", "SP3AA"],
    "Zoology" => ["TS5EG", "ENV4B", "LA14A", "LZ14B", "NMU41"],
    "B.A Tamil" => ["SE241", "SE24A", "SP3A1", "SP3AB", "TSSEH"],
    "B.A English" => []
];

// Handle form submission (unchanged)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO exam_marks (student_id, exam_type, subject, marks, max_marks, exam_date) 
                           VALUES (:sid, :exam_type, :subject, :marks, :max, :date)");
    
    if (isset($_POST['student_id']) && is_array($_POST['student_id'])) {
        foreach ($_POST['student_id'] as $studentId => $sid) {
            if (!empty($_POST['marks'][$studentId]) && !empty($_POST['subject'][$studentId])) {
                try {
                    $stmt->execute([
                        ':sid' => $sid,
                        ':exam_type' => $_POST['exam_type'],
                        ':subject' => $_POST['subject'][$studentId],
                        ':marks' => $_POST['marks'][$studentId],
                        ':max' => $_POST['max_marks'][$studentId],
                        ':date' => $_POST['exam_date']
                    ]);
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    }
}

$marks = $conn->query("SELECT m.*, s.student_id, s.first_name, s.last_name, s.department 
                       FROM exam_marks m 
                       JOIN students s ON m.student_id = s.id 
                       ORDER BY m.exam_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Exam Marks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="margin-top: 150px;">
        <header><!-- Same as dashboard --></header>
        
        <div class="form-container">
            <h2>Add Exam Marks</h2>
            <form method="POST" id="examForm">
                <div class="form-grid">
                    <select name="department" id="department" required onchange="filterStudents()">
                        <option value="">Select Department</option>
                        <?php foreach ($subjects_by_dept as $dept => $subjects): ?>
                            <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="exam_type" id="exam_type" required onchange="filterStudents()">
                        <option value="">Select Exam Type</option>
                        <option value="Internal-1">Internal-1</option>
                        <option value="Internal-2">Internal-2</option>
                        <option value="Model">Model</option>
                    </select>
                    <input type="date" name="exam_date" required>
                </div>

                <div id="students-list" style="margin-top: 20px; display: none;">
                    <h3>Students and Subjects</h3>
                    <table id="students-table">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Max Marks</th>
                        </tr>
                        <?php
                        $students = $conn->query("SELECT id, student_id, first_name, last_name, department FROM students")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($students as $student): ?>
                            <tr class="student-row" data-department="<?php echo $student['department']; ?>" style="display: none;">
                                <td><?php echo $student['student_id']; ?></td>
                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                <td>
                                    <select name="subject[<?php echo $student['id']; ?>]" class="subject-select" disabled>
                                        <option value="">Select Subject</option>
                                    </select>
                                </td>
                                <td><input type="number" name="marks[<?php echo $student['id']; ?>]" min="0" max="100" disabled></td>
                                <td><input type="number" name="max_marks[<?php echo $student['id']; ?>]" value="100" disabled></td>
                                <input type="hidden" name="student_id[<?php echo $student['id']; ?>]" value="<?php echo $student['id']; ?>">
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <button type="submit" style="margin-top: 20px;">Add Marks</button>
                </div>
            </form>
            
            <button id="viewResultsBtn" style="margin-top: 20px;">View Student Results</button>
        </div>

        <div class="marks-list" id="examRecords" style="display: none;">
            <h2>Exam Records</h2>
            <div class="filter-container" style="margin-bottom: 20px;">
                <select id="filterDepartment" onchange="filterRecords()">
                    <option value="">All Departments</option>
                    <?php foreach ($subjects_by_dept as $dept => $subjects): ?>
                        <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="filterExamType" onchange="filterRecords()">
                    <option value="">All Exam Types</option>
                    <option value="Internal-1">Internal-1</option>
                    <option value="Internal-2">Internal-2</option>
                    <option value="Model">Model</option>
                </select>
                <select id="filterSubject" onchange="filterRecords()">
                    <option value="">All Subjects</option>
                    <?php 
                    $all_subjects = array_unique(call_user_func_array('array_merge', array_values($subjects_by_dept)));
                    foreach ($all_subjects as $subject): ?>
                        <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table id="recordsTable">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Exam Type</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Max</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($marks as $record): ?>
                <tr class="record-row" 
                    data-department="<?php echo $record['department']; ?>"
                    data-exam-type="<?php echo $record['exam_type']; ?>"
                    data-subject="<?php echo $record['subject']; ?>">
                    <td><?php echo $record['student_id']; ?></td>
                    <td><?php echo $record['first_name'] . ' ' . $record['last_name']; ?></td>
                    <td><?php echo $record['exam_type']; ?></td>
                    <td><?php echo $record['subject']; ?></td>
                    <td><?php echo $record['marks']; ?></td>
                    <td><?php echo $record['max_marks']; ?></td>
                    <td><?php echo $record['exam_date']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
        const subjectsByDept = <?php echo json_encode($subjects_by_dept); ?>;

        function filterStudents() {
            const department = document.getElementById('department').value;
            const examType = document.getElementById('exam_type').value;
            const studentsList = document.getElementById('students-list');
            const rows = document.getElementsByClassName('student-row');

            if (department === '' || examType === '') {
                studentsList.style.display = 'none';
                return;
            }

            studentsList.style.display = 'block';
            for (let row of rows) {
                const studentDept = row.getAttribute('data-department');
                const subjectSelect = row.querySelector('.subject-select');
                const marksInput = row.querySelector('input[name^="marks"]');
                const maxMarksInput = row.querySelector('input[name^="max_marks"]');

                if (studentDept === department) {
                    row.style.display = 'table-row';
                    subjectSelect.disabled = false;
                    marksInput.disabled = false;
                    maxMarksInput.disabled = false;

                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    if (subjectsByDept[department]) {
                        subjectsByDept[department].forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject;
                            option.text = subject;
                            subjectSelect.appendChild(option);
                        });
                    }
                } else {
                    row.style.display = 'none';
                    subjectSelect.disabled = true;
                    marksInput.disabled = true;
                    maxMarksInput.disabled = true;
                }
            }
        }

        function filterRecords() {
            const department = document.getElementById('filterDepartment').value;
            const examType = document.getElementById('filterExamType').value;
            const subject = document.getElementById('filterSubject').value;
            const rows = document.getElementsByClassName('record-row');

            for (let row of rows) {
                const rowDept = row.getAttribute('data-department');
                const rowExam = row.getAttribute('data-exam-type');
                const rowSubject = row.getAttribute('data-subject');

                const deptMatch = !department || rowDept === department;
                const examMatch = !examType || rowExam === examType;
                const subjectMatch = !subject || rowSubject === subject;

                row.style.display = (deptMatch && examMatch && subjectMatch) ? 'table-row' : 'none';
            }
        }

        document.getElementById('viewResultsBtn').addEventListener('click', function() {
            const examRecords = document.getElementById('examRecords');
            if (examRecords.style.display === 'none' || examRecords.style.display === '') {
                examRecords.style.display = 'block';
                this.textContent = 'Hide Student Results';
            } else {
                examRecords.style.display = 'none';
                this.textContent = 'View Student Results';
            }
        });
    </script>
</body>
</html>