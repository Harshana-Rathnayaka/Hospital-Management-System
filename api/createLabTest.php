<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnNewLabTest'])) {

    if (
        isset($_POST['user_id']) && isset($_POST['details'])

    ) {

        $user_id = $_POST['user_id'];
        $details = trim($_POST['details']);

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->requestALabTest($user_id, $details);

        if ($result == 0) {

            // success
            $_SESSION['success'] = "Lab Test request submitted successfully!";
            $response['error'] = false;
            $response['message'] = "Lab Test request submitted successfully";
            header("location:../patient/new-test.php");

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../patient/new-test.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../patient/new-test.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../patient/new-test.php");

}

// json output
echo json_encode($response);
