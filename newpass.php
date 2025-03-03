<?php
session_start();
include 'connection.php'; // Database connection

$message = ""; // Store validation messages

// Ensure email is passed from the OTP verification step
if (!isset($_SESSION['email'])) {
    header("Location: forgotpassword.php");
    exit();
}

$email = $_SESSION['email']; // Get the email from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation: at least 8 characters, one number, one special character
    if (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $new_password)) {
        $message = "<div class='alert alert-danger fw-bold fade-message'>Password must be at least 8 characters long, include at least one number and one special character.</div>";
    } else {
        // Fetch the current password from the database (stored as plain text)
        $query = "SELECT password FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $current_password = $user['password']; // Stored in plain text

            // Validation checks
            if ($new_password === $current_password) {
                $message = "<div class='alert alert-danger fw-bold fade-message'>New password cannot be the same as the old password.</div>";
            } elseif ($new_password !== $confirm_password) {
                $message = "<div class='alert alert-danger fw-bold fade-message'>New password and confirm password do not match.</div>";
            } else {
                // Update the password in the database (stored as plain text)
                $update_query = "UPDATE users SET password='$new_password' WHERE email='$email'";
                if (mysqli_query($conn, $update_query)) {
                    $message = "<div class='alert alert-success fw-bold fade-message'>Password updated successfully. Redirecting to login...</div>";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 2000);
                          </script>";
                } else {
                    $message = "<div class='alert alert-danger fw-bold fade-message'>Failed to update password. Please try again.</div>";
                }
            }
        } else {
            $message = "<div class='alert alert-danger fw-bold fade-message'>User not found. Please try again.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background: url('lens3.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            background: rgb(226, 227, 207); /* Soft Card Background */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .form-control {
            max-width: 100%;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            opacity: 1;
            transition: opacity 0.5s ease-in-out, max-height 0.5s ease-in-out;
            max-height: 30px;
        }
        .error-message.hide {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
        }
        .input-group-text {
            cursor: pointer;
        }
        .fade-message {
            transition: opacity 1.5s ease-in-out;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Set New Password</h2>

        <?= $message; ?> <!-- Display success/error messages -->

        <form method="POST" action="">
            <label for="new_password" class="form-label fw-semibold"></label>
            <div class="input-group">
                <input type="password" name="new_password" id="newPassword" class="form-control" placeholder="New Password" required>
                <span class="input-group-text" id="toggleNewPassword">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
            <div class="error-message" id="passwordError"></div>

            <label for="confirm_password" class="form-label fw-semibold"></label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                <span class="input-group-text" id="toggleConfirmPassword">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-primary w-100 my-4">Set Password</button>

            <p class="text-center">Already Set Your Password? <a href="index.php">Login</a></p>
        </form> 
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fade out messages after 1.5 seconds
        setTimeout(() => {
            let messageDiv = document.querySelector('.fade-message');
            if (messageDiv) {
                messageDiv.style.opacity = '0';
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 1500);
            }
        }, 1500);

        // Toggle Password Visibility
        document.getElementById('toggleNewPassword').addEventListener('click', function () {
            const newPasswordInput = document.getElementById('newPassword');
            const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        });

        // Client-side password validation
        document.getElementById('newPassword').addEventListener('input', function () {
            const password = this.value;
            const errorDiv = document.getElementById('passwordError');
            const regex = /^(?=.*[0-9])(?=.*[\W_]).{8,}$/;

            if (!regex.test(password)) {
                errorDiv.textContent = "Password must be at least 8 characters, include one number and one special character.";
            } else {
                errorDiv.textContent = "";
            }
        });
    </script>
</body>
</html>
