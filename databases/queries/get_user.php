<?php
// Include database connection file
include '../connection/db.php';

// Check if user ID is provided via GET
if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Prepare SQL statement to select specific user details by ID
    $sql = "SELECT u.id, u.name, u.email, u.contact, u.section AS section_name, u.password
            FROM user u
            WHERE u.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    // Execute the query
    if ($stmt->execute()) {
        // Bind the result variables
        $stmt->bind_result($id, $name, $email, $contact, $sectionName, $password);

        // Fetch the result
        if ($stmt->fetch()) {
            // Create an associative array to hold user details including section
            $userData = array(
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'section' => $sectionName,
                'password' => $password
            );

            // Encode the array as JSON and echo it
            echo json_encode($userData);
        } else {
            // User not found
            echo json_encode(array('error' => 'User not found'));
        }
    } else {
        // Error executing the query
        echo json_encode(array('error' => 'Error executing the query'));
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // User ID not provided
    echo json_encode(array('error' => 'User ID not provided'));
}
?>
