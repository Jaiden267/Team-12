<?php
require_once 'db_connect.php';

if (!isset($_GET['token'])) {
    die("Invalid request");
}

$token = $_GET['token'];
?>

<form method="POST" action="resetpassword_process.php">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <p id="passwordHint" style="font-size: 12px; color: red;"></p>
    <button type="submit">Reset Password</button>
</form>