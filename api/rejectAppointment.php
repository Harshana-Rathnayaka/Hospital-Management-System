<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['rejectAppointment'])) {

    if (isset($_POST['appointment_id']) && isset($_POST['comment'])) {

        $appointment_id = $_POST['appointment_id'];
        $comment = $_POST['comment'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->rejectAppointment($appointment_id, $comment);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Appointment rejected successfully!";
            $response['error'] = false;
            $response['message'] = "Appointment rejected successfully";
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
