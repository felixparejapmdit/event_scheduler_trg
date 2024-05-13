<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    
    $username = $_SESSION['username'];
    $fullname = $_SESSION['fullname'];
} else {
    // Redirect to the login page if the user is not logged in
    //header("Location: ../");
   // exit();
}

$photo = $_SESSION['photo'];
?>
<style>
    .navbar-separator {
    margin: 0 5px;
    font-weight:bold;
}

.navbar-brand
{
    
}
.navbar-brand.active,
    .navbar-brand:hover {
        color: #333A73 !important;
        font-weight:bold;
    }


    .submenu {
        display: none;
        position: absolute;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
    }
    ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
    .nav-item:hover .submenu {
        display: block;
    }

    /* Add a delay when hiding the submenu */
    .submenu:hover {
        display: block;
    }
</style>


<script>
    // JavaScript function to add "active" class to clicked navbar link
    function setActive(element) {
        // Remove "active" class from all navbar links
        document.querySelectorAll('.navbar-brand').forEach(link => {
            link.classList.remove('active');
        });

        // Add "active" class to the clicked navbar link
        element.classList.add('active');

        // Store the ID of the active link in localStorage
        localStorage.setItem('activeLink', element.id);
    }

    // JavaScript function to set active link on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Get the ID of the active link from localStorage
        var activeLinkId = localStorage.getItem('activeLink');

        // If an active link ID exists, set it as active
        if (activeLinkId) {
            var activeLink = document.getElementById(activeLinkId);
            if (activeLink) {
                setActive(activeLink);
            }
        }
    });
</script>
<link rel="icon" href="../images/scheduler.ico" type="image/x-icon">


<?php
// Check if the user is logged in and the session variables are set
if (isset($_SESSION['userid']) && isset($_SESSION['role'])) {
    // Check the role of the user
    if ($_SESSION['role'] == 1) {
        // If the user is an admin (role = 1), use a generic link to section head page
        $eventText = "PMD Events";
        $navLinkText = "Section Chief";
        $eventListName = "PMD";
        $sectionHeadLink = '../views/sectionhead.php';
    } else {
        // If the user is not an admin, include the user ID in the link
        $eventText = "Section Events";
        $navLinkText = "My Schedule";
        $eventListName = "Section";
        $sectionHeadLink = '../views/sectionhead.php?userid=' . $_SESSION['userid'] . '&role= '.$_SESSION['role'];
        
    }
} else {
    // If session variables are not set, use a default link
    $sectionHeadLink = '../views/sectionhead.php';
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light" style="position: sticky; top: 0;">
    <div class="container">

    <!-- Add logo image -->
    <!-- <a class="navbar-brand" href="../views/dashboard.php"><img src="../images/home.png" alt="Logo" style="width:40px;"></a> -->
    <a class="navbar-brand" href="../views/dashboard.php">Home</a>
    <span class="navbar-separator">|</span>
    <a id="schedLink" class="navbar-brand" href="../views/sched.php" onclick="setActive(this)"><?php echo $eventText; ?></a> 
        <span class="navbar-separator">|</span>
    <!-- Section Chief Navigation Item -->
    <a id="sectionHeadLink" class="navbar-brand" href="<?php echo $sectionHeadLink; ?>" onclick="setActive(this)"><?php echo $navLinkText; ?></a>
    <!-- <span class="navbar-separator">|</span>
        <a id="weeklyTaskLink" class="navbar-brand" href="../views/weekly_task.php" onclick="setActive(this)">My Task</a> -->
    

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
        <a class="nav-link" href="#" style="cursor:default;">
        

            <?php echo $fullname; ?>
        </a>
    </li>
         

                <li class="nav-item">
                <a class="nav-link" style="cursor:pointer;font-weight:bold;">
                    <i class="fas fa-chevron-down" style="width: 24px; height: 24px;"></i> <!-- Icon arrow down -->
                    Settings
                </a>
                    <ul class="submenu">
                    <li><a href="#" onclick="redirectToProfile()"  style="color:#272829;"><i class="fas fa-user"></i> Profile</a></li>
                    <?php if ($_SESSION['role'] == 1): ?>
                        <li><a href="#" onclick="redirectToUsers()"  style="color:#272829;"><i class="fas fa-users"></i> Users</a></li>
            <?php else: ?>
                <!-- For non-admin users, hide the container -->
                <li style="display: none;"><a href="#" onclick="redirectToUsers()"  style="color:#272829;"><i class="fas fa-users"></i> Users</a></li>
                
            <?php endif; ?>
                        
                        <li><a href="#" onclick="changeBackgroundColor()"  style="color:#272829;"><i class="fas fa-palette"></i> Change Theme</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#confirmationModal" style="cursor:pointer;font-weight:bold;">
                        <i class="fas fa-sign-out-alt" style="width: 24px; height: 24px;"></i> <!-- Icon logout -->
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>



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


    function logout() {
    event.preventDefault(); // Prevent the default behavior of the button
    // Redirect to logout page or perform logout operation here
    window.location.href = "../";
    }

          function redirectToPMDEvent() {
        window.location.href = "../views/settings.php";
    }

    function redirectToSchedule() {
        window.location.href = "../views/weekly_task.php";
    }

    function redirectToProfile() {
        window.location.href = "../views/profile.php";
    }
    function redirectToUsers() {
        window.location.href = "../views/users.php";
    }
    // Function to set the background color and update text colors
    function setBackgroundColor(color) {
        var body = document.body;
        var container1 = document.getElementById('custom-container1');
        var container2 = document.getElementById('custom-container2');
        var container3 = document.getElementById('custom-container3');
        var icon = document.getElementById('bg-icon');
        var spans = document.querySelectorAll('span');
        var tds = document.querySelectorAll('td');
        var h5s = document.querySelectorAll('h5');
        var divs = document.querySelectorAll('div');

        // Set background color
        body.style.backgroundColor = color;

        container1.style.backgroundColor = color;
        container2.style.backgroundColor = color;
        container3.style.backgroundColor = color;

        // Update text colors based on background color
        if (color === '#353D48') {
            icon.classList.add('light');
            // Set text color of spans, tds, and h5s to white
            spans.forEach(function(span) {
                span.style.color = '#FFFFFF';
            });
            tds.forEach(function(td) {
                td.style.color = '#FFFFFF';
            });
            h5s.forEach(function(h5) {
                h5.style.color = '#FFFFFF';
            });
            divs.forEach(function(div) {
                div.style.color = '#FFFFFF';
            });
        } else {
            icon.classList.remove('light');
            // Set text color of spans, tds, and h5s to black
            spans.forEach(function(span) {
                span.style.color = '#000000';
            });
            tds.forEach(function(td) {
                td.style.color = '#000000';
            });
            h5s.forEach(function(h5) {
                h5.style.color = '#000000';
            });
            divs.forEach(function(div) {
                div.style.color = '#000000';
            });
        }
    }

    // Function to change the background color
    function changeBackgroundColor() {
        var currentColor = document.body.style.backgroundColor;
        var newColor = (currentColor === 'rgb(53, 61, 72)' || currentColor === '#353D48') ? '#FFFFFF' : '#353D48';

        // Set the new background color
        setBackgroundColor(newColor);

        // Store the new background color in localStorage
        localStorage.setItem('backgroundColor', newColor);
    }

    // Function to load the background color from localStorage when the page loads
    window.onload = function() {
        var storedColor = localStorage.getItem('backgroundColor');
        if (storedColor) {
            setBackgroundColor(storedColor);
        }
    };

// Update the time every 5 minutes
//setInterval(updateTime, 300000); // 300000 milliseconds = 5 minutes

</script>