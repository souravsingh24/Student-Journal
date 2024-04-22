<?php
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "project";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $error = array();
    $file_name = $_FILES['image_name']['name'];
    $file_tmp = $_FILES['image_name']['tmp_name'];
    
    $username = $_SESSION['username'];
    $userFolderRoot = 'user_folders/';
    $userFolderPath = $userFolderRoot . $username;

    $uploadedFile = $_FILES['image_name'];
    $fileName = basename($uploadedFile['name']);
    $targetFilePath = $userFolderPath . "/" . $fileName;

    // Get the selected subject and document name
    $subject = mysqli_real_escape_string($conn, $_POST['subject']); // Sanitize the input
    $documentName = mysqli_real_escape_string($conn, $_POST['document_name']); // Sanitize the input

    if (empty($error) == true) {
        $filetype = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $extensions = array("jpg", "jpeg", "png");

        if (in_array($filetype, $extensions)) {
            if (move_uploaded_file($uploadedFile['tmp_name'], $targetFilePath)) {
                $sql = "INSERT INTO imagesupload (images, username, Subject, DocumentName) VALUES ('$targetFilePath', '$username', '$subject', '$documentName')";
                $query = $conn->query($sql);

                if ($query) {
                    $_SESSION['success_message'] = 'Image content upload success';
                } else {
                    $_SESSION['error_message'] = "Failed to upload image content";
                }
            } else {
                $_SESSION['error_message'] = 'Failed to save image content to file';
            }
        } else {
            $_SESSION['error_message'] = 'Invalid file format. Only JPG, JPEG, and PNG files are allowed.';
        }
    }
 
    header('Location: index.php');
    exit();
}
?>
