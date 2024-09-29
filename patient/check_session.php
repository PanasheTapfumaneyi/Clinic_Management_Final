<?php
session_start();

// Check if the session is empty
if (empty($_SESSION)) {
    echo "Session is empty.";
} else {
    // Print the contents of the session
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}
?>
