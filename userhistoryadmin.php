<?php
session_start();

// Include your database connection code here
$db = new mysqli("localhost", "root", "", "project");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$isAdmin = false;

if (isset($_SESSION['admin_logged_in'])) {
    $isAdmin = true;
} elseif (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit;
}

$usernameToView = '';
$userFolderRoot = 'user_folders/';
$userFiles = [];
$errorMsg = ''; // Initialize the errorMsg variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
    $usernameToView = $_POST["username"];

    if ($isAdmin) {
        $username = $usernameToView;
    } else {
        $username = $_SESSION['username'];
    }

    $userFolderPath = $userFolderRoot . $username;

    if (file_exists($userFolderPath)) {
        $userFilesDirectory = $userFolderPath;
        $userFiles = scandir($userFilesDirectory);
        $userFiles = array_diff($userFiles, array('.', '..'));
    } else {
        $errorMsg = "User folder does not exist.";
    }
}
function fetchDataFromTable($db, $tableName) {
    $query = "SELECT * FROM $tableName";
    
    $result = mysqli_query($db, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

$dataFromTable1 = fetchDataFromTable($db, 'pdfupload','Documentname','Subject'); // Adjust table name
$dataFromTable2 = fetchDataFromTable($db, 'textupload'); // Adjust table name
$dataFromTable3 = fetchDataFromTable($db, 'imagesupload'); // Adjust table name
$dataFromTable4 = fetchDataFromTable($db, 'videoupload'); // Adjust table name

mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Files</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Your custom CSS styles go here */
        body {
            background-image: url('homeimg.jpg'); /* Replace with your background image URL */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        a.file-link {
            color: #007bff;
            text-decoration: none;
        }

        a.file-link:hover {
            text-decoration: underline;
        }

        .btn-back {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .file-widget {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .file-widget iframe {
            width: 100%;
            height: 400px; /* Adjust the height as needed */
            border: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">View Student Files</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Enter Student Name:</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">View Student's Files</button>
                </form>
                <?php if (!empty($errorMsg)): ?>
                    <p class="text-danger"><?php echo $errorMsg; ?></p>
                <?php elseif (count($userFiles) > 0): ?>
                    <h3 class="mt-4">Files Uploaded by <?php echo $usernameToView; ?></h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userFiles as $file): ?>
                                <tr>
                                    <td>
                                        <?php echo $file; ?>
                                    </td>
                                    <td>
                                        <?php 
                                      $DocumentName = "";
                                      foreach ($dataFromTable1 as $row) {
                                          if ($row['Documentname'] == $file) {
                                              $DocumentName = $row['Documentname'];
                                              break; // Exit the loop once the Document Name is found
                                          }
                                      }
                                      echo $DocumentName; // Display the Document Name
                                      ?>
                                  </td>
                                  <td>
                                      <?php
                                      $Subject = "";
                                      foreach ($dataFromTable1 as $row) {
                                          if ($row['Documentname'] == $file) {
                                              $Subject = $row['Subject'];
                                              break; // Exit the loop once the Subject is found
                                          }
                                      }
                                      echo $Subject; // Display the Subject
                                      ?>
                                  </td>
                                  <td>
                                      <button class="btn btn-primary view-file" data-file="<?php echo $userFolderPath . '/' . $file; ?>">View</button>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif (isset($usernameToView) && $usernameToView !== ''): ?>
                    <p>No files uploaded by <?php echo $usernameToView; ?> yet.</p>
                <?php endif; ?>
                <div class="text-center">
                    <?php if ($isAdmin): ?>
                        <!-- Back button for admin -->
                        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Admin Dashboard</a>
                    <?php else: ?>
                        <!-- Back button for regular users -->
                        <button id="backButton" class="btn btn-secondary btn-back">Back</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- File Viewer Widget -->
    <div class="file-widget" id="fileWidget">
        <button id="closeFileWidget" class="btn btn-danger float-right">Close</button>
        <iframe id="fileViewer" src="" frameborder="0"></iframe>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Show the file viewer widget when clicking "View" button
            $(".view-file").click(function () {
                var file = $(this).data("file");
                $("#fileViewer").attr("src", file);
                $("#fileWidget").fadeIn();
            });

            // Close the file viewer widget
            $("#closeFileWidget").click(function () {
                $("#fileViewer").attr("src", "");
                $("#fileWidget").fadeOut();
            });

            // Show/hide back button for regular users
            <?php if (!$isAdmin): ?>
                $(window).scroll(function () {
                    if ($(this).scrollTop() > 100) {
                        $("#backButton").fadeIn();
                    } else {
                        $("#backButton").fadeOut();
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>