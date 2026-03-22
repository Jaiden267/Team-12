<?php
include 'header.php';

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $user_id = $_SESSION['admin_id'] ?? null;
    if (!$user_id) {
        echo "<script>alert('You are not logged in as an admin.'); window.location.href = 'login.php';</script>";
    }

    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match'); window.location.href = 'change-password.php';</script>";
    }

    if (strlen($new_password) < 8) {
        echo "<script>alert('New password must be at least 8 characters long'); window.location.href = 'change-password.php';</script>";
    }

    $stmt = $conn->prepare('SELECT password_hash FROM users WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || empty($user['password_hash'])) {
        echo "<script>alert('User not found'); window.location.href = 'change-password.php';</script>";
    }

    if (!password_verify($current_password, $user['password_hash'])) {
        echo "<script>alert('Current password is incorrect'); window.location.href = 'change-password.php';</script>";
    }

    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->prepare('UPDATE users SET password_hash = ? WHERE user_id = ?');
    $update->bind_param('si', $new_password_hash, $user_id);

    if ($update->execute()) {
        echo "<script>alert('Password changed successfully'); window.location.href = 'change-password.php';</script>";
    } else {
        echo "<script>alert('Failed to change password'); window.location.href = 'change-password.php';</script>";
    }
}
?>
      
<main>
	<h1>Change Password</h1>
	<form action="change-password.php" method="post">
		<div>
			<label for="current_password" class="form-label">Current Password</label>
			<input type="password" id="current_password" name="current_password" required class="form-input">
		</div>
		<div>
			<label for="new_password" class="form-label">New Password</label>
			<input type="password" id="new_password" name="new_password" required class="form-input">
		</div>
		<div>
			<label for="confirm_password" class="form-label">Confirm Password</label>
			<input type="password" id="confirm_password" name="confirm_password" required class="form-input">
		</div>
		<input type="submit" name="change_password" value="Change Password" class="btn" style="margin-top: 20px;">
	</form>
</main>

<?php include 'footer.php'; ?>
