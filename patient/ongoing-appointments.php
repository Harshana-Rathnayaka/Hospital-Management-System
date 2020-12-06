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
  <link rel="stylesheet" href="./charge.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="../assets/images/favicon.png" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>

<body>
  <div class="container-scroller">

    <!-- sidebar -->
    <?php
$currentPage = 'ongoing-appointments';
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
                  <li class="breadcrumb-item active" aria-current="page">Ongoing</li>
                </ol>
              </nav>
            </div>


            <!-- make payment modal -->
        <div class="modal fade" id="makePaymentForm" tabindex="-1" role="dialog"
            aria-labelledby="makePaymentFormTitle" aria-hidden="true">
              <div class="modal-dialog " role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="makePaymentFormTitle">Make the Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" class="text-danger">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="container">
                      <form action="../api/charge.php" method="post" id="payment-form">

                        <input type="hidden" id="appointment_id" name="id">
                        <input type="hidden" id="amount" name="amount" value="500000">
                        <input type="hidden" id="payment_for" name="payment_for" value="APPOINTMENT">

                          <div class="form-row">

                          <input type="text" name="name_on_card" class="form-control bg-dark mb-3 text-light StripeElement StripeElement--empty" placeholder="Name on card">

                            <div id="card-element" class="form-control bg-dark">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>

                          </div>

                              <!-- Used to display form errors. -->
                              <div id="card-errors" role="alert" class="text-danger"></div>

                              <small class="text-success">You will be charged an amount of Rs. 5000/= for this appointment.</small>

                        <button name="btnMakePayment" class="btn btn-primary btn-block mt-4">Submit Payment</button>
                      </form>
                    </div>

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
                        <th width="10"> Doctor </th>
                        <th width="10"> Date </th>
                        <th width="10"> Time </th>
                        <th> Description </th>
                        <th width="10"> Status </th>
                        <th width="10"> Action </th>

                      </tr>
                    </thead>
                    <tbody>

                    <?php
require_once '../api/getLists.php';

while ($row = mysqli_fetch_array($ongoing_appointments_user)):
?>
				                      <tr>
				                        <td> <?php echo $row['appointment_id'] ?> </td>
				                        <td> <?php echo $row['full_name'] ?> </td>
				                        <td> <?php echo $row['date'] ?> </td>
				                        <td> <?php echo $row['time'] ?> </td>
                                <td> <?php echo $row['description'] ?> </td>
	                              <td>
                                <label class="badge badge-success"> <?php echo $row['appointment_status'] ?> </label>
                                </td>
                                <td>
                                <button type="button" class="btn btnViewPayment btn-outline-info btn-block"> <i class="mdi mdi-credit-card"></i> </button>
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
  <script src="https://js.stripe.com/v3/"></script>
  <script src="./charge.js"></script>

  <script>
    $(document).ready(function() {
      $('#appointmentsTable').DataTable({
        "lengthMenu": [5, 10],
      });
    });
  </script>

  <!-- make payment modal on button click -->
<script>
    $('.btnViewPayment').on('click', function() {

      $('#makePaymentForm').modal('show');

      $tr = $(this).closest('tr');

      var data = $tr.children('td').map(function() {
        return $(this).text();
      }).get();


      console.log(data);

      $('#appointment_id').val(data[0]);

    });
  </script>
</body>

</html>