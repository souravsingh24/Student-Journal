<?php
// Initialize session and check if the admin is authenticated
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'project');

// Function to submit admin notice
function submitAdminNotice($db, $notice) {
    // Sanitize the input to prevent SQL injection
    $notice = mysqli_real_escape_string($db, $notice);

    // SQL query to insert admin notice
    $sql = "INSERT INTO admin_notice (notice) VALUES ('$notice')";

    if (mysqli_query($db, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Function to delete an admin notice
function deleteAdminNotice($db, $noticeID) {
    // Sanitize the input to prevent SQL injection
    $noticeID = mysqli_real_escape_string($db, $noticeID);

    // SQL query to delete admin notice
    $sql = "DELETE FROM admin_notice WHERE id='$noticeID'";

    if (mysqli_query($db, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Check if the admin notice form is submitted
if (isset($_POST["admin_notice_submit"])) {
    $adminNotice = $_POST["admin_notice"];
    if (!empty($adminNotice)) {
        $noticeSubmitted = submitAdminNotice($db, $adminNotice);
        if ($noticeSubmitted) {
            $adminNoticeSuccess = "Admin notice added successfully.";
        } else {
            $adminNoticeError = "Failed to add admin notice.";
        }
    } else {
        $adminNoticeError = "Admin notice cannot be empty.";
    }
}

// Check if a notice deletion is requested
if (isset($_GET["delete_notice"])) {
    $noticeID = $_GET["delete_notice"];
    $noticeDeleted = deleteAdminNotice($db, $noticeID);
    if ($noticeDeleted) {
        $adminNoticeSuccess = "Admin notice deleted successfully.";
    } else {
        $adminNoticeError = "Failed to delete admin notice.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Admin Notice</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add your custom CSS for the admin dashboard here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 36px;
        }

        h2 {
            font-size: 24px;
        }

        .container {
            text-align: center;
            margin: 20px auto;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .notice {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .notice p {
            margin-bottom: 10px;
        }

        .notice a.btn-danger {
            margin-top: 5px;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h2>Teacher's Notice Board</h2>
    </header>

    <!-- Admin Notice Form -->
    <div class="container mt-3">
        <?php
        if (isset($adminNoticeSuccess)) {
            echo '<div class="alert alert-success">' . $adminNoticeSuccess . '</div>';
        }
        if (isset($adminNoticeError)) {
            echo '<div class="alert alert-danger">' . $adminNoticeError . '</div>';
        }
        ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
               <h3> <label for="admin_notice">Write Admin Notice:</label></h3>
                <textarea class="form-control" id="admin_notice" name="admin_notice" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="admin_notice_submit">Submit Notice</button>
        </form>
    </div>

    <!-- Admin Notice History -->
    <div class="container mt-3">
        <h2>Notice History</h2>
        <?php
        // Function to fetch admin notice history
        function getAdminNoticeHistory($db) {
            // SQL query to select admin notices
            $sql = "SELECT * FROM admin_notice ORDER BY id DESC";
            $result = mysqli_query($db, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table-striped">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Notice</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['ID'] . '</td>';
                    echo '<td>' . $row['notice'] . '</td>';
                    echo '<td><a href="?delete_notice=' . $row['ID'] . '" class="btn btn-danger">Delete</a></td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo "No notices available.";
            }
        }

        // Call the function to get admin notice history
        getAdminNoticeHistory($db);
        ?>
    </div>

    <!-- Back Button -->
    <div class="container mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
