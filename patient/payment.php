<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/main.css">  
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION['payment_error'])) {
        echo "<div class='error'>" . htmlspecialchars($_SESSION['payment_error']) . "</div>";
        unset($_SESSION['payment_error']);
    }

    // Check if the user is logged in
    if (!isset($_SESSION["user"])) {
        header("location: ../login.php");
        exit();
    }

    // Ensure the session has the required values
    if (!isset($_SESSION['scheduleID']) || !isset($_SESSION['apponum'])) {
        header("location: schedule.php");
        exit();
    }

    // Configuration for Stripe
    require_once 'config.php'; // Make sure to include your Stripe keys here
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY); // Replace with your actual Stripe secret key
    ?>

    <div class="container">
        <h1>Payment Details</h1>
        <form id="payment-form" action="process_payment.php" method="post">
            <div id="card-element"><!-- A Stripe Element will be inserted here. --></div>
            <button id="submit">Pay</button>
            <div id="payment-result"></div>
            <label>
                <input type="checkbox" id="terms" name="terms" required>
                I accept the <a href="#">Terms and Conditions</a>
            </label>
        </form>
    </div>

    
</body>
</html>
