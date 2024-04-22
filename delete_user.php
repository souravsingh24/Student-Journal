<?php
// Check if the user is authenticated as an admin (you should include proper admin authentication)
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect to the admin login page
    exit();
}

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    // Get the user ID from the URL
    $user_id = $_GET['id'];

    // Create a database connection (modify the credentials accordingly)
    $db = mysqli_connect('localhost', 'root', '', 'project');

    // Check if the connection is successful
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get the username of the user to be deleted
    $username_query = "SELECT username FROM datalog WHERE ID = $user_id";
    $username_result = mysqli_query($db, $username_query);
    if ($username_row = mysqli_fetch_assoc($username_result)) {
        $username_to_delete = $username_row['username'];
    } else {
        // User not found
        header("Location: admin_dashboard.php");
        exit();
    }

    // Check if confirmation form is submitted
    if (isset($_POST['confirm'])) {
        // Delete the user with the provided ID from the database
        $delete_query = "DELETE FROM `datalog` WHERE `ID` = $user_id";
        if (mysqli_query($db, $delete_query)) {
            // User deleted successfully

            // Delete the user folder
            $userFolderRoot = 'user_folders/';
            $userFolderPath = $userFolderRoot . $username_to_delete;

            if (is_dir($userFolderPath)) {
                // Recursive function to delete user folder and its contents
                function delete_user_folder($dir) {
                    $files = array_diff(scandir($dir), array('.', '..'));
                    foreach ($files as $file) {
                        (is_dir("$dir/$file")) ? delete_user_folder("$dir/$file") : unlink("$dir/$file");
                    }
                    return rmdir($dir);
                }

                if (delete_user_folder($userFolderPath)) {
                    echo "User folder for $username_to_delete deleted successfully.";
                } else {
                    echo "Error deleting user folder.";
                }
            } else {
                echo "User folder not found.";
            }

            header("Location: admin_dashboard.php"); // Redirect to the admin dashboard
            exit();
        } else {
            // Error occurred while deleting the user
            echo "Error: " . $delete_query . "<br>" . mysqli_error($db);
        }
    }
    mysqli_close($db);
} else {
    // Redirect to the admin dashboard if the user ID is not provided
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        p {
            text-align: center;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        button:hover {
            background-color: #bb2c3d;
        }

        a {
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h2>Delete User Confirmation</h2>
    <p>Are you sure you want to delete this user?</p>
    <form method="POST">
        <input type="hidden" name="confirm" value="yes">
        <button type="submit">Yes</button>
        <a href="admin_dashboard.php">No</a>
    </form>
</body>
</html>
