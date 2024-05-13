<?php
// Include the database connection file
include '../connection/db.php';
// Check if the event ID is provided
if (isset($_POST['eventId'])) {
    // Get the event ID
    $eventId = $_POST['eventId'];

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the SQL delete query
    $sql = "DELETE FROM events WHERE id = $eventId";

    if (mysqli_query($conn, $sql)) {
        // Close the database connection
        mysqli_close($conn);
        
        // Redirect to settings.php
        header("Location: ../../views/settings.php");
        exit(); // Make sure to exit after redirecting
    } else {
        echo "Error deleting event details: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
