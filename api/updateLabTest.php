<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnUpdateLabTest'])) {

    if (isset($_POST['lab_test_id']) && isset($_POST['details'])) {

        $lab_test_id = $_POST['lab_test_id'];
        $details = trim($_POST['details']);

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->updateLabTestDetails($lab_test_id, $details);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Lab Test details updated successfully!";
            $response['error'] = false;
            $response['message'] = "Lab Test details updated successfully";
            header("location:../patient/pending-tests.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../patient/pending-tests.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../patient/pending-tests.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../patient/pending-tests.php");

}

// json output
echo json_encode($response);
