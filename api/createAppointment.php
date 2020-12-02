<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnNewAppointment'])) {

    if (
        isset($_POST['user_id']) &&
        isset($_POST['doctor_id']) &&
        isset($_POST['date']) &&
        isset($_POST['description'])

    ) {

        $user_id = $_POST['user_id'];
        $doctor_id = $_POST['doctor_id'];
        $date = trim($_POST['date']);
        $description = trim($_POST['description']);

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->createAppointment($user_id, $doctor_id, $date, $description);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Appointment request submitted successfully!";
            $response['error'] = false;
            $response['message'] = "Appointment request submitted successfully";
            header("location:../patient/new-appointment.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../patient/new-appointment.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../patient/new-appointment.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../patient/new-appointment.php");

}

// json output
echo json_encode($response);
