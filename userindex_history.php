<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

$username = $_SESSION['username'];
$uploadFolder = 'user_folders/' . $username;

// Check if the user folder exists and create it if not
if (!file_exists($uploadFolder)) {
    mkdir($uploadFolder, 0777, true);
}

// Check if the user folder is a directory
if (!is_dir($uploadFolder)) {
    echo "User folder is not accessible.";
    exit; // Handle this error condition as needed.
}

// Check if the user folder exists.
if (!is_dir($uploadFolder)) {
    echo "User folder does not exist.";
    exit; // You can handle this error condition as needed.
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'project');

// Check if the connection was successful
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch data from tables
function fetchDataFromTable($db, $tableName, $username, $subjectFilter = "", $docNameFilter = "") {
    $query = "SELECT * FROM $tableName WHERE Username = '$username'";
    if (!empty($docNameFilter)) {
        $query .= " AND Documentname LIKE '%$docNameFilter%'";
    }
    if (!empty($subjectFilter)) {
        $query .= " AND Subject = '$subjectFilter'";
    }
    
    $result = mysqli_query($db, $query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Function to delete data from the database and file from folder
function deleteDataAndFile($db, $tableName, $uploadFolder, $column, $fileToDelete) {
    $fileToDelete = mysqli_real_escape_string($db, $fileToDelete); // Sanitize input
    $query = "DELETE FROM $tableName WHERE $column = '$fileToDelete'";

    if (mysqli_query($db, $query)) {
        $filePath = $uploadFolder . '/' . $fileToDelete;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }
        return true;
    } else {
        return false;
    }
}

// Handle file deletion when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_file'])) {
    $fileToDelete = $_POST['delete_file'];
    $table = $_POST['table'];
    $column = $_POST['column'];

    if (deleteDataAndFile($db, $table, $uploadFolder, $column, $fileToDelete)) {
        $deleteSuccess = true;
    } else {
        $deleteError = "Error deleting the file. Please try again.";
    }
}

// Filter options
$subjectFilter = isset($_GET['subject']) ? $_GET['subject'] : "";
$docNameFilter = isset($_GET['Documentname']) ? $_GET['Documentname'] : "";

// Fetch data from all tables with filters
$dataFromTable1 = fetchDataFromTable($db, 'pdfupload', $username, $subjectFilter, $docNameFilter);
$dataFromTable2 = fetchDataFromTable($db, 'textupload', $username, $subjectFilter, $docNameFilter);
$dataFromTable3 = fetchDataFromTable($db, 'imagesupload', $username, $subjectFilter, $docNameFilter);
$dataFromTable4 = fetchDataFromTable($db, 'videoupload', $username, $subjectFilter, $docNameFilter);

