<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

// checks the method call
if (isset($_POST['btnLogin'])) {

    if (isset($_POST['username']) and isset($_POST['password'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // db object
        $db = new DbOperations();

        if ($db->userLogin($username, $password)) {

            // getting user data
            $user = $db->getUserByUsername($_POST['username']);

            // checks if the user is approved
            if ($user['user_status'] == 'ACTIVE') {

                // admin account
                if ($user['user_type'] == 'ADMIN') {

                    // session and reroute
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    header("location:../admin/index.php");

                    // doctor account
                } elseif ($user['user_type'] == 'DOCTOR') {

                    // session and reroute
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    header("location:../doctor/index.php");

                    // nurse account
                } elseif ($user['user_type'] == 'NURSE') {

                    // session and reroute
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    header("location:../nurse/index.php");

                    // staff account
                } elseif ($user['user_type'] == 'STAFF') {

                    // session and reroute
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    header("location:../staff/index.php");

                    // patient account
                } elseif ($user['user_type'] == 'PATIENT') {

                    // session and reroute
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    header("location:../patient/index.php");

                } else {

                    // account type not valid
                    $_SESSION['error'] = "Your account is not valid.";
                    header("location:../login.php");
                    $response['error'] = true;
                    $response['message'] = "Your account is not of a valid type.";

                }
            } else {

                // account suspended
                $_SESSION['error'] = "Your account is suspended. Please create a new account or contact the administrator.";
                header("location:../login.php");
                $response['error'] = true;
                $response['message'] = "Your request hasn't been approved yet. Please try again later.";

            }
        } else {

            // incorrect username or password
            $_SESSION['error'] = "The username or password you entered is incorrect. Please check again.";
            $response['error'] = true;
            $response['message'] = "Invalid username or password";
            header("location:../login.php");

        }
    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../login.php");

    }
} else {

    // wrong method
    $response['error'] = true;
    $response['message'] = "Invalid Request";

}

// json output
echo json_encode($response);
