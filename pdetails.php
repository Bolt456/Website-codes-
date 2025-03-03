<?php
include 'connection.php'; // Include your database connection file

// Set the character set to display emojis
mysqli_set_charset($conn, 'utf8mb4');

// Get package ID from URL
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : 0;

// Fetch package details based on the package ID
$sql = "SELECT * FROM packages WHERE p_id = $package_id";
$result = mysqli_query($conn, $sql);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    echo "<h2 class='text-center text-danger mt-5'>Package not found!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"> <!-- Changed to utf-8 to support emojis -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $package['p_name'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <style>
        video.package-video {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
    <div class="container">
        <a class="navbar-brand" href="home.php">SnapVerse Studios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="#navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="packages.php">Packages</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="artists.php">Artists</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-md-5">
                        <?php
                        $filePath = htmlspecialchars($package['image']);
                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                        if (in_array($fileExtension, ['mp4', 'webm', 'ogg'])): ?>
                            <!-- Video Display -->
                            <video class="img-fluid rounded package-video" autoplay muted loop playsinline>
                                <source src="<?= $filePath ?>" type="video/<?= $fileExtension ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <!-- Image Display -->
                            <img src="<?= $filePath ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($package['p_name']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7">
                        <h2 class="mb-3 fw-bold text-center"><?= str_replace(
                                [
                                    "Timeless Traditions:", 
                                    "Together Forever:", 
                                    "Runway Ready:", 
                                    "Enchanted Moments:", 
                                    "Eternal Bloom:", 
                                    "Timeless Celebration:", 
                                    "Engaged in Love:",
                                    "Professional Impact:"
                                ], 
                                [
                                    "Timeless Traditions:<br>", 
                                    "Together Forever:<br>", 
                                    "Runway Ready:<br>", 
                                    "Enchanted Moments:<br>", 
                                    "Eternal Bloom:<br>", 
                                    "Timeless Celebration:<br>", 
                                    "Engaged in Love:<br>",
                                    "Professional Impact:<br>"
                                ], 
                                htmlspecialchars($package['p_name'])
                            ) ?></h2>
                        <!-- Centered Description -->
                        <p class="fw-normal text-center"><?= nl2br(htmlspecialchars($package['p_desc'])) ?></p>
                        <!-- Centered Price -->
                        <p class="fw-normal text-center">Price: &#8377;<?= htmlspecialchars($package['p_price']) ?></p>
                        <!-- Display Details -->
                        <div class="mt-3">
                            <h5 class="fw-bold">Details:</h5>
                            <p class="fw-normal text-start"><?= nl2br(htmlspecialchars($package['details'])) ?></p>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="cbooking.php?package_id=<?= $package['p_id'] ?>&package_price=<?= $package['p_price'] ?>" class="btn btn-primary btn-sm rounded-pill px-3">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-light text-center text-lg-start mt-4">
    <div class="container p-4">
        <h5 class="text-uppercase">Quick Links</h5>
        <ul class="list-unstyled">
            <li><a href="home.php" class="text-dark text-decoration-none">Home</a></li>
            <li><a href="packages.php" class="text-dark text-decoration-none">Packages</a></li>
            <li><a href="contactus.php" class="text-dark text-decoration-none">Contact Us</a></li>
            <li><a href="artists.php" class="text-dark text-decoration-none">Our Artists</a></li>
        </ul>
    </div>
    <div class="text-center p-3 bg-dark text-white">
        © 2025 SnapVerse Studios
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
