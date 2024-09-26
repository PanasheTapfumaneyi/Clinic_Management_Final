<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <?php
    // Start the session
    session_start();

    // Unset all the server side variables
    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";
    $_SESSION["paccounttype"] = ""; // Add this line

    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $_SESSION["date"] = $date;

    // Import database
    include("connection.php");

    if ($_POST) {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];
        
        $error = '<label for="promter" class="form-label"></label>';

        $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
        if ($result->num_rows == 1) {
            $utype = $result->fetch_assoc()['usertype'];

            if ($utype == 'p') {
                // Check patient credentials and fetch paccounttype
                $checker = $database->query("SELECT paccounttype FROM patient WHERE pemail='$email' AND ppassword='$password'");
                if ($checker->num_rows == 1) {
                    $patientData = $checker->fetch_assoc();
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'p';
                    $_SESSION['paccounttype'] = $patientData['paccounttype']; // Store paccounttype in session
                    
                    header('location: patient/index.php');
                    exit(); // Add exit after header redirect
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }

            } elseif ($utype == 'a') {
                // Check admin credentials
                $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
                if ($checker->num_rows == 1) {
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'a';
                    header('location: admin/index.php');
                    exit(); // Add exit after header redirect
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }

            } elseif ($utype == 'd') {
                // Check doctor credentials
                $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
                if ($checker->num_rows == 1) {
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'd';
                    header('location: doctor/index.php');
                    exit(); // Add exit after header redirect
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We cant found any account for this email.</label>';
        }
    } else {
        $error = '<label for="promter" class="form-label">&nbsp;</label>';
    }
    ?>

    <center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
            <tr>
                <td>
                    <p class="header-text">Welcome Back!</p>
                </td>
            </tr>
            <div class="form-body">
                <tr>
                    <td>
                        <p class="sub-text">Login with your details to continue</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td">
                            <label for="useremail" class="form-label">Email: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <label for="userpassword" class="form-label">Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="Password" name="userpassword" class="input-text" placeholder="Password" required>
                    </td>
                </tr>
                <tr>
                    <td><br>
                    <?php echo $error ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Login" class="login-btn btn-primary btn">
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>
                    </form>
            </table>
        </div>
    </center>
</body>
</html>
