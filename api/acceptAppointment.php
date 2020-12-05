<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnAcceptAppointment'])) {

    if (isset($_POST['appointment_id']) && isset($_POST['date']) && isset($_POST['time'])) {

        $appointment_id = $_POST['appointment_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->acceptAppointment($appointment_id, $date, $time);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Appointment accepted successfully!";
            $response['error'] = false;
            $response['message'] = "Appointment accepted successfully";
            header("location:../doctor/pending-appointments.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../doctor/pending-appointments.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../doctor/pending-appointments.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../doctor/pending-appointments.php");

}

// json output
echo json_encode($response);
