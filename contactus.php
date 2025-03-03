<?php
// Include Connection File
include 'connection.php';

// Start session for feedback messages
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $phone_no = trim(htmlspecialchars($_POST['phone'])); // Fixed: Now storing phone separately
    $message = trim(htmlspecialchars($_POST['message']));

    try {
        // Prepare the SQL query with `?` placeholders
        $sql = "INSERT INTO messages (name, email, phone, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Bind the parameters (Fixed: Changed "sss" to "ssss")
        $stmt->bind_param("ssss", $name, $email, $phone_no, $message);
        
        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Message submitted successfully!";
        } else {
            $_SESSION['error'] = "Failed to submit message. Please try again.";
        }
        
        // Close the statement
        $stmt->close();
        
        // Redirect user
        header("Location: contactus.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        // Log error and show user-friendly message
        error_log($e->getMessage(), 3, 'errors.log'); // Log error for debugging
        $_SESSION['error'] = "Oops! Something went wrong. Please try again later.";
        header("Location: contactus.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar .navbar-brand {
            color: #007bff;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar .navbar-brand:hover {
            color: #0056b3;
        }

        .navbar .nav-link {
            color: #555;
        }

        .navbar .nav-link:hover {
            color: #007bff;
        }

        .nav-link.active {
            color: #007bff !important;
            font-weight: bold;
        }

        #contact {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 60px 20px;
            margin-top: 60px;
        }

        #contact h2 {
            font-size: 2.5rem;
            color: #333;
        }

        #contact .form-label {
            color: #555;
        }

        #contact .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        #contact .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            transition: all 0.3s;
        }

        #contact .btn-primary:hover {
            background-color: #0056b3;
        }

        footer {
            background: #f8f9fa;
            color: #333;
            padding: 20px 0;
        }

        footer p {
            margin: 0;
        }

        .contact-info p {
            font-size: 1.1rem;
            color: #333;
        }

        .contact-info i {
            color: #007bff;
            margin-right: 10px;
        }

        html, body {
            height: 100%;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">SnapVerse Studios</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="home.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="packages.php">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="artists.php">Artists</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contact Us Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Contact Us</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="contact-info">
                        <h5 class="mb-3">Get in Touch</h5>
                        <p><i class="fas fa-map-marker-alt"></i> Jisha Heights, Dadar, Mumbai, Maharashtra, India</p>
                        <p><i class="fas fa-phone-alt"></i> +91 8262977375</p>
                        <p><i class="fas fa-envelope"></i> snapversestudios@gmail.com</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Send Us a Message</h5>
                    <form action="contactus.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="phone" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-3">
        <p>&copy; 2025 SnapVerse Studios. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
