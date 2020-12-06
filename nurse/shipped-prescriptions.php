<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Session timed out. Please login to continue.";
    header('location:../login.php');
} elseif (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];

    if ($user_type == 'DOCTOR') {
        header('location:../doctor/index.php');
    } else if ($user_type == 'ADMIN') {
        header('location:../admin/index.php');
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>

</head>

<body>
  <div class="container-scroller">

    <!-- sidebar -->
    <?php
$currentPage = 'shipped-prescriptions';
include 'sidebar.php';
?>
    <!-- sidebar -->

    <div class="container-fluid page-body-wrapper">

     <!-- navbar -->
      <?php include 'navbar.php';?>
      <!-- navbar -->

      <div class="main-panel">
        <div class="content-wrapper">


         <!-- update prescription location modal -->
         <div class="modal fade" id="updatePrescriptionLocationForm" tabindex="-1" role="dialog"
            aria-labelledby="updatePrescriptionLocationFormTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updatePrescriptionLocationFormTitle">Update Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <form action="../api/updateLocation.php" method="POST">

                    <input name="prescription_id" id="prescription_id" type="hidden">

                      <div class="form-group">
                        <label>Location</label>
                        <textarea name="prescription_location" id="prescription_location" required placeholder="Start typing..." class="form-control" cols="30" rows="10"></textarea>
                        <small class="text-success">This is the current location of the delivery</small>
                      </div>

                      <button type="submit" name="btnUpdateLocation" class="btn btn-block btn-primary">Save</button>

                    </form>

                  </div>
                </div>
              </div>
            </div>


          <!-- Table -->
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                </p>
                <div class="table-responsive">
                  <table id="usersTable" class="table table-sm table-bordered">
                    <thead>
                      <tr>
                        <th width="5"> # </th>
                        <th > Prescription </th>
                        <th width="10"> Location </th>
                        <th width="10"> Action </th>
                      </tr>
                    </thead>
                    <tbody>

                    <?php
require_once '../api/getLists.php';

while ($row = mysqli_fetch_array($shipped_prescriptions)):
    $user_type = $row['user_type'];
    ?>
						                      <tr>
						                        <td> <?php echo $row['prescription_id'] ?> </td>
						                        <td> <?php echo $row['prescription'] ?> </td>
						                        <td> <?php echo $row['prescription_location'] ?> </td>

			                                    <td class="text-center">
						                        <button type="button" class="btn btnUpdatePresLocation btn-outline-primary btn-block">
				                                <i class="mdi mdi-pencil-box"></i></button>
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
      $('#usersTable').DataTable({
        "lengthMenu": [5, 10],
      });
    });
  </script>


  <!-- open update prescription location modal on button click -->
<script>
    $('.btnUpdatePresLocation').on('click', function() {

      $('#updatePrescriptionLocationForm').modal('show');

      $tr = $(this).closest('tr');

      var data = $tr.children('td').map(function() {
        return $(this).text();
      }).get();

      console.log(data);

      $('#prescription_id').val(data[0]);
      $('#prescription_location').val(data[2]);

    });prescription_location
  </script>
</body>

</html>