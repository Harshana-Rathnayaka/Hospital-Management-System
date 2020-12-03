<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnAddPrescription'])) {

    if (isset($_POST['appointment_id']) && isset($_POST['patient_id']) && isset($_POST['prescription'])) {

        $doctor_id = $_SESSION['user_id'];
        $appointment_id = $_POST['appointment_id'];
        $patient_id = $_POST['patient_id'];
        $prescription = $_POST['prescription'];

        // we can operate the data further
        $db = new DbOperations();

        // uploading the prescription
        $result = $db->uploadPrescription($doctor_id, $patient_id, $appointment_id, $prescription);

        if ($result == 0) {

            // completing the appointment
            $result2 = $db->completeAppointment($appointment_id);

            if ($result2 == 0) {

                // success
                $_SESSION['success'] = "Prescription uploaded and marked appointment as Complete!";
                $response['error'] = false;
                $response['message'] = "Prescription uploaded successfully";
                header("location:../doctor/index.php");

            } else {

                // some error
                $_SESSION['error'] = "Something went wrong, could not mark as read. Please try again!";
                $response['error'] = true;
                $response['message'] = "Some error occured, please try again";
                header("location:../doctor/index.php");

            }

        } elseif ($result == 1) {

            // some error
            $_SESSION['error'] = "Something went wrong, Could not upload the prescription. Please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../doctor/index.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../doctor/index.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../doctor/index.php");

}

// json output
echo json_encode($response);