// Close the database connection
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

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #343a40;
                color: #fff;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
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

            .filter-form {
                margin-top: 20px;
                padding: 10px;
                background-color: rgba(255, 255, 255, 0.9);
                border-radius: 5px;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            .blur {
                filter: blur(3px);
                pointer-events: none;
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

            .alert-success {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                padding: 10px 20px;
                border-radius: 5px;
                animation: fadeInDown 1s;
            }

            .alert-danger {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                padding: 10px 20px;
                border-radius: 5px;
                animation: fadeInDown 1s;
            }

            @keyframes fadeInDown {
                from {
                    top: -50px;
                    opacity: 0;
                }
                to {
                    top: 20px;
                    opacity: 1;
                }
            }

            /* Add this CSS to your existing styles */

            /* Styling for success widget */
            .success-widget {
                display: none;
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                background-color: #28a745;
                color: #fff;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            .success-widget button.close {
                color: #fff;
                font-size: 20px;
                margin-left: 10px;
                cursor: pointer;
            }

            /* Styling for delete confirmation dialog */
            .confirm-dialog {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 9999;
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            .confirm-dialog h3 {
                margin-bottom: 10px;
            }

            .confirm-dialog button {
                margin-top: 10px;
            }

            /* Styling for close button in file widget */
            #closeFileWidget {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 20px;
                color: #fff;
                background: none;
                border: none;
                cursor: pointer;
            }

            #closeFileWidget:hover {
                color: #ccc;
            }

            /* Styling for the back button */
            #backButton {
                display: block;
                margin: 20px auto;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            #backButton:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h2 class="text-center mb-4">Files Uploaded by <?php echo $username; ?></h2>
                    <?php if (isset($deleteSuccess) && $deleteSuccess) : ?>
                        <div class="alert alert-success">
                            File deleted successfully.
                        </div>
                    <?php elseif (isset($deleteError) && !empty($deleteError)) : ?>
                        <div class="alert alert-danger">
                            <?php echo $deleteError; ?>
                        </div>
                    <?php endif; ?>
                    <div class="filter-form">
                        <form method="GET" class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="Documentname" placeholder="Filter by Document Name" value="<?php echo $docNameFilter; ?>">
                            </div> 
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="subject" placeholder="Filter by Subject" value="<?php echo $subjectFilter; ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered">  <!-- Data from all tables -->
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th>Subject</th>
                                <th>Document Type</th>
                                <th>Delete</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php // Define the user folder root for use in the function
                            $username = $_SESSION['username'];
                            $uploadFolder = 'user_folders/' . $username;
                            function generateTableRows($data, $documentType, $uploadFolder, $column) {
                                foreach ($data as $row) {
                                    echo '<tr>';
                                    echo '<td>' . $row['Documentname'] . '</td>';
                                    echo '<td>' . $row['Subject'] . '</td>';
                                    echo '<td>' . $documentType . '</td>';
                                    echo '<td>';
                                    echo '<form method="POST" style="display: inline;" class="delete-file-form">';
                                    echo '<input type="hidden" name="delete_file" value="' . $row[$column] . '">';
                                    echo '<input type="hidden" name="table" value="' . $documentType . 'upload">';
                                    echo '<input type="hidden" name="column" value="' . $column . '">';
                                    echo '<button type="button" class="btn btn-danger delete-file">Delete</button>';
                                    echo '</form>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                            generateTableRows($dataFromTable1, 'pdf', $uploadFolder, 'pdf');
                            generateTableRows($dataFromTable2, 'TEXT', $uploadFolder, 'TEXT');
                            generateTableRows($dataFromTable3, 'images', $uploadFolder, 'images');
                            generateTableRows($dataFromTable4, 'video', $uploadFolder, 'video');
                            ?>
                        </tbody>
                        </table>
                    <div class="text-center">
                    `  <a href="index.php" class="btn btn-outline-dark btn-back" id="backButton">Back</a>
                    </div>
                    <!-- Add this code inside your existing HTML -->
                    <div class="confirm-dialog" id="deleteConfirmation">
                        <h3>Confirm Deletion</h3>
                        <p>Are you sure you want to delete this file?</p>
                        <button class="btn btn-danger" id="confirmDelete">Delete</button>
                        <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
        $(document).ready(function () {
                $(".delete-file").click(function () {
                    var fileToDelete = $(this).closest(".delete-file-form").find("input[name='delete_file']").val();
                    var table = $(this).closest(".delete-file-form").find("input[name='table']").val();
                    var column = $(this).closest(".delete-file-form").find("input[name='column']").val();
                    // Set the values for confirmation
                    $("#confirmDelete").attr("data-filename", fileToDelete);
                    $("#confirmDelete").attr("data-table", table);
                    $("#confirmDelete").attr("data-column", column);
                    // Show the confirmation dialog
                    $("#deleteConfirmation").show();
                });
                $("#cancelDelete").click(function () {
                    // Hide the confirmation dialog
                    $("#deleteConfirmation").hide();
                });

                $("#confirmDelete").click(function () {
                    var fileToDelete = $(this).attr("data-filename");
                    var table = $(this).attr("data-table");
                    var column = $(this).attr("data-column");

                    // Update the values in the form
                    $(".delete-file-form input[name='delete_file']").val(fileToDelete);
                    $(".delete-file-form input[name='table']").val(table);
                    $(".delete-file-form input[name='column']").val(column);

                    // Submit the form to delete the file
                    $(".delete-file-form").submit();
                });

                // Show the success message widget when a file is deleted
                <?php if (isset($deleteSuccess) && $deleteSuccess) : ?>
                    $(".success-widget").fadeIn(500).delay(5000).fadeOut(500);
                <?php endif; ?>

                // Close the file widget
                $("#closeFileWidget").click(function () {
                    $("#fileWidget").hide();
                });
            });
        </script>
    </body>
    </html>
