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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>

</head>

<body>
  <div class="container-scroller">

    <!-- sidebar -->
    <?php
$currentPage = 'completed-tests';
include 'sidebar.php';
?>
    <!-- sidebar -->

    <div class="container-fluid page-body-wrapper">

     <!-- navbar -->
      <?php include 'navbar.php';?>
      <!-- navbar -->

      <div class="main-panel">
        <div class="content-wrapper">


        <!-- Breadcrumb -->
        <div class="page-header">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Lab Tests</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Completed</li>
                </ol>
              </nav>
            </div>



          <!-- Table -->
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                </p>
                <div class="table-responsive">
                  <table id="appointmentsTable" class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="10"> # </th>
                        <th> Details </th>
                        <th width="10"> Status </th>
                        <th width="10"> Lab Report </th>
                      </tr>
                    </thead>
                    <tbody>

                    <?php
require_once '../api/getLists.php';

while ($row = mysqli_fetch_array($completed_lab_tests_user)):
?>
				                      <tr>
				                        <td> <?php echo $row['test_id'] ?> </td>
				                        <td> <?php echo $row['details'] ?> </td>
				                        <td> <label class="badge badge-primary"> <?php echo $row['test_status'] ?> </label> </td>
                                <td class="text-center">
                                  <a href="../lab-reports/<?php echo $row['file_location'] ?>" download="<?php echo $row['file_location'] ?>">
                                    <button type="button" class="btn btn-outline-secondary btn-block">
                                      <i class="mdi mdi-cloud-download"></i>
                                    </button>
                                  </a>
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
            Swal.fire({
              title: "SUCCESS!",
              text: "<?php echo $success; ?>",
              icon: "success",
              timer: 3000,
              showConfirmButton: false,
              timerProgressBar: true,
              background: '#05781a'
            });
          </script>
        <?php
unset($_SESSION['success']);
} elseif (@$_SESSION['error'] == true) {
    $error = $_SESSION['error'];
    ?>
          <script>
            Swal.fire({
              title: "ERROR!",
              text: "<?php echo $error; ?>",
              icon: "error",
              timer: 3000,
              showConfirmButton: false,
              timerProgressBar: true,
              background: '#c91b08'
            });
          </script>
        <?php
unset($_SESSION['error']);
} elseif (@$_SESSION['missing'] == true) {
    $missing = $_SESSION['missing'];
    ?>
          <script>
            Swal.fire({
              title: "INFO!",
              text: "<?php echo $missing; ?>",
              icon: "info",
              timer: 3000,
              showConfirmButton: false,
              timerProgressBar: true,
              background: '#055096'
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
      $('#appointmentsTable').DataTable({
        "lengthMenu": [5, 10],
      });
    });
  </script>

</body>

</html>