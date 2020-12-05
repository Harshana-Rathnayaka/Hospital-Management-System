<?php

session_start();

require_once '../vendor/autoload.php';

$stripe = new \Stripe\StripeClient('sk_test_TeymjdiMfBgX3S3Y4aH02mHJ00iHAcAqO7');
\Stripe\Stripe::setApiKey('sk_test_TeymjdiMfBgX3S3Y4aH02mHJ00iHAcAqO7');

// sanitizing the POST array
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

// getting the values
$appointment_id = $POST['appointment_id'];
$name_on_card = $POST['name_on_card'];
$token = $POST['stripeToken'];

// create the customer in stripe
$customer = \Stripe\Customer::create(array(
    "name" => $name_on_card,
    "source" => $token,
));

$charge = \Stripe\Charge::create(array(
    "amount" => 500000,
    "currency" => "lkr",
    "description" => "Payment for the appointment",
    "customer" => $customer->id,
));

// success
$_SESSION['success'] = $charge->description . ' was successful. Payment ID - ' . $charge->id;
header("location:../patient/ongoing-appointments.php");
