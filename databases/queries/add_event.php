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

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data

if ($_POST['savetype'] === "weekly") {
    $eventName = "weekly";
    $title = $_POST['addDetails'];

    $incharge = $_SESSION['username'];
    $contact_number = "";
    $host = "";
    $date = $_POST['date_tosave'];
    $time = $_POST['addTime'];

    // $location  = $_POST['addVenue'];
    $location = ($_POST['venueSelect'] === 'Others') ? $_POST['addVenue'] : $_POST['venueSelect'];

    $week_number = $_POST['week_number'];
    $details = "";
    $preparedby = $_POST['userid'];

    $event_type = 2;

    $isDisplay = 0;
} else {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
        $eventName = isset($_POST['eventName']) ? $_POST['eventName'] : 'Others';
        $isDisplay = 1;
    } else {
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
    $week_number = 0;
    $details = $_POST['addDetails'];
    $preparedby = $_SESSION['userid'];
    $event_type = 1;
}

// Prepare the SQL statement
$sql = "INSERT INTO events (event_name, title, incharge, contact_number, host, date, time, location, weeknumber, prepared_by, event_type, details, is_display) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "ssssssssssiss", $eventName, $title, $incharge, $contact_number, $host, $date, $time, $location, $week_number, $preparedby, $event_type, $details, $isDisplay);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    // Redirect to appropriate page
    if ($_POST['savetype'] === "weekly") {
        // Handle redirection for weekly type
        $currentURL = $_POST['current_url'];
        if (strpos($currentURL, 'sectionhead.php') !== false) {
            header("Location: ../../views/sectionhead.php");
        } elseif (strpos($currentURL, 'weekly_task.php') !== false) {
            header("Location: ../../views/weekly_task.php");
        }
    } elseif ($_POST['savetype'] === "pmdevent") {
        // Handle redirection for pmdevent type
        $currentURL = $_POST['current_url'];
        if (strpos($currentURL, 'sched.php') !== false) {
            header("Location: ../../views/sched.php");
        } elseif (strpos($currentURL, 'settings.php') !== false) {
            header("Location: ../../views/settings.php");
        }
    }
    exit();
} else {
    // Error handling
    echo "Error adding event details: " . mysqli_stmt_error($stmt);
    $response = ["success" => false, "message" => "Error adding event details: " . mysqli_stmt_error($stmt)];
}

// Close the statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($conn);

// Send JSON response
echo json_encode($response);

// Make sure to exit after sending the response
exit();
?>
