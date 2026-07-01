<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: student_login.php');
    exit();
}

$reg_number = $_SESSION['reg_number'];
$marks_query = mysqli_query($conn, "SELECT * FROM marks WHERE reg_number = '$reg_number' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mount Kenya University - Student Dashboard</title>
    
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
        .navbar {
            background: #0a2647 !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #d4af37 !important;
        }
        
        .navbar-brand i {
            color: #d4af37;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #0a2647, #1a3a6a);
            color: white;
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(10, 38, 71, 0.2);
        }
        
        .welcome-section h2 {
            font-weight: 700;
        }
        
        .welcome-section .reg-number {
            color: #d4af37;
            font-weight: 600;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-header {
            background: #0a2647;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            border: none;
        }
        
        .card-header i {
            color: #d4af37;
            margin-right: 10px;
        }
        
        .table {
            margin: 0;
        }
        
        .table th {
            background: #f8f9fa;
            color: #0a2647;
            font-weight: 600;
            border-bottom: 2px solid #d4af37;
        }
        
        .grade-a { background: #48bb78; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-b { background: #4299e1; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-c { background: #ed8936; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-d { background: #ecc94b; color: #2d3748; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        .grade-f { background: #fc8181; color: white; padding: 5px 12px; border-radius: 20px; font-size: 13px; }
        
        .logout-btn {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            transition: 0.3s;
            text-decoration: none;
            font-weight: 600;
        }
        
        .logout-btn:hover {
            background: #c53030;
            transform: translateY(-2px);
            color: white;
        }
        
        .footer {
            background: #0a2647;
            color: white;
            padding: 30px 0;
            margin-top: 40px;
        }
        
        .footer a {
            color: #d4af37;
            text-decoration: none;
        }
        
        .footer a:hover {
            color: #f6e05e;
        }
        
        .footer .footer-divider {
            border-color: rgba(255,255,255,0.1);
        }
        
        .empty-state {
            padding: 40px;
            text-align: center;
            color: #a0aec0;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #d4af37;
        }
        
        @media (max-width: 768px) {
            .welcome-section {
                padding: 25px 20px;
            }
            .welcome-section h2 {
                font-size: 20px;
            }
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
                    <h2><i class="fas fa-user-graduate me-2"></i> Welcome, <?php echo $_SESSION['username']; ?>!</h2>
                    <p class="mb-0">Registration Number: <span class="reg-number"><?php echo $reg_number; ?></span></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span style="background: #d4af37; color: #0a2647; padding: 8px 20px; border-radius: 30px; font-weight: 700; font-size: 14px;">
                        <i class="fas fa-user"></i> Student
                    </span>
                </div>
            </div>
        </div>

        <!-- Marks Card -->
        <div class="card animate-fade delay-1">
            <div class="card-header">
                <i class="fas fa-list"></i> My Marks
            </div>
            <div class="card-body p-0">
                <?php if(mysqli_num_rows($marks_query) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Registration Number</th>
                                    <th>Unit Name</th>
                                    <th>Marks (%)</th>
                                    <th>Grade</th>
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
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5>No Marks Found</h5>
                        <p class="text-muted">You have no marks recorded yet. Please contact your lecturer.</p>
                    </div>
                <?php endif; ?>
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