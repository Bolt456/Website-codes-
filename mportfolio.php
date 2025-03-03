<?php
session_start();

// Logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php'); // Redirect to login page if not logged in as admin
    exit();
}

// Include database connection
include('connection.php');

// Fetch artists who don't have a portfolio
$artistsQuery = "SELECT id, name FROM artists WHERE id NOT IN (SELECT artist_id FROM portfolio)";
$artistsResult = mysqli_query($conn, $artistsQuery);

// Fetch artists with a portfolio (For delete operation)
$portfolioArtistsQuery = "SELECT portfolio_id, artist_id, name FROM portfolio";
$portfolioArtistsResult = mysqli_query($conn, $portfolioArtistsQuery);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD OR UPDATE PORTFOLIO
    if (isset($_POST['artist_id']) && !empty($_POST['artist_id'])) {
        $artist_id = $_POST['artist_id'];
        $portfolio_id = isset($_POST['portfolio_id']) ? $_POST['portfolio_id'] : null;
        $name = $_POST['name'];
        $description = $_POST['description'];
        $instagram = $_POST['instagram'];
        $role = $_POST['role'];
        $experience = $_POST['experience'];
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        // Check if artist exists
        $stmt = $conn->prepare("SELECT id FROM artists WHERE id = ?");
        $stmt->bind_param("i", $artist_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            echo "Error: Invalid artist ID.";
            $stmt->close();
            exit;
        }
        $stmt->close();

        // If portfolio ID is provided, update the portfolio
        if ($portfolio_id) {
            // Fetch current image path
            $stmt = $conn->prepare("SELECT image FROM portfolio WHERE portfolio_id = ?");
            $stmt->bind_param("i", $portfolio_id);
            $stmt->execute();
            $stmt->bind_result($currentImage);
            $stmt->fetch();
            $stmt->close();

            // If a new image is uploaded, replace the old one
            if (!empty($image)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Remove old image
                    if (!empty($currentImage) && file_exists($currentImage)) {
                        unlink($currentImage);
                    }
                    $stmt = $conn->prepare("UPDATE portfolio SET name=?, description=?, instagram_link=?, role=?, exp=?, image=? WHERE portfolio_id=?");
                    $stmt->bind_param("ssssisi", $name, $description, $instagram, $role, $experience, $target_file, $portfolio_id);
                } else {
                    echo "Error uploading image.";
                    exit;
                }
            } else {
                // Update without changing the image
                $stmt = $conn->prepare("UPDATE portfolio SET name=?, description=?, instagram_link=?, role=?, exp=? WHERE portfolio_id=?");
                $stmt->bind_param("ssssii", $name, $description, $instagram, $role, $experience, $portfolio_id);
            }
            $stmt->execute();
            $stmt->close();

            // Set session message for portfolio updated
            $_SESSION['portfolio_updated'] = true;

            // Redirect to avoid form resubmission after update
            header('Location: mportfolio.php');
            exit;

        } else {
            // Add new portfolio
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO portfolio (artist_id, name, description, instagram_link, role, exp, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssis", $artist_id, $name, $description, $instagram, $role, $experience, $target_file);
                $stmt->execute();
                $stmt->close();

                // Set session message for portfolio added
                $_SESSION['portfolio_added'] = true;

                // Clear the form fields after successful addition
                $_POST['artist_id'] = '';
                $_POST['name'] = '';
                $_POST['description'] = '';
                $_POST['instagram'] = '';
                $_POST['role'] = '';
                $_POST['experience'] = '';
                $_FILES['image']['name'] = ''; // Clear the uploaded image

                // Redirect to avoid form resubmission after add
                header('Location: mportfolio.php');
                exit;
            } else {
                echo "Error uploading image.";
            }
        }
    } else {
        echo "Error: Artist ID is not set or empty.";
    }

    // DELETE PORTFOLIO FUNCTIONALITY
    if (isset($_POST['delete_portfolio']) && !empty($_POST['portfolio_id'])) {
        $portfolio_id = $_POST['portfolio_id'];

        // Fetch artist_id and portfolio image from the portfolio before deleting
        $stmt = $conn->prepare("SELECT artist_id, image FROM portfolio WHERE portfolio_id = ?");
        $stmt->bind_param("i", $portfolio_id);
        $stmt->execute();
        $stmt->bind_result($artist_id, $imagePath);
        $stmt->fetch();
        $stmt->close();

        // Only delete the portfolio image (if it exists) and NOT the artist image
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);  // Delete only the portfolio image file
        }

        // Delete the portfolio from the database
        $stmt = $conn->prepare("DELETE FROM portfolio WHERE portfolio_id = ?");
        $stmt->bind_param("i", $portfolio_id);
        $stmt->execute();
        $stmt->close();

        // Set session message for portfolio deleted
        $_SESSION['portfolio_deleted'] = true;

        // Redirect to avoid form resubmission after deletion
        header("Location: mportfolio.php");
        exit();
    } elseif (isset($_POST['delete_portfolio'])) {
        echo "Error: Portfolio ID is not set or empty.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Artists - SnapVerse Studios</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    /* Sidebar Styles */
    #sidebar-wrapper {
        min-height: 100vh;
        width: 180px;
        background-color: #1e3a5f;
        color: #fff;
        position: fixed;
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
        background-color: #ff8c00;
        color: #fff;
    }

    .list-group-item.active {
        background-color: #345a8a;
        color: #fff;
    }

    /* Main Content Styles */
    #page-content-wrapper {
        margin-left: 200px;
        padding: 100px 80px;
    }

    .operation-card {
        background-color: #87CEFA;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        height: 220px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        margin-bottom: 20px; /* Add space between the rows */
    }

    .operation-card:hover {
        transform: scale(1.05);
    }

    .operation-card button {
        width: 100px;
        font-size: 14px;
        margin-top: 10px;
        background-color: #ffffff;
        color: #000;
        padding: 10px;
        border-radius: 5px;
    }

    .operation-card:nth-child(1) {
        background-color: #5dade2;
    }

    .operation-card:nth-child(2) {
        background-color: #5499c7;
    }

    .operation-card:nth-child(3) {
        background-color: #2980b9;
    }

    /* Update the row styling */
    .row {
        display: flex;
        justify-content: space-between; /* Distribute the cards evenly */
        flex-wrap: nowrap; /* Ensure no wrapping of the cards */
    }

    /* Modify each column's width */
    .col-md-3 {
        flex: 1; /* Allow each column to take equal width */
        margin-right: 10px; /* Add space between the cards */
    }

    /* Ensure the last column has no extra margin */
    .col-md-3:last-child {
        margin-right: 0;
    }

    /* Ensure the cards don't grow too large */
    .operation-card:nth-child(1),
    .operation-card:nth-child(2),
    .operation-card:nth-child(3),
    .operation-card:nth-child(4) {
        max-width: 220px; /* Ensure cards fit in the row */
    }

    /* Center Modal */
    .modal-dialog {
        max-width: 500px;
        margin: 8vh auto;
    }

    /* Modal Content Adjustments */
    .modal-content {
        border-radius: 15px;
        background-color: #f0f8ff;
    }

    .modal-header {
        background-color: #2980b9;
        color: white;
        border: none; /* Remove the header border */
        justify-content: center;
    }

    .modal-body {
        background-color: #ecf0f1;
    }

    .modal-footer {
        background-color: #f1f1f1;
    }

    .btn-primary {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-primary:hover {
        background-color: #1c5985;
        border-color: #1c5985;
    }

    .btn-danger {
        background-color: #e74c3c;
        border-color: #e74c3c;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }

    .form-control {
        border-radius: 5px;
    }

    /* Center the Add Artist Button */
    .modal-footer {
        justify-content: center;
    }

    .modal-footer .btn-primary {
        width: 150px; /* Center and resize the button */
    }

    /* Alert Styles */
    .alert {
        position: fixed;
        top: 35px;
        left: 70%; /* Shift it slightly to the right */
        transform: translateX(-50%);
        width: 30%; /* Reduce the width */
        text-align: center;
        padding: 10px; /* Reduce the padding */
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1050; /* Ensure it's above other elements */
    }

    /* Table Styles */
    .artist-table {
        margin-top: 30px;
    }

    /* Responsiveness (adjust for small screens) */
    @media (max-width: 768px) {
        .col-md-3 {
            flex: 0 0 48%; /* Take up 48% width on smaller screens */
            margin-right: 10px;
        }

        .col-md-3:last-child {
            margin-right: 0;
        }
    }
</style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">SnapVerse Studios</div>
            <div class="list-group list-group-flush">
                <a href="admindashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
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
            <h1 class="mb-3">Manage Artists Portfolio</h1>
            <p class="mb-4">Manage Portfolios by adding, updating, or deleting their profiles.</p>

            <!-- Display Success Messages -->
            <?php
            if (isset($_SESSION['portfolio_added'])) {
                echo "<div class='alert alert-success' id='successAlert'>Portfolio Added Successfully</div>";
                unset($_SESSION['portfolio_added']);
            }
            if (isset($_SESSION['portfolio_updated'])) {
                echo "<div class='alert alert-info' id='updateAlert'>Portfolio Updated Successfully</div>";
                unset($_SESSION['portfolio_updated']);
            }
            if (isset($_SESSION['portfolio_deleted'])) {
                echo "<div class='alert alert-danger' id='deleteAlert'>Portfolio Deleted Successfully</div>";
                unset($_SESSION['portfolio_deleted']);
            }
            ?>

            <!-- Display add, update, and delete artist options -->
            <div class="row mt-5 justify-content-center">
                <div class="col-md-3 mx-3">
                    <div class="operation-card">
                        <h4>Add Portfolio</h4>
                        <p>Add a new portfolio to the database.</p>
                        <button class="btn" data-toggle="modal" data-target="#addPortfolioModal">Add</button>
                    </div>
                </div>

                <div class="col-md-3 mx-3">
                    <div class="operation-card">
                        <h4>Update Portfolio</h4>
                        <p>Modify existing portfolio</p>
                        <button class="btn" data-toggle="modal" data-target="#updatePortfolioModal">Update</button>
                    </div>
                </div>

                <div class="col-md-3 mx-3">
                    <div class="operation-card">
                        <h4>Delete Portfolio</h4>
                        <p>Remove a portfolio from the database.</p>
                        <button class="btn" data-toggle="modal" data-target="#deletePortfolioModal">Delete</button>
                    </div>
                </div>

                <div class="col-md-3 mx-3">
                    <div class="operation-card">
                        <h4>Portfolio Images</h4>
                        <p>Showcase the work of the Artists.</p>
                        <button class="btn" data-toggle="modal" data-target="#portfolioImagesModal">Images</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


    <!-- Add Portfolio Modal -->
<div class="modal fade" id="addPortfolioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Portfolio</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="mportfolio.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Select Artist</label>
                        <select name="artist_id" class="form-control" required>
                            <option value="">Choose an artist</option>
                            <?php while ($artist = mysqli_fetch_assoc($artistsResult)) { ?>
                                <option value="<?php echo htmlspecialchars($artist['id']); ?>">
                                    <?php echo htmlspecialchars($artist['name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Artist Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Instagram Link</label>
                        <input type="url" name="instagram" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" name="role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Experience (in years)</label>
                        <input type="number" name="experience" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Upload Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- Update Portfolio Modal -->
<div class="modal fade" id="updatePortfolioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Portfolio</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="mportfolio.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Select Artist</label>
                        <select name="artist_id" id="artistSelect" class="form-control" required>
                        <option value="">Choose an artist</option>
                    
                    <?php 
                    $portfolioQuery = "SELECT * FROM portfolio";
                    $portfolioResult = mysqli_query($conn, $portfolioQuery);
                    while ($portfolio = mysqli_fetch_assoc($portfolioResult)) { ?>
                        <option value="<?php echo htmlspecialchars($portfolio['artist_id']); ?>" 
                            data-portfolio-id="<?php echo htmlspecialchars($portfolio['portfolio_id']); ?>"
                            data-name="<?php echo htmlspecialchars($portfolio['name']); ?>" 
                            data-description="<?php echo htmlspecialchars($portfolio['description']); ?>" 
                            data-instagram="<?php echo htmlspecialchars($portfolio['instagram_link']); ?>" 
                            data-role="<?php echo htmlspecialchars($portfolio['role']); ?>" 
                            data-exp="<?php echo htmlspecialchars($portfolio['exp']); ?>" 
                            data-image="<?php echo htmlspecialchars($portfolio['image']); ?>">
                            <?php echo htmlspecialchars($portfolio['name']); ?>
                        </option>
                    <?php } ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Artist Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Instagram Link</label>
                        <input type="url" id="instagram" name="instagram" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" id="role" name="role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Experience (in years)</label>
                        <input type="number" id="experience" name="experience" class="form-control" required oninput="validateExperience(this)">
                    </div>
                    <div class="form-group">
                        <label>Current Image</label><br>
                        <img id="currentImage" src="" width="100" alt="Current Image">
                    </div>
                    <div class="form-group">
                        <label>Upload New Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <input type="hidden" name="update" value="true">
                    <input type="hidden" id="portfolio_id" name="portfolio_id">
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Portfolio Modal -->
<div class="modal fade" id="deletePortfolioModal" tabindex="-1" aria-labelledby="deletePortfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePortfolioModalLabel">Delete Portfolio</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="portfolio_id" class="form-label">Select Artist</label>
                        <select class="form-control" id="portfolio_id" name="portfolio_id" required>
                            <option value="">-- Select Artist --</option>
                            <?php
                            // Fetch and display portfolio artists
                            if ($portfolioArtistsResult) {
                                while ($row = mysqli_fetch_assoc($portfolioArtistsResult)) {
                                    echo "<option value='" . $row['portfolio_id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            } else {
                                echo "<option disabled>No artists found.</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="delete_portfolio" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.getElementById('artistSelect').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    
    document.getElementById('name').value = selectedOption.getAttribute('data-name');
    document.getElementById('description').value = selectedOption.getAttribute('data-description');
    document.getElementById('instagram').value = selectedOption.getAttribute('data-instagram');
    document.getElementById('role').value = selectedOption.getAttribute('data-role');
    document.getElementById('experience').value = selectedOption.getAttribute('data-exp');
    document.getElementById('currentImage').src = selectedOption.getAttribute('data-image');
    document.getElementById('portfolio_id').value = selectedOption.getAttribute('data-portfolio-id'); // Set portfolio_id hidden field
});

function validateExperience(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>

    <script>
        $(document).ready(function(){
            setTimeout(function(){
                $(".alert").fadeOut("slow");
            }, 2000);
        });
    </script>

</body>
</html>