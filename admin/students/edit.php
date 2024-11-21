<?php
   session_start();
   require('../../functions.php');
   guard();

   $error = '';

   if (isset($_GET['id'])) {
       $student_id = sanitizeInput($_GET['id']);
       $student = getStudentById($student_id);

       if (isset($student['error'])) {
           $_SESSION['error'] = $student['error'];
           header("Location: register.php");
           exit();
       }

       $id = $student['id'];
       $firstname = $student['first_name'];
       $lastname = $student['last_name'];

       if (isset($_POST['btnEdit'])) {
           $id = sanitizeInput($_POST['student_id']);
           $firstname = sanitizeInput($_POST['student_firstname']);
           $lastname = sanitizeInput($_POST['student_lastname']);

           if (empty($id)) {
               $error = "Empty Student ID";
           } elseif (!is_numeric($id)) {
               $error = "Invalid Student ID. It must be a number.";
           } elseif (empty($firstname)) {
               $error = "Empty First Name";
           } elseif (empty($lastname)) {
               $error = "Empty Last Name";
           }

           if (empty($error)) {
               $result = updateStudent($id, $firstname, $lastname);
               if ($result !== true) {
                   $error = $result;
               }
           }
       }
   } else {
       $_SESSION['error'] = "Student ID is not provided.";
       header("Location: register.php");
       exit();
   }

   include('../partials/header.php');
   include('../partials/side-bar.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
  <h1 class="h2">Edit Student Details</h1>
  <hr>
  <p><a href="/admin/dashboard.php">Dashboard</a><a href="register.php">/ Register</a> / Edit Student</p>

  <?php 
      if (!empty($error)) {
          echo '
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>' . $error . '</sytrong>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      }
  ?>

  <div class="row">
      <div class="col-md-8 offset-md-2">
          <div class="border p-4 rounded">
              <form method="POST" action="">
                  <div class="mb-3">
                      <input type="text" class="form-control" name="student_id" placeholder="Student ID" value="<?php echo $id ?>" readonly>
                  </div>
                  <div class="mb-3">
                      <label for="studentName" class="form-label">First Name</label>
                      <input type="text" class="form-control" name="student_firstname" value="<?php echo $firstname; ?>">
                  </div>

                  <div class="mb-3">
                      <label for="studentEmail" class="form-label">Last Name</label>
                      <input type="text" class="form-control" name="student_lastname" value="<?php echo $lastname; ?>">
                  </div>

                  <button type="submit" name="btnEdit" class="btn btn-primary">Update Student</button>
              </form>
          </div>
      </div>
  </div>
</main>
