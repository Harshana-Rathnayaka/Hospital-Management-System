<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnCancelLabTest'])) {

    if (isset($_POST['test_id'])) {

        $lab_test_id = $_POST['test_id'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->cancelLabTest($lab_test_id);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Lab Test cancelled!";
            $response['error'] = false;
            $response['message'] = "Lab Test cancelled";
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
// echo json_encode($response);
