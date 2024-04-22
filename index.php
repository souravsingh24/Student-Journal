<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Student Journal</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            background-image: url('homeimg.jpg'); /* background image URL */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .welcome-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s;
        }

        .btn-action {
            margin: 10px 0;
        }

        /* Navbar styling */
        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        .navbar-toggler-icon {
            background-color: #fff;
        }

        .navbar-nav .nav-item .nav-link {
            color: #fff;
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: #007bff;
        }

        /* Sidebar styling */
        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #343a40;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 20px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #007bff;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 30px;
            margin-left: 50px;
        }

        .btn-add {
            background-color: #007bff;
            color: #fff;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        /* History button */
        .btn-history {
            background-color: #007bff;
            color: #fff;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-history:hover {
            background-color: #0056b3;
        }

        /* Logout button */
        .btn-logout {
            background-color: #dc3545;
            color: #fff;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-logout:hover {
            background-color: #bb2c3d;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }
        /* Widget animation */
        @keyframes adminWidgetMovement {
            0% {
                 transform: translateX(100%);
                }
         100% {
        transform: translateX(-100%);
             }
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

    /* Close button styling */
    .close-button {
        position: absolute;
        top: 5px;
        right: 10px;
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
    }

    /* Animation for fading in and out */
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
    /* Admin Notice Board styling */
.admin-notice {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
}

/* Animation for admin notice board */
@keyframes adminWidgetMovement {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(0);
    }
}

#admin-widget {
    animation: adminWidgetMovement 2s ease-in-out;
}
/* New Menu Bar styling */
.menu-bar {
    position: fixed;
    top: 10px;
    right: 75px;
    background-color: rgba(0240, 255, 255, 0.8);
    border-radius: 2px;
    box-shadow: 0 0 10px rgba(6, 0, 0, 0.2);
    padding: 5px;
}

.menu-bar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.menu-bar li {
    display: inline-block;
    margin-right: 5px;
}

.menu-bar .nav-link {
    text-decoration: none;
    color: #030;
    font-weight: bold;
    font-size: 16px;
    transition: color 0.3s ease-in-out;
}

.menu-bar .nav-link:hover {
    color: #037bff;
}
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Student Journal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Menu
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="menuDropdown">
                        <a class="dropdown-item" href="update_profile.php">Update profile</a>
                        <a class="dropdown-item" href="userindex_history.php">History</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

  <!-- Widgets Section -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Success Popup for Student -->
        <div class="col-md-8 text-center" id="success-popup">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '
                <div class="alert alert-success alert-dismissible fade show">
                    ' . $_SESSION['success_message'] . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                unset($_SESSION['success_message']); // Clear the success message
            }
            ?>
        </div>
    </div>
    
<!-- Success Popup for Video Upload -->
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php
            if (isset($_SESSION['video_success_message'])) {
                echo '
                <div class="alert alert-success alert-dismissible fade show">
                    ' . $_SESSION['video_success_message'] . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                unset($_SESSION['video_success_message']); // Clear the video success message
            }
            ?>
        </div>
    </div>
</div>

<!-- Success Popup for Image Upload -->
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php
            if (isset($_SESSION['image_success_message'])) {
                echo '
                <div class="alert alert-success alert-dismissible fade show">
                    ' . $_SESSION['image_success_message'] . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                unset($_SESSION['image_success_message']); // Clear the image success message
            }
            ?>
        </div>
    </div>
</div>

<!-- Success Popup for PDF Upload -->
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php
            if (isset($_SESSION['pdf_success_message'])) {
                echo '
                <div class="alert alert-success alert-dismissible fade show">
                    ' . $_SESSION['pdf_success_message'] . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                unset($_SESSION['pdf_success_message']); // Clear the PDF success message
            }
            ?>
        </div>
    </div>
</div>
    <div class="row justify-content-center">
        <div class="col-md-8 welcome-container">
            <h2 class="text-center mb-4">Welcome to Student Journal</h2>
            <p>Hello, <?php echo $_SESSION['username']; ?></p>
            <p>Here are your latest journal entries:
                <br>
                <br>
                 <a href="historydisplay.php" class="btn btn-primary">Directory</a></p>
            <div class="text-center mt-3">
                <button class="btn btn-add" onclick="openNav()">Add</button>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="imgae.php">Upload Image</a>
        <a href="pdfuploads.php">Upload PDF</a>
        <a href="videouploasds.php">Upload Video</a>
        <a href="wrtietext.php">Write Text Document</a>  
    </div>

<!-- Widgets Section -->
<div class="container mt-5">
    <div class="row justify-content-center">
      <!-- Admin's Note Board Widget -->
        <div class="col-md-4">
            <div class="card" id="admin-widget">
            <div class="card-body">
            <h5 class="card-title">Teacher's Note Board</h5>
            <?php
            // Function to fetch the admin's notice from the database
            function getAdminNotice($db){
                $sql = "SELECT * FROM admin_notice";
                $result = mysqli_query($db, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['notice'];
                } else {
                    return "No notice available.";
                }
            }

            // Database connection
            $db = mysqli_connect('localhost', 'root', '', 'project'); // Replace with your database details

            // Check if the connection was successful
            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Call the function to get the admin's notice
            $admin_notice = getAdminNotice($db);

            echo '<p class="card-text admin-notice">' . $admin_notice . '</p>';
            ?>
        </div>
    </div>
</div>
<!-- Include Bootstrap JS (optional) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Function to open the sidebar
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    // Function to close the sidebar
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }

    // Function to animate the "Add" button
    function animateAddButton() {
        var addButton = document.getElementById("addButton");
        addButton.classList.add("animate-button");

        // Reset the animation after a delay
        setTimeout(function() {
             addButton.classList.remove("animate-button");
        }, 1000); // Adjust the duration as needed (in milliseconds)
    }
</script>
<?php
if (isset($_SESSION['success_message'])) {
    echo '<div id="success-message" class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Clear the success message
} elseif (isset($_SESSION['error_message'])) {
    echo '<div id="error-message" class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Clear the error message
}
?>
<script>
    setTimeout(function() {
        document.getElementById('success-message').style.display = 'none';
        document.getElementById('error-message').style.display = 'none';
        window.location.href = 'index.php'; // Redirect to the home page
    }, 250); // Adjust the duration (in milliseconds) as needed

    // Function to close the message widget
    function closeMessage() {
        var messageWidget = document.getElementById('message-widget');
        messageWidget.style.animation = 'fadeOut 0.5s';
        setTimeout(function() {
            messageWidget.style.display = 'none';
        }, 500);
    }

    // Automatically close the message widget after 5 seconds
    setTimeout(closeMessage, 250); // Adjust the duration (in milliseconds) as needed
</script>

</body>
</html>
    