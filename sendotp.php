<?php
session_start();
include 'connection.php'; // Database connection

// Include PHPMailer files
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = ''; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, generate OTP
            $otp = rand(100000, 999999); 
            $_SESSION['otp'] = $otp; 
            $_SESSION['email'] = $email; 

            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pavanclicks2004@gmail.com'; 
                $mail->Password = 'fqwn oaqz khhe qxyj'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('pavanclicks2004@gmail.com', 'Snapverse Studios');
                $mail->addAddress($email);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body = "Your OTP for password reset is: $otp";

                // Send email
                if ($mail->send()) {
                    echo "<script>
                            alert('OTP sent to your registered email address.');
                            window.location.href = 'otp.php';
                          </script>";
                    exit();
                } else {
                    $message = "Failed to send OTP. Please try again.";
                }
            } catch (Exception $e) {
                $message = "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            $message = "Email not found. Please register first.";
        }

        $stmt->close(); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <style>
        body {
            background-image: url('lens3.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100%;
        }

        .card {
            background-color: rgb(226, 227, 207);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        .submit_btn {
            width: 100%;
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
            color: black;
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <section class="wrapper">
        <div class="card">
            <form class="form-group" action="sendotp.php" method="POST">
                <h3 class="text-center mb-3 mt-2">Forgot Password</h3>
                <div class="fw-normal mb-2 text-center"> Enter email to reset your password </div>

                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Email Address" required/>
                
                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary submit_btn ms-1 mt-2 mb-2 my-4">Send OTP</button>
                </div>

                <?php if ($message != ''): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Message fade-out effect after 2 seconds
        setTimeout(() => {
            let messageDiv = document.querySelector('.message');
            if (messageDiv) {
                messageDiv.style.opacity = '0';
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 1000);
            }
        }, 2000);
    </script>
</body>
</html>
