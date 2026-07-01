<?php
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
    $password = md5($_POST['password']);
    
    $student_query = mysqli_query($conn, "SELECT * FROM marks WHERE reg_number = '$reg_number'");
    
    if (mysqli_num_rows($student_query) == 0) {
        $error = "Registration number not found";
    } else {
        $user_query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$reg_number' AND password = '$password' AND role = 'student'");
        
        if (mysqli_num_rows($user_query) == 1) {
            $user = mysqli_fetch_assoc($user_query);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['reg_number'] = $reg_number;
            $_SESSION['role'] = 'student';
            header('Location: student_dashboard.php');
            exit();
        } else {
            $error = "Invalid registration number or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mount Kenya University - Student Login</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%230a2647'/><text x='50' y='70' font-size='60' text-anchor='middle' fill='%23d4af37'>MKU</text></svg>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-slide {
            animation: slideIn 0.6s ease forwards;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #0a2647;
            background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 38, 71, 0.75);
            z-index: 0;
        }
        
        .login-card {
            background: #0a2647;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            max-width: 450px;
            width: 100%;
            position: relative;
            z-index: 1;
            color: white;
        }
        
        .mku-logo {
            text-align: center;
            margin-bottom: 25px;
        }
        .mku-logo .logo-box {
            display: inline-block;
            background: white;
            padding: 12px 30px;
            border-radius: 10px;
        }
        .mku-logo .logo-box span {
            color: #0a2647;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 3px;
        }
        .mku-logo h5 {
            color: #d4af37;
            font-weight: 700;
            margin-top: 10px;
        }
        .mku-logo p {
            color: rgba(255,255,255,0.7);
            font-size: 13px;
        }
        
        .login-card label {
            color: rgba(255,255,255,0.9);
            font-weight: 600;
        }
        
        .login-card .form-control {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
        }
        
        .login-card .form-control::placeholder {
            color: rgba(255,255,255,0.5);
        }
        
        .login-card .form-control:focus {
            background: rgba(255,255,255,0.2);
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            color: white;
        }
        
        .btn-success {
            background: #d4af37 !important;
            border: none !important;
            color: #0a2647 !important;
            font-weight: 700;
            padding: 12px;
            border-radius: 10px;
        }
        .btn-success:hover {
            background: #f6e05e !important;
            transform: translateY(-2px);
        }
        
        .login-card a {
            color: #d4af37;
            text-decoration: none;
        }
        .login-card a:hover {
            color: #f6e05e;
            text-decoration: underline;
        }
        
        .login-card h4 {
            color: white;
        }
        
        .alert-danger {
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ffcccc;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="login-card animate-slide">
        <!-- MKU Logo -->
        <div class="mku-logo">
            <div class="logo-box">
                <span>MKU</span>
            </div>
            <h5>MOUNT KENYA UNIVERSITY</h5>
            <p>Unlocking Infinite Possibilities.</p>
        </div>
        
        <h4 class="text-center mb-3">🎓 Student Login</h4>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label>Registration Number</label>
                <input type="text" name="reg_number" class="form-control" placeholder="e.g., BSCCS/2025/42975" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
        <p class="text-center mt-3"><a href="index.php">← Back to Home</a></p>
    </div>
</body>
</html>