<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher's Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-image: url('login.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh; /* Full viewport height */
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 25px; /* Decreased padding for a smaller box */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease; /* Add a fade-in animation */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-13"> <!-- Increased the column size -->
                    <div class="card">
                        <div class="card-header">
                            Teacher's Login
                        </div>
                        <div class="card-body">
                            <form action="admin_login.php" method="POST">
                                <?php if (isset($error_message)): ?>
                                    <div class="alert alert-danger"><?= $error_message ?></div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username"required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text password-toggle-icon">
                                                <i class="fas fa-eye" id="toggle-icon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-login">Login</button>
                                <span class="float-right mt-2"> <a href="login.php">Student login</a> </span>
                            </form>
                        </div>
                    </div>
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
