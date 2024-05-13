<?php
// Include the database connection file using the absolute path
include __DIR__ . '/../connection/db.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all users from the database
$user_query = "SELECT * FROM user";
$user_result = mysqli_query($conn, $user_query);

// Check for errors in query execution
if (!$user_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch all user details
$user_details = mysqli_fetch_all($user_result, MYSQLI_ASSOC);

// Close the database connection
//mysqli_close($conn);

// Return user details as JSON
echo json_encode($user_details);
?>
