<?php
// Include database connection file
include '../connection/db.php';

// Check if eventId and isChecked are provided via POST
if (isset($_POST['eventId']) && isset($_POST['isChecked'])) {
    // Sanitize and validate input
    $eventId = intval($_POST['eventId']);
    $isChecked = intval($_POST['isChecked']);

    // Prepare SQL statement to update is_display column in the events table
    $sql = "UPDATE events SET is_display = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isChecked, $eventId);

    // Execute the query
    if ($stmt->execute()) {
        // Success response
        echo json_encode(array('success' => 'Checkbox updated successfully'));
    } else {
        // Error response
        echo json_encode(array('error' => 'Error updating checkbox'));
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Error response if eventId or isChecked are not provided
    echo json_encode(array('error' => 'Event ID or isChecked not provided'));
}
?>
