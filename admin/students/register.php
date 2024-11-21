<?php
  session_start();
  require('../../functions.php');
  guard();

  $error = '';
  if (isset($_POST['btnRegister'])) {
      $id = sanitizeInput($_POST['student_id']);
      $firstname = sanitizeInput($_POST['student_firstname']);
      $lastname = sanitizeInput($_POST['student_lastname']);

      $error = addStudent($id, $firstname, $lastname);
    //   if (empty($error)) {
    //     $_SESSION['student_success'] = true;
    //     header('Location: ' . $_SERVER['PHP_SELF']); 
    //     exit();
    // }
  }

  $students = getStudents();

  include('../partials/header.php');
  include('../partials/side-bar.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Register a New Student</h1>
    <hr>
    <p><a href="/admin/dashboard.php">Dashboard</a> / Register Student</p>

    <?php 
        if (isset($_SESSION['student_success']) && $_SESSION['student_success'] === true) {
            echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Student registered successfully!</strong>
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
            unset($_SESSION['student_success']);
        } elseif (!empty($error)) {
            echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>' . htmlspecialchars($error) . '</strong> Please correct the issue and try again.
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        }
    ?>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="border p-4 rounded">
                <form method="POST" action="">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="student_id" placeholder="Student ID" value="">
                    </div>
                    <div class="mb-3">
                        <label for="studentName" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="student_firstname" value="">
                    </div>

                    <div class="mb-3">
                        <label for="studentEmail" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="student_lastname" value="">
                    </div>

                    <button type="submit" name="btnRegister" class="btn btn-primary">Register Student</button>
                </form>
            </div>
        </div>
    </div>

    <hr>

    <h3 class="h4">Registered Students</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Student ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)) { 
                foreach ($students as $student) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $student['student_id']; ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="delete.php?delete_id=<?php echo $student['student_id']; ?>" class="btn btn-danger btn-sm" >Delete</a>
                            <a href="delete.php?delete_id=<?php echo $student['student_id']; ?>" class="btn btn-warning btn-sm" >Attach Subject</a>
                        </td>
                    </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="4">No students registered yet.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</main>

<?php 
  include('../partials/footer.php');
?>
