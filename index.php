<?php
session_start();
require_once('functions.php');


$error = ''; 

if (isset($_POST['login'])) {
    $email = sanitizeEmail($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $error = loginUser($email, $password);
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Login</title>
</head>

    <body class="bg-secondary-subtle">
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="col-3">
                <?php 
                    if (!empty($error)) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>' . $error . '</strong> Please check your credentials and try again.
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                    }
                ?>
                <div class="card">
                    <div class="card-body">
                        <h1 class="h3 mb-4 fw-normal">Login</h1>
                        <form method="post" action="">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="email" name="email" placeholder="">
                                <label for="email">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                <label for="password">Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>

</html>
