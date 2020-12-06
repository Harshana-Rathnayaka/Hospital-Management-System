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
$currentPage = 'pending-prescriptions';
include 'sidebar.php';
?>
    <!-- sidebar -->

    <div class="container-fluid page-body-wrapper">

     <!-- navbar -->
      <?php include 'navbar.php';?>
      <!-- navbar -->

      <div class="main-panel">
        <div class="content-wrapper">


        <!-- view prescription modal -->
        <div class="modal fade" id="viewPrescriptionForm" tabindex="-1" role="dialog"
            aria-labelledby="viewPrescriptionFormTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="viewPrescriptionFormTitle">Prescription Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <form>

                    <div class="form-group">
                        <label>Patient</label>
                        <input id="patient" disabled class="form-control bg-dark text-light" cols="30" rows="10">
                      </div>
                      <div class="form-group">
                        <label>Prescription</label>
                        <textarea id="prescription" disabled class="form-control bg-dark text-light" cols="30" rows="10"></textarea>
                      </div>
                      <div class="form-group">
                        <label>Address</label>
                        <textarea id="address" disabled class="form-control bg-dark text-light" cols="30" rows="10"></textarea>
                      </div>

                      <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-block btn-secondary">Close</button>

                    </form>

                  </div>
                </div>
              </div>
            </div>

          <!-- Table -->
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="usersTable" class="table table-sm table-bordered">
                    <thead>
                      <tr>
                        <th width="10%"> # </th>
                        <th width="10"> Patient </th>
                        <th width="15%"> Status </th>
                        <th width="20%"> Action </th>
                      </tr>
                    </thead>
                    <tbody>

                    <?php
require_once '../api/getLists.php';

while ($row = mysqli_fetch_array($pending_prescriptions)):
?>
			                      <tr>
			                        <td> <?php echo $row['prescription_id'] ?> </td>
			                        <td> <?php echo $row['full_name'] ?> </td>
			                        <td class="text-center"> <label class="badge badge-warning"> <?php echo $row['prescription_status'] ?> </label> </td>
			                        <td class="text-center">
			                                <form action="../api/shipPrescription.php" method="POST">

			                                <input name="prescription_id" type="hidden" value="<?php echo $row['prescription_id'] ?>">

			                                <input class="patientPrescription" type="hidden" value="<?php echo $row['prescription'] ?>">
			                                <input class="patientAddress" type="hidden" value="<?php echo $row['address'] ?>">


			                            <button type="button" class="btn btnViewPrescription btn-outline-info btn-sm"> <i class="mdi mdi-eye"></i> </button>
			                            <button type="submit" name="btnShipPrescription" class="btn btn-outline-success btn-sm"><i class="mdi mdi-send"></i></button>

			                                </form>
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

  <!-- view prescription modal on button click -->
<script>
    $('.btnViewPrescription').on('click', function() {

      $('#viewPrescriptionForm').modal('show');

      $tr = $(this).closest('tr');
      $form = $(this).closest('form');

      var data = $tr.children('td').map(function() {
        return $(this).text();
      }).get();

      var additionalInfo = $form.children('input').map(function() {
        return $(this).val();
      }).get();

      console.log(data);
      console.log(additionalInfo);

      $('#patient').val(data[1]);
      $('#prescription').val(additionalInfo[1]);
      $('#address').val(additionalInfo[2]);

    });
  </script>
</body>

</html>