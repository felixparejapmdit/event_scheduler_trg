<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fullscreen Test</title>
    <!-- Include jQuery and Bootstrap CSS/JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .fixed-icons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .fixed-icons a, .fixed-icons button {
            margin-bottom: 10px;
            font-size: 24px;
            color: #000;
            text-decoration: none;
        }

        .fixed-icons a:hover, .fixed-icons button:hover {
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="fixed-icons">
    <a href="../views/sched.php" id="eventsBtn"><i class="fas fa-calendar"></i></a>
    <a href="../views/settings.php" id="settingsBtn"><i class="fas fa-cog"></i></a>
    <button data-toggle="modal" data-target="#EventAddModal"><i class="fas fa-plus"></i></button>
    <a href="#" data-toggle="modal" data-target="#confirmationModal"><i class="fas fa-sign-out-alt"></i></a>
</div>

<script>
$(document).ready(function() {

// Track the current state of icons visibility
var isIconsHidden = false;

// Add keydown event listener for F11
document.addEventListener('keydown', function(event) {
    if (event.key === "F11" || event.keyCode === 122) {
        // Toggle the visibility of fixed icons
        toggleFixedIcons();
    }
});

// Add fullscreen change event listener
document.addEventListener('fullscreenchange', handleFullscreenChange);
document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
document.addEventListener('mozfullscreenchange', handleFullscreenChange);
document.addEventListener('MSFullscreenChange', handleFullscreenChange);

// Function to toggle the visibility of fixed icons
function toggleFixedIcons() {
    var fixedIcons = document.querySelector('.fixed-icons');
    // Check if icons are hidden
    if (isIconsHidden) {
        // Icons are hidden, show them
        fixedIcons.style.display = 'flex';
        isIconsHidden = false;
    } else {
        // Icons are shown, hide them
        fixedIcons.style.display = 'none';
        isIconsHidden = true;
    }
}

// Function to handle fullscreen change
function handleFullscreenChange() {
    // Check if fullscreen mode is active
    if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
        // Fullscreen mode is active, hide icons
        var fixedIcons = document.querySelector('.fixed-icons');
        fixedIcons.style.display = 'none';
        isIconsHidden = true;
    } else {
        // Fullscreen mode is inactive, show icons if they were hidden by F11
        if (isIconsHidden) {
            toggleFixedIcons(); // Show icons
        }
    }
}

});



</script>

</body>
</html>
