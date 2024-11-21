<?php

    function openCon() {
        $con = mysqli_connect("localhost", "root", "", "php-exam");

        if ($con === false) {
            die("Error Database couldn't connect" . mysqli_connect_error());
        }

        return $con;
    }

    function closeCon($con) {
        mysqli_close($con);
    }

    function sanitizeInput($input) {
        return stripslashes(htmlspecialchars(trim($input)));
    }

    function sanitizeEmail($email) {
        $email = sanitizeInput($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return $email;
    }

    function guard() {
        if (!isset($_SESSION['email'])) {
            header("Location: /index.php");
            exit();
        }
    }

    function hashPassword($password) {
        return md5($password);
    }

    function loginUser($email, $password) {
        $con = openCon();
        $error = '';

        if ($stmt = $con->prepare("SELECT id, password FROM users WHERE email = ?")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();

                if (md5($password) === $hashed_password) {
                    $_SESSION['email'] = $email;
                    guard();
                    header('Location: admin/dashboard.php');
                    exit();
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "Invalid email or password.";
            }
            $stmt->close();
        } else {
            $error = "Database error. Please try again later.";
        }

        closeCon($con);
        return $error;
    }

?>
