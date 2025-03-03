<?php
include 'connection.php'; // Include your database connection file

// Get category from URL
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch categories for the dropdown
$sql_categories = "SELECT c_id, c_name FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

// Fetch packages based on selected category
$sql = $category_id > 0 ? "SELECT * FROM packages WHERE c_id = $category_id" : "SELECT * FROM packages";
$result = mysqli_query($conn, $sql);
$packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <style>
        .card-img-top {
            height: 300px;
            object-fit: cover;
        }

        .card {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
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
                <li class="nav-item"><a class="nav-link" href="artists.php">Artists</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4">Our Packages</h2>

    <!-- Category Filter Dropdown -->
    <div class="text-center mb-4">
        <form method="GET" action="packages.php">
            <label for="category" class="fw-bold">Select Category:</label>
            <select name="category" id="category" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="0">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['c_id'] ?>" <?= ($category_id == $cat['c_id']) ? 'selected' : '' ?> >
                        <?= htmlspecialchars($cat['c_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="row">
        <?php foreach ($packages as $package): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="pdetails.php?package_id=<?= $package['p_id'] ?>" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        <?php
                        $filePath = htmlspecialchars($package['image']);
                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                        if (in_array($fileExtension, ['mp4', 'webm', 'ogg'])): ?>
                            <!-- Video Display -->
                            <video class="card-img-top" autoplay loop muted playsinline>
                                <source src="<?= $filePath ?>" type="video/<?= $fileExtension ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php else: ?>
                            <!-- Image Display -->
                            <img src="<?= $filePath ?>" class="card-img-top"
                                 alt="<?= htmlspecialchars($package['p_name']) ?>"
                                 onerror="this.onerror=null;this.src='uploads/default.jpg';">
                        <?php endif; ?>

                        <div class="overlay">View Details</div>
                        <div class="card-body text-center">
                            <h5 class="card-title text-dark"><?= str_replace(
                                    [
                                        "Eternal Bloom:", 
                                        "Timeless Traditions:", 
                                        "Timeless Celebration:", 
                                        "Engaged in Love:", 
                                        "Professional Impact:"
                                    ], 
                                    [
                                        "Eternal Bloom:<br>", 
                                        "Timeless Traditions:<br>", 
                                        "Timeless Celebration:<br>", 
                                        "Engaged in Love:<br>", 
                                        "Professional Impact:<br>"
                                    ], 
                                    htmlspecialchars($package['p_name'])
                                ) ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
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
