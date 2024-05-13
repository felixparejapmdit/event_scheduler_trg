
<?php
// Start the session
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include the database connection file
include '../connection/db.php';

// Check if the form is submitted
if(isset($_POST['addEvent'])) {
   
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get form data

    if ($_POST['savetype'] === "weekly")
    {
        $eventName = "weekly_update";
        $title = $_POST['addDetails'];

        $incharge = $_SESSION['username'];
        $contact_number = "";
        $host = "";
        $date = $_POST['date_tosave'];
        $time = $_POST['addTime'];

        $location  = $_POST['addVenue'];

        $week_number = $_POST['week_number'];
        $details = "";
        $preparedby = $_POST['userid'];

        $event_type = 2;

        $isDisplay = 0;
    }
    else{
        //$eventName = $_POST['eventName'];

        // Check if the session role is set and equal to 1
        if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
            // If role is 1, set $eventName to the value from POST if it exists, otherwise set it to 'others'
            $eventName = isset($_POST['eventName']) ? $_POST['eventName'] : 'Others';
            $isDisplay = 1;
        } else {
            // If role is not 1, set $eventName to 'others'
            $eventName = 'Others';
            $isDisplay = 0;
        }


        $title = $_POST['title'];
        $incharge = $_POST['incharge'];
        $contact_number = $_POST['contact_number'];
        $host = $_POST['host'];
        $date = $_POST['date'];
        $time = $_POST['time'];
    
        $location = ($_POST['venueSelect'] === 'Others') ? $_POST['location'] : $_POST['venueSelect'];
        $week_number = "";
        $details = $_POST['addDetails'];
        $preparedby = $_SESSION['userid'];
        $event_type = 1;

        
    }

    // Insert data into events table
    $sql = "INSERT INTO events (event_name, title, incharge, contact_number, host, date, time, location, weeknumber, prepared_by, event_type, details, is_display) VALUES ('$eventName', '$title', '$incharge', '$contact_number', '$host', '$date', '$time', '$location', '$week_number', '$preparedby', '$event_type', '$details', ' $isDisplay')";

    if (mysqli_query($conn, $sql)) {
        // Close the database connection
        mysqli_close($conn);
        
        if ($_POST['savetype'] === "weekly") {
            $currentURL = $_POST['current_url'];
                        // Check if the current URL contains "sectionhead.php"
            if (strpos($currentURL, 'sectionhead.php') !== false) {
                // Redirect to sched.php
                header("Location: ../../views/sectionhead.php");
                echo json_encode($response);
                exit; // Make sure to stop executing the script after redirection
            } 
            
            if (strpos($currentURL, 'weekly_task.php') !== false) {
                // Redirect to settings.php
                header("Location: ../../views/weekly_task.php");
                echo json_encode($response);
                exit; // Make sure to stop executing the script after redirection
            }
            
        } 
        if ($_POST['savetype'] === "pmdevent") {
            $currentURL = $_POST['current_url'];

            // Check if the current URL contains "sched.php"
            if (strpos($currentURL, 'sched.php') !== false) {
                // Redirect to sched.php
                header("Location: ../../views/sched.php");
                echo json_encode($response);
                exit; // Make sure to stop executing the script after redirection
            }
            
            if (strpos($currentURL, 'settings.php') !== false) {
                // Redirect to settings.php
                header("Location: ../../views/settings.php");
                echo json_encode($response);
                exit; // Make sure to stop executing the script after redirection
            }
        }
        
        
      
        exit(); // Make sure to exit after redirecting
    } else {
        echo "Error adding event details: " . mysqli_error($conn);
    }
    // Close the database connection
    mysqli_close($conn);
}
?>
