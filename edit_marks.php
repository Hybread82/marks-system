<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lecturer') {
    header('Location: lecturer_login.php');
    exit();
}

$id = (int)$_GET['id'];
$record = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM marks WHERE id = $id"));

if (!$record) {
    header('Location: lecturer_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
    $unit_name = mysqli_real_escape_string($conn, $_POST['unit_name']);
    $marks = (int)$_POST['marks'];
    
    if ($marks >= 70) $grade = 'A';
    elseif ($marks >= 60) $grade = 'B';
    elseif ($marks >= 50) $grade = 'C';
    elseif ($marks >= 40) $grade = 'D';
    else $grade = 'F';
    
    $update = "UPDATE marks SET 
        student_name = '$student_name',
        reg_number = '$reg_number',
        unit_name = '$unit_name',
        marks = $marks,
        grade = '$grade'
        WHERE id = $id";
    
    mysqli_query($conn, $update);
    header('Location: lecturer_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Marks</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%230a2647'/><text x='50' y='70' font-size='60' text-anchor='middle' fill='%23d4af37'>MKU</text></svg>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #0a2647 0%, #1a3a6a 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center" style="min-height: 100vh; align-items: center;">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h4 class="text-center mb-3">✏️ Edit Marks</h4>
                    <form method="POST">
                        <div class="mb-2">
                            <label>Student Name</label>
                            <input type="text" name="student_name" class="form-control" value="<?php echo $record['student_name']; ?>" required>
                        </div>
                        <div class="mb-2">
                            <label>Registration Number</label>
                            <input type="text" name="reg_number" class="form-control" value="<?php echo $record['reg_number']; ?>" required>
                        </div>
                        <div class="mb-2">
                            <label>Unit Name</label>
                            <input type="text" name="unit_name" class="form-control" value="<?php echo $record['unit_name']; ?>" required>
                        </div>
                        <div class="mb-2">
                            <label>Marks (%)</label>
                            <input type="number" name="marks" class="form-control" value="<?php echo $record['marks']; ?>" min="0" max="100" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update</button>
                        <a href="lecturer_dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>