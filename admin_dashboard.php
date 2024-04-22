<?php
// Start a session to manage user authentication
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher's Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

        .button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .button:hover {
            background-color: #0056b3;
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
    <!-- Sidebar -->
    <div id="mySidenav" class="sidenav">
        <span class="closebtn" onclick="closeNav()">&times;</span>
        <a href="adduser.php">Add Student</a>
        <a href="view_userdata.php">View StudentInfo</a>
        <a href="userhistoryadmin.php"> Student UploadView</a>
        <a href="noticeadmin.php">Importnat Notice</a>
    </div>

    <header>
        <h1>Welcome,Teacher </h1>
    </header>

    <!-- Menu Button to Open Sidebar -->
    <button class="menu-button" onclick="openNav()">&#9776; Menu</button>

    <!-- Logout button -->
    <a href="admin_logout.php" class="btn btn-secondary logout-button">Logout</a>

    <div class="container">
        <h2>Student List</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Student'Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // connect to the database
                $db = mysqli_connect('localhost', 'root', '', 'project');
                $sql = "SELECT `ID`, `username`, `email`, `password` FROM `datalog` WHERE 1";
                $result = mysqli_query($db,$sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td><a href='delete_user.php?id=" . $row['ID'] . "' class='btn btn-danger'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <br>
    </div>

    <!-- JavaScript to open/close the sidebar -->
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
