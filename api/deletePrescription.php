<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnDeletePrescription'])) {

    if (isset($_POST['prescription_id'])) {

        $prescription_id = $_POST['prescription_id'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->deletePrescription($prescription_id);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Prescription deleted successfully!";
            $response['error'] = false;
            $response['message'] = "Prescription deleted successfully";
            header("location:../nurse/completed-prescriptions.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../nurse/completed-prescriptions.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../nurse/completed-prescriptions.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../nurse/completed-prescriptions.php");

}

// json output
echo json_encode($response);
