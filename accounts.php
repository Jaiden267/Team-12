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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='stylesheet'>

	<!-- DataTables -->
	<link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>

	<!-- My CSS -->
	<link rel="stylesheet" href="styles.css" />

	<title>Account Settings</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<a href="index.php"><img src="../assets/lunare_logo.png" alt="Lunare Clothing logo" style="width: 150px; height: 60px; margin-left: 20px; margin-top:20px;"></a>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="dashboard.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">My Account</span>
				</a>
			</li>
            <li>
				<a href="mydetails.php">
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">My Details</span>
				</a>
			</li>

			<li>
				<a href="myorders.php">
					<i class='bx bxs-doughnut-chart bx-sm' ></i>
					<span class="text">My Orders</span>
				</a>
			</li>
			
			
			<li>
				<a href="change-password.php">
					<i class='bx bxs-group bx-sm' ></i>
					<span class="text">Change Password</span>
				</a>
			</li>
			<li>
				<a href="myreviews.php">
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">My Reviews</span>
				</a>
			</li>
			<li>
				<a href="myreturns.php">
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">Returns</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu bottom">
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bx-power-off bx-sm bx-burst-hover' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<main>
    <div class="head-title">
        <div class="left">
            <h1>My Account</h1>
        </div>
    </div>

    <ul class="box-info">
        <a href="mydetails.php"><li>
            <i class='bx bxs-user'></i>
            <div class="text">
                <h3>My Details</h3>
                <p>Update personal info</p>
            </div>
        </li>

        <a href="myorders.php"><li>
            <i class='bx bxs-package'></i>
            <div class="text">
                <h3>Orders</h3>
                <p>View past orders</p>
            </div>
        </li>

        <a href="change-password.php"><li>
            <i class='bx bxs-lock'></i>
            <div class="text">
                <h3>Password</h3>
                <p>Change password</p>
            </div>
        </li>

        <a href="cart.php"><li >
            <i class='bx bxs-cart'></i>
            <div class="text">
                <h3>Basket</h3>
                <p>View your items</p>
            </div>
        </li>

        <a href="myreviews.php"><li>
            <i class='bx bxs-star'></i>
            <div class="text">
                <h3>Reviews</h3>
                <p>Rate products</p>
            </div>
        </li>

        <a href="myreturns.php"><li>
            <i class='bx bx-undo'></i>
            <div class="text">
                <h3>Returns</h3>
                <p>Return items</p>
            </div>
        </li>
    </ul>
</main>

