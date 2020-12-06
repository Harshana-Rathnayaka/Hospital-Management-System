<?php

session_start();

require_once '../vendor/autoload.php';
require_once '../includes/dbOperations.php';

$stripe = new \Stripe\StripeClient('sk_test_TeymjdiMfBgX3S3Y4aH02mHJ00iHAcAqO7');
\Stripe\Stripe::setApiKey('sk_test_TeymjdiMfBgX3S3Y4aH02mHJ00iHAcAqO7');

// sanitizing the POST array
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

// getting the values
$id = $POST['id'];
$amount = $POST['amount'];
$payment_for = $POST['payment_for'];
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
$db = new DbOperations();

$stripe_customer_id = $charge->customer;
$real_amount = substr($amount, 0, 4);

$result = $db->addToPayments($patient_id, $payment_for, $real_amount, $stripe_customer_id);

if ($result == 0) {

    $result2 = $db->markAsPaid($id, $payment_for);

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

    // payment was successful and added to stripe
    $_SESSION['success'] = $charge->description . ' was successful. Payment ID - ' . $charge->id;
    $response['error'] = false;
    $response['message'] = "Payment was successful";
    header("location:../patient/ongoing-appointments.php");

} elseif ($result == 1) {

    // payment was not successful and could not be added to the stripe dashboard
    $_SESSION['error'] = "Something went wrong, please try again!";
    $response['error'] = true;
    $response['message'] = "Some error occured, please try again";
    header("location:../patient/ongoing-appointments.php");

}
