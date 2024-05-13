
<!-- Styles for fixed icons -->
<style>
    .fixed-icons {
        position: fixed;
        bottom: 20px; /* Adjust the bottom position as needed */
        right: 155px; /* Initially hide the icons off-screen */
        transition: left 0.3s ease; /* Add smooth transition effect */
        z-index: 1000; /* Ensure the icons appear above other content */
    }

    .fixed-icons a,
    .fixed-icons button {
        display: inline-block;
        background-color: #0b190b; /* Example background color */
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
    <a href="../views/sectionhead.php" id="eventsBtn"><i class="fas fa-calendar"></i></a>
    <!-- Settings icon -->
    <a href="../views/weekly_task.php" id="settingsBtn"><i class="fas fa-cog"></i></a>
    <!-- Add icon (opens modal) -->
    <button data-toggle="modal" data-target="#EventAddModal"><i class="fas fa-plus"></i></button>
    
</div>

<!-- JavaScript to clear input values when modal is shown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('EventAddModal').addEventListener('show.bs.modal', function() {
        console.log("Modal shown"); // Check if this log appears in the console
        // Clear input values when modal is shown
        document.getElementById("addDetails").value = "";
        document.getElementById("addTime").value = "";
        document.getElementById("venueSelect").value = "0";
        document.getElementById("addVenue").value = "";
    });
});


</script>

<?php endif; ?>

<!-- Script for toggling fixed icons -->
<script>

    
    // Toggle fixed icons on click
    // document.getElementById('toggleIconsBtn').addEventListener('click', function() {
    //     var fixedIcons = document.querySelector('.fixed-icons');
    //     var toggleIconsBtn = document.getElementById('toggleIconsBtn');
        
    //     if (fixedIcons.style.right === '-116px' || fixedIcons.style.right === '') {
            
    //         // Show fixed icons
    //         fixedIcons.style.right = '-5px';
    //         toggleIconsBtn.innerHTML = '<i class="fas fa-chevron-right"></i>'; // Change icon to right arrow
    //     } else {
            
    //         // Hide fixed icons
    //         fixedIcons.style.right = '-116px';
    //         toggleIconsBtn.innerHTML = '<i class="fas fa-chevron-left"></i>'; // Change icon to left arrow
    //     }
    // });
</script>


<script>
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
if (window.location.href.includes('weekly_task')) {
    // Hide eventsBtn if the URL contains "settings"
    document.getElementById('settingsBtn').style.display = 'none';
} else if (window.location.href.includes('sectionhead')) {
    // Hide settingsBtn if the URL contains "sched"
    document.getElementById('eventsBtn').style.display = 'none';
} 

</script>