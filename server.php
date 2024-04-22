<?php
session_start();

// initializing variables
$username = "";
$email = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'project');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { 
    array_push($errors, "Username is required");
  }
  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($password_1)) {
    array_push($errors, "Password is required");
  }
  if ($password_1 != $password_2) {
    array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  // $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, "SELECT * FROM datalog WHERE username='$username' OR email='$email' LIMIT 1");
  $user = mysqli_fetch_assoc($result);

  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = md5($password_1); //encrypt the password before saving in the database

    $query = "INSERT INTO datalog (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now logged in";
    header('location: index.php');
  }
  $_SESSION[$username];
  $username = $_SESSION[$username];

  $userFolderRoot = 'user_folders/';

  $userFolderPath = $userFolderRoot . $username ;
  if (!file_exists($userFolderPath)) {
    // If the folder doesn't exist, create it.
    if (mkdir($userFolderPath, 0777, true)) {
      echo "User folder for $username created successfully.";
    } else {
      echo "Error creating user folder.";
    }
  } else {
    echo "User folder already exists.";
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM datalog WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

//admin login 
if (isset($_POST['admin_registration'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $admin_key = mysqli_real_escape_string($db, $_POST['admin_key']);

  // Verify the admin key (you may want to store it securely)
  if ($admin_key === "123") {
      $query = "INSERT INTO datalog (username, email, password, admin_key) VALUES ('$username', '$email', '$password_1', 1)";
      $result = mysqli_query($db, $query);

      if ($result) {
          $_SESSION['admin_success'] = "Admin registration successful";
          $_SESSION['admin_username'] = $username;
          header('location: admin.php'); // Redirect to the admin dashboard
      } else {
          array_push($errors, "Failed to register admin");
      }
  } else {
      array_push($errors, "Invalid admin key");
  }
}


?>