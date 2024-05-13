<?php
// Include database connection file
include '../connection/db.php';

// Check if user ID is provided via POST
if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Prepare SQL statement to delete user by ID
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    // Execute the query
    if ($stmt->execute()) {
        // User deleted successfully
        header("Location: ../../views/users.php");
        exit(); 
        echo json_encode(array('success' => 'User deleted successfully'));
    } else {
        // Error deleting user
        echo json_encode(array('error' => 'Error deleting user'));
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // User ID not provided
    echo json_encode(array('error' => 'User ID not provided'));
}
?>
