<?php


require '../vendor/autoload.php';

$stripe_secret_key = "sk_test_51Q4LmKBhAeLMgReUABf6aIY2ncoGS0xBLALuvy93bKRO6ZuSPaLeMI4P9mz3JGKAWIqJIwSDqM6JLylaQTMQE5Ra00SpTbEytI";

\Stripe\Stripe::setApiKey($stripe_secret_key);

session_start(); // Ensure session is started to use session variables
$scheduleid = $_SESSION['scheduleID'];

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "https://cb7e-102-117-42-13.ngrok-free.app/edoc-echanneling-main/patient/booking-complete.php?id=" . $scheduleid,
    // "cancel_url" => "http://localhost/index.php",
    "locale" => "auto",
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "usd",
                "unit_amount" => 2000,
                "product_data" => [
                    "name" => "GP Booking"
                ]
            ]
        ],
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
