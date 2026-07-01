<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lecturer') {
    header('Location: lecturer_login.php');
    exit();
}

// Add marks
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_mark'])) {
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
    $unit_name = mysqli_real_escape_string($conn, $_POST['unit_name']);
    $marks = (int)$_POST['marks'];
    
    if ($marks >= 70) $grade = 'A';
    elseif ($marks >= 60) $grade = 'B';
    elseif ($marks >= 50) $grade = 'C';
    elseif ($marks >= 40) $grade = 'D';
    else $grade = 'F';
    
    $check = mysqli_query($conn, "SELECT id FROM marks WHERE reg_number = '$reg_number'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Student already has marks! Use Edit instead.";
    } else {
        $insert = "INSERT INTO marks (student_name, reg_number, unit_name, marks, grade) 
                   VALUES ('$student_name', '$reg_number', '$unit_name', $marks, '$grade')";
        if (mysqli_query($conn, $insert)) {
            $success = "Marks added successfully! Grade: $grade";
        } else {
            $error = "Failed to add marks.";
        }
    }
}

// Delete marks
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM marks WHERE id = $id");
    header('Location: lecturer_dashboard.php');
    exit();
}

$marks_query = mysqli_query($conn, "SELECT * FROM marks ORDER BY created_at DESC");

$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM marks"))['count'];
$average_marks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(marks) as avg FROM marks"))['avg'];
$passed = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM marks WHERE grade != 'F'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mount Kenya University - Lecturer Dashboard</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%230a2647'/><text x='50' y='70' font-size='60' text-anchor='middle' fill='%23d4af37'>MKU</text></svg>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade {
            animation: fadeInUp 0.6s ease forwards;
        }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; min-height: 100vh; }
        
        /* Navbar */
        .navbar { background: #0a2647 !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .navbar-brand { font-weight: 700; color: #d4af37 !important; }
        .navbar-brand i { color: #d4af37; }
        
        .logout-btn { background: #e53e3e; color: white; border: none; padding: 8px 20px; border-radius: 10px; transition: 0.3s; text-decoration: none; font-weight: 600; }
        .logout-btn:hover { background: #c53030; transform: translateY(-2px); color: white; }
        
        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #0a2647, #1a3a6a);
            color: white;
            padding: 30px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(10, 38, 71, 0.2);
        }
        .welcome-section h2 { font-weight: 700; }
        .welcome-section .role-badge { background: #d4af37; color: #0a2647; padding: 8px 20px; border-radius: 30px; font-weight: 700; font-size: 14px; }
        
        /* Stats */
        .stat-card { border: none; border-radius: 15px; padding: 25px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .stat-card h2 { font-size: 2.5rem; font-weight: 700; }
        .stat-primary { background: linear-gradient(135deg, #0a2647, #1a3a6a); }
        .stat-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stat-info { background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); }
        
        /* Cards */
        .card { border: none; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; }
        .card-header { background: #0a2647; color: white; padding: 15px 20px; font-weight: 600; border: none; }
        .card-header i { color: #d4af37; margin-right: 10px; }
        .card-header .btn-light { background: #d4af37; color: #0a2647; border: none; font-weight: 600; }
        .card-header .btn-light:hover { background: #f6e05e; }
        
        .btn-primary { background: #0a2647 !important; border: none !important; }
        .btn-primary:hover { background: #1a3a6a !important; }
        
        /* Grades */
        .grade-a { background: #48bb78; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-b { background: #4299e1; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-c { background: #ed8936; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-d { background: #ecc94b; color: #2d3748; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-f { background: #fc8181; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        
        /* Table */
        .table th { background: #f8f9fa; color: #0a2647; font-weight: 600; border-bottom: 2px solid #d4af37; }
        .table td { vertical-align: middle; }
        
        /* Progress Bar */
        .progress { height: 30px; border-radius: 10px; overflow: hidden; }
        .progress-bar { font-weight: 700; font-size: 14px; }
        
        /* Footer */
        .footer { background: #0a2647; color: white; padding: 30px 0; margin-top: 40px; }
        .footer a { color: #d4af37; text-decoration: none; }
        .footer a:hover { color: #f6e05e; }
        .footer .footer-divider { border-color: rgba(255,255,255,0.1); }
        
        .empty-state { padding: 40px; text-align: center; color: #a0aec0; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; color: #d4af37; }
        
        @media (max-width: 768px) {
            .welcome-section { padding: 20px; }
            .welcome-section h2 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <span class="navbar-brand"><i class="fas fa-graduation-cap me-2"></i>Mount Kenya University</span>
            <div class="d-flex align-items-center">
                <span class="text-white me-3"><i class="fas fa-user me-1"></i> <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="welcome-section animate-fade">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i class="fas fa-chalkboard-teacher me-2"></i> Welcome, <?php echo $_SESSION['username']; ?>!</h2>
                    <p class="mb-0">Manage student marks and track performance</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="role-badge"><i class="fas fa-user"></i> Lecturer</span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-4 animate-fade delay-1">
                <div class="stat-card stat-primary">
                    <h2><?php echo $total_students; ?></h2>
                    <p><i class="fas fa-users me-2"></i>Total Students</p>
                </div>
            </div>
            <div class="col-md-4 animate-fade delay-2">
                <div class="stat-card stat-success">
                    <h2><?php echo round($average_marks, 1); ?>%</h2>
                    <p><i class="fas fa-chart-line me-2"></i>Average Marks</p>
                </div>
            </div>
            <div class="col-md-4 animate-fade delay-3">
                <div class="stat-card stat-info">
                    <h2><?php echo $total_students > 0 ? round(($passed / $total_students) * 100) : 0; ?>%</h2>
                    <p><i class="fas fa-check-circle me-2"></i>Pass Rate</p>
                </div>
            </div>
        </div>

        <!-- Pass/Fail Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-md-12 animate-fade delay-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-2"></i> Pass/Fail Statistics
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <h2 style="color: #48bb78;"><?php echo $passed; ?></h2>
                                <p class="text-success"><i class="fas fa-check-circle"></i> Passed</p>
                            </div>
                            <div class="col-md-6 text-center">
                                <h2 style="color: #fc8181;"><?php echo $total_students - $passed; ?></h2>
                                <p class="text-danger"><i class="fas fa-times-circle"></i> Failed</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" 
                                    style="width: <?php echo $total_students > 0 ? round(($passed / $total_students) * 100) : 0; ?>%;">
                                    <?php echo $total_students > 0 ? round(($passed / $total_students) * 100) : 0; ?>% Pass
                                </div>
                                <div class="progress-bar bg-danger" role="progressbar" 
                                    style="width: <?php echo $total_students > 0 ? 100 - round(($passed / $total_students) * 100) : 0; ?>%;">
                                    <?php echo $total_students > 0 ? 100 - round(($passed / $total_students) * 100) : 0; ?>% Fail
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Add Marks Form -->
            <div class="col-lg-4">
                <div class="card animate-fade delay-1">
                    <div class="card-header">
                        <i class="fas fa-plus-circle me-2"></i> Add Marks
                    </div>
                    <div class="card-body">
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Student Name</label>
                                <input type="text" name="student_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Registration Number</label>
                                <input type="text" name="reg_number" class="form-control" placeholder="Use unique reg number" required>
                            </div>
                            <div class="mb-3">
                                <label>Unit Name</label>
                                <input type="text" name="unit_name" class="form-control" value="Internet Programming" required>
                            </div>
                            <div class="mb-3">
                                <label>Marks (%)</label>
                                <input type="number" name="marks" class="form-control" min="0" max="100" required>
                            </div>
                            <button type="submit" name="add_mark" class="btn btn-primary w-100">Add Marks</button>
                        </form>
                        <div class="mt-2 text-center">
                            <small class="text-muted">
                                <span class="grade-a">A</span> 70-100 &nbsp;
                                <span class="grade-b">B</span> 60-69 &nbsp;
                                <span class="grade-c">C</span> 50-59 &nbsp;
                                <span class="grade-d">D</span> 40-49 &nbsp;
                                <span class="grade-f">F</span> 0-39
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marks Table -->
            <div class="col-lg-8">
                <div class="card animate-fade delay-2">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list me-2"></i> All Marks</span>
                        <a href="export_excel.php" class="btn btn-light btn-sm">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <?php if(mysqli_num_rows($marks_query) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Reg No</th>
                                            <th>Unit</th>
                                            <th>Marks</th>
                                            <th>Grade</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; while($row = mysqli_fetch_assoc($marks_query)): 
                                            $grade_class = 'grade-' . strtolower($row['grade']);
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row['student_name']; ?></td>
                                            <td><?php echo $row['reg_number']; ?></td>
                                            <td><?php echo $row['unit_name']; ?></td>
                                            <td><strong><?php echo $row['marks']; ?>%</strong></td>
                                            <td><span class="<?php echo $grade_class; ?>"><?php echo $row['grade']; ?></span></td>
                                            <td class="text-center">
                                                <a href="edit_marks.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h5>No Marks Added</h5>
                                <p class="text-muted">Start adding student marks using the form on the left.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center text-md-start">
                    <h5 style="color: #d4af37; font-weight: 700;">MKU</h5>
                    <p style="opacity: 0.7; font-size: 14px;">Mount Kenya University</p>
                </div>
                <div class="col-md-4 text-center">
                    <p style="opacity: 0.6; font-size: 13px;">&copy; <?php echo date('Y'); ?> Mount Kenya University</p>
                    <p style="opacity: 0.6; font-size: 13px;">Marks Management System</p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <p style="opacity: 0.6; font-size: 13px;"><i class="fas fa-envelope me-1"></i> info@mku.ac.ke</p>
                    <p style="opacity: 0.6; font-size: 13px;"><i class="fas fa-phone me-1"></i> +254709153000</p>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="text-center" style="opacity: 0.5; font-size: 12px;">
                Unlocking Infinite Possibilities.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>