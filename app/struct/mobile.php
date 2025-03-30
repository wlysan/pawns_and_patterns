<!doctype html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport"
              content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="theme-color" content="#000000">
        <title>Repairly</title>
        <meta name="description" content="Repairly">
        <meta name="keywords"
              content="" />
        <link rel="icon" type="image/png" href="/assets/img/favicon.png" sizes="32x32">
        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icon/192x192.png">
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="manifest" href="/__manifest.json">
        <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
        <script>
            const beamsClient = new PusherPushNotifications.Client({
                instanceId: '620d2f79-5830-4ffe-aec3-e62bc148c5a9',
            });

            beamsClient.start()
                    .then(() => beamsClient.addDeviceInterest('hello'))
                    .then(() => console.log('Successfully registered and subscribed!'))
                    .catch(console.error);
        </script>

    </head>

    <body>


        <!-- loader -->
        <div id="loader">
            <img src="/assets/img/loading-icon.png" alt="icon" class="loading-icon">
        </div>
        <!-- * loader -->

        <!-- App Header -->
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                    <ion-icon name="menu-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">
                <img src="/assets/img/logo4.png" alt="logo" class="logo">
            </div>
            <div class="right">
                <a href="#" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>
                <a href="#" class="headerButton">
                    <img src="/assets/img/sample/avatar/avatar1.jpg" alt="image" class="imaged w32">
                    <span class="badge badge-danger">6</span>
                </a>
            </div>
        </div>
        <!-- * App Header -->


        <!-- App Capsule -->
        <div id="appCapsule">
            <?php
             get_std_controller($rota['route']);
             include get_view($rota['route']);
            ?>
        </div>
        <!-- * App Capsule -->

        <!-- App Bottom Menu -->
        <div class="appBottomMenu">
            <a href="/index.php/home" class="item <?php highlight_route('/home'); ?>">
                <div class="col">
                    <ion-icon name="home-outline"></ion-icon>
                    <strong>Home</strong>
                </div>
            </a>
            <a href="#" class="item <?php highlight_route('/history'); ?>">
                <div class="col">
                    <ion-icon name="document-text-outline"></ion-icon>
                    <strong>History</strong>
                </div>
            </a>
            <a href="#" class="item <?php highlight_route('/requests'); ?>">
                <div class="col">
                    <ion-icon name="apps-outline"></ion-icon>
                    <strong>New Request</strong>
                </div>
            </a>
            <a href="#" class="item <?php highlight_route('/payment'); ?>">
                <div class="col">
                    <ion-icon name="card-outline"></ion-icon>
                    <strong>Payment History</strong>
                </div>
            </a>
            <a href="/index.php/settings" class="item <?php highlight_route('/settings'); ?>">
                <div class="col">
                    <ion-icon name="settings-outline"></ion-icon>
                    <strong>Settings</strong>
                </div>
            </a>
        </div>
        <!-- * App Bottom Menu -->

        <!-- App Sidebar -->
        <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <!-- profile box -->
                        <?php
                        load_hook('menu_lateral');
                        
                        ?>
                        <!-- * profile box -->
                        <!-- balance -->
                        <div class="sidebar-balance">
                            <div class="listview-title">Balance</div>
                            <div class="in">
                                <h1 class="amount">$ 2,562.50</h1>
                            </div>
                        </div>
                        <!-- * balance -->

                        <!-- action group -->
                        <div class="action-group">
                            <a href="#" class="action-button">
                                <div class="in">
                                    <div class="iconbox">
                                        <ion-icon name="add-outline"></ion-icon>
                                    </div>
                                    Deposit
                                </div>
                            </a>
                            <a href="#" class="action-button">
                                <div class="in">
                                    <div class="iconbox">
                                        <ion-icon name="arrow-down-outline"></ion-icon>
                                    </div>
                                    Withdraw
                                </div>
                            </a>
                            <a href="#" class="action-button">
                                <div class="in">
                                    <div class="iconbox">
                                        <ion-icon name="arrow-forward-outline"></ion-icon>
                                    </div>
                                    Send
                                </div>
                            </a>

                        </div>
                        <!-- * action group -->

                        <!-- menu -->
                        <div class="listview-title mt-1">Menu</div>
                        <ul class="listview flush transparent no-line image-listview">
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="home-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Home Information
                                    </div>
                                </a>
                            </li>
                            <?php
                            load_hook('menu_lateral_items');
                            
                            ?>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="apps-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Services
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="card-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Payment Methods
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- * menu -->

                        <!-- others -->
                        <div class="listview-title mt-1">Others</div>
                        <ul class="listview flush transparent no-line image-listview">
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="settings-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Settings
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="chatbubble-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Support
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="log-out-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Log out
                                    </div>
                                </a>
                            </li>


                        </ul>
                        <!-- * others -->

                        <!-- send money -->

                        <!-- * send money -->

                    </div>
                </div>
            </div>
        </div>
        <!-- * App Sidebar -->

        <!-- iOS Add to Home Action Sheet -->
        <div class="modal inset fade action-sheet ios-add-to-home" id="ios-add-to-home-screen" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add to Home Screen</h5>
                        <a href="#" class="close-button" data-bs-dismiss="modal">
                            <ion-icon name="close"></ion-icon>
                        </a>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content text-center">
                            <div class="mb-1"><img src="/assets/img/icon/192x192.png" alt="image" class="imaged w64 mb-2">
                            </div>
                            <div>
                                Install <strong>Repairly</strong> on your iPhone's home screen.
                            </div>
                            <div>
                                Tap <ion-icon name="share-outline"></ion-icon> and Add to homescreen.
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary btn-block" data-bs-dismiss="modal">CLOSE</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- * iOS Add to Home Action Sheet -->

        <!-- Android Add to Home Action Sheet -->
        <div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1"
             role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add to Home Screen</h5>
                        <a href="#" class="close-button" data-bs-dismiss="modal">
                            <ion-icon name="close"></ion-icon>
                        </a>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content text-center">
                            <div class="mb-1">
                                <img src="/assets/img/icon/192x192.png" alt="image" class="imaged w64 mb-2">
                            </div>
                            <div>
                                Install <strong>Repairly</strong> on your Android's home screen.
                            </div>
                            <div>
                                Tap <ion-icon name="ellipsis-vertical"></ion-icon> and Add to homescreen.
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary btn-block" data-bs-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Android Add to Home Action Sheet -->

        <!-- ========= JS Files =========  -->
        <!-- Bootstrap -->

        <script src="/assets/js/lib/bootstrap.bundle.min.js"></script>
        <!-- Ionicons -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <!-- Splide -->
        <script src="/assets/js/plugins/splide/splide.min.js"></script>
        <!-- Base Js File -->
        <script src="/assets/js/base.js"></script>  

        <script>
            // Add to Home with 2 seconds delay.
            AddtoHome("2000", "once");
        </script>

    </body>

</html>