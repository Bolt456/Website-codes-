<?php
session_start();
include 'connection.php';

// Check if the user is logged in properly
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query the database for user information
$query = "SELECT email, username, phone_no FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);

// Check if the statement was prepared successfully
if ($stmt === false) {
    error_log("Database query failed: " . $conn->error);
    echo '<div class="alert alert-danger text-center">An error occurred. Please try again later.</div>';
    exit;
}

// Bind the user_id to the query
$stmt->bind_param("i", $user_id);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if user data is found
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo '<div class="alert alert-danger text-center">User not found.</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('profilebackground.jpg') no-repeat center center fixed; 
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }
        .profile-card {
            max-width: 550px;
            border: none;
            border-radius: 15px;
            background:rgb(202, 223, 232); 
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            padding: 30px;
            color: #000000; 
        }
        .profile-img img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-top: -65px;
            border: 5px solid #ffffff;
            background: #f8f9fa;
        }
        .card-body h3 {
            margin-bottom: 15px;
            font-size: 2rem;
            font-weight: bold;
            color:rgb(24, 15, 15); /* White title text for better contrast */
        }
        .profile-info {
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: #000000; /* Black text for details */
        }
        .btn-custom {
            background-color: #E76F51; /* Burnt orange button */
            color: #fff;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-custom:hover {
            background-color: #D54B30; /* Darker burnt orange on hover */
        }
    </style>
</head>
<body>
    <div class="card profile-card text-center">
        <div class="card-body p-4">
            <div class="profile-img mx-auto">
                <img src="userimage.png" alt="Profile Picture">
            </div>
            <h3 class="mt-3">User Profile</h3>
            <hr>
            <div class="profile-info">
                <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
            </div>
            <div class="profile-info">
                <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
            </div>
            <div class="profile-info">
                <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_no']); ?>
            </div>
            <hr>
            <a href="home.php" class="btn btn-custom px-4">Back</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
