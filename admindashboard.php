<?php
session_start();

// Logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>alert('Logged out successfully'); window.location.href='index.php';</script>";
    exit();
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login page if not logged in as admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapVerse Studios Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar Styles */
        #sidebar-wrapper {
            min-height: 100vh;
            width: 180px;
            background-color: #1e3a5f; /* Dark blue shade */
            color: #fff;
            position: fixed; /* Fix the sidebar's position */
        }

        .sidebar-heading {
            padding: 1rem;
            font-size: 1.5rem;
            text-align: center;
            background-color: #162d4e;
        }

        .list-group-item {
            border: none;
            padding: 1rem;
            background-color: transparent;
            color: #fff;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #ff8c00; /* Distinct hover color */
            color: #fff; /* Text color on hover */
        }

        .list-group-item.active {
            background-color: #345a8a; /* Fixed color when clicked */
            color: #fff; /* Active item text color */
        }

        /* Main Section Styles */
        #page-content-wrapper {
            margin-left: 180px; /* Make room for the fixed sidebar */
            background-color: #ffffff; /* White background for the main section */
            padding: 20px; /* Add some padding for spacing */
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">SnapVerse Studios</div>
            <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action active">Dashboard</a>
            <a href="martists.php" class="list-group-item list-group-item-action">Artists</a>
            <a href="mportfolio.php" class="list-group-item list-group-item-action">Portfolio</a>
            <a href="#" class="list-group-item list-group-item-action">Services</a>
            <a href="#" class="list-group-item list-group-item-action">Bookings</a>
            <a href="#" class="list-group-item list-group-item-action">Payments</a>
            <a href="#" class="list-group-item list-group-item-action">Profile</a>
            <a href="admindashboard.php?logout=true" class="list-group-item list-group-item-action">Logout</a>

            </div>
        </div>

        <div id="page-content-wrapper" class="p-4">
            <h1>Welcome to SnapVerse Studios Admin Panel</h1>
            <p>Manage your photography services, bookings, and customer interactions seamlessly.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
