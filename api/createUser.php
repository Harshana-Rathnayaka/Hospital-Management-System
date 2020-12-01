<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnNewUser'])) {

    if (
        isset($_POST['full_name']) &&
        isset($_POST['username']) &&
        isset($_POST['email']) &&
        isset($_POST['contact']) &&
        isset($_POST['password']) &&
        isset($_POST['user_type'])
    ) {

        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];

        // we can operate the data further
        $db = new DbOperations();

        $result = $db->createUser($full_name, $username, $email, $contact, $password, $user_type);

        if ($result == 1) {

            // success
            $_SESSION['success'] = "User created successfully!";
            $response['error'] = false;
            $response['message'] = "User created successfully";
            header("location:../admin/new-user.php");

        } elseif ($result == 2) {

            // some error
            $_SESSION['error'] = "Something went wrong, please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../admin/new-user.php");

        } elseif ($result == 0) {

            // user exists
            $_SESSION['error'] = "It seems that this user already exists, please choose a different email and username.";
            $response['error'] = true;
            $response['message'] = "It seems you are already registered, please choose a different email and username";
            header("location:../admin/new-user.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../admin/new-user.php");

    }

} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../admin/new-user.php");

}

// json output
// echo json_encode($response);
