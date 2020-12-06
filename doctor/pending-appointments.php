<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Session timed out. Please login to continue.";
    header('location:../login.php');
} elseif (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];

    if ($user_type == 'ADMIN') {
        header('location:../admin/index.php');
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>

</head>

<body>
    <div class="container-scroller">

        <!-- sidebar -->
    <?php
$currentPage = 'pending-appointments';
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
                                <li class="breadcrumb-item"><a href="#">Appointments</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pending</li>
                            </ol>
                        </nav>
                    </div>


                    <!-- Accept appointment modal -->
            <div class="modal fade" id="acceptAppointmentForm" tabindex="-1" role="dialog"
            aria-labelledby="acceptAppointmentFormTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="acceptAppointmentFormTitle">Accept the Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <form action="../api/acceptAppointment.php" method="POST">

                    <input name="appointment_id" id="accept_appointment_id" type="hidden">

                      <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" required class="form-control" />
                        <small class="text-success">Please select a date for this appointment</small>
                      </div>

                      <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="time" required class="form-control" />
                        <small class="text-success">Please select a time slot for this appointment</small>
                      </div>

                      <button type="submit" name="btnAcceptAppointment" class="btn btn-block btn-primary">Save</button>
                      
                    </form>

                  </div>
                </div>
              </div>
            </div>



            <!-- Reject appointment modal -->
            <div class="modal fade" id="rejectAppointmentForm" tabindex="-1" role="dialog"
            aria-labelledby="rejectAppointmentFormTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="rejectAppointmentFormTitle">Reject the Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <form action="../api/rejectAppointment.php" method="POST">

                    <input name="appointment_id" id="reject_appointment_id" type="hidden">

                      <div class="form-group">
                        <label>Reason</label>
                        <textarea name="comment" required placeholder="Start typing..." class="form-control" cols="30" rows="10"></textarea>
                        <small class="text-success">Please enter the reason for rejecting this appointment</small>
                      </div>

                      <button type="submit" name="rejectAppointment" class="btn btn-block btn-primary">Save</button>
                      
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
                  <table id="appointmentsTable" class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="10"> # </th>
                        <th width="10"> Patient </th>
                        <th> Description </th>
                        <th width="10"> Status </th>
                        <th width="10"> Action </th>
                      </tr>
                    </thead>
                    <tbody>

                    <?php
require_once '../api/getLists.php';
while ($row = mysqli_fetch_array($pending_appointments_doctor)):
    $appointment_status = $row['appointment_status'];
    ?>
								                      <tr>
								                        <td> <?php echo $row['appointment_id'] ?> </td>
								                        <td> <?php echo $row['full_name'] ?> </td>
								                        <td> <?php echo $row['description'] ?> </td>
					                              <td>
					                              <label class="badge badge-warning">
					                            <?php echo $row['appointment_status'] ?>
					                            </label>
				                              </td>

			                                <td>
			                                <form>
			                            <button type="button" class="btn btnAccept btn-outline-success btn-sm"><i class="mdi mdi-check"></i></button>
			                            <button type="button" class="btn btnReject btn-outline-danger btn-sm"> <i class="mdi mdi-close"></i> </button>
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
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#appointmentsTable').DataTable({
        "lengthMenu": [5, 10],
      });
    });
  </script>

  <!-- open reject appointment modal on button click -->
<script>
    $('.btnReject').on('click', function() {

      $('#rejectAppointmentForm').modal('show');

      $tr = $(this).closest('tr');

      var data = $tr.children('td').map(function() {
        return $(this).text();
      }).get();

      console.log(data);

      $('#reject_appointment_id').val(data[0]);

    });
  </script>

  <!-- open accept appointment modal on button click -->
<script>
    $('.btnAccept').on('click', function() {

      $('#acceptAppointmentForm').modal('show');

      $tr = $(this).closest('tr');

      var data = $tr.children('td').map(function() {
        return $(this).text();
      }).get();

      console.log(data);

      $('#accept_appointment_id').val(data[0]);

    });
  </script>
</body>

</html>