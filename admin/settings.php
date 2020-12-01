<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Session timed out. Please login to continue.";
    header('location:../login.php');
} elseif (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];

    if ($user_type == 'DOCTOR') {
        header('location:../doctor/index.php');
    } else if ($user_type == 'NURSE') {
        header('location:../nurse/index.php');
    } else if ($user_type == 'STAFF') {
        header('location:../staff/index.php');
    } else if ($user_type == 'PATIENT') {
        header('location:../patient/index.php');
    }
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
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>

<body>
    <div class="container-scroller">

        <!-- sidebar -->
    <?php
$currentPage = 'settings';
include 'sidebar.php';
?>
    <!-- sidebar -->

        <div class="container-fluid page-body-wrapper">

             <!-- navbar -->
      <?php include 'navbar.php';?>
      <!-- navbar -->


            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Account</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                            </ol>
                        </nav>
                    </div>



                     <!-- Cards on top -->
          <?php include 'top-cards.php';?>
          <!-- Cards on top -->

                    </div>

                    <!-- Settings input form -->
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Account</h4>
                                <p class="card-description"> Account info </p>
                                <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="exampleInputName1">Name</label>
                                        <input type="text" class="form-control" id="exampleInputName1" required
                                            placeholder="John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Username</label>
                                        <input type="email" class="form-control" id="exampleInputEmail3" required
                                            placeholder="username123">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Email</label>
                                        <input type="email" class="form-control" id="exampleInputEmail3" required
                                            placeholder="example@email.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail3">Contact</label>
                                        <input type="email" class="form-control" id="exampleInputEmail3" required
                                            placeholder="0172456987">
                                    </div>



                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <button type="submit" class="btn btn-success btn-block mr-2">Submit</button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button class="btn btn-block btn-danger">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                </div>
                <!-- content-wrapper ends -->

                <?php
if (@$_SESSION['success'] == true) {
    $success = $_SESSION['success'];
    ?>
          <script>
            swal({
              title: "SUCCESS!",
              text: "<?php echo $success; ?>",
              icon: "success",
              button: "OK",
            });
          </script>
        <?php
unset($_SESSION['success']);
} elseif (@$_SESSION['error'] == true) {
    $error = $_SESSION['error'];
    ?>
          <script>
            swal({
              title: "ERROR!",
              text: "<?php echo $error; ?>",
              icon: "warning",
              button: "OK",
            });
          </script>
        <?php
unset($_SESSION['error']);
} elseif (@$_SESSION['missing'] == true) {
    $missing = $_SESSION['missing'];
    ?>
          <script>
            swal({
              title: "INFO!",
              text: "<?php echo $missing; ?>",
              icon: "info",
              button: "OK",
            });
          </script>
        <?php
unset($_SESSION['missing']);
}
?>

                <!-- footer -->
        <?php include '../footer.php';?>
        <!-- footer -->

            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="../assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <script src="../assets/js/settings.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
</body>

</html>
