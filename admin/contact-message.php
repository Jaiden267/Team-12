<?php
include 'header.php';

$messages = mysqli_query($conn, 'SELECT * FROM contact_messages');
?>

<main>
    <h1>Contact Messages</h1>
    <div class="table-data">
        <div class="order">
            <table id="contactMessagesTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($message = mysqli_fetch_assoc($messages)): ?>
                        <tr>
                            <td><?php echo $message['name']; ?></td>
                            <td><?php echo $message['email']; ?></td>
                            <td><?php echo $message['message']; ?></td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
	$(document).ready(function() {
		new DataTable('#contactMessagesTable');
	});
</script>

<?php include 'footer.php'; ?>