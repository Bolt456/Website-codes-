<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch current user details
$query = "SELECT email, username, phone_no FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $phone_no = isset($_POST['phone_no']) ? trim($_POST['phone_no']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Check if new details are different from current details
    if ($username == $user['username'] && $phone_no == $user['phone_no'] && $email == $user['email']) {
        $message = '<div id="alert" class="alert alert-warning text-center">Enter new details for updating the profile.</div>';
    } elseif (empty($username) || empty($phone_no) || empty($email)) {
        $message = '<div id="alert" class="alert alert-danger text-center">All fields are required.</div>';
    } else {
        // Update query
        $query = "UPDATE users SET email = ?, username = ?, phone_no = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("sssi", $email, $username, $phone_no, $user_id);
        if ($stmt->execute()) {
            $_SESSION['user_email'] = $email;  // Update session variable
            $message = '<div id="alert" class="alert alert-success text-center">Profile updated successfully.</div>';
        } else {
            $message = '<div id="alert" class="alert alert-danger text-center">Error updating profile.</div>';
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
    <title>Update Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .update-profile-card {
            width: 90%;
            max-width: 400px;
            padding: 25px;
            border-radius: 10px;
            background: #ffffff;
        }
        .update-profile-card h3 {
            color:rgb(27, 24, 25);
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-label {
            color:rgb(23, 23, 29);
            font-weight: 500;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-primary {
            background-color:rgb(228, 161, 61);
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color:rgb(237, 175, 88);
            transform: scale(1.05);
        }
        .btn-secondary {
            background-color:rgb(98, 95, 96);
            border-radius: 20px;
        }
        .btn-secondary:hover {
            background-color:rgb(80, 89, 85);
            transform: scale(1.05);
        }
        #alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="update-profile-card">
        <h3>Update Profile</h3>
        <?php echo $message; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="text" class="form-control" name="phone_no" value="<?php echo htmlspecialchars($user['phone_no']); ?>" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="home.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            var alertBox = document.getElementById('alert');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, 2000);
    </script>
</body>
</html>
