<?php
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
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require "mailerconfig.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <br><br>
    <div class="cont">
        <div class="form sign-in">
            <h2>Welcome</h2>
            <form action="login.php" method="POST">
                <label>
                    <span>Email</span>
                    <input name="email" type="email" required />
                </label>
                <label>
                    <span>Password</span>
                    <input name="password" type="password" required />
                </label>
                <p class="forgot-pass">Forgot password?</p>
                <button name="sign_in" type="submit" class="submit">Sign In</button>
            </form>
        </div>
        <div class="sub-cont">
            <div class="img">
                <div class="img__text m--up">
                    <h2>Hello,Friend!</h2>
                    <h3>Enter your personal details and start journey with us !</h3>
                </div>
                <div class="img__text m--in">
                    <h3>If you already have an account, just sign in .</h3>
                </div>
                <div class="img__btn">
                    <span class="m--up">Sign Up</span>
                    <span class="m--in">Sign In</span>
                </div>
            </div>
            <div class="form sign-up">
                <form action="" method="POST">
                    <h2>Create your Account</h2>
                    <label>
                        <span>Name</span>
                        <input name="username" type="text" required />
                    </label>
                    <label>
                        <span>Email</span>
                        <input name="email" type="email" required />
                    </label>
                    <label>
                        <span>Password</span>
                        <input name="password" type="password" required />
                    </label>
                    <label>
                        <span>Confirm Password</span>
                        <input name="confirmpass" type="password" required />
                    </label>
                    <label>
                        <span>Phone Number</span>
                        <input name="phone_number" type="text" required />
                    </label>
                    <button name="sign_up" type="submit" class="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.img__btn').addEventListener('click', function() {
            document.querySelector('.cont').classList.toggle('s--signup');
        });
    </script>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['sign_up'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirmpass'];
        $username = $_POST['username'];
        $phone_number = $_POST['phone_number'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format!')</script>";
            echo "<meta http-equiv='refresh' content='0;url=login.php'>";
            exit;
        }

        // Validate password length
        if (strlen($password) < 8) {
            echo "<script>alert('Password must be at least 8 characters long!')</script>";
            echo "<meta http-equiv='refresh' content='0;url=index.html'>";
            exit;
        }

        // Validate password contains at least one letter
        if (!preg_match("/[a-z]/i", $password)) {
            echo "<script>alert('Password should contain at least one letter!')</script>";
            echo "<meta http-equiv='refresh' content='0;url=index.html'>";
            exit;
        }

        // Validate password contains at least one number
        if (!preg_match("/[0-9]/", $password)) {
            echo "<script>alert('Password should contain at least one number!')</script>";
            echo "<meta http-equiv='refresh' content='0;url=index.html'>";
            exit;
        }

        // Validate passwords match
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!')</script>";
            echo "<meta http-equiv='refresh' content='0;url=index.html'>";
            exit;
        }

        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists!');</script>";
            echo "<meta http-equiv='refresh' content='0;url=login.php'>";
            exit;
        }

        // Insert new user
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $query = "INSERT INTO users (username, password, email, phone_number, verification_code) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query);
        $hashpass = password_hash($password, PASSWORD_DEFAULT);
        $stmt2->bind_param("sssss", $username, $hashpass, $email, $phone_number, $verification_code);

        if ($stmt2->execute()) {
            // Send verification email
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ahmadghosen20@gmail.com'; // Your Gmail address
                $mail->Password = 'rkodaawdlfntfrcs'; // Your Gmail password or App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                 $_SESSION['email'] = $email;
                // Email content
                $mail->setFrom('your_email@gmail.com', 'Chaina global door Administrator');
                $mail->addAddress($email, $username);
                $mail->isHTML(true);
                $mail->Subject = 'Email verification';
                $mail->Body = '<p>Dear <b>' . $username . '</b>,</p><p>Your verification code is: <b style="font-size: 15px;">' . $verification_code . '</b></p>
                       <p>Regards,</p><p>Chaina global door Administrator</p>';

                $mail->send();
                echo "<script>alert('Registration successful! Please check your email to verify your account.'); window.location.href = 'email-verification.php';</script>";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<script>alert('Error during registration!');</script>";
            echo "Error: " . $stmt2->error;
        }

        $stmt->close();
        $stmt2->close();
    // The login logic remains the same...
    } elseif (isset($_POST['sign_in'])) {
        // Retrieve email and password from the form
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Check if username already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result(); // fetch the result
    
        // Check if the user exists and the password matches
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables if needed
                $_SESSION['user_email'] = $email;
    
                // Check user type and redirect accordingly
                if ($row['role'] == 'user') {
                    header('Location: index.php');
                } elseif ($row['role'] == 'admin') {
                    header('Location: admin/home.php');
                } elseif ($row['role'] == 'realtor') {
                    header('Location: realtor/home.php');
                }
                exit; // Stop further execution
            } else {
                // Password is incorrect
                echo "<script>alert('Incorrect password!');</script>";
                echo "<meta http-equiv='refresh' content='0;url=login.php'>";
                exit; // Stop further execution
            }
        } else {
            // User does not exist
            echo "<script>alert('User not found!');</script>";
            echo "<meta http-equiv='refresh' content='0;url=login.php'>";
            exit; // Stop further execution
        }
    
        $stmt->close();
    }
}
?>
