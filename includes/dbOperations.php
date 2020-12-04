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

    // create new lab test request by user
    public function requestALabTest($user_id, $details)
    {
        $stmt = $this->con->prepare("INSERT INTO `lab_tests` (`test_id`, `patient_id`, `details`, `test_status`) VALUES (NULL, ?, ?, 'PENDING');");
        $stmt->bind_param("is", $user_id, $details);

        if ($stmt->execute()) {
            // new appointment created
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

    // uploading lab report by doctor
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

    // adding to wishlist
    public function addToWishlist($user_id, $vehicle_id, $make_id, $quantity)
    {
        $stmt = $this->con->prepare("INSERT INTO `wishlist`(`user_id`, `vehicle_id`, `make_id`, `quantity`) VALUES (?, ?, ?, ?); ");
        $stmt->bind_param("iiii", $user_id, $vehicle_id, $make_id, $quantity);

        if ($stmt->execute()) {
            // added to wishlist
            return 1;
        } else {
            // some error
            return 2;
        }
    }

    // adding to cart
    public function addToCart($user_id, $vehicle_id, $make_id, $quantity, $total)
    {
        $stmt = $this->con->prepare("INSERT INTO `cart`(`user_id`, `vehicle_id`, `make_id`, `quantity`, `total_price`) VALUES (?, ?, ?, ?, ?); ");
        $stmt->bind_param("iiiii", $user_id, $vehicle_id, $make_id, $quantity, $total);

        if ($stmt->execute()) {
            // added to cart
            return 1;
        } else {
            // some error
            return 2;
        }
    }

    // adding to orders
    public function addToOrders($user_id, $make, $model, $quantity, $total)
    {
        $stmt = $this->con->prepare("INSERT INTO `orders`(`user_id`, `make`, `model`, `quantity`, `paid_amount`) VALUES (?, ?, ?, ?, ?); ");
        $stmt->bind_param("issii", $user_id, $make, $model, $quantity, $total);

        if ($stmt->execute()) {
            // added to orders table
            return 1;
        } else {
            // some error
            return 2;
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

    // retreiving vehicle data
    public function getVehicleByID($vehicle_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `vehicles` INNER JOIN `manufacturers` ON manufacturers.make_id = vehicles.make INNER JOIN `colours` ON colours.id = vehicles.colour INNER JOIN `transmissions` ON transmissions.id = vehicles.transmission WHERE `vehicle_id` = ?");
        $stmt->bind_param("i", $vehicle_id);
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

    // checking if the manufacturer exists
    private function isManufacturerExist($name)
    {
        $stmt = $this->con->prepare("SELECT `make_id` FROM `manufacturers` WHERE `name` = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // retrieving manufacturers table
    public function getManufacturers()
    {
        $stmt = $this->con->prepare("SELECT * FROM `manufacturers`");
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving colours table
    public function getColours()
    {
        $stmt = $this->con->prepare("SELECT * FROM `colours`");
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving vehicles table
    public function getVehicles()
    {
        $stmt = $this->con->prepare("SELECT * FROM `vehicles` INNER JOIN `manufacturers` ON manufacturers.make_id = vehicles.make INNER JOIN `colours` ON colours.id = vehicles.colour INNER JOIN `transmissions` ON transmissions.id = vehicles.transmission ORDER BY `vehicle_id`");
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving wishlist table
    public function getWishlistByUserId($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `wishlist` INNER JOIN `users` ON users.id = wishlist.user_id INNER JOIN
		vehicles ON vehicles.vehicle_id = wishlist.vehicle_id INNER JOIN `manufacturers` ON manufacturers.make_id = wishlist.make_id
		 WHERE `user_id` = ? ORDER BY `wishlist_id`
		");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving cart table
    public function getCartByUserId($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `cart` INNER JOIN `users` ON users.id = cart.user_id INNER JOIN
		vehicles ON vehicles.vehicle_id = cart.vehicle_id INNER JOIN `manufacturers` ON manufacturers.make_id = cart.make_id
		WHERE `user_id` = ? ORDER BY `cart_id`
		");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving orders table by user id
    public function getOrdersByUserId($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `orders` INNER JOIN `users` ON users.id = orders.user_id
		WHERE `user_id` = ? ORDER BY `order_id`");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving pending orders to user
    public function getPendingOrdersById($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `orders` INNER JOIN `users` ON users.id = orders.user_id
		WHERE `user_id` = ? AND `order_status` = 0 ORDER BY `order_id`");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving pending orders to admin
    public function getAllPendingOrders()
    {
        $stmt = $this->con->prepare("SELECT * FROM `orders` INNER JOIN `users` ON users.id = orders.user_id
		WHERE `order_status` = 0 ORDER BY `timestamp`");
        $stmt->execute();
        return $stmt->get_result();
    }

    // retrieving all orders to admin
    public function getAllOrders()
    {
        $stmt = $this->con->prepare("SELECT * FROM `orders` INNER JOIN `users` ON users.id = orders.user_id ORDER BY `timestamp`");
        $stmt->execute();
        return $stmt->get_result();
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

    // getting the ongoing appointments table to the user
    public function getOngoingAppointmentsByUser($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.doctor_id WHERE `patient_id` = ? AND `appointment_status` = 'ACCEPTED'");
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
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` WHERE `patient_id` = ? AND `test_status` = 'COMPLETED'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();

    }

    // getting all appointments table to the doctor
    public function getOngoingAppointmentsByDoctor($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `appointments` INNER JOIN `users` ON users.user_id = appointments.patient_id WHERE `doctor_id` = ? AND `appointment_status` = 'ACCEPTED'");
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
        $stmt = $this->con->prepare("SELECT * FROM `lab_tests` INNER JOIN `users` ON users.user_id = lab_tests.patient_id WHERE `test_status` = 'PENDING'");
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

    // getting the orders count by user
    public function getOrdersCountByUserId($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `orders` WHERE `user_id` = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return mysqli_num_rows($result);
    }

    // getting the cart count by user
    public function getCartCountByUserId($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `cart` WHERE `user_id` = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return mysqli_num_rows($result);
    }

    // getting the wishlist count by user
    public function getWishlistCountByUserId($user_id)
    {
        $stmt = $this->con->prepare("SELECT * FROM `wishlist` WHERE `user_id` = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return mysqli_num_rows($result);
    }

    /* CRUD  -> U -> UPDATE */

    // accept an appointment by doctor
    public function acceptAppointment($appointment_id, $date)
    {
        $stmt = $this->con->prepare("UPDATE `appointments` SET `appointment_status` = 'ACCEPTED', `date` = ? WHERE `appointment_id` = ?");
        $stmt->bind_param("si", $date, $appointment_id);

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

    // confirm or refund order by updating order status from admin side
    public function updateOrderStatus($order_id, $status)
    {
        $stmt = $this->con->prepare("UPDATE `orders` SET `order_status` = ? WHERE `order_id` = ?");
        $stmt->bind_param("ii", $status, $order_id);

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

    // update a vehicle
    public function updateVehicleDetails($vehicle_id, $model, $year, $engine, $transmission, $horsepower, $condition, $seats, $price, $in_stock)
    {
        $stmt = $this->con->prepare("UPDATE `vehicles` SET `model` = ?, `year` = ?, `engine_capacity` = ?, `transmission` = ?, `horsepower` = ?, `vehicle_condition` = ?, `seats` = ?, `price` = ?, `in_stock` = ? WHERE `vehicle_id` = ?");
        $stmt->bind_param("ssssssisss", $model, $year, $engine, $transmission, $horsepower, $condition, $seats, $price, $in_stock, $vehicle_id);

        if ($stmt->execute()) {
            // vehicle updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update colours
    public function updateColour($colour_id, $colour)
    {
        $stmt = $this->con->prepare("UPDATE `colours` SET `colour` = ? WHERE `id` = ?");
        $stmt->bind_param("si", $colour, $colour_id);

        if ($stmt->execute()) {
            // colour updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    // update manufacturers
    public function updateManufacturer($manufacturer_id, $name, $address, $email, $contact)
    {
        $stmt = $this->con->prepare("UPDATE `manufacturers` SET `name` = ?, `address` = ?, `email` = ?, `contact` = ? WHERE `make_id` = ?");
        $stmt->bind_param("ssssi", $name, $address, $email, $contact, $manufacturer_id);

        if ($stmt->execute()) {
            // manufacturer updated
            return 0;
        } else {
            // some error
            return 1;
        }
    }

    /* CRUD  -> D -> DELETE */

    // delete wishlist item
    public function deleteWishlist($wishlist_id)
    {
        $stmt = $this->con->prepare("DELETE FROM `wishlist` WHERE `wishlist_id` = ?");
        $stmt->bind_param("i", $wishlist_id);

        if ($stmt->execute()) {
            // item deleted
            return 1;
        } else {
            // some error
            return 2;
        }
    }

    // delete cart item
    public function deleteCartItem($cart_id)
    {
        $stmt = $this->con->prepare("DELETE FROM `cart` WHERE `cart_id` = ?");
        $stmt->bind_param("i", $cart_id);

        if ($stmt->execute()) {
            // item deleted
            return 1;
        } else {
            // some error
            return 2;
        }
    }

    // delete vehicle
    public function deleteVehicle($vehicle_id)
    {
        $stmt = $this->con->prepare("DELETE FROM `vehicles` WHERE `vehicle_id` = ?");
        $stmt->bind_param("i", $vehicle_id);

        if ($stmt->execute()) {
            // vehicle deleted
            return 1;
        } else {
            // some error
            return 2;
        }
    }
}
