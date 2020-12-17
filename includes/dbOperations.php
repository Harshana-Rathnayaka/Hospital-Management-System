<?php

class DbOperations
{

    private $con;

    public function __construct()
    {

        require_once dirname(__FILE__) . '/dbConnection.php';

        $db = new DbConnect();

        $this->con = $db->connect();
    }

    /* CRUD  -> C -> CREATE */

    // user creation by admin
    public function createUser($full_name, $username, $email, $contact, $pass, $user_type)
    {
        $password = md5($pass); // password encrypting
        if ($this->isUserExist($username, $email)) {
            // user exists
            return 0;
        } else {
            $stmt = $this->con->prepare("INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `contact`, `password`, `user_type`, `user_status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, 'ACTIVE');");
            $stmt->bind_param("ssssss", $full_name, $username, $email, $contact, $password, $user_type);

            if ($stmt->execute()) {
                // user created
                return 1;
            } else {
                // some error
                return 2;
            }
        }
    }

    // user registration
    public function registerUser($full_name, $username, $email, $contact, $address, $pass)
    {
        $password = md5($pass); // password encrypting
        if ($this->isUserExist($username, $email)) {
            // user exists
            return 0;
        } else {
            $stmt = $this->con->prepare("INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `contact`, `address`, `password`, `user_type`, `user_status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, 'PATIENT', 'ACTIVE');");
            $stmt->bind_param("ssssss", $full_name, $username, $email, $contact, $address, $password);

            if ($stmt->execute()) {
                // user registered
                return 1;
            } else {
                // some error
                return 2;
            }
        }
    }

