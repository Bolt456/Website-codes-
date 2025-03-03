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

// Handle image upload and add artist
if (isset($_POST['add_artist'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];

    if ($_FILES['image']['name']) {
        $image_name = time() . "_" . $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = "uploads/" . $image_name;

        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $sql = "INSERT INTO artists (name, role, image) VALUES ('$name', '$role', '$image_folder')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['artist_added'] = true;
            }
        }
    }
    header('Location: martists.php'); // Redirect to prevent resubmission
    exit();
}

if (isset($_POST['update_artist'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_destination = 'uploads/' . $image_name;

        if (move_uploaded_file($image_tmp_name, $image_destination)) {
            $image = $image_destination;
        } else {
            $image = NULL;
        }
    } else {
        $image = NULL;
    }

    if ($image) {
        $sql = "UPDATE artists SET name='$name', role='$role', image='$image' WHERE id='$id'";
    } else {
        $sql = "UPDATE artists SET name='$name', role='$role' WHERE id='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['artist_updated'] = true;
    }
    header('Location: martists.php'); // Redirect to avoid resubmission
    exit();
}

if (isset($_POST['delete_artist'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM artists WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['artist_deleted'] = true;
    }
    header('Location: martists.php'); // Redirect after deletion
    exit();
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
            overflow-y: auto;  /* Add this line */
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

        /* Center Modal */
        .modal-dialog {
            max-width: 500px;
            margin: 18vh auto;
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
            top: 30px;
            left: 60%; /* Shift it slightly to the right */
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
            <h1 class="mb-3">Manage Artists</h1>
            <p class="mb-4">Manage artists by adding, updating, or deleting their profiles.</p>

            <!-- Display Success Messages -->
            <?php
            if (isset($_SESSION['artist_added'])) {
                echo "<div class='alert alert-success' id='successAlert'>Artist Added Successfully</div>";
                unset($_SESSION['artist_added']);
            }
            if (isset($_SESSION['artist_updated'])) {
                echo "<div class='alert alert-info' id='updateAlert'>Artist Updated Successfully</div>";
                unset($_SESSION['artist_updated']);
            }
            if (isset($_SESSION['artist_deleted'])) {
                echo "<div class='alert alert-danger' id='deleteAlert'>Artist Deleted Successfully</div>";
                unset($_SESSION['artist_deleted']);
            }
            ?>

             <!--Display add, update, and delete artist options--> 
            <div class='row mt-5 justify-content-center'>
                <div class='col-md-3 mx-3'>
                    <div class='operation-card'>
                        <h4>Add Artist</h4>
                        <p>Add a new artist to the database.</p>
                        <button class='btn' data-toggle='modal' data-target='#addArtistModal'>Add</button>
                    </div>
                </div>
                
            <div class='col-md-3 mx-3'>
                <div class='operation-card'>
                        <h4>Update Artist</h4>
                        <p>Modify existing artist details.</p>
                        <button class='btn' data-toggle='modal' data-target='#updateArtistModal'>Update</button>
                    </div>
                </div>
            
            <div class='col-md-3 mx-3'>
                <div class='operation-card'>
                        <h4>Delete Artist</h4>
                        <p>Remove an artist from the database.</p>
                        <button class='btn' data-toggle='modal' data-target='#deleteArtistModal'>Delete</button>
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Existing Artists</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM artists");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['role']}</td>";
                    echo "<td><img src='{$row['image']}' width='50' height='50'></td>";
                    echo "<td>
                    <button class='btn btn-info' data-toggle='modal' data-target='#updateArtistModal' onclick='setArtistIdToUpdate({$row['id']})'>Update</button>
                    <form method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button type='submit' class='btn btn-danger' name='delete_artist'>Delete</button>
                    </form>
                  </td>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Artist Modal -->
    <div class="modal" id="addArtistModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Artist</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" name="role" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control-file" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add_artist">Add Artist</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Update Artist Modal -->
<div class="modal" id="updateArtistModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Artist</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST"  enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="update_artist">Select Artist</label>
                        <select name="id" id="update_artist" class="form-control" required onchange="fetchArtistDetails(this.value)">
                            <option value="">Select an Artist</option>
                            <?php
                                // Fetch all artists from the database for the dropdown
                                $result = mysqli_query($conn, "SELECT id, name FROM artists");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="update_name">Name</label>
                        <input type="text" name="name" id="update_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="update_role">Role</label>
                        <input type="text" name="role" id="update_role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="update_image">Image</label>
                        <input type="file" name="image" id="update_image" class="form-control">
                    </div>
                    <button type="submit" name="update_artist" class="btn btn-primary">Update Artist</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function fetchArtistDetails(artistId) {
        const artists = <?php
            $result = mysqli_query($conn, "SELECT * FROM artists");
            $artists = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $artists[$row['id']] = $row;
            }
            echo json_encode($artists);
        ?>;

        if (artistId === "") {
            // Clear all fields when "Select an Artist" is chosen
            document.getElementById('update_name').value = "";
            document.getElementById('update_role').value = "";
            document.getElementById('update_image').value = "";
        } else if (artistId in artists) {
            document.getElementById('update_name').value = artists[artistId].name;
            document.getElementById('update_role').value = artists[artistId].role;
            // Optionally, handle the image input if necessary
            // document.getElementById('update_image').value = artists[artistId].image;
        }
    }
</script>

<!-- Delete Artist Modal -->
<div class="modal" id="deleteArtistModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Artist</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Select Artist</label>
                        <select name="id" class="form-control" id="artistDropdown" onchange="showArtistFields()">
                            <option value="">Select Artist</option>
                            <?php
                            $result = mysqli_query($conn, "SELECT id, name FROM artists");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div id="artistFields" style="display: none;">
                        <!-- Add any additional fields you want to show when an artist is selected -->
                        <p>Artist details can be shown here.</p>
                    </div>
                    <button type="submit" class="btn btn-danger" name="delete_artist">Delete Artist</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- jQuery and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function(){
            setTimeout(function(){
                $(".alert").fadeOut("slow");
            }, 2000);
        });
    </script>

  <script>
    function setArtistIdToUpdate(artistId) {
        document.getElementById('update_artist').value = artistId;
        fetchArtistDetails(artistId);
    }

</body>
</html>
