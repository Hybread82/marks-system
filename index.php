<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mount Kenya University - Marks Management System</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%230a2647'/><text x='50' y='70' font-size='60' text-anchor='middle' fill='%23d4af37'>MKU</text></svg>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
        
        .animate-fade {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a2647;
            background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 20px;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 38, 71, 0.8);
            z-index: 0;
        }
        
        .container {
            position: relative;
            z-index: 1;
            max-width: 900px;
        }
        
        .main-card {
            background: #0a2647;
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.5);
            text-align: center;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }
        
        .mku-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .mku-logo .logo-box {
            display: inline-block;
            background: white;
            padding: 15px 40px;
            border-radius: 15px;
            margin-bottom: 15px;
        }
        
        .mku-logo .logo-box span {
            color: #0a2647;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 4px;
        }
        
        .mku-logo h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        
        .mku-logo .tagline {
            color: #d4af37;
            font-size: 14px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .portal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            max-width: 550px;
            margin: 30px auto 0;
        }
        
        .portal-card {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        
        .portal-card:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-8px);
            border-color: #d4af37;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        
        .portal-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        .portal-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .portal-card p {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin: 0;
        }
        
        .portal-card .btn-portal {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 30px;
            background: #d4af37;
            color: #0a2647;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
        }
        
        .portal-card .btn-portal:hover {
            background: #f6e05e;
            transform: scale(1.05);
        }
        
        .footer-text {
            margin-top: 30px;
            color: rgba(255,255,255,0.4);
            font-size: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 20px;
        }
        
        .footer-text span {
            color: #d4af37;
        }
        
        @media (max-width: 600px) {
            .portal-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .main-card {
                padding: 30px 20px;
            }
            .mku-logo .logo-box span {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="container">
        <div class="main-card animate-fade">
            <!-- MKU Logo -->
            <div class="mku-logo">
                <div class="logo-box">
                    <span>MKU</span>
                </div>
                <h1>MOUNT KENYA UNIVERSITY</h1>
                <div class="tagline">Unlocking Infinite Possibilities.</div>
            </div>
            
            <div class="portal-grid">
                <!-- Lecturer Portal -->
                <a href="lecturer_login.php" class="portal-card animate-fade delay-1">
                    <span class="portal-icon">👨‍🏫</span>
                    <h3>Lecturer Portal</h3>
                    <p>Add and manage student marks</p>
                    <span class="btn-portal">Login</span>
                </a>
                
                <!-- Student Portal -->
                <a href="student_login.php" class="portal-card animate-fade delay-2">
                    <span class="portal-icon">🎓</span>
                    <h3>Student Portal</h3>
                    <p>View your marks</p>
                    <span class="btn-portal">Login</span>
                </a>
            </div>
            
            <div class="footer-text">
                &copy; <?php echo date('Y'); ?> <span>Mount Kenya University</span> — All Rights Reserved
            </div>
        </div>
    </div>
</body>
</html>