<?php
session_start();
if (isset($_SESSION['username'])) {
    $user_type = $_SESSION['user_type'];
    if ($user_type == 'ADMIN') {
        header("location:admin/index.php");
    } elseif ($user_type == 'DOCTOR') {
        header("location:doctor/index.php");
    } elseif ($user_type == 'NURSE') {
        header("location:nurse/index.php");
    } elseif ($user_type == 'STAFF') {
        header("location:staff/index.php");
    } elseif ($user_type == 'PATIENT') {
        header("location:patient/index.php");
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hospital Management System</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Login</h3>
                <form action="api/userLogin.php" method="POST">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required class="form-control p_input">
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control p_input">
                  </div>
                  <div class="form-group d-flex align-items-center ">



                    <?php
if (@$_SESSION['error'] == true) {
    ?>
                        <label class="text-danger">
                            <?php echo $_SESSION['error']; ?>
                       </label>
                    <?php
unset($_SESSION['error']);
} elseif (@$_SESSION['missing'] == true) {
    ?>
                        <label class="text-info">
                            <?php echo $_SESSION['missing']; ?>
                       </label>
                    <?php
unset($_SESSION['success']);
} elseif (@$_SESSION['success'] == true) {
    ?>
                        <label class="text-success">
                            <?php echo $_SESSION['success']; ?>
                       </label>
                    <?php
unset($_SESSION['success']);
}
?>


                  </div>
                  <div class="text-center">
                    <button type="submit" name="btnLogin" class="btn btn-primary btn-block enter-btn">Sign in</button>
                  </div>
                  <p class="sign-up">Don't have an Account?<a href="register.php"> Sign Up</a></p>
                </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>
