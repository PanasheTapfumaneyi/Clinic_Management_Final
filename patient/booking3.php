<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Sessions</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }
    
    // Import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];
    $paccounttype = $userfetch["paccounttype"];

    // Determine channeling fee based on account type
    $channelingFee = 0;
    if ($paccounttype === "GP") {
        $channelingFee = 20; // Fee in dollars
    } elseif ($paccounttype === "Practitioner") {
        $channelingFee = 50; // Fee in dollars
    }

    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');

    // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booknow'])) {
    //     // Include Microsoft Graph SDK
    //     require_once __DIR__ . '/vendor/autoload.php'; // Adjust path as necessary

    //     // use Microsoft\Graph\GraphServiceClient;
    //     // use Microsoft\Graph\Generated\Models\OnlineMeeting;

    //     // Assuming you have the tokenRequestContext and scopes properly set
    //     // You need to implement the logic to obtain a valid access token here
    //     $tokenRequestContext = /* Your logic to obtain the token */;
    //     $scopes = ["https://graph.microsoft.com/.default"]; // Adjust scopes as needed

    //     // Create an online meeting
    //     $graphServiceClient = new GraphServiceClient($tokenRequestContext, $scopes);

    //     $requestBody = new OnlineMeeting();
    //     $requestBody->setStartDateTime(new \DateTime('2024-09-26T14:30:00')); // Adjust time as needed
    //     $requestBody->setEndDateTime(new \DateTime('2024-09-26T15:00:00')); // Adjust time as needed
    //     $requestBody->setSubject('User Token Meeting');
    //     $meeting = $graphServiceClient->me()->onlineMeetings()->post($requestBody)->wait();

    //     // Send the meeting link to the email
    //     $meetingLink = $meeting->getJoinWebUrl();
    //     $toEmail = 'test@gmail.com';
    //     $subject = 'Your Meeting Link';
    //     $message = "You have a new meeting scheduled. Join here: $meetingLink";

    //     // Sending email (assuming you have mail server configured)
    //     mail($toEmail, $subject, $message);
    // }
    ?>

    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="schedule.php" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors">&nbsp;&nbsp;
                            <?php
                            echo '<datalist id="doctors">';
                            $list11 = $database->query("select DISTINCT * from doctor;");
                            $list12 = $database->query("select DISTINCT * from schedule GROUP BY title;");

                            for ($y = 0; $y < $list11->num_rows; $y++) {
                                $row00 = $list11->fetch_assoc();
                                $d = $row00["docname"];
                                echo "<option value='$d'><br/>";
                            }

                            for ($y = 0; $y < $list12->num_rows; $y++) {
                                $row00 = $list12->fetch_assoc();
                                $d = $row00["title"];
                                echo "<option value='$d'><br/>";
                            }

                            echo '</datalist>';
                            ?>
                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php echo $today; ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                    </td>
                </tr>
                
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                                <tbody>
                                    <?php
                                    if (($_GET)) {
                                        if (isset($_GET["id"])) {
                                            $id = $_GET["id"];
                                            $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduleid=$id";
                                            $result = $database->query($sqlmain);
                                            $row = $result->fetch_assoc();
                                            $docname = $row["docname"];
                                            $docemail = $row["docemail"];
                                            $scheduletime = $row["scheduletime"];
                                            $scheduledate = $row["scheduledate"];
                                            $scheduleid = $row["scheduleid"];
                                            $apponum = $_GET['apponum'];

                                            echo '<tr style="height:60px">
                                                    <td colspan="2" style="width: 50%;border:none;vertical-align:middle;"> 
                                                        <div class="table-card-title" style="margin: 0; border:none; border-radius: 10px;">'.$docname.'</div>
                                                    </td>
                                                    <td style="border:none;vertical-align:middle;">
                                                        <div class="table-card-title" style="margin: 0; border:none; border-radius: 10px;">'.$scheduledate.'</div>
                                                    </td>
                                                    <td style="border:none;vertical-align:middle;">
                                                        <div class="table-card-title" style="margin: 0; border:none; border-radius: 10px;">'.$scheduletime.'</div>
                                                    </td>
                                                </tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                       </center>
                   </td>
                </tr>

                <tr>
                    <td colspan="4">
                        <form method="POST" action="schedule.php" id="myform">
                            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                                <p style="margin: 0; font-weight: bold;">Channeling Fee: $<?php echo $channelingFee; ?></p>
                                <input type="hidden" name="scheduleid" value="<?php echo $scheduleid; ?>">
                                <input type="hidden" name="apponum" value="<?php echo $apponum; ?>">
                                <input type="hidden" name="date" value="<?php echo $scheduledate; ?>">
                                <input type="submit" name="booknow" value="Book Now" class="btn-primary-soft btn" style="padding:10px 20px;">
                            </div>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html> -->
