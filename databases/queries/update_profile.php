<?php
// Assuming you have a database connection established
// Include the database connection file
include '../connection/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
    // Retrieve the JSON data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the required fields are present in the JSON data
    if (isset($data['field']) && isset($data['value'])) {
        // Sanitize the input data (optional but recommended)
        $field = htmlspecialchars(strip_tags($data['field']));
        $value = htmlspecialchars(strip_tags($data['value']));

        // Update the user's profile information in the database
        // Example: Update the field in the 'users' table for the current user
        $userId = $_SESSION['userid']; // Assuming you have the user's ID stored in a session variable
        $updateQuery = "UPDATE user SET $field = '$value' WHERE id = $userId";

        if (mysqli_query($conn, $updateQuery)) {
            // Return a success message or updated data
            echo json_encode(array("status" => "success", "message" => "Profile information updated successfully."));
        } else {
            // Return an error message if the update query fails
            echo json_encode(array("status" => "error", "message" => "Failed to update profile information: " . mysqli_error($conn)));
        }
    } else {
        // Return an error message if required fields are missing
        echo json_encode(array("status" => "error", "message" => "Missing required fields."));
    }
} else {
    // Return an error message if the request method is not POST
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}
?>
