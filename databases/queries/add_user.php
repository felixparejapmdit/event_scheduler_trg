<?php
// Include database connection file
include '../connection/db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $name = trim($_POST['addName']);
    $email = trim($_POST['addUserEmail']);
    $contact = trim($_POST['addContact']);
    //$section = trim($_POST['addSection']);
    $section = ($_POST['sectionSelect'] === 'Others') ? $_POST['addSection'] : $_POST['sectionSelect'];
    // Check if all required fields are filled
    if (empty($name) || empty($email) || empty($contact) || empty($section)) {
        // Handle empty fields error (you can customize this part based on your requirements)
        echo "All fields are required";
        exit();
    }

    // Prepare SQL statement to insert new user
    $sql = "INSERT INTO user (name, email, contact, section) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssss", $name, $email, $contact, $section);

    // Execute the query
    if ($stmt->execute()) {
        // User added successfully
        header("Location: ../../views/users.php");
        exit();
    } else {
        // Error adding user
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to the appropriate page if the form is not submitted
    header("Location: ../../views/users.php");
    exit();
}
?>
