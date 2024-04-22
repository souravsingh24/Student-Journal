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
    <title>Write Text Document</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-image: url('logoutimg.jpg'); /* Replace with your background image URL */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .write-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s;
        }

        .btn-save {
            background-color: #007bff;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #0056b3;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 write-container">
                <h2 class="text-center mb-4">Write Text Document</h2>
                <form action="text_save.php" method="post">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="document_name">Document Name:</label>
                            <input type="text" name="document_name" id="document_name" class="form-control" required>
                        </div>
                        <label for="subject">Select Subject:</label>
                        <select name="subject" class="form-control" required>
                            <option value="math">Math</option>
                            <option value="science">Science</option>
                            <option value="history">History</option>
                            <!-- Add more subjects as needed -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text_content">Write your text content:</label><br>
                        <textarea name="text_content" class="form-control mt-2" rows="5" placeholder="Text Content" required></textarea>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-save" name="submit" value="Save Text Content">
                    </div>
                </form>
                <!-- Back Button -->
                <div class="text-center">
                    <a href="index.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
