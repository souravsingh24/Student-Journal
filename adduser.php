<?php
// Initialize session and check if the admin is authenticated
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
// Initialize variables for notification
$notificationMessage = '';
$notificationType = '';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate and sanitize input (you can add more validation as needed)
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Hash the password for security (you should use a more secure method)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $db = mysqli_connect('localhost', 'root', '', 'project');

    // Check if the username or email already exists in the database
    $checkQuery = "SELECT * FROM `datalog` WHERE `username`='$username' OR `email`='$email'";
    $result = mysqli_query($db, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Username or email already exists
        $notificationMessage = "Username or email already exists. Please choose a different one.";
        $notificationType = "alert-danger";
    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO `datalog` (`username`, `email`, `password`) VALUES ('$username', '$email', '$hashedPassword')";
        if (mysqli_query($db, $insertQuery)) {
            // User added successfully
            $notificationMessage = "User added successfully.";
            $notificationType = "alert-success";

            // Create a new user folder in the directory to store data/uploads
            $userFolder = 'user_folders/' . $username;
            if (!file_exists($userFolder)) {
                mkdir($userFolder, 0755, true); // Create the user folder with appropriate permissions
            }

            // Redirect to the admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Database error
            $notificationMessage = "Error: " . mysqli_error($db);
            $notificationType = "alert-danger";
        }
    }

    // Close the database connection
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Add User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        h2 {
            font-size: 24px;
        }

        .container {
            text-align: center;
            margin: 20px auto;
        }

        .form-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s;
            transition: transform 0.3s ease-in-out;
        }

        .form-container.opened {
            transform: translateY(0);
        }

        .btn-add-user {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-add-user:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
        .notification-box {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .alert {
            border-radius: 5px;
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <header> <!-- Header -->
    <h2>Add Student Details</h2>
    </header>
    <div class="notification-box"> <!-- Notification Box -->
        <?php if (!empty($notificationMessage)): ?>
            <div class="alert <?php echo $notificationType; ?> alert-dismissible fade show" role="alert">
                <?php echo $notificationMessage; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
        <div class="container mt-5"><!-- Add User Form -->
        <div class="form-container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="username">Student</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your studentID" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password_1" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="password_2" placeholder="Confirm password" required>
                </div>
                <button type="submit" name="add_user" class="btn btn-add-user">Add User</button>
            </form>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Back Button -->
    <div class="container mt-3">
        <button class="btn btn-back float-right" onclick="goBack()">Back</button>
    </div>

    <script>
        // JavaScript function to go back to the admin dashboard
        function goBack() {
            window.location.href = "admin_dashboard.php";
        }

        // JavaScript function to open the form with animation
        document.addEventListener("DOMContentLoaded", function () {
            var formContainer = document.querySelector(".form-container");
            formContainer.classList.add("opened");
        });
    </script>
</body>
</html>
