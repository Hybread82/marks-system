<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lecturer') {
    header('Location: lecturer_login.php');
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="marks_report_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Student Name', 'Registration Number', 'Unit Name', 'Marks', 'Grade']);

$result = mysqli_query($conn, "SELECT * FROM marks ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [$row['student_name'], $row['reg_number'], $row['unit_name'], $row['marks'], $row['grade']]);
}

fclose($output);
exit();
?>