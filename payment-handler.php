<?php
require 'vendor/autoload.php'; // Include the Stripe PHP library

\Stripe\Stripe::setApiKey('YOUR_SECRET_STRIPE_KEY'); // Replace with your secret Stripe key

$data = json_decode(file_get_contents('php://input'), true);
$paymentMethodId = $data['paymentMethodId'];
$amount = $data['amount'];

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method' => $paymentMethodId,
        'confirmation_method' => 'manual',
        'confirm' => true,
    ]);

    // Payment succeeded
    http_response_code(200);
} catch (\Stripe\Exception\CardException $e) {
    // Payment failed
    http_response_code(500);
}
?>
