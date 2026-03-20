<?php 

include 'header.php';

$customers = mysqli_query($conn, 'SELECT * FROM users WHERE role = "customer"');
?>  

<main>
	<div class="table-data">
		<div class="order">
			<table id="customersTable">
				<thead>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Phone Number</th>
						<th>Role</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($customer = mysqli_fetch_assoc($customers)): ?>
						<tr>
							<td><?php echo $customer['first_name']; ?></td>
							<td><?php echo $customer['last_name']; ?></td>
							<td><?php echo $customer['email']; ?></td>
							<td><?php echo $customer['phone'] ?? 'N/A'; ?></td>
							<td><?php echo $customer['role']; ?></td>
							<td>
								<a href="customer-edit.php?id=<?php echo $customer['user_id']; ?>" class="btn btn-primary">Edit</a>
							</td>
						</tr>
					<?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
	$(document).ready(function() {
		new DataTable('#customersTable');
	});
</script>

<?php include 'footer.php'; ?>