<?php
// Include the database connection file
include '../connection/db.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if event ID is provided
//if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

   
    // Fetch event details from the database
    $event_query = "SELECT * FROM events WHERE id = '$eventId'";
    $event_result = mysqli_query($conn, $event_query);
   // echo $event_query;
    if ($event_result && mysqli_num_rows($event_result) > 0) {
        // Fetch the event details
        $event_details = mysqli_fetch_assoc($event_result);
        
        // Return event details as JSON
        header('Content-Type: application/json');
        echo json_encode($event_details);
    } else {
        echo 'Event not found1';
    }
// } else {
//     echo 'Event ID not provided';
// }

// Close the database connection
mysqli_close($conn);
?>
