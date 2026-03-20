<?php
include '../db_connect.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
	header('Location: login.php');
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
	<link rel="stylesheet" href="assets/style.css">

	<title>AdminHub</title>
</head>
<body>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="../assets/lunare_logo.png" alt="Lunare Clothing logo" style="width: 150px; height: 60px; margin-left: 20px; margin-top:20px;">
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="dashboard.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="category-view.php">
					<i class='bx bxs-doughnut-chart bx-sm' ></i>
					<span class="text">Categories</span>
				</a>
			</li>
			<li>
				<a href="product-vew.php">
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">Products</span>
				</a>
			</li>
			
			<li>
				<a href="customer-view.php">
					<i class='bx bxs-group bx-sm' ></i>
					<span class="text">Customers</span>
				</a>
			</li>
			<li>
				<a href="order-view.php">
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">Orders</span>
				</a>
			</li>
			<li>
				<a href="stock-view.php">
					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">Stock Management</span>
				</a>
			</li>
			<li>
				<a href="reviews.php">
					<i class='bx bxs-star bx-sm' ></i>
					<span class="text">Reviews/Ratings</span>
				</a>
			</li>
			<li>
				<a href="contact-message.php">
					<i class='bx bxs-message-dots bx-sm' ></i>
					<span class="text">Contact Messages</span>
				</a>
			</li>
			<li>
				<a href="../index.php">
					<i class='bx bx-globe bx-sm' ></i>
					<span class="text">Website</span>
				</a>
			</li>
			<li>
				<a href="change-password.php">
					<i class='bx bx-lock-open bx-sm' ></i>
					<span class="text">Change Password</span>
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