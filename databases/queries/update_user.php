<?php
// Include database connection file
include '../connection/db.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['editUserId']) && isset($_POST['editName']) && isset($_POST['editUserEmail']) && isset($_POST['editContact']) && isset($_POST['editSection'])) {
        // Retrieve form data
        $userId = $_POST['editUserId'];
        $name = $_POST['editName'];
        $email = $_POST['editUserEmail'];
        $contact = $_POST['editContact'];
        //$section = $_POST['editSection'];
        $section = ($_POST['editsectionSelect'] === 'Others') ? $_POST['editSection'] : $_POST['editsectionSelect'];

        // Prepare SQL statement to update user details
        $sql = "UPDATE user SET name=?, email=?, contact=?, section=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $contact, $section, $userId);

        // Execute the query
        if ($stmt->execute()) {
            // User details updated successfully
            header("Location: ../../views/users.php");
            exit(); 
            echo json_encode(array('success' => 'User details updated successfully'));
        } else {
            // Error updating user details
            echo json_encode(array('error' => 'Error updating user details'));
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Required fields are missing
        echo json_encode(array('error' => 'All required fields are not provided'));
    }
} else {
    // No form data submitted
    echo json_encode(array('error' => 'No form data submitted'));
}
?>
