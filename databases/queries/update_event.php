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
   
    if ($_POST['savetype'] === "weekly")
    {
        $eventName = "weekly_update";
        $title = $_POST['editDetails'];

        $incharge = $_SESSION['username'];
        $contact_number = "";
        $host = "";

        $details = "";
        $date = $_POST['editDate'];
        $time = $_POST['editTime'];

        // $location  = $_POST['editVenue'];
        $location = ($_POST['editVenueSelect'] === 'Others') ? $_POST['editVenue'] : $_POST['editVenueSelect'];
    }
    else{
       
        $eventName = $_POST['editEventName'];
        $title = $_POST['editTitle'];
        $host = $_POST['editHost'];
        $date = $_POST['editDate'];
        $time = $_POST['editTime'];
    
        $location = ($_POST['editVenueSelect'] === 'Others') ? $_POST['editLocation'] : $_POST['editVenueSelect'];
    
        $incharge = $_POST['editIncharge'];
        $contact_number = $_POST['editContact_number'];
        $details = $_POST['editDetails'];
    }



    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the SQL update query
    $sql = "UPDATE events SET event_name='$eventName', title='$title', host='$host', date='$date', time='$time', location='$location', incharge='$incharge', contact_number='$contact_number',details='$details' WHERE id=$eventId";

    if (mysqli_query($conn, $sql)) {
        // Close the database connection
        mysqli_close($conn);
        

        if ($_POST['savetype'] === "weekly") {
            $currentURL = $_POST['current_url'];
            // Check if the current URL contains "sectionhead.php"
            if (strpos($currentURL, 'sectionhead.php') !== false) {
                //Redirect to sched.php
                header("Location: ../../views/sectionhead.php");
                exit; // Make sure to stop executing the script after redirection
            } 
            
            if (strpos($currentURL, 'weekly_task.php') !== false) {
                // Redirect to settings.php
                header("Location: ../../views/weekly_task.php");
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
