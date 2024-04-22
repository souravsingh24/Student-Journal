<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "project";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
} else {
  echo 'Connection successfully completed.';
}

if (isset($_POST['submit'])) {
  $error = array();
  $file_name = $_FILES['video_name']['name'];
  $file_tmp = $_FILES['video_name']['tmp_name'];
  $username = $_SESSION['username'];
  $userFolderRoot = 'user_folders/';
  $userFolderPath = $userFolderRoot . $username;
  $subject = $_POST['subject'];
  $documentName = mysqli_real_escape_string($conn, $_POST['document_name']);

  $uploadedFile = $_FILES['video_name'];
  $fileName = basename($uploadedFile['name']);
  $targetFilePath = $userFolderPath . "/" . $fileName;

  if (empty($error) == true) {
    $filetype = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedExtensions = array("mp4", "avi", "mov", "mpeg", "3gp"); // Add more extensions if needed

    if (in_array($filetype, $allowedExtensions)) {
      if (move_uploaded_file($uploadedFile['tmp_name'], $targetFilePath)) {
        $sql = "INSERT INTO videoupload(video,username,Subject, DocumentName) VALUES('$targetFilePath','$username','$subject','$documentName')";
        $query = $conn->query($sql);
        if ($query) {
          $_SESSION['success_message'] = 'Video content upload success';
      } else {
          $_SESSION['error_message'] = "Failed to upload Video content";
      }
  } else {
      $_SESSION['error_message'] = 'Failed to save Video content to file';
  }
} else {
  $_SESSION['error_message'] = $error;
}
}
}
header('Location: index.php');
exit();
?>