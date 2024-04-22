<?php
// Initialize session and check if the admin is authenticated
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'project');

// Function to fetch and display user data with sorting and filtering, including decrypted password
function viewUserData($db, $nameFilter, $emailFilter) {
    $output = '';

    // Build the SQL query with sorting and filtering conditions
    $sql = "SELECT `ID`, `username`, `email` FROM `datalog`";

    if (!empty($nameFilter) || !empty($emailFilter)) {
        $sql .= " WHERE";
        if (!empty($nameFilter)) {
            $sql .= " `username` LIKE '%$nameFilter%'";
        }
        if (!empty($nameFilter) && !empty($emailFilter)) {
            $sql .= " AND";
        }
        if (!empty($emailFilter)) {
            $sql .= " `email` LIKE '%$emailFilter%'";
        }
    }

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $output .= '<h2>Student Information</h2>';
        $output .= '<table class="table table-bordered">';
        $output .= '<thead class="thead-dark">';
        $output .= '<tr>';
        $output .= '<th>ID</th>';
        $output .= '<th>Username</th>';
        // $output .= '<th>Password</th>';
        $output .= '<th>Email</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<tr>';
            $output .= '<td>' . $row['ID'] . '</td>';
            $output .= '<td>' . $row['username'] . '</td>';
            $output .= '<td>' . $row['email'] . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
    } else {
        $output .= '<p>No user data available.</p>';
    }

    return $output;
}

// Initialize filter variables
$nameFilter = '';
$emailFilter = '';

// Check if the filter form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameFilter = $_POST["name_filter"];
    $emailFilter = $_POST["email_filter"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Data</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include your custom CSS here -->
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
            padding: 10px;
        }

        h1 {
            font-size: 36px;
        }

        h2 {
            font-size: 24px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #ddd;
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

        .container {
            text-align: center;
            margin: 20px auto;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Filter Form Styling */
        .form-row {
            margin-top: 20px;
            justify-content: center;
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
            top: 10px;
            left: 10px;
            font-size: 30px;
            margin-right: 50px;
            cursor: pointer;
        }

        /* Add button styling */
        .add-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .add-button:hover {
            background-color: #0056b3;
        }

        /* Logout button positioning */
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        /* Menu button styling */
        .menu-button {
            background-color: #343a40;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            position: fixed;
            top: 20px;
            left: 0;
        }

        .menu-button:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Welcome, Teacher!</h1>
    </header>

    <!-- Filter Form -->
    <div class="container mt-3">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-row">
                <div class="col">
                    <input type="text" name="name_filter" class="form-control" placeholder="Filter by Name" value="<?php echo $nameFilter; ?>">
                </div>
                <div class="col">
                    <input type="text" name="email_filter" class="form-control" placeholder="Filter by Email" value="<?php echo $emailFilter; ?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- User Data Section -->
    <div class="container mt-3">
        <?php echo viewUserData($db, $nameFilter, $emailFilter); ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Back Button -->
    <div class="container mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
