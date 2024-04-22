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
        $text_content = $_POST['text_content'];
        $subject = $_POST['subject'];
        $documentName = mysqli_real_escape_string($conn, $_POST['document_name']);
        $username = $_SESSION['username'];
        $userFolderRoot = 'user_folders/';
        $userFolderPath = $userFolderRoot . $username;
        
        // Ensure the user folder exists; if not, create it
        if (!file_exists($userFolderPath)) {
            mkdir($userFolderPath, 0777, true);
        }
        
        if (empty($error) == true) {
            // Generate a unique file name for the text document
            $file_name = uniqid() . ".txt"; // You can change the file extension as needed
            $file_path = $userFolderPath . "/" . $file_name;

            // Save the text content to the file
            if (file_put_contents($file_path, $text_content) !== false) {
                // Correct the SQL query
                $sql = "INSERT INTO textupload (TEXT, Subject, username, DocumentName) VALUES ('$file_path', '$subject', '$username', '$documentName')";

                $query = $conn->query($sql);
                
                if ($query) {
                    $_SESSION['success_message'] = 'Text content upload success';
                } else {
                    $_SESSION['error_message'] = "Failed to upload text content";
                }
            } else {
                $_SESSION['error_message'] = 'Failed to save text content to file';
            }
        } else {
            $_SESSION['error_message'] = $error;
        }
    }

    header('Location: index.php');
    exit();
    ?>
