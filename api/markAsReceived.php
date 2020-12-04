<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnMarkAsReceived'])) {

    if (isset($_POST['prescription_id'])) {

        $prescription_id = $_POST['prescription_id'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->markAsReceived($prescription_id);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Prescription marked as Received!";
            $response['error'] = false;
            $response['message'] = "Prescription marked as Received";
            header("location:../patient/incoming-prescriptions.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../patient/incoming-prescriptions.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../patient/incoming-prescriptions.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../patient/incoming-prescriptions.php");

}

// json output
echo json_encode($response);
