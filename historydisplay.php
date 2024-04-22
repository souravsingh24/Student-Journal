<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

$username = $_SESSION['username'];
$uploadFolder = 'user_folders/' . $username;

if (!file_exists($uploadFolder)) {
    mkdir($uploadFolder, 0777, true); // Create the folder if it doesn't exist
}

// Get a list of uploaded files
$uploadedFiles = scandir($uploadFolder);
$uploadedFiles = array_diff($uploadedFiles, ['.', '..']);

// Handle file deletion and display success or error message
$deleteMessage = '';

if (isset($_GET['message'])) {
    // Display success or error message received from delete_file.php
    $deleteMessage = $_GET['message'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Delete Files</title>
    <!-- Include Bootstrap CSS or your preferred CSS framework -->
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

        .btn-back {
            position: fixed;
            bottom: 20px;
            right: 20px;
            animation: fadeIn 1s;
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
        .message-widget {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        text-align: center;
        padding: 10px;
        animation: fadeIn 0.5s;
    }

    .message-content {
        display: inline-block;
        max-width: 80%;
        margin: 10px auto;
    }

    .close-button {
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        font-size: 20px;
        position: absolute;
        top: 5px;
        right: 10px;
    }

    .success-widget {
        background-color: #28a745;
    }

    .error-widget {
        background-color: #dc3545;
    }

    /* Add an animation for the message widget */
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
    </style>
</head>
<body>
    <?php if (!empty($deleteMessage)) : ?>
        <div class="message-widget <?php echo strpos($deleteMessage, 'successfully') !== false ? 'success-widget' : 'error-widget'; ?>">
        <div class="message-content">
            <?php echo $deleteMessage; ?>
            <button class="close-button" onclick="closeMessageWidget()">&times;</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Files Uploaded by <?php echo $username; ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uploadedFiles as $filename) : ?>
                    <tr>
                        <td><?php echo $filename; ?></td>
                        <td class="action-buttons">
                            <a href="#" class="btn btn-primary view-file action-button" data-filename="<?php echo $filename; ?>">View</a>
                            <a href="#" class="btn btn-danger delete-file action-button" data-filename="<?php echo $filename; ?>" data-toggle="modal" data-target="#deleteConfirmModal">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary btn-back">Back</a>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this file?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a id="confirmDeleteButton" href="delete_his.php" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- File Viewer Widget -->
    <div class="file-widget" id="fileWidget">
        <button id="closeFileWidget" class="btn btn-danger float-right">Close</button>
        <iframe id="fileViewer" src="" frameborder="0"></iframe>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Show the file viewer widget when clicking a "View" button
            $(".view-file").click(function () {
                var filename = $(this).data("filename");
                var fileURL = '<?php echo $uploadFolder; ?>/' + filename;
                $("#fileViewer").attr("src", fileURL);
                $("#fileWidget").fadeIn();
            });

            // Store the filename in the modal when clicking the "Delete" button
            $(".delete-file").click(function () {
                var filename = $(this).data("filename");
                $("#confirmDeleteButton").attr("href", "delete_his.php?filename=" + filename);
            });

            // Close the file viewer widget
            $("#closeFileWidget").click(function () {
                $("#fileViewer").attr("src", "");
                $("#fileWidget").fadeOut();
            });
        });
    function closeMessageWidget() {
        var messageWidget = document.querySelector('.message-widget');
        messageWidget.style.animation = 'fadeOut 0.5s';
        setTimeout(function() {
            messageWidget.style.display = 'none';
        }, 500);
    }
</script>
</body>
</html>
