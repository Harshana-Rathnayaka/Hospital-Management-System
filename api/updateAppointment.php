<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnUpdateAppointment'])) {

    if (isset($_POST['appointment_id']) && isset($_POST['description'])) {

        $appointment_id = $_POST['appointment_id'];
        $description = trim($_POST['description']);

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->updateDescription($appointment_id, $description);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Description updated successfully!";
            $response['error'] = false;
            $response['message'] = "Description updated successfully";
            header("location:../patient/pending-appointments.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../patient/pending-appointments.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../patient/pending-appointments.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../patient/pending-appointments.php");

}

// json output
echo json_encode($response);
