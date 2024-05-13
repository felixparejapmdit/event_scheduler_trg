<?php
// Include the database connection file
include '../connection/db.php';

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if the userid is set in the session
if(isset($_SESSION['userid'])) {
    // Get the userid from the session
    $userid = $_SESSION['userid'];
} else {
    // Handle the case where userid is not set
    $userid = ""; // or any default value
}

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the date_tosave parameter from the AJAX request
$date_tosave = $_POST['date_tosave'];

// Perform a database query to fetch data based on the provided date_tosave
$today_query = "SELECT * FROM events WHERE event_name = 'weekly_update' AND date = '$date_tosave'";
//echo $today_query;
// Check if the user's role is not 1 (assuming 1 is for admin)
if ($_SESSION['role'] !== 1) {
    // Append the condition based on the user's ID
    $today_query .= " AND prepared_by = " . $_SESSION['userid'];
}

// Complete the query with sorting
$today_query .= " ORDER BY date DESC, time ASC";

//echo $today_query;
$today_result = mysqli_query($conn, $today_query);

// Check if there are any rows returned from the query
if (mysqli_num_rows($today_result) > 0) {
    // Loop through the fetched rows and generate HTML content
    while ($row = mysqli_fetch_assoc($today_result)) {
        $title = $row['title'];
        // Change date format
        $date = date("F j, Y", strtotime($row['date']));
        // Convert time to 12-hour format
        $time_12_hour = date("h:i A", strtotime($row['time']));
        $location = $row['location'];
        $incharge = $row['incharge'];
        $eventId = $row['id']; // Add event ID

        echo "<tr>";
            echo "<td class='text-center'>" . $title . "</td>";
            echo "<td class='text-center' style='width:20%;'>" . $date . "</td>";
            echo "<td class='text-center' style='width:15%;'>" . $time_12_hour . "</td>";
            echo "<td class='text-center' style='width:20%;'>" . $location . "</td>";
            echo "<td class='text-center' style='width:8%;'>";
                echo "<a href='#' class='edit-event' data-toggle='modal' data-target='#EventEditModal' data-event-id='" . $eventId . "'><i class='fas fa-edit'></i></a> ";
                echo "<a href='#' onclick='deleteEvent(" . $eventId . ")'><i class='fas fa-trash'></i></a> ";
            echo "</td>";
        echo "</tr>";
    }
} else {
    // If no rows are returned, display a message or perform any other action as needed
    echo "<tr><td colspan='4' class='text-center' style='color:red;'>No events found for the selected date.</td></tr>";
}

// Close the database connection
mysqli_close($conn);
?>
