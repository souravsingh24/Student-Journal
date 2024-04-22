<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

// Function to delete a file
function deleteFile($username, $filename) {
    $uploadFolder = 'user_folders/' . $username;
    $filePath = $uploadFolder . '/' . $filename;

    if (file_exists($filePath)) {
        unlink($filePath); // Delete the file
        return true; // File deleted successfully
    } else {
        return false; // File not found or deletion failed
    }
}

if (isset($_GET['filename'])) {
    $username = $_SESSION['username'];
    $filename = $_GET['filename'];

    if (deleteFile($username, $filename)) {
        $message = "File '$filename' has been deleted successfully.";
        $messageClass = "success-widget";
    } else {
        $message = "Failed to delete file '$filename'.";
        $messageClass = "error-widget";
    }

    // Redirect back to the previous page with a success or error message
    header("location: historydisplay.php?message=" . urlencode($message) . "&class=" . $messageClass);
    exit();
}
?>
