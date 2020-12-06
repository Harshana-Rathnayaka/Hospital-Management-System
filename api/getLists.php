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
    $pending_appointments_user = $db->getPendingAppointmentsByUser($user_id);
    $payable_appointments_user = $db->getPayableAppointmentsByUser($user_id);
    $ongoing_appointments_user = $db->getOngoingAppointmentsByUser($user_id);
    $rejected_appointments_user = $db->getRejectedAppointmentsByUser($user_id);
    $completed_appointments_user = $db->getCompletedAppointmentsByUser($user_id);
    $incoming_prescriptions_user = $db->getIncomingPrescriptionsByUser($user_id);
    $completed_lab_tests_user = $db->getCompletedLabTestsByUser($user_id);
    $pending_lab_tests_user = $db->getPendingLabTestsByUser($user_id);
    $ongoing_lab_tests_user = $db->getOngoingLabTestsByUser($user_id);

    // lists for doctor
    $ongoing_appointments_doctor = $db->getOngoingAppointmentsByDoctor($user_id);
    $pending_appointments_doctor = $db->getPendingAppointmentsByDoctor($user_id);
    $rejected_appointments_doctor = $db->getRejectedAppointmentsByDoctor($user_id);
    $completed_appointments_doctor = $db->getCompletedAppointmentsByDoctor($user_id);

    // lists for nurse
    $pending_prescriptions = $db->getPendingPrescriptions();
    $shipped_prescriptions = $db->getShippedPrescriptions();
    $completed_prescriptions = $db->getCompletedPrescriptions();

    // lists for staff
    $pending_lab_tests_staff = $db->getPendingLabTests();
    $ongoing_lab_tests_staff = $db->getOngoingLabTests();
    $completed_lab_tests_staff = $db->getCompletedLabTests();

} else {
    $_SESSION['error'] = "Session timed out. Please login to continue.";
    $response['error'] = true;
    $response['message'] = "Session timed out. Please login to continue.";
}

// echo json_encode($response);
