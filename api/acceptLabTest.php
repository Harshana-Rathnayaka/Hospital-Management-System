<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnAcceptLabTest'])) {

    if (isset($_POST['lab_test_id']) && isset($_POST['date'])) {

        $lab_test_id = $_POST['lab_test_id'];
        $date = $_POST['date'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->acceptLabTest($lab_test_id, $date);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Lab Test accepted successfully!";
            $response['error'] = false;
            $response['message'] = "Lab Test accepted successfully";
            header("location:../staff/index.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../staff/index.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../staff/index.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../staff/index.php");

}

// json output
echo json_encode($response);
