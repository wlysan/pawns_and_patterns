<?php
// Admin Login Structure
// This file provides the basic HTML structure for admin login page
// It's deliberately minimal to focus on security and performance

// Start output buffering for clean output
ob_start();

// Define page title
$page_title = "Admin Login | Paws&Patterns";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $page_title; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="/assets/bootstrap-5.2.3-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Admin Styles -->
    <link rel="stylesheet" href="/plugins/admin/css/admin_login.css">
</head>
<body class="admin-login-body">
    <div class="admin-login-container">
        <!-- Main Content Area -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="admin-login-card">
                        <div class="admin-login-header">
                            <!-- Logo -->
                            <div class="logo-container">
                                <div class="logo-image-container">
                                    <span class="logo-text">Paws</span>
                                    <img class="logo-image" src="/assets/images/logo.svg" alt="Paws&Patterns">
                                    <span class="logo-text">Patterns</span>
                                </div>
                                <p class="logo-tagline">Administration Panel</p>
                            </div>
                        </div>
                        
                        <!-- Content Section -->
                        <div class="admin-login-content">
                            <?php 
                            // Load the specific view content
                            get_std_controller($rota['route']);
                            include get_view($rota['route']);
                            ?>
                        </div>
                        
                        <div class="admin-login-footer">
                            <p>&copy; <?php echo date('Y'); ?> Paws&Patterns - Pet Boutique Ireland. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="/assets/bootstrap-5.2.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
<?php
// Send the output buffer and end it
ob_end_flush();
?>