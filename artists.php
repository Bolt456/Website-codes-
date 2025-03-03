<?php
include 'connection.php';  // Include your database connection file

// Fetch all artists from the database
$query = "SELECT * FROM artists";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Artists - SnapVerse Studios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <style>
        .card-img-top {
            height: 300px;
            object-fit: cover;
        }
        .card-body {
            text-align: center;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 1rem;
            color: #555;
        }
        .card {
            position: relative;
            cursor: pointer;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(22, 20, 20, 0.6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card:hover .overlay {
            opacity: 1;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="home.php">SnapVerse Studios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="packages.php">Packages</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4">Our Artists</h2>
    <div class="row">
        <?php while ($artist = mysqli_fetch_assoc($result)) { ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100 shadow-sm" onclick="window.location.href='portfolio.php?artist_id=<?= $artist['id'] ?>'">
                    <img src="<?= $artist['image'] ?>" class="card-img-top" alt="<?= $artist['name'] ?>"
                         onerror="this.onerror=null;this.src='uploads/default.jpg';">
                    <div class="overlay">View Portfolio</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $artist['name'] ?></h5>
                        <p class="card-text"><?= $artist['role'] ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Quick Links Section -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <h5 class="text-uppercase">Quick Links</h5>
        <ul class="list-unstyled">
            <li><a href="home.php" class="text-dark text-decoration-none">Home</a></li>
            <li><a href="packages.php" class="text-dark text-decoration-none">Packages</a></li>
            <li><a href="contactus.php" class="text-dark text-decoration-none">Contact Us</a></li>
           
        </ul>
    </div>
    <div class="text-center p-3 bg-dark text-white">
        © 2025 SnapVerse Studios
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
