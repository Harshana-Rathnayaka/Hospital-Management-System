<?php

session_start();

require_once '../vendor/autoload.php';
require_once '../includes/dbOperations.php';

$stripe = new \Stripe\StripeClient('sk_test_TeymjdiMfBgX3S3Y4aH02mHJ00iHAcAqO7');
\Stripe\Stripe::setApiKey('sk_test_TeymjdiMfBgX3S3Y4aH02mHJ00iHAcAqO7');

// db object
$db = new DbOperations();

// sanitizing the POST array
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

// first get the payment type
$payment_for = $POST['payment_for'];

// now check what kind of payment it is (APPOINTMENT/ LAB_TEST)
if ($payment_for == 'APPOINTMENT') {

    // payment type is APPOINTMENT

    // getting the values
    $id = $POST['id'];
    $amount = $POST['amount'];
    $name_on_card = $POST['name_on_card'];
    $token = $POST['stripeToken'];
    $patient_id = $_SESSION['user_id'];

// create the customer in stripe
    $customer = \Stripe\Customer::create(array(
        "name" => $name_on_card,
        "source" => $token,
    ));

// charge the customer
    $charge = \Stripe\Charge::create(array(
        "amount" => $amount,
        "currency" => "lkr",
        "description" => "Payment for the " . $payment_for,
        "customer" => $customer->id,
    ));

// we can operate the data further
    $stripe_customer_id = $charge->customer;
    $real_amount = substr($amount, 0, 4);

    // adding to the payments table in the db
    $result = $db->addToPayments($patient_id, $payment_for, $real_amount, $stripe_customer_id);

    if ($result == 0) {

        $result2 = $db->markAsPaid($id);

        if ($result2 == 0) {

            // pament was successful and added to both stripe and the db
            $_SESSION['success'] = $charge->description . ' was successful. Payment ID - ' . $charge->id;
            $response['error'] = false;
            $response['message'] = "Payment was successful";
            header("location:../patient/ongoing-appointments.php");

        } elseif ($result2 == 1) {

            // could not add to the db
            $_SESSION['error'] = "Something went wrong, " . $payment_for . " could not be marked as PAID!";
            $response['error'] = true;
            $response['message'] = "Something went wrong, " . $payment_for . " could not be marked as PAID!";
            header("location:../patient/ongoing-appointments.php");
        }

    } elseif ($result == 1) {

        // payment was not successful and could not be added to the stripe dashboard
        $_SESSION['error'] = "Something went wrong, please try again!";
        $response['error'] = true;
        $response['message'] = "Some error occured, please try again";
        header("location:../patient/ongoing-appointments.php");

    }
} elseif ($payment_for == 'LAB_TEST') {

    // payment type is LAB_TEST

    // getting the values
    $amount = $POST['amount'];
    $name_on_card = $POST['name_on_card'];
    $token = $POST['stripeToken'];
    $patient_id = $_SESSION['user_id'];
    $details = $POST['details'];

    // create the customer in stripe
    $customer = \Stripe\Customer::create(array(
        "name" => $name_on_card,
        "source" => $token,
    ));

// charge the customer
    $charge = \Stripe\Charge::create(array(
        "amount" => $amount,
        "currency" => "lkr",
        "description" => "Payment for the " . $payment_for,
        "customer" => $customer->id,
    ));

    // we can operate the data further
    $stripe_customer_id = $charge->customer;
    $real_amount = substr($amount, 0, 4);

    // adding to the payments table in the db
    $result = $db->addToPayments($patient_id, $payment_for, $real_amount, $stripe_customer_id);

    if ($result == 0) {

        // creating the lab test request in the lab_tests table
        $result2 = $db->requestALabTest($patient_id, $details);

        if ($result2 == 0) {

            // payment was successful and added the lab test request to the db
            $_SESSION['success'] = "Lab Test request submitted successfully! Payment ID - " . $charge->id;
            $response['error'] = false;
            $response['message'] = "Lab Test request submitted successfully";
            header("location:../patient/new-test.php");

        } elseif ($result2 == 1) {

            // lab test request was not added to the db
            $_SESSION['error'] = "Something went wrong, lab test request could not be submitted. Please try again!";
            $response['error'] = true;
            $response['message'] = "Some error occured, please try again";
            header("location:../patient/new-test.php");

        }

    } elseif ($result == 1) {

        // payment was not successful and could not be added to the stripe dashboard
        $_SESSION['error'] = "Something went wrong, please try again!";
        $response['error'] = true;
        $response['message'] = "Some error occured, please try again";
        header("location:../patient/new-test.php");

    }

}
