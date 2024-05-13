<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Check if the user is logged in and the session variables are set
if (!isset($_SESSION['userid'])) {
    // Redirect the user to the login page if not logged in
   header("Location: index.php");
    exit(); // Stop executing the script
}

// Get user information from session or database
$fullname = $_SESSION['fullname']; // Assuming the session contains the full name
$username = $_SESSION['username']; // Assuming the session contains the username
$contact = $_SESSION['contact'];
$photo = $_SESSION['photo'];  //Assuming the session contains the path to the user's photo
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Tracker - User Profile</title>
    <link rel="icon" href="../images/scheduler.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
  
        <!-- Include jQuery before Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Bootstrap CSS (Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
            display: contents;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            margin-top: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .upload-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<?php include '../layouts/sidemenu_bar.php'; ?>
    <div class="container">
        <div class="profile-container">
            <h2 class="text-center">User Profile</h2>
            <div class="container text-center">
            <form action="../databases/queries/upload_photo.php" method="post" enctype="multipart/form-data">
                <div class="profile-picture-container">
                    <?php if (!empty($photo)) : ?>
                        <img src="../uploads/<?php echo $photo; ?>" alt="Profile Picture" class="profile-picture-preview" onclick="document.getElementById('photo').click()">
                    <?php else : ?>
                        <img src="../images/placeholder.png" alt="Profile Picture" class="profile-picture-preview" onclick="document.getElementById('photo').click()">
                    <?php endif; ?>
                    <label for="photo" id="upload-btn" class="upload-btn">
                        <i class="fas fa-camera" style="color:#fff;"></i>
                    </label>
                    <input type="file" name="photo" id="photo" class="form-control-file" onchange="previewImage()" style="display: none;">
                </div>
                
                <div class="text-center"> <!-- New parent container -->
            <button type="submit" class="btn btn-success">Update</button>
        </div>
                </form>
            </div>
            <script src="../script/app.js"></script> 

            <script>
    function previewImage() {
        var fileInput = document.getElementById('photo');
        var previewImg = document.querySelector('.profile-picture-preview');

        fileInput.addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.addEventListener('load', function() {
                    previewImg.src = this.result;
                });
                reader.readAsDataURL(file);
            }
        });
    }

    // Call the previewImage function to attach the event listener
    previewImage();
</script>
            
            <style>
                .profile-picture-container {
    position: relative; /* Changed to relative */
    display: inline-block;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 1px solid grey;
    margin-bottom: 30px; /* Moved from absolute positioning */
}

.upload-btn {
    position: absolute;
    bottom: -1;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    background: rgba(0, 0, 0, 0.2);
    color: white;
    line-height: 40px; /* Changed to match height of container */
    font-size: 15px;
    cursor: pointer;
    width: 100%;
}

#photo {
    height: 100%;
    width: 100%;
    opacity: 0; /* Changed to hide the input element */
}

.upload-btn i {
    font-size: 48px;
    color: #61677A;
}

.profile-picture-preview {
    display: block;
    width: 100%; /* Changed to fill the container */
    height: 100%; /* Changed to fill the container */
    border: 1px solid #ccc;
    position: relative;
    z-index: 0;
}


            </style>
            <div class="form-group">
                <label for="name">Full Name:</label>
                <div class="input-group">
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $fullname; ?>" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary edit-btn" type="button"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success update-btn d-none" type="button">Update</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <div class="input-group">
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary edit-btn" type="button"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success update-btn d-none" type="button">Update</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="contact">Contact:</label>
                <div class="input-group">
                    <input type="text" id="contact" name="contact" class="form-control" value="<?php echo $contact; ?>" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary edit-btn" type="button"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success update-btn d-none" type="button">Update</button>
                    </div>
                </div>
            </div>
            <!-- Include additional profile information fields here -->
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // Add event listeners to edit buttons
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(editBtn => {
            editBtn.addEventListener('click', () => {
                const inputGroup = editBtn.parentElement.parentElement;
                const inputField = inputGroup.querySelector('input');
                const updateBtn = inputGroup.querySelector('.update-btn');

                // Enable input field for editing
                inputField.readOnly = false;
                inputField.focus();

                // Show update button and hide edit button
                editBtn.classList.add('d-none');
                updateBtn.classList.remove('d-none');
            });
        });

// Add event listeners to update buttons
const updateButtons = document.querySelectorAll('.update-btn');
updateButtons.forEach(updateBtn => {
    updateBtn.addEventListener('click', () => {
        const inputGroup = updateBtn.parentElement.parentElement;
        const inputField = inputGroup.querySelector('input');
        const inputValue = inputField.value; // Get the updated value

        // Disable input field for editing
        inputField.readOnly = true;

        // Hide update button and show edit button
        updateBtn.classList.add('d-none');
        inputGroup.querySelector('.edit-btn').classList.remove('d-none');

        // Perform AJAX request to update the data on the server
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../databases/queries/update_profile.php'); // Replace 'update_profile.php' with your backend endpoint
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Handle successful update response
                console.log('Data updated successfully:', xhr.responseText);
            } else {
                // Handle error response
                console.error('Error updating data:', xhr.statusText);
            }
        };
        xhr.onerror = function() {
            // Handle connection error
            console.error('Failed to send update request.');
        };
        xhr.send(JSON.stringify({ field: inputField.id, value: inputValue }));
    });
});

    </script>
</body>


</html>
