<?php

require_once '../includes/dbOperations.php';
$response = array();

if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];

    // db object
    $db = new DbOperations();

    // lists for admin
    $users_admin = $db->getUsers();
    $users = $db->getUsers();
    $doctor_count = 0;
    $nurse_count = 0;
    $staff_count = 0;
    $patient_count = 0;

    while ($row = mysqli_fetch_array($users)) {

        if ($row['user_type'] == 'DOCTOR') {
            $doctor_count = $doctor_count += 1;
        } elseif ($row['user_type'] == 'NURSE') {
            $nurse_count = $nurse_count += 1;
        } elseif ($row['user_type'] == 'STAFF') {
            $staff_count = $staff_count += 1;
        } elseif ($row['user_type'] == 'PATIENT') {
            $patient_count = $patient_count += 1;
        }

        $response['doctors'] = $doctor_count;
        $response['nurse'] = $nurse_count;
        $response['staff'] = $staff_count;
        $response['patient'] = $patient_count;
        $response['users'] = $users_admin;

    }

    // lists for patient
    $doctors = $db->getDoctors();

} else {
    $_SESSION['error'] = "Session timed out. Please login to continue.";
    $response['error'] = true;
    $response['message'] = "Session timed out. Please login to continue.";
}

// echo json_encode($response);
