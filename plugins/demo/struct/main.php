<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap PWA Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional styling */
        .navbar .navbar-brand {
            flex-grow: 1;
            text-align: center;
        }
        .bottom-navbar {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .bottom-nav-item {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="menu-icon.png" alt="" width="30" height="24">
        </a>
        <a class="navbar-brand" href="#">
            <img src="menu-icon2.png" alt="" width="30" height="24">
        </a>
        <a class="navbar-brand" href="#">
            <img src="menu-icon3.png" alt="" width="30" height="24">
        </a>
        <a class="navbar-brand ms-auto" href="#">
            <img src="user-picture.jpg" alt="User" width="30" class="d-inline-block align-text-top">
        </a>
    </div>
</nav>


<?php
get_std_controller($rota['route']);
include get_view($rota['route']);
?>


    <!-- Bottom Navbar -->
    <nav class="navbar navbar-light bg-light bottom-navbar">
        <div class="row text-center">
            <div class="col bottom-nav-item">
                <img src="icon1.png" alt="Icon 1" class="icon-img">
                <div>Home</div>
            </div>
            <div class="col bottom-nav-item">
                <img src="icon2.png" alt="Icon 2" class="icon-img">
                <div>Search</div>
            </div>
            <div class="col bottom-nav-item">
                <img src="icon3.png" alt="Icon 3" class="icon-img">
                <div>Profile</div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>