<?php
// Include Database Connection
include 'connection.php';

$emailExistsError = "";
$phoneError = "";
$usernameError = "";
$passwordError = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone_no'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (strlen($username) > 20) {
        $usernameError = "Username should not be more than 20 characters.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) { 
        $phoneError = "Phone number must be exactly 10 digits.";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $passwordError = "Password should be at least 8 characters long, containing letters, numbers, and special characters.";
    } else {
        $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            $emailExistsError = "Email already exists, use a different email.";
        } elseif ($password !== $confirm_password) {
            $emailExistsError = "Passwords do not match.";
        } else {
            $sql = "INSERT INTO users (email, username, phone_no, password) VALUES ('$email', '$username', '$phone', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registered Successfully'); window.location.href = 'index.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-center">User Registration</h2>
        <form method="POST" action="register.php" onsubmit="return validateForm()">
            <div id="errorContainer">
                <?php if (!empty($emailExistsError)) echo "<div class='error-message'>$emailExistsError</div>"; ?>
                <?php if (!empty($phoneError)) echo "<div class='error-message'>$phoneError</div>"; ?>
                <?php if (!empty($usernameError)) echo "<div class='error-message'>$usernameError</div>"; ?>
                <?php if (!empty($passwordError)) echo "<div class='error-message'>$passwordError</div>"; ?>
            </div>

            <label for="email" class="form-label fw-semibold"></label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email Address" required/>

            <label for="username" class="form-label fw-semibold"></label>
            <input type="text" name="username" class="form-control" id="username" placeholder="User Name" required/>

            <label for="phone_no" class="form-label fw-semibold"></label>
            <input type="text" name="phone_no" class="form-control" id="phone_no" placeholder="Phone Number" required/>

            <label for="password" class="form-label fw-semibold"></label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <span class="input-group-text" id="togglePassword">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>

            <label for="confirm_password" class="form-label fw-semibold"></label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                <span class="input-group-text" id="toggleConfirmPassword">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-primary w-100 my-4">Register</button>

            <p class="text-center">Already Registered?<a href="index.php"> Login</a></p>
        </form> 
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.error-message').forEach(error => {
                setTimeout(() => error.classList.add("hide"), 2000);
            });
        });

        document.getElementById('togglePassword').addEventListener('click', function () {
            togglePasswordVisibility('password', this);
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            togglePasswordVisibility('confirmPassword', this);
        });

        function togglePasswordVisibility(inputId, toggleIcon) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            toggleIcon.innerHTML = type === 'password' ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        }

        function validateForm() {
            const phoneInput = document.getElementById('phone_no').value;
            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phoneInput)) {
                alert("Phone number must be exactly 10 digits.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