    // user login
    public function userLogin($username, $pass)
    {
        $password = md5($pass); // password decrypting
        $stmt = $this->con->prepare("SELECT `user_id` FROM `users` WHERE `username` = ? AND `password` = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // create new appointment by user
    public function createAppointment($user_id, $doctor_id, $description)
    {
        $stmt = $this->con->prepare("INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `description`, `appointment_status`) VALUES (NULL, ?, ?, ?, 'PENDING');");
        $stmt->bind_param("iis", $user_id, $doctor_id, $description);

        if ($stmt->execute()) {
            // new appointment created
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // make payments by user
    public function addToPayments($patient_id, $payment_for, $amount, $stripe_customer_id)
    {
        $stmt = $this->con->prepare("INSERT INTO `payments`(`payment_id`, `patient_id`, `payment_for`, `paid_amount`, `stripe_customer_id`) VALUES (NULL, ?, ?, ?, ?);");
        $stmt->bind_param("isss", $patient_id, $payment_for, $amount, $stripe_customer_id);

        if ($stmt->execute()) {
            // payment created
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // marking the appointment as PAID by the user
    public function markAsPaid($appointment_id)
    {

        $stmt = $this->con->prepare("UPDATE `appointments` SET `appointment_status` = 'PAID' WHERE `appointment_id` = ?");
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            // marked as PAID
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // create new lab test request by user
    public function requestALabTest($patient_id, $details)
    {
        $stmt = $this->con->prepare("INSERT INTO `lab_tests` (`test_id`, `patient_id`, `details`, `test_status`) VALUES (NULL, ?, ?, 'PAID');");
        $stmt->bind_param("is", $patient_id, $details);

        if ($stmt->execute()) {
            // new lab test created
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // uploading prescription by doctor
    public function uploadPrescription($doctor_id, $patient_id, $appointment_id, $prescription)
    {
        $stmt = $this->con->prepare("INSERT INTO `prescriptions` (`prescription_id`, `doctor_id`, `patient_id`, `appointment_id`, `prescription`, `prescription_status`) VALUES (NULL, ?, ?, ?, ?, 'PENDING');");
        $stmt->bind_param("iiis", $doctor_id, $patient_id, $appointment_id, $prescription);

        if ($stmt->execute()) {
            // prescription uploaded
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // uploading lab report by staff
    public function uploadTheLabReport($lab_test_id, $report_link)
    {
        $stmt = $this->con->prepare("INSERT INTO `lab_reports` (`report_id`, `lab_test_id`, `file_location`) VALUES (NULL, ?, ?);");
        $stmt->bind_param("is", $lab_test_id, $report_link);

        if ($stmt->execute()) {
            // report uploaded
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    /* CRUD  -> r -> RETRIEVE */

    // retreiving user data by username
    public function getUserByUsername($username)
    {
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `username` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // retreiving user data by id
    public function getUserById($userid)
    {
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // checking if the user exists
    private function isUserExist($username, $email)
    {
        $stmt = $this->con->prepare("SELECT `user_id` FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // checking if the email is taken
    public function isEmailTaken($email)
    {
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // checking if the username is taken
    public function isUsernameTaken($username)
    {
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `username` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // retrieving users table
    public function getUsers()
    {
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `user_type` != 'ADMIN' AND `user_status` = 'ACTIVE'");
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving the doctors for patient
    public function getDoctors()
    {
        $stmt = $this->con->prepare("SELECT * FROM `users` WHERE `user_type` = 'DOCTOR' AND `user_status` = 'ACTIVE'");
        $stmt->execute();
        return $stmt->get_result();
    }

    // getting the pending appointments table to the user
    public function getPendingAppointmentsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.doctor_id WHERE `patient_id` = ? AND `appointment_status` = 'PENDING'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the payable appointments table to the user
    public function getPayableAppointmentsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.doctor_id WHERE `patient_id` = ? AND `appointment_status` = 'ACCEPTED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the ongoing appointments table to the user
    public function getOngoingAppointmentsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.doctor_id WHERE `patient_id` = ? AND `appointment_status` = 'PAID'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the rejected appointments table to the user
    public function getRejectedAppointmentsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.doctor_id WHERE `patient_id` = ? AND `appointment_status` = 'REJECTED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the completed appointments table to the user
    public function getCompletedAppointmentsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` app LEFT JOIN `users` u ON u.user_id = app.doctor_id
        LEFT JOIN `prescriptions` pres ON pres.appointment_id = app.appointment_id WHERE app.patient_id = ? AND `appointment_status` = 'COMPLETED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the incoming prescriptions table to the user
    public function getIncomingPrescriptionsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `prescriptions` WHERE `patient_id` = ? AND `prescription_status` = 'SHIPPED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the completed lab tests table to the user
    public function getCompletedLabTestsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` INNER JOIN `lab_reports` ON lab_reports.lab_test_id = lab_tests.test_id WHERE `patient_id` = ? AND `test_status` = 'COMPLETED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the pending lab tests table to the user
    public function getPendingLabTestsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` WHERE `patient_id` = ? AND `test_status` = 'PAID'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting the ongoing lab tests table to the user
    public function getOngoingLabTestsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` WHERE `patient_id` = ? AND `test_status` = 'ACCEPTED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting all appointments table to the doctor
    public function getOngoingAppointmentsByDoctor($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.patient_id WHERE `doctor_id` = ? AND `appointment_status` = 'PAID'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting pending appointments table to the doctor
    public function getPendingAppointmentsByDoctor($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.patient_id WHERE `doctor_id` = ? AND `appointment_status` = 'PENDING'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting rejected appointments table to the doctor
    public function getRejectedAppointmentsByDoctor($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.patient_id WHERE `doctor_id` = ? AND `appointment_status` = 'REJECTED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting completed appointments table to the doctor
    public function getCompletedAppointmentsByDoctor($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.patient_id WHERE `doctor_id` = ? AND `appointment_status` = 'COMPLETED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting pending prescriptions to the nurse
    public function getPendingPrescriptions()
    {
        $stmt = $this->con->prepare("SELECT * FROM `prescriptions` INNER JOIN `users` ON users.user_id = prescriptions.patient_id WHERE `prescription_status` = 'PENDING'");
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting shipped prescriptions to the nurse
    public function getShippedPrescriptions()
    {
        $stmt = $this->con->prepare("SELECT * FROM `prescriptions` INNER JOIN `users` ON users.user_id = prescriptions.patient_id WHERE `prescription_status` = 'SHIPPED'");
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting completed prescriptions to the nurse
    public function getCompletedPrescriptions()
    {
        $stmt = $this->con->prepare("SELECT * FROM `prescriptions` INNER JOIN `users` ON users.user_id = prescriptions.patient_id WHERE `prescription_status` = 'RECEIVED'");
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting pending lab tests to the staff
    public function getPendingLabTests()
    {
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` INNER JOIN `users` ON users.user_id = lab_tests.patient_id WHERE `test_status` = 'PAID'");
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting ongoing lab tests to the staff
    public function getOngoingLabTests()
    {
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` INNER JOIN `users` ON users.user_id = lab_tests.patient_id WHERE `test_status` = 'ACCEPTED'");
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting completed lab tests to the staff
    public function getCompletedLabTests()
    {
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` INNER JOIN `users` ON users.user_id = lab_tests.patient_id WHERE `test_status` = 'COMPLETED'");
        $stmt->execute();
        return $stmt->get_result();

    }

    /* CRUD  -> U -> UPDATE */

    // accept an appointment by doctor
    public function acceptAppointment($appointment_id, $date, $time)
    {
        $stmt = $this->con->prepare("UPDATE `appointments` SET `appointment_status` = 'ACCEPTED', `date` = ?, `time` = ? WHERE `appointment_id` = ?");
        $stmt->bind_param("ssi", $date, $time, $appointment_id);

        if ($stmt->execute()) {
            // appointment accepted
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // accept a lab test by staff
    public function acceptLabTest($lab_test_id, $date)
    {
        $stmt = $this->con->prepare("UPDATE `lab_tests` SET `test_status` = 'ACCEPTED', `date` = ? WHERE `test_id` = ?");
        $stmt->bind_param("si", $date, $lab_test_id);

        if ($stmt->execute()) {
            // lab test accepted
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // reject an appointment by doctor
    public function rejectAppointment($appointment_id, $comment)
    {
        $stmt = $this->con->prepare("UPDATE `appointments` SET `appointment_status` = 'REJECTED', `comments` = ? WHERE `appointment_id` = ?");
        $stmt->bind_param("si", $comment, $appointment_id);

        if ($stmt->execute()) {
            // appointment rejected
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // complete an appointment by doctor
    public function completeAppointment($appointment_id)
    {
        $stmt = $this->con->prepare("UPDATE `appointments` SET `appointment_status` = 'COMPLETED' WHERE `appointment_id` = ?");
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            // appointment completed
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // mark lab test as complete by staff
    public function completeLabReport($lab_test_id)
    {
        $stmt = $this->con->prepare("UPDATE `lab_tests` SET `test_status` = 'COMPLETED' WHERE `test_id` = ?");
        $stmt->bind_param("i", $lab_test_id);

        if ($stmt->execute()) {
            // lab test completed
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // mark a prescription as shipped
    public function shipPrescription($prescription_id)
    {
        $stmt = $this->con->prepare("UPDATE `prescriptions` SET `prescription_status` = 'SHIPPED', `prescription_location` = 'Dispatched from the Hospital' WHERE `prescription_id` = ?");
        $stmt->bind_param("i", $prescription_id);

        if ($stmt->execute()) {
            // prescription shipped
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update prescription location
    public function updateLocation($prescription_id, $prescription_location)
    {
        $stmt = $this->con->prepare("UPDATE `prescriptions` SET `prescription_location` = ? WHERE `prescription_id` = ?");
        $stmt->bind_param("si", $prescription_location, $prescription_id);

        if ($stmt->execute()) {
            // prescription shipped
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // mark a prescription as received
    public function markAsReceived($prescription_id)
    {
        $stmt = $this->con->prepare("UPDATE `prescriptions` SET `prescription_status` = 'RECEIVED' WHERE `prescription_id` = ?");
        $stmt->bind_param("i", $prescription_id);

        if ($stmt->execute()) {
            // prescription received
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update appointment description
    public function updateDescription($appointment_id, $description)
    {
        $stmt = $this->con->prepare("UPDATE `appointments` SET `description` = ? WHERE `appointment_id` = ?");
        $stmt->bind_param("si", $description, $appointment_id);

        if ($stmt->execute()) {
            // appointment updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update lab test details
    public function updateLabTestDetails($lab_test_id, $details)
    {
        $stmt = $this->con->prepare("UPDATE `lab_tests` SET `details` = ? WHERE `test_id` = ?");
        $stmt->bind_param("si", $details, $lab_test_id);

        if ($stmt->execute()) {
            // lab test details updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // cancel a lab test by user
    public function cancelLabTest($lab_test_id)
    {
        $stmt = $this->con->prepare("UPDATE `lab_tests` SET `test_status` = 'CANCELLED' WHERE `test_id` = ?");
        $stmt->bind_param("i", $lab_test_id);

        if ($stmt->execute()) {
            // lab test cancelled
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // cancel an appointment by user
    public function cancelAppointment($appointment_id)
    {
        $stmt = $this->con->prepare("UPDATE `appointments` SET `appointment_status` = 'CANCELLED' WHERE `appointment_id` = ?");
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            // appointment cancelled
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // deleting the prescription by changing the status. Not really deleting.
    public function deletePrescription($prescription_id)
    {
        $stmt = $this->con->prepare("UPDATE `prescriptions` SET `prescription_status` = 'COMPLETED' WHERE `prescription_id` = ?");
        $stmt->bind_param("i", $prescription_id);

        if ($stmt->execute()) {
            // prescription deleted (status marked as completed)
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // activate or deactivate user account by updating user status from admin side
    public function updateUserStatus($user_id, $status)
    {
        $stmt = $this->con->prepare("UPDATE `users` SET `user_status` = ? WHERE `id` = ?");
        $stmt->bind_param("ii", $status, $user_id);

        if ($stmt->execute()) {
            // user account status updated by admin
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update admin details
    public function updateAdminAccountDetails($userid, $firstname, $lastname, $username, $email)
    {
        $stmt = $this->con->prepare("UPDATE `users` SET `first_name` = ?, `last_name` = ?, `username` = ?, `email` = ? WHERE `id` = ?");
        $stmt->bind_param("ssssi", $firstname, $lastname, $username, $email, $userid);

        if ($stmt->execute()) {
            // admin account details updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update user details
    public function updateUserAccountDetails($userid, $firstname, $lastname, $birthday, $gender, $username, $email, $contact)
    {
        $stmt = $this->con->prepare("UPDATE `users` SET `first_name` = ?, `last_name` = ?, `birthday` = ?, `gender` = ?, `username` = ?, `email` = ?, `contact` = ? WHERE `id` = ?");
        $stmt->bind_param("sssssssi", $firstname, $lastname, $birthday, $gender, $username, $email, $contact, $userid);

        if ($stmt->execute()) {
            // user account details updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

}
