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


    function addStudent($id, $firstname, $lastname) {
        $error = '';
        
        if (empty($id)) {
            $error = "Empty Student ID";
        } elseif (!is_numeric($id)) {
            $error = "Invalid Student ID. It must be a number.";
        } elseif (empty($firstname)) {
            $error = "Empty First Name";
        } elseif (empty($lastname)) {
            $error = "Empty Last Name";
        } else {
            $con = openCon();
            $sqlAdd = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
            if ($stmt = mysqli_prepare($con, $sqlAdd)) {
                mysqli_stmt_bind_param($stmt, "sss", $id, $firstname, $lastname);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['student_success'] = true;
                    header('Location: ' . $_SERVER['PHP_SELF']); 
                    exit();
                } else {
                    $error = "Error: " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error = "Error preparing query: " . mysqli_error($con);
            }
            closeCon($con);
        }

        return $error;
    }

    function getStudents() {
        $students = [];
        $con = openCon();
        $query = "SELECT student_id, first_name, last_name FROM students";
        if ($result = mysqli_query($con, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        closeCon($con);
        return $students;
    }

    function totalStudents() {
        $con = openCon();
        $sql = "SELECT COUNT(*) AS total_students FROM students";
        $result = mysqli_query($con, $sql);
        $total_students = 0;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $total_students = $row['total_students'];
        }
        closeCon($con);
        return $total_students;
    }

?>
