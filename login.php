<?php include('server.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Journal Login</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS for the eye icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-image: url('login.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 60px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .btn-login {
            background-color: #007BFF;
            color: #fff;
            border: none;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .password-toggle-icon {
            cursor: pointer;
            position: relative;
        }

        .password-toggle-icon i {
            font-size: 18px;
        }
</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7.5 login-container">
                <h2 class="text-center mb-4">Student Journal Login</h2>
                <!-- Login Form -->
                <form method="post" action="server.php">
                    <?php include('errors.php'); ?> <!-- Include error messages -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                         <label for="password">Password:</label>
                             <div class="input-group">
                                   <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required>
                                      <div class="input-group-append">
                                           <span class="input-group-text password-toggle-icon">
                                                <i class="fas fa-eye" id="toggle-icon"></i>
                                            </span>
                                        </div>
                             </div>
                     </div>
                     <button type="submit" class="btn btn-primary btn-block btn-login" name="login_user">Login</button>
                </form>
                <div class="text-center mt-3">
                     <a href="register.php">Register</a> |  
                    <a href="adminlogin.php">ADMIN</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function () {
            // Show/hide password functionality
            $('.password-toggle-icon').on('click', function () {
                var passwordInput = $('#password');
                var toggleIcon = $('#toggle-icon');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>
</html>
