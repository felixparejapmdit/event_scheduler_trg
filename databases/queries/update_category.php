<?php
// Include database connection file
include '../connection/db.php';

// Check if eventId and isChecked are provided via POST
if (isset($_POST['eventId']) && isset($_POST['category'])) {
    // Sanitize and validate input
    $eventId = intval($_POST['eventId']);
    $category = $_POST['category'];

// Prepare SQL statement to update event_name column in the events table
$sql = "UPDATE events SET event_name = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt) {
    // Bind parameters
    $stmt->bind_param("si", $category, $eventId); // Use 'si' for string and integer types

    // Execute the query
    if ($stmt->execute()) {
        // Success response
        echo json_encode(array('success' => 'Category updated successfully'));
    } else {
        // Error response
        echo json_encode(array('error' => 'Error updating category'));
    }

    // Close statement
    $stmt->close();
} else {
    // Error response if the statement preparation failed
    echo json_encode(array('error' => 'Error preparing statement'));
}

// Close database connection
$conn->close();

} else {
    // Error response if eventId or isChecked are not provided
    echo json_encode(array('error' => 'Category not provided'));
}
?>
