
<!-- Styles for fixed icons -->
<style>
    .fixed-icons {
        position: fixed;
        bottom: 30px; /* Adjust the bottom position as needed */
        right: -2px; /* Initially hide the icons off-screen */
        transition: left 0.3s ease; /* Add smooth transition effect */
        z-index: 1000; /* Ensure the icons appear above other content */
    }

    .fixed-icons a,
    .fixed-icons button {
        display: inline-block;
        background-color: #41B06E; /* Example background color */
        color: #fff; /* Example text color */
        border: none;
        border-radius: 50%; /* Make the icons circular */
        width: 40px; /* Adjust the width of the icons */
        height: 40px; /* Adjust the height of the icons */
        text-align: center;
        line-height: 40px;
        font-size: 20px;
        margin-right: 10px; /* Adjust the margin between icons */
    }
</style>

<!-- Fixed icons -->
<?php if ($_SESSION['role'] != 3): ?>
    <div class="fixed-icons">
        <!-- Toggle icons button -->
        <!-- <button id="toggleIconsBtn"><i class="fas fa-chevron-left"></i></button> -->
        <!-- Events icon -->
        <a href="../views/sched.php" id="eventsBtn"><i class="fas fa-calendar"></i></a>
        <!-- Settings icon -->
        <a href="../views/settings.php" id="settingsBtn"><i class="fas fa-cog"></i></a>
        <!-- Add icon (opens modal) -->
        <button data-toggle="modal" data-target="#EventAddModal"><i class="fas fa-plus"></i></button>
        <!-- Fullscreen icon -->
        <button id="fullscreenBtn"><i class="fas fa-expand"></i></button>
        <!-- Logout Button -->
        <a href="#" data-toggle="modal" data-target="#confirmationModal"><i class="fas fa-sign-out-alt"></i></a>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fullscreenBtn = document.getElementById('fullscreenBtn');

        // Add click event listener to the fullscreen button
        fullscreenBtn.addEventListener('click', function() {
            if (document.fullscreenElement) {
                exitFullscreen();
            } else {
                enterFullscreen();
            }
        });

        // Function to enter fullscreen mode
        function enterFullscreen() {
            var element = document.documentElement;
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }

            
        }

        // Function to exit fullscreen mode
        function exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });
</script>

<!-- Confirmation modal -->
<div class="modal fade" data-backdrop="static" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
  
      <div class="modal-header">
                <h5 class="modal-title" id="EventAddModalLabel">Log-out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
      <div class="modal-body">
        <p>Are you sure you want to log out?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="logout(event)">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function() {

document.addEventListener('fullscreenchange', onFullScreenChange);
document.addEventListener('mozfullscreenchange', onFullScreenChange);
document.addEventListener('webkitfullscreenchange', onFullScreenChange);
document.addEventListener('msfullscreenchange', onFullScreenChange);

function onFullScreenChange() {
    var fixedIcons = document.querySelector('.fixed-icons');
    if (document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement) {
        // Fullscreen mode is active
        fixedIcons.style.display = 'none'; // Hide the icons
    } else {
        // Fullscreen mode is inactive
        fixedIcons.style.display = 'flex'; // Show the icons
    }
}

});

function logout() {
    event.preventDefault(); // Prevent the default behavior of the button
    // Redirect to logout page or perform logout operation here
    window.location.href = "../";
    }
 window.onload = function() {
    var eventsBtn = document.getElementById('eventsBtn');
    var settingsBtn = document.getElementById('settingsBtn');
    var eventsBtnPosition = eventsBtn.style.order;
    
    // Swap the order of eventsBtn and settingsBtn
    eventsBtn.style.order = settingsBtn.style.order;
    settingsBtn.style.order = eventsBtnPosition;



    

 };
    // Script for toggling fixed icons
// document.getElementById('toggleIconsBtn').addEventListener('click', function() {
// });


// Check if the current URL contains "settings" or "sched"
if (window.location.href.includes('settings')) {
    // Hide eventsBtn if the URL contains "settings"
    document.getElementById('settingsBtn').style.display = 'none';
} else if (window.location.href.includes('sched')) {
    // Hide settingsBtn if the URL contains "sched"
    document.getElementById('eventsBtn').style.display = 'none';
} else if (window.location.href.includes('suguan')) {
    // Hide settingsBtn if the URL contains "sched"
    document.getElementById('eventsBtn').style.display = 'none';
} 

</script>