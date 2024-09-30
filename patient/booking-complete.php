<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start the session
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

// Get the scheduleid from session
$scheduleid = $_SESSION['scheduleID']; // Make sure this session variable is set somewhere in your code
$apponum = $_SESSION['apponum'];
$date = date('Y-m-d');



$sql2="insert into appointment(pid,apponum,scheduleid,appodate) values ($userid,$apponum,$scheduleid,'$date')";
$result = $database->query($sql2);
header("location: booking_test.php?action=booking-added&id=".$apponum."&titleget=none");


?>