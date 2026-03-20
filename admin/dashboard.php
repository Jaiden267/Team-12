	<?php
	include 'header.php';
	$new_orders = mysqli_query($conn, 'SELECT * FROM orders where created_at >= CURDATE()');
	$total_orders = mysqli_query($conn, 'SELECT * FROM orders');
	$total_sales = mysqli_query($conn, 'SELECT SUM(total) AS total_sales FROM orders ');
	$row = mysqli_fetch_assoc($total_sales);
	$total_sales = $row['total_sales'] ?? 0;
	$total_customers = mysqli_query($conn, 'SELECT * FROM users WHERE role = "customer"');

	// Stock + order KPIs
	$out_of_stock = mysqli_query($conn, 'SELECT COUNT(*) AS cnt FROM stock WHERE quantity = 0');
	$low_stock = mysqli_query($conn, 'SELECT COUNT(*) AS cnt FROM stock WHERE quantity > 0 AND quantity <= 5');
	$pending_orders = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM orders WHERE payment_status = 'pending'");

	$out_of_stock_row = mysqli_fetch_assoc($out_of_stock);
	$low_stock_row = mysqli_fetch_assoc($low_stock);
	$pending_orders_row = mysqli_fetch_assoc($pending_orders);

	$out_of_stock_cnt = (int) ($out_of_stock_row['cnt'] ?? 0);
	$low_stock_cnt = (int) ($low_stock_row['cnt'] ?? 0);
	$pending_orders_cnt = (int) ($pending_orders_row['cnt'] ?? 0);
	?>
	<main>
		<div class="head-title">
			<div class="left">
				<h1>Dashboard</h1>
			</div>

		</div>

		<ul class="box-info">
			<li>
				<i class='bx bxs-calendar-check'></i>
				<span class="text">
					<h3><?php echo mysqli_num_rows($total_orders); ?></h3>
					<p>Total Orders</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-time'></i>
				<span class="text">
					<h3><?php echo $pending_orders_cnt; ?></h3>
					<p>Pending Orders</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-group'></i>
				<span class="text">
					<h3><?php echo mysqli_num_rows($total_customers); ?></h3>
					<p>Total Customers</p>
				</span>
			</li>
			<li>
				<i class='bx bx-money'></i>
				<span class="text">
					<h3><?php echo number_format($total_sales, 2); ?></h3>
					<p>Total Revenue Generated</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-error'></i>
				<span class="text">
					<h3><?php echo $out_of_stock_cnt; ?></h3>
					<p>Out of Stock Products</p>
				</span>
			</li>
			<li>
				<i class='bx bxs-bulb'></i>
				<span class="text">
					<h3><?php echo $low_stock_cnt; ?></h3>
					<p>Low Stock Products (<= 5)</p>
				</span>
			</li>
			
		</ul>


		<div class="table-data">
			<div class="order">
				<div class="head">
					<h3>Recent Orders</h3>

				</div>
				<table>
					<thead>
						<tr>
							<th>Order ID</th>
							<th>User</th>
							<th>Total Price</th>
							<th>Date Order</th>
							<th>Payment Status</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($order = mysqli_fetch_assoc($new_orders)): ?>
							<a href="order-details.php?id=<?php echo $order['order_id']; ?>" >
								<tr>
									<td><?php echo $order['order_id']; ?></td>
									<td><?php echo $order['full_name']; ?></td>
									<td><?php echo number_format($order['total'], 2); ?></td>
									<td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
									<td><span class="status <?php echo $order['payment_status']; ?>"><?php echo $order['payment_status']; ?></span></td>
								</tr>
							</a>
						<?php endwhile; ?>

					</tbody>
				</table>
			</div>
			<div class="todo">
				<div class="head">
					<h3>Todos</h3>


				</div>
				<ul class="todo-list">
					<a href="stock-view.php" style="text-decoration: none; color: inherit;">
						<li class="completed" style="margin-bottom: 10px;">
							<p>View Stock</p>
						</li>
					</a>

					<a href="reviews.php" style="text-decoration: none; color: inherit;">
						<li class="completed" style="margin-bottom: 10px;">
							<p>View Reviews</p>

						</li>
					</a>
					<a href="contact-message.php" style="text-decoration: none; color: inherit;">
						<li class="completed" style="margin-bottom: 10px;">
							<p>View Contact Messages</p>

						</li>
					</a>
					<a href="order-view.php" style="text-decoration: none; color: inherit;">
						<li class="completed" style="margin-bottom: 10px;">
							<p>View Orders</p>

						</li>
					</a>
				</ul>
			</div>
		</div>
	</main>
	<?php include 'footer.php'; ?>