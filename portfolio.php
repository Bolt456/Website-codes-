<?php
include 'connection.php'; // Include database connection

// Get artist ID from URL
if (isset($_GET['artist_id'])) {
    $artist_id = intval($_GET['artist_id']); // Prevent SQL injection

    // Fetch artist details including image, role, and experience from database
    $sql = "SELECT * FROM portfolio WHERE artist_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $artist = $result->fetch_assoc();
    } else {
        echo "<div class='container text-center mt-5'><h2>Portfolio not found.</h2></div>";
        exit;
    }

    // Fetch artist's portfolio images from pimages tablem
    $img_sql = "SELECT * FROM pimages WHERE artist_id = ?";
    $img_stmt = $conn->prepare($img_sql);
    $img_stmt->bind_param("i", $artist_id);
    $img_stmt->execute();
    $img_result = $img_stmt->get_result();
    $portfolio_images = $img_result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "<div class='container text-center mt-5'><h2>Invalid request.</h2></div>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artist['name']); ?> - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom CSS (optional) -->
    <style>
        .artist-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 15px;
        }
        .portfolio-image {
            width: 100%;
            height: 350px;
            object-fit: contain;
            border-radius: 15px;
        }
        .portfolio-text {
            text-align: center;
            margin-bottom: 30px;
        }
        .portfolio-card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .artist-details {
            text-align: left;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light m-0 p-0" id="navbar">
    <div class="container-fluid px-3">
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

<!-- Portfolio Section -->
<div class="container mt-5">
    <div class="row align-items-center">
        <!-- Artist Image on the left -->
        <div class="col-md-5">
            <img src="<?php echo htmlspecialchars($artist['image']); ?>" class="img-fluid artist-image shadow" alt="Artist Image">
        </div>

        <!-- Artist Details on the right -->
        <div class="col-md-7 artist-details">
            <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($artist['name']); ?></h2>
            <p class="text-muted">Role: <?php echo htmlspecialchars($artist['role']); ?></p>
            <p class="text-muted">Experience: <?php echo htmlspecialchars($artist['exp']); ?></p>
            <p class="text-muted"><?php echo htmlspecialchars($artist['description']); ?></p>

            <?php if (!empty($artist['instagram_link'])): ?>
                <p><a href="<?php echo htmlspecialchars($artist['instagram_link']); ?>" target="_blank" class="text-primary fw-bold">View Instagram</a></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Portfolio Images Section -->
    <h3 class="fw-bold mt-5 portfolio-text">Portfolio Images</h3>
    <div class="row">
        <?php foreach ($portfolio_images as $image) { ?>
            <div class="col-md-4 mb-4">
                <div class="card portfolio-card">
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                         class="img-fluid portfolio-image" 
                         alt="Portfolio Image">
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
            <li><a href="artists.php" class="text-dark text-decoration-none">Our Artists</a></li>
        </ul>
    </div>
    <div class="text-center p-3 bg-dark text-white">
        © 2025 SnapVerse Studios
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
