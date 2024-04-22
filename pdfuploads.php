<?php
// Define $dataFrompdf as an empty array to avoid undefined variable error
$dataFrompdf = [];
$db = mysqli_connect('localhost', 'root', '', 'project');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM pdfupload";
$result = mysqli_query($db, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $dataFrompdf[] = $row;
}

mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select PDF Document to Upload</title>
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

        .upload-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s;
        }

        .btn-upload {
            background-color: #28a745;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-upload:hover {
            background-color: #1e7e34;
        }

        .btn-secondary {
            background-color: #343a40;
            color: #fff;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #1b1e21;
        }

        .custom-file-upload {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

        .custom-file-upload:hover {
            color: #0056b3;
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
            <div class="col-md-6 upload-container">
                <h2 class="text-center mb-4">Select PDF Document to Upload</h2>
                <form action="pdf_upload.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="pdf_name">Choose a PDF file:</label><br>
                        <input type="file" name="pdf_name" id="fileToUpload" accept=".pdf" required>
                    </div>
                    <div class="form-group">
                        <label for="document_name">Document Name:</label>
                        <input type="text" name="document_name" id="document_name" class="form-control" required>
                    </div>
                    <?php foreach ($dataFrompdf as $row): ?>
                    <?php endforeach; ?>

                    <div class="form-group">
                        <label for="subject">Select Subject:</label>
                        <select name="subject" class="form-control" required>
                            <option value="math">Math</option>
                            <option value="science">Science</option>
                            <option value="history">History</option>
                            <!-- Add more subjects as needed -->
                        </select>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-success btn-upload" name="submit" value="Upload PDF">
                    </div>
                </form>
                <!-- Back Button -->
                <div class="text-center">
                    <button id="backButton" class="btn btn-secondary">Back</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Back Button JavaScript -->
    <script>
        document.getElementById("backButton").onclick = function () {
            history.go(-1); // Go back one page in the browser's history
        }
    </script>
</body>
</html>
