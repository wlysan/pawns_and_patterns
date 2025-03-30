<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogFashion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Infant:wght@500;600;700&family=Josefin+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
    /* Minimal custom CSS */
    :root {
        --hero-font: 'Cormorant Infant', serif;
        --body-font: 'Josefin Sans', sans-serif;
    }

    .fixed-header {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
        background: rgba(255, 255, 255, 0.95);
        border-bottom: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .hero-section {
        height: 90vh;
        min-height: 600px;
        background-size: cover;
        background-position: center;
    }

    .product-card img {
        transition: transform 0.3s ease;
    }

    .product-card:hover img {
        transform: scale(1.03);
    }

    body {
        padding-top: 80px;
        font-family: var(--body-font);
    }

    .brand-logo {
        font-family: var(--hero-font);
        font-weight: 600;
        letter-spacing: 1px;
    }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg fixed-header">
        <div class="container">
            <!-- Left Menu -->
            <ul class="navbar-nav d-none d-lg-flex">
                <li class="nav-item"><a href="#" class="nav-link">New In</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Collections</a></li>
            </ul>

            <!-- Center Logo -->
            <a class="navbar-brand brand-logo mx-auto" href="index.php">DogFashion</a>

            <!-- Right Icons -->
            <div class="d-flex align-items-center">
                <a href="login.php" class="nav-link me-3"><i class="fas fa-user"></i></a>
                <a href="#" class="nav-link me-3"><i class="fas fa-search"></i></a>
                <a href="#" class="nav-link"><i class="fas fa-shopping-bag"></i></a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php
        get_std_controller($rota['route']);
        include get_view($rota['route']);
        ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h5 class="text-uppercase mb-4">DogFashion</h5>
                    <p class="text-secondary">Celebrating canine elegance through bespoke fashion design.</p>
                </div>
                
                <div class="col-lg-2">
                    <h6 class="text-uppercase mb-4">Collections</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-secondary text-decoration-none">Summer 2024</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Bridal</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Accessories</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="text-uppercase mb-4">Customer Service</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-secondary text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Shipping</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Returns</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="text-uppercase mb-4">Follow Us</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-secondary"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="text-secondary"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-top pt-4 mt-4 text-center text-lg-start">
                <div class="row">
                    <div class="col-lg-6">
                        <p class="text-secondary mb-0">&copy; 2024 DogFashion. All rights reserved.</p>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <a href="#" class="text-secondary text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-secondary text-decoration-none">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>