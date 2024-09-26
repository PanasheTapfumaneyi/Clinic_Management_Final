<!DOCTYPE html>
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
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
            display: none; /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .overlay {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }
    
    // Import database
    include("../connection.php");
    $userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
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
                <!-- Menu rows omitted for brevity -->
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
                            $list11 = $database->query("SELECT DISTINCT * FROM doctor;");
                            $list12 = $database->query("SELECT DISTINCT * FROM schedule GROUP BY title;");

                            for ($y = 0; $y < $list11->num_rows; $y++) {
                                $row00 = $list11->fetch_assoc();
                                $d = $row00["docname"];
                                echo "<option value='$d'><br/>";
                            };

                            for ($y = 0; $y < $list12->num_rows; $y++) {
                                $row00 = $list12->fetch_assoc();
                                $d = $row00["title"];
                                echo "<option value='$d'><br/>";
                            };

                            echo '</datalist>';
                            ?>
                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Today's Date</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;"><?php echo $today; ?></p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;">
                        <!-- Additional content omitted for brevity -->
                    </td>
                </tr>

                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                             <tbody>
                            <?php
                            if ($_GET) {
                                if (isset($_GET["id"])) {
                                    $id = $_GET["id"];
                                    $sqlmain = "SELECT * FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid WHERE schedule.scheduleid=$id ORDER BY schedule.scheduledate DESC";
                                    $result = $database->query($sqlmain);
                                    $row = $result->fetch_assoc();
                                    $scheduleid = $row["scheduleid"];
                                    $title = $row["title"];
                                    $docname = $row["docname"];
                                    $docemail = $row["docemail"];
                                    $scheduledate = $row["scheduledate"];
                                    $scheduletime = $row["scheduletime"];
                                    $sql2 = "SELECT * FROM appointment WHERE scheduleid=$id";
                                    $result12 = $database->query($sql2);
                                    $apponum = ($result12->num_rows) + 1;

                                    echo '
                                        <form id="bookingForm">
                                            <input type="hidden" name="scheduleid" value="'.$scheduleid.'" >
                                            <input type="hidden" name="apponum" value="'.$apponum.'" >
                                            <input type="hidden" name="date" value="'.$today.'" >
                                            <input type="hidden" name="channelingFee" value="'.$channelingFee.'">
                                    ';

                                    echo '
                                    <td style="width: 50%;" rowspan="2">
                                            <div class="dashboard-items search-items">
                                                <div style="width:100%">
                                                    <div class="h1-search" style="font-size:25px;">Session Details</div><br><br>
                                                    <div class="h3-search" style="font-size:18px;line-height:30px">
                                                        Doctor name:  &nbsp;&nbsp;<b>'.$docname.'</b><br>
                                                        Doctor Email:  &nbsp;&nbsp;<b>'.$docemail.'</b> 
                                                    </div>
                                                    <div class="h3-search" style="font-size:18px;">
                                                        Session Title: '.$title.'<br>
                                                        Session Scheduled Date: '.$scheduledate.'<br>
                                                        Session Starts : '.$scheduletime.'<br>
                                                        Channeling fee: $'.$channelingFee.'
                                                    </div><br>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="dashboard-items search-items">
                                                <div style="width:100%">
                                                    <div class="h1-search" style="font-size:20px;">Bookings</div><br>
                                                    <div class="h3-search" style="font-size:20px;line-height:30px">  
                                                        <b>Booking number:</b> &nbsp;&nbsp;<b>'.$apponum.'</b>
                                                    </div>
                                                    <br><br>
                                                    <input type="button" value="Book Now" class="login-btn btn-primary btn" id="bookNowBtn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                                                </div>
                                            </div>
                                        </td>
                                        </form>
                                        ';
                                    }
                                } else {
                                    echo "No schedules found.";
                                }
                            ?>
                            </tbody>
                        </table>
                        </div>
                        </center>
                   </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="paymentModal">
        <h2>Complete Your Payment</h2>
        <form id="payment-form">
            <div id="card-element"></div>
            <button id="submit">Pay $<span id="amount"><?php echo $channelingFee; ?></span></button>
            <div id="card-errors" role="alert"></div>
        </form>
        <button onclick="closeModal()">Close</button>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('YOUR_PUBLIC_STRIPE_KEY'); // Replace with your public Stripe key
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        document.getElementById('payment-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const {paymentMethod, error} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                const bookingForm = document.getElementById('bookingForm');
                const formData = new FormData(bookingForm);
                const paymentData = {
                    paymentMethodId: paymentMethod.id,
                    amount: formData.get('channelingFee') * 100 // Amount in cents
                };

                fetch('YOUR_SERVER_ENDPOINT', { // Replace with your server endpoint for handling payments
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(paymentData),
                }).then(response => {
                    if (response.ok) {
                        // Payment succeeded
                        window.location.href = 'booking-complete.php'; // Redirect to confirmation page
                    } else {
                        // Payment failed
                        document.getElementById('card-errors').textContent = 'Payment failed. Please try again.';
                    }
                });
            }
        });

        function openModal() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('paymentModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('paymentModal').style.display = 'none';
        }

        document.getElementById('bookNowBtn').addEventListener('click', (event) => {
            event.preventDefault(); // Prevent form submission
            openModal();
        });
    </script>
</body>
</html>
