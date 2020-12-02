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
    } else if ($user_type == 'ADMIN') {
        header('location:../admin/index.php');
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
$currentPage = 'dashboard';
include 'sidebar.php';
?>
    <!-- sidebar -->

    <div class="container-fluid page-body-wrapper">

     <!-- navbar -->
      <?php include 'navbar.php';?>
      <!-- navbar -->

      <div class="main-panel">
        <div class="content-wrapper">




          <!-- Table -->
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                </p>
                <div class="table-responsive">
                  <table id="usersTable" class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="10"> # </th>
                        <th width="10"> Doctor </th>
                        <th width="10"> Date </th>
                        <th width="10"> Status </th>

                      </tr>
                    </thead>
                    <tbody>

                    <?php
require_once '../api/getLists.php';

while ($row = mysqli_fetch_array($users_admin)):
    $user_type = $row['user_type'];
    ?>
				                      <tr>
				                        <td> <?php echo $row['user_id'] ?> </td>
				                        <td> <?php echo $row['full_name'] ?> </td>
				                        <td> <?php echo $row['username'] ?> </td>



	                              <td>
	                              <label class="badge
	                              <?php
    if ($user_type == 'DOCTOR') {echo 'badge-primary';} elseif ($user_type == 'NURSE') {echo 'badge-danger';} elseif ($user_type == 'STAFF') {echo 'badge-info';} elseif ($user_type == 'PATIENT') {echo 'badge-success';}

?>">
	                            <?php echo $row['user_type'] ?>
	                            </label>
                              </td>

                      </tr>

                      <?php
endwhile;
?>

                    </tbody>
                  </table>
                </div>
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
  <!-- inject:js -->
  <script src="../assets/js/off-canvas.js"></script>
  <script src="../assets/js/hoverable-collapse.js"></script>
  <script src="../assets/js/misc.js"></script>
  <script src="../assets/js/settings.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="../assets/js/dashboard.js"></script>
  <!-- End custom js for this page -->

  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#usersTable').DataTable({
        "lengthMenu": [5, 10],
      });
    });
  </script>
</body>

</html>