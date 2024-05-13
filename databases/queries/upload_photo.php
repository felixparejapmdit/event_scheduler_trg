<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect the user to the login page or handle the situation appropriately
    header("Location: ../login.php");
    exit();
}

// Include the database connection file
include '../connection/db.php';

// Check if a file was uploaded without errors
if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
    $userId = $_SESSION['userid']; // Assuming userid is stored in the session

    // Define allowed file types
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

    // Get file extension
    $fileExtension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);

    // Check if the uploaded file type is allowed
    if (in_array(strtolower($fileExtension), $allowedTypes)) {
        // Define upload directory
        $uploadDir = "../../uploads/";

        // Generate a unique filename for the uploaded file
        $newFileName = "profile_" . $userId . "." . $fileExtension;

        // Set the file path
        $filePath = $uploadDir . $newFileName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $filePath)) {
            // Update the user's profile photo in the database
            $sql = "UPDATE user SET profile_photo='$newFileName' WHERE id='$userId'";
            if (mysqli_query($conn, $sql)) {
                // Profile photo updated successfully
                $_SESSION['photo'] = $newFileName;
                header("Location: ../../views/profile.php");
                exit();
            } else {
                // Error updating profile photo
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            // Error moving the uploaded file
            echo "<div class='message error'>Error uploading file</div>";
            echo "<a href='../../views/profile.php' class='btn btn-primary back-button' style='align-items:center;justify-content: center;display: flex'>Back to Profile</a>";
        }
    } else {
       // File type not allowed
echo "<div class='message error'>Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.</div>";
echo "<a href='../../views/profile.php' class='btn btn-primary back-button' style='align-items:center;justify-content: center;display: flex'>Back to Profile</a>";
    }
} else {
// No file uploaded or errors occurred during upload
echo "<div class='message error'>No file uploaded or an error occurred during upload</div>";

echo "<a href='../../views/profile.php' class='btn btn-primary back-button' style='align-items:center;justify-content: center;display: flex'>Back to Profile</a>";

}

// Close the database connection
mysqli_close($conn);
?>
<style>
    .message {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f8f9fa;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .error {
        border-color: #dc3545;
        background-color: #f8d7da;
        color: #721c24;
    }

    .success {
        border-color: #28a745;
        background-color: #d4edda;
        color: #155724;
    }

    .message a {
        text-decoration: none;
        color: #007bff;
    }

    .message a:hover {
        text-decoration: underline;
    }

    .back-button {
        margin-top: 20px;
    }
</style>