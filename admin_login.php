<?php
// Start a session to manage user authentication
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace these values with your actual admin username and password
    $admin_username = "admin";
    $admin_password = "admin123";

    // Retrieve input values from the form
    $input_username = $_POST["username"];
    $input_password = $_POST["password"];

    // Check if the input matches the admin credentials
    if ($input_username === $admin_username && $input_password === $admin_password) {
        // Authentication successful; store a session variable to indicate login
        $_SESSION["admin_logged_in"] = true;
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error_message = "Invalid credentials. Please try again.";
    }
}

// If not POST request or invalid credentials, show the login page with an error message
?>