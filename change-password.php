<?php
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- DataTables (optional if you use it elsewhere) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

    <!-- Your CSS -->
    <link rel="stylesheet" href="styles.css" />

    <title>Change Password</title>

</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="index.php" class="brand" aria-label="Home">
            <img src="../assets/lunare_logo.png" alt="Lunare Clothing logo"
                 style="width:150px;height:60px;margin-left:20px;margin-top:20px;" />
        </a>

        <ul class="side-menu top">
            <li>
                <a href="accounts.php">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="text">My Account</span>
                </a>
            </li>
            <li>
                <a href="mydetails.php">
                    <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                    <span class="text">My Details</span>
                </a>
            </li>
            <li>
                <a href="myorders.php">
                    <i class='bx bxs-doughnut-chart bx-sm'></i>
                    <span class="text">My Orders</span>
                </a>
            </li>
            <li class="active">
                <a href="change-password.php">
                    <i class='bx bxs-group bx-sm'></i>
                    <span class="text">Change Password</span>
                </a>
            </li>
            <li>
                <a href="myreviews.php">
                    <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                    <span class="text">My Reviews</span>
                </a>
            </li>
            <li>
                <a href="myreturns.php">
                    <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                    <span class="text">Returns</span>
                </a>
            </li>
        </ul>

        <ul class="side-menu bottom">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /SIDEBAR -->

    <!-- CONTENT: This wrapper is essential for proper width/offset next to the sidebar -->
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1 class="page-title">Change Password</h1>
                    <p class="page-subtitle">Keep your account secure by choosing a strong, unique password.</p>
                    <ul class="breadcrumb" style="margin-top:8px;">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="change-password.php" class="active">Change Password</a></li>
                    </ul>
                </div>
            </div>

            <div class="content-grid" style="margin-top:20px;">
                <section class="content-card">
                    <form action="change-password.php" method="post" autocomplete="off">
                        <div style="margin-bottom:16px;">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required class="form-input" />
                        </div>
                        <div style="margin-bottom:16px;">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" id="new_password" name="new_password" required class="form-input" />
                        </div>
                        <div style="margin-bottom:16px;">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required class="form-input" />
                        </div>
                        <button type="submit" name="change_password" class="btn">Change Password</button>
                    </form>
                </section>
            </div>
        </main>
    </section>
    <!-- /CONTENT -->
</body>
</html>