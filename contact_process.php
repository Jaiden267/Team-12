<?php
require_once 'db_connect.php';

// Your email
$to = "lunare.clothing@mail.com";

// Get values
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$message = trim($_POST['message']);

// Backend validation
if (empty($name) || empty($email) || empty($message)) {
    echo "<script>
        alert('All fields are required.');
        window.location.href='contact.php';
    </script>";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('Please enter a valid email address.');
        window.location.href='contact.php';
    </script>";
    exit();
}

// Saves to database
$sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $message);

if (!$stmt->execute()) {
    echo "<script>
        alert('There was an error saving your message.');
        window.location.href='contact.php';
    </script>";
    exit();
}

// send email notifcation
$subject = "New Contact Enquiry from $name";

$body = "You received a new message from your website:\n\n" .
        "Name: $name\n" .
        "Email: $email\n\n" .
        "Message:\n$message\n";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";

@mail($to, $subject, $body, $headers);

// Pop up message for successful submission
echo "<script>
    alert('Thank you! Your message has been sent and saved successfully.');
    window.location.href='contact.php';
</script>";

$stmt->close();
$conn->close();
?>
