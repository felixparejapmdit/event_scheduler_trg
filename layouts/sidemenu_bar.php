
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

<?php
// Check if the user is logged in and the session variables are set
if (isset($_SESSION['userid']) && isset($_SESSION['role'])) {
    // Check the role of the user
    if ($_SESSION['role'] == 1 || $_SESSION['role'] == 3) {
        // If the user is an admin (role = 1), use a generic link to section head page
        $eventText = "TRG Events";
        $navLinkText = "Personnel";
        $eventListName = "TRG";
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="icon" href="../images/scheduler.ico" type="image/x-icon">
    <!--========== BOX ICONS ==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>  
    <!--========== CSS ==========-->
    <link rel="stylesheet" href="../css/sidemenu.css" />
</head>

<body>

  <?php
  // Check if the current URL contains 'dashboard.php'
  $isHomeActive = strpos($_SERVER['REQUEST_URI'], 'dashboard.php') !== false;

  // Check if the current URL contains 'sched.php'
  $isPMDEventsActive = strpos($_SERVER['REQUEST_URI'], 'sched.php') !== false || strpos($_SERVER['REQUEST_URI'], 'settings.php') !== false;

  $isSectionEventsActive = strpos($_SERVER['REQUEST_URI'], 'sectionhead.php') !== false || strpos($_SERVER['REQUEST_URI'], 'weekly_task.php') !== false;

  $isProfileActive = strpos($_SERVER['REQUEST_URI'], 'profile.php') !== false || strpos($_SERVER['REQUEST_URI'], 'users.php') !== false;
  ?>

<style>
  .large-icon {
    font-size: 22px; /* Adjust the size as needed */
    -webkit-text-stroke-width: 1px; /* Width of the stroke */
    -webkit-text-stroke-color: #387ADF; /* Color of the stroke */
    text-stroke-width: 1px; /* Width of the stroke */
    text-stroke-color: black; /* Color of the stroke */
  }

  .nav__link img.large-icon {
    filter: drop-shadow(2px 2px 2px #387ADF); /* Apply drop shadow effect */
    -webkit-text-stroke-color: #387ADF; /* Set the stroke color */
    -webkit-text-stroke-width: 1px !important; /* Ensure the stroke width */
  }
</style>
<!--========== NAV ==========-->
<div class="nav" id="navbar">
      <nav class="nav__container">
        <div>
        

          <div class="nav__list">
           

            <div class="nav__items">
              <h3 style="font-size:14px;!important"><?php echo $_SESSION['fullname'] ?></h3>
              

        <div class="nav__items">
    <a href="../views/sched.php" class="nav__link <?php echo $isPMDEventsActive ? 'active' : ''; ?>">
        
        <i class="bx bx-building nav__icon <?php echo $isPMDEventsActive ? 'large-icon' : ''; ?>"></i>
        <span class="nav__name <?php echo $isPMDEventsActive ? 'bold' : ''; ?>"><?php echo $eventText; ?></span>
    </a>
</div>

<!-- 
              <div class="nav__items">
                <a href="<?php echo $sectionHeadLink; ?>" class="nav__link <?php echo $isSectionEventsActive ? 'active' : ''; ?>">
                  <i class="bx bx-group nav__icon <?php echo $isSectionEventsActive ? 'large-icon' : ''; ?>"></i>
                  <span class="nav__name <?php echo $isSectionEventsActive ? 'bold' : ''; ?>"><?php echo $navLinkText; ?></span>
              
                </a> -->

                <!-- <div class="nav__dropdown-collapse">
                  <div class="nav__dropdown-content">
                    <a href="#" onclick="redirectToSchedule()" class="nav__dropdown-item <?php echo $isSectionEventsActive ? 'bold' : ''; ?>">Modify Events</a>
                  </div>
                </div> -->
              <!-- </div> -->

            <div class="nav__items"  style="display:none;">
              <h3 class="nav__subtitle">Profile</h3>
              <div class="nav__dropdown"> 

                <div class="nav__dropdown-collapse">
                  <div class="nav__dropdown-content">
                    <a href="#" onclick="redirectToProfile()" class="nav__dropdown-item"><i class="fas fa-user"></i>My Profile</a>
                    <?php if ($_SESSION['role'] == 1): ?>
                        <a href="#" class="nav__dropdown-item" onclick="redirectToUsers()"><i class="fas fa-users"></i>Users</a>
                      <?php else: ?>
                          <!-- For non-admin users, hide the container -->
                          <!-- <li style="display: none;"><a href="#" onclick="redirectToUsers()"  style="color:#272829;"><i class="fas fa-users"></i> Users</a></li> -->
                          <a href="#" class="nav__dropdown-item" onclick="redirectToUsers()" style="display: none;"><i class="fas fa-users"></i>Users</a>
                      <?php endif; ?>
                    
                    <!-- <a href="#" onclick="changeBackgroundColor()"  class="nav__dropdown-item"><i class="fas fa-palette"></i>Change Theme</a> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <a href="#" data-toggle="modal" data-target="#confirmationModal" class="nav__link nav__logout">
          <i class="bx bx-log-out nav__icon"></i>
          <span class="nav__name">Log Out</span>
        </a>
      </nav>
    </div>


    
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
    

// Update the time every 5 minutes
//setInterval(updateTime, 300000); // 300000 milliseconds = 5 minutes

</script>
    <!--========== MAIN JS ==========-->
    <!-- <script src="../js/main.js"></script> -->

</body>

</html>

