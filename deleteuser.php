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

    // Query the database to get the username associated with the provided user ID
    $sql = "SELECT `username` FROM `datalog` WHERE `ID` = $user_id";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the username
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];

        // Close the result set
        mysqli_free_result($result);

        // Display a confirmation message with JavaScript
        echo '<script>';
        echo 'if (confirm("Are you sure you want to delete the user and their folder?")) {';
        echo '  window.location.href = "delete_user.php?confirm=yes&id=' . $user_id . '";';
        echo '} else {';
        echo '  window.location.href = "admin_dashboard.php";';
        echo '}';
        echo '</script>';
    } else {
        // User not found, redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    }
    
    // Close the database connection
    mysqli_close($db);
} else {
    // Redirect to the admin dashboard if the user ID is not provided
    header("Location: admin_dashboard.php");
    exit();
}
?>
