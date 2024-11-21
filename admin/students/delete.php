<?php
  session_start();
  require('../../functions.php');
  guard();

  $arrStudentsRec = [];
  $error = '';

  if (isset($_GET['delete_id'])) {
      $deleteId = sanitizeInput($_GET['delete_id']);
      
      $arrStudentsRec = getStudentById($deleteId);

      if (empty($arrStudentsRec)) {
          $error = "Student not found.";
      }

      if (isset($_POST['btnDelete'])) {
          $deleteResult = deleteStudentById($deleteId);

          if ($deleteResult === true) {
              $_SESSION['student_success'] = "Student record deleted successfully!";
              header("Location: register.php");
              exit();
          } else {
              $error = $deleteResult;
          }
      }
  } else {
      $_SESSION['error'] = "No student ID.";
      header("Location: register.php");
      exit();
  }

  include('../partials/header.php');
  include('../partials/side-bar.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
  <h1 class="h2">Delete Student Record</h1>
  <hr>
  <p><a href="/admin/dashboard.php">Dashboard</a><a href="register.php">/ Register</a> / Delete Student</p>

  <div class="border p-3 rounded">
    <h3>Are you sure you want to delete the record of the following student?</h3>
    
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">
        <p><?php echo htmlspecialchars($error); ?></p>
      </div>
    <?php endif; ?>

    <?php if (!empty($arrStudentsRec)): ?>
      <ul>
        <li><strong>Student ID:</strong> <?php echo htmlspecialchars($arrStudentsRec['id']); ?></li>
        <li><strong>First Name:</strong> <?php echo htmlspecialchars($arrStudentsRec['first_name'] ?? 'N/A'); ?></li>
        <li><strong>Last Name:</strong> <?php echo htmlspecialchars($arrStudentsRec['last_name'] ?? 'N/A'); ?></li>
      </ul>

      <form method="POST" action="">
        <button type="submit" name="btnCancel" class="btn btn-secondary" href='register.php'>Cancel</button>
        <button type="submit" name="btnDelete" class="btn btn-danger">Delete Student Record</button>
      </form>
    <?php else: ?>
      <p>No student found with this ID.</p>
    <?php endif; ?>
  </div>
</main>

<?php include('../partials/footer.php'); ?>
