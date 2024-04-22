<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "project";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];
    $newPassword = $_POST["new_password"];

    // Validate and sanitize input (you can add more validation as needed)
    $newUsername = mysqli_real_escape_string($conn, $newUsername);
    $newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);
    $newPassword = mysqli_real_escape_string($conn, $newPassword);

    // Hash the password for security (you should use a more secure method)
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $oldUsername = $_SESSION['username'];
    $userFolderPath = 'user_folders/';

    // Check if the username already exists in the database
    $checkQuery = "SELECT * FROM `datalog` WHERE `username`='$newUsername'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Username already exists
        $_SESSION['error_message'] = "Username already exists. Please choose a different one.";
    } else {
        // Update username and email
        $updateQuery = "UPDATE `datalog` SET `username`='$newUsername', `email`='$newEmail', `password`='$hashedPassword' WHERE `username`='$oldUsername'";

        if (mysqli_query($conn, $updateQuery)) {
            // Rename user folder
            $oldFolderPath = $userFolderPath . $oldUsername;
            $newFolderPath = $userFolderPath . $newUsername;

            if (file_exists($oldFolderPath) && !file_exists($newFolderPath)) {
                rename($oldFolderPath, $newFolderPath);
            }

            $_SESSION['username'] = $newUsername; // Update session username
            $_SESSION['success_message'] = 'User information updated successfully.';
        } else {
            $_SESSION['error_message'] = "Error updating user information: " . mysqli_error($conn);
        }
    }

    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Update</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome CSS for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Add your custom CSS here -->
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
            padding: 20px;
        }

        h1 {
            font-size: 36px;
        }

        .container {
            text-align: center;
            margin: 20px auto;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-group {
            text-align: left;
            position: relative; /* Added for positioning the edit icon */
        }

        /* Style for the edit icon box */
        .edit-icon-box {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            padding: 0.5rem;
            cursor: pointer;
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>User Profile Update</h1>
    </header>

    <!-- User Profile Update Form -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="new_username">New Username:</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                    <span class="edit-icon-box" onclick="enableEdit('new_username')">
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="new_email">New Email:</label>
                <div class="input-group mb-3">
                    <?php
                        $email = ''; // Initialize the email variable
                        $username = $_SESSION['username'];
                        $emailQuery = "SELECT email FROM datalog WHERE username='$username'";
                        $result = mysqli_query($conn, $emailQuery);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $email = $row['email'];
                        }
                    ?>
                    <input type="email" class="form-control" id="new_email" name="new_email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    <span class="edit-icon-box" onclick="enableEdit('new_email')">
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="new_password" name="new_password" value="********" readonly>
                    <span class="edit-icon-box" onclick="enableEdit('new_password')">
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
    <div class="text-center">
          <button id="backButton" class="btn btn-secondary">Back</button>
     </div>
    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Add your custom JavaScript here -->
    <script>
        document.getElementById("backButton").onclick = function () {
        history.go(-1); // Go back one page in the browser's history
    }

        function enableEdit(fieldId) {
            var field = document.getElementById(fieldId);
            field.removeAttribute("readonly");

            var editIconBox = field.parentElement.querySelector('.edit-icon-box');
            editIconBox.style.display = "none"; // Hide the edit icon during editing

            field.focus(); // Focus on the input field for editing

            var saveButton = document.createElement("button");
            saveButton.className = "btn btn-success edit-icon-box";
            saveButton.innerHTML = '<i class="fas fa-check"></i>';
            saveButton.onclick = function() {
                field.setAttribute("readonly", "true");
                editIconBox.style.display = "inline-block"; // Show the edit icon after editing
            };

            field.parentElement.appendChild(saveButton);
        }
    </script>
</body>
</html>
