<?php
session_start();
$message = ''; // Initialize message variable

// Ensure email is passed through session
if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    $message = "Session expired or invalid access!";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get entered OTP from individual inputs and concatenate them
        $enteredOtp = $_POST['digit-1'] . $_POST['digit-2'] . $_POST['digit-3'] . $_POST['digit-4'] . $_POST['digit-5'] . $_POST['digit-6'];

        // Check if entered OTP matches the one stored in session
        if ($enteredOtp == $_SESSION['otp']) {
            // OTP matches, allow user to set a new password
            $_SESSION['otp_verified'] = true; // Store verification status in session
            header("Location: newpass.php");
            exit();
        } else {
            // OTP does not match, set message
            $message = "Invalid OTP. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('lens3.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .otp-container {
            background-color: rgb(226, 227, 207); /* Card color */
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .otp-input {
            display: flex;
            justify-content: space-between;
            gap: 5px;
        }

        .otp-input input {
            width: 50px;
            height: 50px;
            font-size: 20px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .otp-input input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        .message {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .submit-btn, .clear-btn {
            width: 48%;
        }
    </style>
</head>
<body>

<div class="otp-container">
    <h3 class="text-center mb-4">Two-Step Verification</h3>
    <p class="text-center mb-4">Enter the 6-digit OTP sent to <strong><?php echo $_SESSION['email'] ?? 'your email'; ?></strong></p>

    <form method="POST" action="otp.php">
        <div class="otp-input mb-3">
            <input type="text" maxlength="1" id="digit-1" name="digit-1" class="form-control" oninput="moveToNext(this, 'digit-2')">
            <input type="text" maxlength="1" id="digit-2" name="digit-2" class="form-control" oninput="moveToNext(this, 'digit-3')">
            <input type="text" maxlength="1" id="digit-3" name="digit-3" class="form-control" oninput="moveToNext(this, 'digit-4')">
            <input type="text" maxlength="1" id="digit-4" name="digit-4" class="form-control" oninput="moveToNext(this, 'digit-5')">
            <input type="text" maxlength="1" id="digit-5" name="digit-5" class="form-control" oninput="moveToNext(this, 'digit-6')">
            <input type="text" maxlength="1" id="digit-6" name="digit-6" class="form-control">
        </div>

        <!-- Display error message if OTP is incorrect -->
        <?php if ($message != ''): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="btn-container">
            <button type="submit" class="btn btn-primary submit-btn">Verify OTP</button>
            <button type="button" class="btn btn-secondary clear-btn" onclick="clearOtp()">Clear</button>
        </div>
    </form>
</div>

<script>
    function moveToNext(current, nextId) {
        if (current.value.length === current.maxLength && nextId) {
            document.getElementById(nextId).focus();
        }
    }

    function clearOtp() {
        document.querySelectorAll('.otp-input input').forEach(input => input.value = '');
        document.getElementById('digit-1').focus();
    }
</script>

</body>
</html>
