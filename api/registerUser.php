<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnRegisterUser'])) {

    if (
        isset($_POST['full_name']) &&
        isset($_POST['username']) &&
        isset($_POST['email']) &&
        isset($_POST['contact']) &&
        isset($_POST['address']) &&
        isset($_POST['password'])
    ) {

        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $password = $_POST['password'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->registerUser($full_name, $username, $email, $contact, $address, $password);

        if ($result == 1) {

            // success
            $_SESSION['success'] = "You have registered successfully, Please log in!";
            $response['error'] = false;
            $response['message'] = "User created successfully";
            header("location:../login.php");

        } elseif ($result == 2) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../register.php");

        } elseif ($result == 0) {

            // user exists
            $_SESSION['error'] = "It seems that this user already exists, please choose a different email and username.";
            $response['error'] = true;
            $response['message'] = "It seems you are already registered, please choose a different email and username";
            header("location:../register.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../register.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../register.php");

}

// json output
// echo json_encode($response);
