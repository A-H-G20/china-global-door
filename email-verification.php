<?php
session_start();

// Check if the email is set in the session
if (!isset($_SESSION['email'])) {
    // Handle the case when the email is not set in the session (e.g., redirect back to login)
    header("Location: login.php");
    exit;
}

// Get the email from the session
$email = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "china-global-door";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["verify_email"])) {
    // Get the verification code from the form
    $verification_code = $_POST["verification_code"];

    // Prepare and bind the update query
    $stmt = $conn->prepare("UPDATE users SET email_verified_at = NOW(), is_verified = 'True' WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $verification_code);
    $stmt->execute();

    // Check if the verification was successful
    if ($stmt->affected_rows === 0) {
        echo "<script>alert('Verification code failed. Please try again.'); window.location.href = 'email-verification.php';</script>";
    } else {
        echo "<script>alert('Email verified successfully!'); window.location.href = 'login.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!-- Your HTML code -->
<link rel="stylesheet" href="css/verify.css">
<div class="container">
    <!-- Your existing HTML content -->
</div>

<form method="POST">
    <input type="text" name="verification_code" placeholder="Enter verification code" required />
    <input type="submit" name="verify_email" value="Verify Email">
</form>