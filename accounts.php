<?php
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

function e(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
$firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

   
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

    
    <link rel="stylesheet" href="accounts.css" />

    <title>My Account</title>

    <style>
        
        .content-card { background: var(--light); border-radius: 16px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        .welcome { color: var(--dark-grey); margin-top: 6px; }
       
        .card-link { display: block; color: inherit; }
        .card-link li { transition: .2s ease; }
        .card-link li:hover { transform: translateY(-2px); }
        .box-info { margin-top: 24px; }
    </style>
</head>
<body>
<div id="account-wrapper">

    
    <section id="sidebar">
    <a href="index.php" class="brand" aria-label="Lunare Clothing Home" style="display:flex; align-items:center;">
        <img src="../assets/lunare_logo.png" alt="Lunare Clothing logo"
             style="width:150px; height:60px; margin-left:20px; margin-top:20px;">
    </a>

    <ul class="side-menu top">
        <!-- Make THIS page active -->
        <li class="active">
            <a href="accounts.php">
                <i class='bx bxs-dashboard bx-sm'></i>
                <span class="text">My Account</span>
            </a>
        </li>

        <li>
            <a href="mydetails.php">
                <i class='bx bxs-user-detail bx-sm'></i>
                <span class="text">My Details</span>
            </a>
        </li>

        <li>
            <a href="myorders.php">
                <i class='bx bxs-doughnut-chart bx-sm'></i>
                <span class="text">My Orders</span>
            </a>
        </li>

        <!-- Remove 'active' from here -->
        <li>
            <a href="change-password.php">
                <i class='bx bxs-key bx-sm'></i>
                <span class="text">Change Password</span>
            </a>
        </li>

        <li>
            <a href="myreviews.php">
                <i class='bx bxs-message-dots bx-sm'></i>
                <span class="text">My Reviews</span>
            </a>
        </li>

        <li>
            <a href="myreturns.php">
                <i class='bx bxs-package bx-sm'></i>
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
    
    <section id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>My Account</h1>
                    <?php if ($firstName): ?>
                        <p class="welcome">Welcome back, <?= e($firstName) ?>.</p>
                    <?php endif; ?>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="accounts.php">My Account</a></li>
                    </ul>
                </div>
            </div>

            
            <ul class="box-info">
                <a class="card-link" href="mydetails.php">
                    <li>
                        <i class='bx bxs-user'></i>
                        <span class="text">
                            <h3>My Details</h3>
                            <p>Update personal info</p>
                        </span>
                    </li>
                </a>

                <a class="card-link" href="myorders.php">
                    <li>
                        <i class='bx bxs-package'></i>
                        <span class="text">
                            <h3>Orders</h3>
                            <p>View past orders</p>
                        </span>
                    </li>
                </a>

                <a class="card-link" href="change-password.php">
                    <li>
                        <i class='bx bxs-lock'></i>
                        <span class="text">
                            <h3>Password</h3>
                            <p>Change password</p>
                        </span>
                    </li>
                </a>

                <a class="card-link" href="cart.php">
                    <li>
                        <i class='bx bxs-cart'></i>
                        <span class="text">
                            <h3>Basket</h3>
                            <p>View your items</p>
                        </span>
                    </li>
                </a>

                <a class="card-link" href="myreviews.php">
                    <li>
                        <i class='bx bxs-star'></i>
                        <span class="text">
                            <h3>Reviews</h3>
                            <p>Rate products</p>
                        </span>
                    </li>
                </a>

                <a class="card-link" href="myreturns.php">
                    <li>
                        <i class='bx bx-undo'></i>
                        <span class="text">
                            <h3>Returns</h3>
                            <p>Return items</p>
                        </span>
                    </li>
                </a>
            </ul>

          
        </main>
    </section>
   

</div>
</body>
</html>