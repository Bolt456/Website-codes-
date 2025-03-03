<?php
session_start();
include 'connection.php'; // Include the database connection file

// Admin Email and Password 
$admin_email = "admin12345@gmail.com";
$admin_password = "admin12345"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 

    // Checks credentials for admin 
    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;
        header('Location: admindashboard.php');
        exit();
    } else {
        
        // Checks if details are for user 
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: home.php');
                exit();
            } else {
                echo "<script>alert('Incorrect password'); window.location.href='index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Enter Registered Email Or Register First'); window.location.href='index.php';</script>";
            exit();
        }
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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

        .login-card {
            background-color: rgb(226, 227, 207);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 350px;
            height: auto;
        }

        .input-group-text {
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="login-card mx-auto">
            <form method="POST" action="index.php">
                <h2 class="text-center mb-4">User Login</h2>
                
                <label for="email" class="form-label fw-semibold"></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
      
                <label for="password" class="form-label mt-3 fw-semibold"></label>
                <div class="input-group">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>

                <button type="submit" class="btn btn-primary w-100 my-4">Submit</button>

                <div class="text-center">
                    <p>New User? <a href="register.php" class="fw-semibold">Register</a></p>
                    <p><a href="sendotp.php" class="fw-semibold">Forgot Password?</a></p> <!-- Modified link -->
                </div>
            </form> 
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password Visibility -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.className = type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>
</body>
</html>
