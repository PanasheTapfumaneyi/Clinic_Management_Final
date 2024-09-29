<?php
require_once 'config.php';

// Start session to access the scheduleID and user email
session_start();

// Retrieve scheduleID and user email from the session
$scheduleID = $_SESSION['scheduleID'];
$userEmail = $_SESSION['user'];

// Import database connection
include "../connection.php";

// Fetch the session details from the database
$sql = "SELECT title, scheduledate, docid FROM schedule WHERE scheduleid = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("i", $scheduleID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sessionTitle = $row['title'];
    $scheduledDate = $row['scheduledate'];
    $docID = $row['docid'];

    // Fetch doctor's email using the docid
    $sqlDoctor = "SELECT docemail FROM doctor WHERE docid = ?";
    $stmtDoctor = $database->prepare($sqlDoctor);
    $stmtDoctor->bind_param("i", $docID);
    $stmtDoctor->execute();
    $resultDoctor = $stmtDoctor->get_result();
    
    if ($resultDoctor->num_rows > 0) {
        $rowDoctor = $resultDoctor->fetch_assoc();
        $doctorEmail = $rowDoctor['docemail'];

        // Call the create_meeting function with the retrieved details and emails
        create_meeting($sessionTitle, $scheduledDate, $doctorEmail, $userEmail);
    } else {
        echo "No doctor found for the provided docID.";
    }
} else {
    echo "No session found for the provided schedule ID.";
}

function create_meeting($sessionTitle, $scheduledDate, $doctorEmail, $userEmail) {
    $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
    
    $db = new DB();
    $arr_token = $db->get_access_token();
    $accessToken = $arr_token->access_token;
    
    try {
        $response = $client->request('POST', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer $accessToken"
            ],
            'json' => [
                "topic" => $sessionTitle,
                "type" => 2,
                "start_time" => $scheduledDate, // Ensure this is in the correct format (e.g., "2023-05-05T20:30:00")
                "duration" => 30, // 30 mins
                "password" => "123456"
            ],
        ]);
        
        $data = json_decode($response->getBody());
        $joinUrl = $data->join_url;
        $meetingPassword = $data->password;

        // Send meeting details to doctor and user
        send_email($doctorEmail, $sessionTitle, $scheduledDate, $joinUrl, $meetingPassword);
        send_email($userEmail, $sessionTitle, $scheduledDate, $joinUrl, $meetingPassword);

        echo "Join URL: " . $joinUrl;
        echo "<br>";
        echo "Meeting Password: " . $meetingPassword;

    } catch(Exception $e) {
        if( 401 == $e->getCode() ) {
            $refresh_token = $db->get_refresh_token();
            
            $client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic ". base64_encode(CLIENT_ID.':'.CLIENT_SECRET)
                ],
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token
                ],
            ]);
            $db->update_access_token($response->getBody());

            create_meeting($sessionTitle, $scheduledDate, $doctorEmail, $userEmail); // Recursively call the function with the correct parameters
        } else {
            echo $e->getMessage();
        }
    }
}

function send_email($recipientEmail, $sessionTitle, $scheduledDate, $joinUrl, $meetingPassword) {
    $subject = "Zoom Meeting Details: " . $sessionTitle;
    $message = "You have been scheduled for a Zoom meeting.\n\n";
    $message .= "Session Title: " . $sessionTitle . "\n";
    $message .= "Scheduled Date: " . $scheduledDate . "\n";
    $message .= "Join URL: " . $joinUrl . "\n";
    $message .= "Meeting Password: " . $meetingPassword . "\n\n";
    $message .= "Please join the meeting on time.";

    // Additional headers if needed
    $headers = "From: no-reply@yourdomain.com";

    // Send the email
    mail($recipientEmail, $subject, $message, $headers);
}
?>
