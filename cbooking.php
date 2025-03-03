<?php
session_start(); 

include 'connection.php'; 

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : 0;

// Fetching details form database
$sql = "SELECT * FROM packages WHERE p_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $package_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    echo "<h2 class='text-center text-danger mt-5'>Package not found!</h2>";
    exit;
}

// Todays Date 
$today = date('Y-m-d');

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username FROM users WHERE user_id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);

if (!$result_user) {
    echo "Error fetching user details: " . mysqli_error($conn);
    exit();
}

$user = mysqli_fetch_assoc($result_user);
$username = $user ? $user['username'] : 'Guest';

// Store package and user details in session
$_SESSION['package_id'] = $package['p_id'];
$_SESSION['package_name'] = $package['p_name'];
$_SESSION['package_price'] = $package['p_price'];
$_SESSION['customer_name'] = $username;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['phone'] = $_POST['phone'] ?? '';
    $_SESSION['event_date'] = $_POST['event_date'] ?? '';
    $_SESSION['event_location'] = $_POST['event_location'] ?? '';
    $_SESSION['email'] = $_POST['email'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Package - <?= htmlspecialchars($package['p_name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .booking-form {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="home.php">SnapVerse Studios</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="booking-form">
                <h3 class="text-center">Book Your Package</h3>
                <form action="summary.php" method="POST" onsubmit="return validatePhoneNumber()">
                    <input type="hidden" name="package_id" value="<?= $package['p_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Selected Package</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($package['p_name']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Package Price</label>
                        <input type="text" class="form-control" value="&#8377;<?= htmlspecialchars($package['p_price']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Date</label>
                        <input type="date" class="form-control" name="event_date" min="<?= $today ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Location</label>
                        <textarea class="form-control" name="event_location" placeholder="Enter event location" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Your Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-custom">Confirm Booking</button>
                        <a href="home.php" class="btn btn-cancel">Cancel Booking</a>
                    </div>

                    <div id="phone-error" class="text-danger text-center mt-2" style="display: none;">
                        Enter a valid 10-digit phone number.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="bg-light text-center mt-4 p-3">
    <div class="container">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">
            <li><a href="home.php" class="text-dark text-decoration-none">Home</a></li>
            <li><a href="packages.php" class="text-dark text-decoration-none">Packages</a></li>
            <li><a href="contactus.php" class="text-dark text-decoration-none">Contact Us</a></li>
            <li><a href="artists.php" class="text-dark text-decoration-none">Our Artists</a></li>
        </ul>
    </div>
    <div class="bg-dark text-white p-2">
        © 2025 SnapVerse Studios
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function validatePhoneNumber() {
        var phone = document.getElementById('phone').value;
        var errorMessage = document.getElementById('phone-error');

        if (!/^\d{10}$/.test(phone)) {
            errorMessage.style.display = 'block';
            setTimeout(() => { errorMessage.style.display = 'none'; }, 2000);
            return false;
        }
        return true;
    }
</script>

</body>
</html>
