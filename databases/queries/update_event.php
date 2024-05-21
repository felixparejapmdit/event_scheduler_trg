<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include the database connection file
include '../connection/db.php';

// Check if the form is submitted
// if (isset($_POST['editEvent'])) {
    // Retrieve the event ID and other form data
    $eventId = $_POST['editEventId'];
   
    if ($_POST['savetype'] === "suguanevent")
    {
        $eventName = 'Suguan';
        $title = $_POST['editTitle'];
        $date = $_POST['editDate'];
        $time = $_POST['editTime'];
    
        $location =  $_POST['editlocal'];
        $district =  $_POST['editdistrict'];
        
        $incharge = '';
        $contact_number = '';
        $details = $_POST['editgampanin'];
    }
    else{
       
        $eventName = $_POST['editEventName'];
        $title = $_POST['editTitle'];
        $date = $_POST['editDate'];
        $time = $_POST['editTime'];
    
        $location = ($_POST['editVenueSelect'] === 'Others') ? $_POST['editLocation'] : $_POST['editVenueSelect'];
        $district =  '';
        $incharge = $_POST['editIncharge'];
        $contact_number = $_POST['editContact_number'];
        $details = $_POST['editDetails'];
    }


    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the SQL update query
    $sql = "UPDATE events SET event_name='$eventName', title='$title', date='$date', time='$time', location='$location', district='$district', incharge='$incharge', contact_number='$contact_number',details='$details' WHERE id=$eventId";

    if (mysqli_query($conn, $sql)) {
        // Close the database connection
        mysqli_close($conn);
        

        if ($_POST['savetype'] === "suguanevent") {
            $currentURL = $_POST['current_url'];
            // Check if the current URL contains "suguan.php"
            if (strpos($currentURL, 'suguan.php') !== false) {
                //Redirect to sched.php
                header("Location: ../../views/suguan.php");
                exit; // Make sure to stop executing the script after redirection
            } 
            
            if (strpos($currentURL, 'settings.php') !== false) {
                // Redirect to settings.php
                header("Location: ../../views/settings.php");
                exit; // Make sure to stop executing the script after redirection
            }
        } 
        if ($_POST['savetype'] === "pmdevent") {
            $currentURL = $_POST['current_url'];

            // Check if the current URL contains "sched.php"
            if (strpos($currentURL, 'sched.php') !== false) {
                // Redirect to sched.php
                header("Location: ../../views/sched.php");
                exit; // Make sure to stop executing the script after redirection
            }
            
            if (strpos($currentURL, 'settings.php') !== false) {
                // Redirect to settings.php
                header("Location: ../../views/settings.php");
                exit; // Make sure to stop executing the script after redirection
            }
        }
        exit(); // Make sure to exit after redirecting
    } else {
        echo "Error updating event details: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
// }
?>
