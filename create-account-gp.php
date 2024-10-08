<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create Account</title>
    <style>
        .container {
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
<?php

session_start();

// Unset session variables
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Import database connection
include("connection.php");

if ($_POST) {
    $result = $database->query("SELECT * FROM webuser");

    // Get personal data from session
    $fname = $_SESSION['personal']['fname'];
    $lname = $_SESSION['personal']['lname'];
    $name = $fname . " " . $lname;
    $address = $_SESSION['personal']['address'];
    $nic = $_SESSION['personal']['nic'];
    $dob = $_SESSION['personal']['dob'];

    // Get form data
    $email = $_POST['newemail'];
    $tele = $_POST['tele'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['cpassword'];
    $accountType = "GP"; // Set the account type to GP

    if ($newpassword == $cpassword) {
        // Check if the email already exists
        $result = $database->query("SELECT * FROM webuser WHERE email='$email';");
        if ($result->num_rows == 1) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        } else {
            // Insert into 'patient' table with the 'paccounttype' column
            $database->query("INSERT INTO patient (pemail, pname, ppassword, paddress, pnic, pdob, ptel, paccounttype) 
                              VALUES ('$email', '$name', '$newpassword', '$address', '$nic', '$dob', '$tele', '$accountType');");

            // Insert into 'webuser' table
            $database->query("INSERT INTO webuser VALUES ('$email', 'p')");

            // Set session variables and redirect
            $_SESSION["user"] = $email;
            $_SESSION["usertype"] = "p";
            $_SESSION["username"] = $fname;

            header('Location: patient/index.php');
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Please re-confirm your password.</label>';
    }
} else {
    $error = '<label for="promter" class="form-label"></label>';
}

?>

<center>
<div class="container">
    <table border="0" style="width: 69%;">
        <tr>
            <td colspan="2">
                <p class="header-text">Let's Get Started</p>
                <p class="sub-text">It's Okey, Now Create User Account.</p>
            </td>
        </tr>
        <tr>
            <form action="" method="POST">
            <td class="label-td" colspan="2">
                <label for="newemail" class="form-label">Email: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <label for="tele" class="form-label">Mobile Number: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="tel" name="tele" class="input-text" placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}">
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <label for="newpassword" class="form-label">Create New Password: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <label for="cpassword" class="form-label">Confirm Password: </label>
            </td>
        </tr>
        <tr>
            <td class="label-td" colspan="2">
                <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required>
            </td>
        </tr>
        
        <!-- Hidden field to send 'GP' as account type -->
        <input type="hidden" name="accounttype" value="GP">

        <tr>
            <td colspan="2">
                <?php echo $error ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
            </td>
            <td>
                <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                <a href="login.php" class="hover-link1 non-style-link">Login</a>
                <br><br><br>
            </td>
        </tr>
            </form>
        </tr>
    </table>
</div>
</center>
</body>
</html>
