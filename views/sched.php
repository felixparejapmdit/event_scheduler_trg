<?php
// Include the database connection file
include '../databases/connection/db.php';
date_default_timezone_set('Asia/Manila');


// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if the userid is set in the session
if(isset($_SESSION['userid'])) {
    // Get the userid from the session
    $userid = $_SESSION['userid'];
} else {
    // Handle the case where userid is not set
    $userid = ""; // or any default value
}


// Get the user ID from the URL parameter
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    $roleid = $_GET['role'];
} else {
    // Default to the current user's ID if no user ID is provided in the URL
    $userid = $_SESSION['userid'] ?? null;
}

// Check if the userid is set in the session
if(isset($_SESSION['sectionid'])) {
    // Get the sectionid from the session
    $sectionid = $_SESSION['sectionid'];
} else {
    // Handle the case where sectionid is not set
    $sectionid = ""; // or any default value
}

// Check if the session role is set and equal to 1
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $displayStyle = "block"; // Display the form for role 1
    $isRequired = "required"; // Event name is required for role 1
} else {
    $displayStyle = "none"; // Hide the form for other roles
    $isRequired = ""; // Event name is not required for other roles
}


// Function to get count of events
function getEventCount($conn, $condition = "") {
    // SQL query to count the number of events
    $sql = "SELECT COUNT(*) AS event_count 
            FROM events e
            LEFT JOIN user u ON u.id = e.prepared_by ";

    // Add condition if provided
    if (!empty($condition)) {
        $sql .= " WHERE " . $condition;
    }

    //echo  $sql;
    // Execute the SQL query
    $result = $conn->query($sql);

    // Check if result is not null
    if ($result->num_rows > 0) {
        // Fetch and return the event count
        $row = $result->fetch_assoc();
        return $row["event_count"];
    } else {
        return 0; // No events found
    }
}

?>



<?php
// Function to output the thead with background color based on event name
function outputTableHeader($eventName) {
    switch ($eventName) {
        case 'Suguan':
            echo '<thead class="table" style="background-color: #e4e6e0;" padding:5px; color:#e8e8e4;!important>';
            break;
        case 'ATG Appointment':
            echo '<thead class="table" style="background-color: #924e3c;" padding:5px;>';
            break;
        case 'Non-ATG':
            echo '<thead class="table" style="background-color: #d9cec1;" padding:5px;>';
            break;
        case 'Family':
            echo '<thead class="table" style="background-color: #7a9192;" padding:5px;>';
            break;
        default:
            echo '<thead class="table" style="background-color: #7e93ab;" padding:5px;>';
            break;
    }
}


function IconForEvent($eventId)
{
   // echo $eventId;
    
    if ($_SESSION['role'] != 3)
    {
        echo '<i class="fas fa-edit" id="edit-event" style="margin-right:3px; cursor:pointer;" data-toggle="modal" data-target="#EventEditModal" data-event-id=' . $eventId . '></i>'; // Example: Default calendar icon
    }
        // Add more cases for other events as needed
}

function VenueColorCoding($venue)
{
   
    switch($venue)
    {
        case "1":
            return '<b><a href="#" style="color: #519eaa;">TRG Conference room</a></b><br>';
        case "2":
            return '<b><a href="#" style="color: #d09e6a;">ECD Office</a></b>';
        case "3":
            return '<b><a href="#" style="color: #248fb2;">SFM - TRG Satellite Office</a></b>';
        default:
            return '<b><a href="#" style="color: #6e9f8a;">TRG Office</a></b>';
    }
}


function VenueColorCoding1($venue)
{
    //echo $venue;
    switch($venue)
    {
        case "1":
            return '<b><a style="color: #519eaa;">TRG Conference room</a></b>';
            break;
        case "2":
            return '<b><a style="color: #d09e6a;">ECD Office</a></b>';
            break;
        case "3":
            return '<b><a style="color: #248fb2;">SFM - TRG Satellite Office</a></b>';
            break;
        default:
            return '<b><a style="color: #6e9f8a;">TRG Office</a></b>';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Event Scheduler</title>
    <link rel="icon" href="../images/scheduler.ico" type="image/x-icon">


    <!-- Include jQuery before Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Bootstrap CSS (Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <style>
        body {
            transition: background-color 0.5s ease;
            transition: opacity 1s ease-in-out;
            background-color: #96cdee!important; /* Default background color */
            font-family: Arial, sans-serif; /* Specify a sans-serif font family */
        }
        
        .change-bg-btn {
            position: fixed;
            top: 20px;
            right: 80px;
            z-index: 1000;
            cursor: pointer;
            font-size: 24px;
            color: #000000;
        }

        .settings-bg-btn {
            position: fixed;
            top: 20px;
            right: 50px;
            z-index: 1000;
            cursor: pointer;
            font-size: 24px;
            color: #000000;
        }
        .logout-btn {
            position: fixed;
            top: 32px;
            right: 20px;
            z-index: 1000;
            cursor: pointer;
            font-size: 16px;
            color: #000000;
        }
        .change-bg-btn.light {
            color: #fffffd; /* Color when background is dark */
        }

        /* Reduce font size for smaller screens */
        @media (max-width: 768px) {
            td, th {
                font-size: 14px;
            }
        }

        .table-custom tbody tr:first-child td {
        border-top: 6px solid transparent; /* Creates space between thead and tbody */
        border-left: 6px solid transparent; /* Creates space between thead and tbody */
        border-right: 6px solid transparent; /* Creates space between thead and tbody */
        border-bottom: 6px solid transparent; /* Creates space between thead and tbody */
    }

    </style>
<script>

    function loadSuguan() {
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();

        // Define the URL of suguan.php
        var url = 'suguan.php';

        // Send a GET request to suguan.php
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            // Check if the request is completed and the response is ready
            if (xhr.readyState === XMLHttpRequest.DONE) {
                // Check if the request was successful (status code 200)
                if (xhr.status === 200) {
                    // Insert the response HTML into divSuguan
                    document.getElementById('divSuguan').innerHTML = xhr.responseText;
                    document.getElementById('sched_event').style.display = 'none';
                } else {
                    // Display an error message if the request fails
                    console.error('Failed to load suguan.php. Status code: ' + xhr.status);
                }
            }
        };
        xhr.send();
    }

    function checkFullscreen() {
        var isFullscreen = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
        
        
        // Check if the screen is in fullscreen mode
        if (isFullscreen) {
            // Redirect to suguan.php after 1 minute (60000 milliseconds)
            setTimeout(function() {
                //loadSuguan();
            }, 10000); // Adjust the timeout to 10 seconds for testing
        }
    }

    // Call the function after a slight delay to ensure the document is fully loaded
    setTimeout(checkFullscreen, 100);
    
    // Add event listener for fullscreen change
    document.onfullscreenchange = checkFullscreen;
</script>

    
</head>

<body>
<!-- Logout Button -->
<a href="#" data-toggle="modal" data-target="#confirmationModal" class="logout-btn btn btn-success" style="display:none;">
    <i class="fas fa-sign-out-alt"></i>
</a>

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

<!-- Notification Banner -->
<div id="notificationBanner" class="notification-banner" style="display:none;">
    <div class="notification-content">
        <div class="notification-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="notification-message">
            <!-- Your event "<?php echo $title; ?>" is starting soon! -->
        </div>
        <div class="notification-close">
            <i class="fas fa-times"></i>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
        function logout() {
    event.preventDefault(); // Prevent the default behavior of the button
    // Redirect to logout page or perform logout operation here
    window.location.href = "../";
    }
    // Function to update notification status for each event
    function updateNotification() {
        // Get all table rows
      
        var tableRows = document.querySelectorAll('.table-custom tbody tr');
       
        // Loop through each table row
        tableRows.forEach(function(row) {
            //alert("AS");
            // Get event time and title from the row data attributes
            var eventTime = new Date(row.getAttribute('data-event-time'));
           // alert(eventTime);
            var eventTitle = row.getAttribute('data-event-title');

            // Calculate notification time (one hour before event time)
            var notificationTime = new Date(eventTime.getTime() - (60 * 60 * 1000)); // One hour before event time

            // Current time
            var currentTime = new Date();

            // Check if it's time to show the notification for this event
            if (currentTime >= notificationTime && currentTime < eventTime) {
                // Show notification banner with event title
                document.getElementById('notificationBanner').style.display = 'block';
                document.querySelector('.notification-message').textContent = 'Your event "' + eventTitle + '" is starting soon!';
                return; // Exit the loop after showing the notification for the first event
            }
        });
    }

    // Update notification status every minute
    setInterval(updateNotification, 10000);

    // Close notification banner
    document.querySelector('.notification-close').addEventListener('click', function() {
        document.getElementById('notificationBanner').style.display = 'none';
    });

    // Initial update
   // updateNotification();
</script>


<script>
    // Update the time every 5 minutes
setInterval(updateTime, 300000); // 300000 milliseconds = 5 minutes

</script>
<script>

document.addEventListener("DOMContentLoaded", function() {
    // Function to update the time
    function updateTime() {
        var currentDate = new Date();
        var hours = currentDate.getHours();
        var minutes = currentDate.getMinutes();
        var seconds = currentDate.getSeconds();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // 12-hour format
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        var currentTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
        var currentTimeElement = document.getElementById("currentTime");
        if (currentTimeElement) {
            currentTimeElement.innerText = currentTime;
        }
    }

    // Call updateTime initially
    updateTime();

    // Update the time every 3 seconds
    setInterval(updateTime, 1000); // 3000 milliseconds = 3 seconds
});

</script>


<?php include '../layouts/footer.php'; ?>

</style>
<!-- Add Event Modal -->
<div class="modal fade" data-backdrop="static" id="EventAddModal" tabindex="-1" aria-labelledby="EventAddModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="EventAddModalLabel">Add New Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <form method="post" id="addEventForm" name="addEventForm" action="../databases/queries/add_event.php">
                            <input type="hidden" id="savetype" name="savetype" value="pmdevent">
                            <input type="hidden" id="current_url" name="current_url" value="sched.php">
                                <div class="form-group"  style="display: <?php echo $displayStyle; ?>;">
                                    <label for="eventName">Category:</label>
            
                                    <?php
                                        // Assuming you have already established a connection to the database in $conn

                                        // Fetch event names from the events table
                                        $eventNamesQuery = "SELECT DISTINCT id, name FROM category ORDER BY id ASC";
                                        $eventNamesResult = mysqli_query($conn, $eventNamesQuery);

                                        // The variable $eventName should be defined earlier in your script
                                        // It could be the currently selected event name for comparison

                                        echo "<select class='form-control' id='eventName' name='eventName' " . $isRequired . " style='width: 100%;'>";
                                        echo "<option value='0' disabled selected>Select category</option>";
                                        while ($eventNameRow = mysqli_fetch_assoc($eventNamesResult)) {
                                            $eventNamesql = $eventNameRow['name'];
                                            $eventID = $eventNameRow['id'];
                                            // Output each event name as an option in the dropdown
                                            echo "<option value='" . htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($eventNamesql, ENT_QUOTES, 'UTF-8') . "</option>";
                                        }
                                        
                                        echo "<option value='Others'>Others</option>";
                                        echo "</select>";
                                        ?>
                                </div>

                                <div class="form-group">
                                    <label id="titleLabel" for="title">Title:</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>

                                <div class="form-group" style="display:none;">
                                    <label id="hostLabel" for="host">Host:</label>
                                    <input type="text" id="host" name="host" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="date">Date:</label>
                                    <input type="date" id="date" name="date" class="form-control" required>
                                </div>

                                <div class="form-group" id="formtime">
                                    <label for="time">Time:</label>
                                    <input type="time" id="time" name="time" class="form-control" required>
                                </div>

                                <div class="form-group" id="formselect">
                                
                                    <style>
                                        /* Style for the select dropdown */
                                        select {
                                        padding: 5px;
                                        }
                                        select option.trgroom {
                                        color: #c78171;
                                        }
                                        select option.ecdoffice {
                                        color: #7a5aab;
                                        }
                                        select option.sfmoffice {
                                        color: #e0c750;
                                        }
                                        select option.Others {
                                        color: #6e9f8a;
                                        }
                                        </style>

                                    <label for="venueSelect">Venue:</label>
                   
                                    <?php
                        // Assuming you have already established a connection to the database in $conn

                        // Fetch event names from the locations table
                        $locationsQuery = "SELECT DISTINCT id, name FROM location ORDER BY id ASC";
                        $locationsResult = mysqli_query($conn, $locationsQuery);
                       
                        // The variable $isRequired should be defined earlier in your script
                        // It could be something like $isRequired = 'required' or $isRequired = '';

                        echo "<select class='form-control' id='venueSelect' name='venueSelect' " . $isRequired . " onchange='toggleLocationInput1()'>";
                        echo "<option value='0' disabled selected>Select category</option>";

                        while ($eventNameRow = mysqli_fetch_assoc($locationsResult)) {
                            $locationsql = $eventNameRow['name'];
                            $eventID = $eventNameRow['id'];
                            // Output each event name as an option in the dropdown
                            echo "<option value='" . htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($locationsql, ENT_QUOTES, 'UTF-8') . "</option>";
                        }

                        echo "<option value='Others'>Others</option>";
                        echo "</select>";
                        ?>
                                    
                                    <!-- Input field for location -->
                                    <input type="text" id="location" name="location" class="form-control mt-2" placeholder="Enter venue" style="display: none;">

                                    <script>

                                        document.getElementById('eventName').addEventListener('change', function() {
                                            var selectedText = this.options[this.selectedIndex].text;
                                            var timeGroup = document.getElementById('formtime');
                                            var venueGroup = document.getElementById('formselect');
                                            
                                            var timeInput = document.getElementById('time');
                                            var venueSelect = document.getElementById('venueSelect');

                                            if (selectedText === 'Birthdays' || selectedText === 'Anniversary' || selectedText === 'Holidays') {
                                                timeGroup.style.display = 'none';
                                                venueGroup.style.display = 'none';
                                                timeInput.removeAttribute('required');
                                                venueSelect.removeAttribute('required');
                                            } else {
                                                timeGroup.style.display = 'block';
                                                venueGroup.style.display = 'block';
                                                timeInput.setAttribute('required', 'required');
                                                venueSelect.setAttribute('required', 'required');
                                            }
                                        });

                                        function toggleLocationInput1() {
                                            var venueSelect = document.getElementById('venueSelect');
                                            var locationInput = document.getElementById('location');

                                            // Check if the selected value is "Others"
                                            if (venueSelect.value === 'Others') {
                                                locationInput.style.display = 'block'; // Show the location input field
                                                locationInput.setAttribute('required', 'required'); // Make the input field required
                                            } else {
                                                locationInput.style.display = 'none'; // Hide the location input field
                                                locationInput.removeAttribute('required'); // Remove the required attribute
                                            }
                                        }
                                    </script>
                                </div>

                                <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="incharge">Point Person:</label>
                                        <input type="text" id="incharge" name="incharge" class="form-control" required>
                                    </div>
                                    <div class="col">
                                        <label for="contact_number">Contact #:</label>
                                        <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                                    </div>
                                </div>
                            </div>


                                <div class="form-group">
                                    <label for="addDetails">Details:</label>
                                    <textarea id="addDetails" name="addDetails" class="form-control"></textarea>
                                </div>


                                <div class="text-center">
                                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="addEvent" id="saveButton" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                        
                        </div>
                    </div>
                </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" data-backdrop="static" id="EventEditModal" tabindex="-1" aria-labelledby="EventEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EventEditModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <form method="post" id="editEventForm" name="editEventForm" action="../databases/queries/update_event.php">
                <input type="hidden" id="savetype" name="savetype" value="pmdevent">
                <input type="hidden" id="current_url" name="current_url" value="sched.php">
                    <input type="hidden" id="editEventId" name="editEventId">
                    
                    <div class="form-group"  style="display: <?php echo $displayStyle; ?>;">
                        <label for="editEventName">Category</label>

                        <?php
                            // Ensure $eventName is set and provide a default value if not
                            $eventName = isset($eventName) ? $eventName : '';
                            // Fetch event names from the events table
                            $edit_eventNameQuery = "SELECT DISTINCT id, name FROM category ORDER BY id ASC";
                            $edit_eventNamesResult = mysqli_query($conn, $edit_eventNameQuery);
                                
                            // The variable $eventName should be defined earlier in your script
                            // It could be the currently selected event name for comparison

                            echo "<select class='form-control' id='editEventName' name='editEventName' " . $isRequired . " style='width: 100%;'>";
                            echo "<option value='0' disabled selected>Select category</option>";

                            while ($eventNameRow = mysqli_fetch_assoc($edit_eventNamesResult)) {
                                $eventNamesql = $eventNameRow['name'];
                                $eventID = $eventNameRow['id'];
                                // Output each event name as an option in the dropdown
                                echo "<option value='" . htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') . "'" . ($eventNamesql == $eventName ? ' selected' : '') . ">" . htmlspecialchars($eventNamesql, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            
                            echo "<option value='Others'>Others</option>";
                            echo "</select>";
                        ?>

                    </div>


                    <div class="form-group">
                        <label id="titleLabel" for="editTitle">Title:</label>
                        <input type="text" id="editTitle" name="editTitle" class="form-control" required>
                    </div>


                    <div class="form-group" style="display:none;">
                        <label for="editHost">Host:</label>
                        <input type="text" id="editHost" name="editHost" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="editDate">Date:</label>
                        <input type="date" id="editDate" name="editDate" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="editTime">Time:</label>
                        <input type="time" id="editTime" name="editTime" class="form-control" required>
                    </div>

                    <div class="form-group">
                    
                    <style>
                        /* Style for the select dropdown */
                        select {
                        padding: 5px;
                        }

                        /* Style for the options */
                        select option.MPH {
                        color: #519eaa;
                        }

                        select option.chapel1 {
                        color: #d09e6a;
                        }

                        select option.chapel4 {
                        color: #248fb2;
                        }
                        select option.studioA {
                        color: #5c65bd;
                        }
                        select option.studioB {
                        color: #af479f;
                        }
                        select option.studioC {
                        color: #0083cd;
                        }
                        select option.ConferenceRoom6F {
                        color: #238fb6;
                        }
                        select option.CommonArea3F {
                        color: #5567a9;
                        }
                        select option.ConferenceRoom3F {
                        color: #5c64ae;
                        }
                        select option.LanguageRoom3F {
                        color: #b7be64;
                        }
                        select option.DojoRoom3F {
                        color: #c78171;
                        }
                        select option.Auditorium {
                        color: #7a5aab;
                        }
                        select option.PublicLobby {
                        color: #e0c750;
                        }
                        select option.Others {
                        color: #6e9f8a;
                        }
                        </style>

                    <label for="editVenueSelect">Venue:</label>
            
                    <?php
                        // Assuming you have already established a connection to the database in $conn

                        // Fetch event names from the locations table
                        $locationsQuery = "SELECT DISTINCT id, name FROM location ORDER BY id ASC";
                        $locationsResult = mysqli_query($conn, $locationsQuery);
                        
                        // The variable $isRequired should be defined earlier in your script
                        // It could be something like $isRequired = 'required' or $isRequired = '';

                        echo "<select class='form-control' id='editVenueSelect' name='editVenueSelect' " . $isRequired . " onchange='toggleLocationInput()'>";
                        echo "<option value='0' disabled selected>Select location</option>";

                        while ($eventNameRow = mysqli_fetch_assoc($locationsResult)) {
                            $locationsql = $eventNameRow['name'];
                            $eventID = $eventNameRow['id'];
                            // Output each event name as an option in the dropdown
                            echo "<option value='" . htmlspecialchars($eventID, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($locationsql, ENT_QUOTES, 'UTF-8') . "</option>";
                        }

                        echo "<option value='Others'>Others</option>";
                        echo "</select>";
                    ?>

                    <!-- Input field for location -->
                    <input type="text" id="editLocation" name="editLocation" class="form-control mt-2" placeholder="Enter venue" style="display: none;">

                    <script>
                        function toggleLocationInput() {
                            var venueSelect = document.getElementById('editVenueSelect');
                            var locationInput = document.getElementById('editLocation');

                            // Check if the selected value is "Others"
                            if (venueSelect.value === 'Others') {
                                locationInput.style.display = 'block'; // Show the location input field
                                locationInput.setAttribute('required', 'required'); // Make the input field required
                            } else {
                                locationInput.style.display = 'none'; // Hide the location input field
                                locationInput.removeAttribute('required'); // Remove the required attribute
                                locationInput.value = '';
                            }
                        }
                    </script>
                    
                </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="editIncharge">Point Person:</label>
                                <input type="text" id="editIncharge" name="editIncharge" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="editContact_number">Contact #:</label>
                                <input type="text" id="editContact_number" name="editContact_number" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="editDetails">Details:</label>
                        <textarea id="editDetails" name="editDetails" class="form-control"></textarea>
                    </div>


                    <div class="text-center">
                        <button type="submit" id="editEvent"  name="editEvent" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>



    


<div id="sched_event">
    <style>
         /* Apply rounded corners to the entire table */
         table#event_table{
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensure rounded corners are visible */
            width: 100%; /* Make table width 100% */
        }

        #event_table td,
            #event_table th {
                padding: 10px; /* Add padding to improve appearance */
                border: 1px solid #ccc; /* Add border to td and th elements */
                border-radius: 5px; /* Adjust the value to change the roundness */
            }

            #event_table thead {
                border: none; /* Remove border from thead */
            }

            .smaller-table-upcoming,
            .smaller-table-upcoming th,
            .smaller-table-upcoming td {
                width: 100%;
            }


        .custom-border {
            border-radius: 10px;
            background-color: #ffffff; /* Specify the background color here */
        }

        </style>
  <div class="d-flex mt-2">

    <div class="container mt-2 ml-2 mr-2 custom-border" id="custom-container1">

<!-- <div class="row"> -->
    <?php
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get today's date
    $today = date("Y-m-d");

    // Fetch today's events
    if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
    {
       
  // Get the roleid from the URL
  if (!isset($_GET['role'])) {
    $roleid = 1;
}
   // Get the current URL
   $current_url = $_SERVER['REQUEST_URI'];

   // Check if the current URL contains 'userid'
   if (strpos($current_url, 'sectionid') !== false) {
       //echo "1";
    $sectionid = $_GET['sectionid'];
    $today_query = "SELECT *,e.id as event_id FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE  event_type = 1 AND date = '$today' AND e.prepared_by NOT IN(14, 40) 
    AND u.section = '$sectionid' 
    ORDER BY time ASC";
   }
   else{
    //echo "2";
    $today_query = "SELECT *,e.id as event_id FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE is_display = 1 AND event_type = 1 AND date = '$today' AND e.prepared_by NOT IN(14, 40) 
    ORDER BY time ASC";
   }



    }
    else{
       // echo "3";
        $today_query = "SELECT *,e.id as event_id FROM events e WHERE e.event_type = 1 AND e.date = '$today' AND e.prepared_by = " . $_SESSION['userid'] . " ORDER BY e.time ASC";
    }
   
//echo $today_query;
    $today_result = mysqli_query($conn, $today_query);

    // Get the row count
    $row_count = mysqli_num_rows($today_result);


    $_SESSION['COUNT_SESSION'] = $row_count ;
    // Counter to keep track of the number of tables created
    $counter = 0;


      // Change date format
      //$date = date("F j, Y");
      $date = date("Y F j, l");
    // Output the "Today's Events" heading with row count and date, including the current time

    echo '<h4 class="mb-1 ml-1 mt-2" style="font-weight:bold;">' . ($counter == 0 ? 'Today (' . $row_count . ') | <span id="currentTime" style="background: linear-gradient(90deg, #0B60B0, #66a5ad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold; background-color: #ffffff; display: inline-block; padding: 5px;"></span><br>' . $date . ' ' : '<p style="color: rgba(0, 0, 0, 0);">Today</p>') . '</h4>';
    echo '<div class="row">';
    while ($row = mysqli_fetch_assoc($today_result)) {
        $eventid = $row['event_id'];
        $eventName = $row['event_name'];
        $title = $row['title'];
        // Increment the counter for each iteration
        $counter++;

   // Check if the counter is a multiple of 5 or is equal to 1
   if ($counter % 5 == 1 || $counter == 1) {
        // If it is, close the previous col-md-3 div and open a new one
        if ($counter != 1) {
            echo '</div>'; // Close the previous col-md-3 div
        }

        // Determine the number of columns based on the row count
        $numColumns = $row_count <= 5 ? 12 : 6;

        echo '<div class="col-md-' . $numColumns . ' mx-auto mt-4">'; // Open a new col-md div with margin-top


    }

        // Output each event row
        echo '<table class="table table-bordered rounded table-custom" id="event_table">';
        // Output the thead with background color
        outputTableHeader($eventName);

        // Output the table header content
        echo '<tr>';
        echo '<th style="padding: 1px;">';

        // Add icon based on the event name
        IconForEvent($eventid);
        
        echo '<span class="text-white">' . $title . '</span>';
        // Add edit icon in the right corner
        echo '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';


        // Convert time to 12-hour format
        $time_12_hour = date("h:i A", strtotime($row['time']));

        // Adjusting row values
        $incharge = $row['incharge'];
        $contact_number = $row['contact_number'];
        $host = $row['host'];
        $location = $row['location'];
        $details = $row['details'];

        // Output the table body content
        //echo '<tr>';
        echo '<tr data-event-time="' . $row['time'] . '" data-event-title="' . $title . '">';
        echo '<td style="font-size: 17px;padding: 1px;">';

        
        // Check the event name value and conditionally display the time and location
        if (!in_array($eventName, [1, 2, 5])) {
           
            echo '<b><span style="color: #231c35;margin-bottom: 0;">' . $time_12_hour . '</span></b><br>';
            echo VenueColorCoding($location);
            } 

        // Use a regular expression to find the links in the details
        $pattern = '/(?:^|\s)(https?:\/\/(?:www\.)?(?:\S+\.)?\S{2,}(?:\S+)?(?:\.[a-z]{2,})?\S*)(?:$|\s)/i';
        $contact_number = preg_replace_callback($pattern, function($matches) {
            $url = $matches[1];
        
            if (strpos($url, 't.me') !== false) {
                // Extract username from t.me link
                $username = substr($url, strpos($url, 't.me/') + 5);
                return '<a href="' . $url . '" target="_blank">' . $username . '</a>';
            } else {
                $domain = preg_replace('/^https?:\/\/(?:www\.)?|\/.*$/i', '', $url);
                return '<a href="' . $url . '" target="_blank">' . $domain . '</a>';
            }
        }, $contact_number);

        echo '<span style="color: #231c35; font-weight: 500;">' . $incharge . '</span>, <i>' . $contact_number . '</i><br>';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
if (!empty($details) && trim($details) !== '') {
    echo '<td style="font-size: 13px;padding: 1px;">';

    // Use a regular expression to find the links in the details
    $pattern = '/(?:^|\s)(https?:\/\/(?:www\.)?(?:\S+\.)?\S{2,}(?:\S+)?(?:\.[a-z]{2,})?\S*)(?:$|\s)/i';
    $details = preg_replace_callback($pattern, function($matches) {
        $url = $matches[1];
      
        if (strpos($url, 'webex.com') !== false) {
            return '<a href="' . $url . '" target="_blank">Webex LINK</a>';
        } else if (strpos($url, 't.me') !== false) {
            return '<a href="' . $url . '" target="_blank">Telegram LINK</a>';
        } else {
            $domain = preg_replace('/^https?:\/\/(?:www\.)?|\/.*$/i', '', $url);
            return '<a href="' . $url . '" target="_blank">' . $domain . '</a>';
        }
}, $details);

echo '<span style="margin-left:8px;">Details: </span> <br> ' . "\t" . nl2br($details);
echo '</td>';
}

echo '</tr>';

        echo '</tbody>';
        echo '</table>';
    }

    // Close the database connection
   // mysqli_close($conn);
    ?>

    
<style>
    @keyframes grow {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .table-custom {
        animation: grow 3s infinite;
    }
</style>
</div>
<?php
if ($_SESSION['COUNT_SESSION']===0)
{
    echo '<div class="no-events">NO EVENTS</div>';
}
?>
<style>
    .no-events {
    font-size: 24px;
    font-weight: bold;
    color: red!important;
    text-align: center;
    margin-top: 50px; /* Adjust as needed */
}

</style>

</div>
<?php
if ($_SESSION['COUNT_SESSION']>0)
{
echo '</div>';
}
?>

<div class="container mt-2 ml-2 mr-2 custom-border" id="custom-container2">

<?php
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Calculate tomorrow's date
    $tomorrow = date("Y-m-d", strtotime("+1 day"));
    // Fetch today's events

    if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
    {
        //$today_query = "SELECT * FROM events WHERE is_display = 1 AND event_type = 1 AND date = '$tomorrow' AND events.prepared_by NOT IN(1, 14, 40) ORDER BY time ASC";
        // Get the roleid from the URL
    if (!isset($_GET['role'])) 
    {
        $roleid = 1;
    }
   // Get the current URL
   $current_url = $_SERVER['REQUEST_URI'];

   // Check if the current URL contains 'userid'
   if (strpos($current_url, 'sectionid') !== false) {
      // echo "1";
    $sectionid = $_GET['sectionid'];
  
    $today_query = "SELECT *,e.id as event_id  FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE event_type = 1 AND date = '$tomorrow' AND e.prepared_by NOT IN(14, 40) 
    AND u.section = '$sectionid' 
    ORDER BY time ASC";
   }
   else{
    $today_query = "SELECT *,e.id as event_id  FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE is_display = 1 AND event_type = 1 AND date = '$tomorrow' AND e.prepared_by NOT IN(14, 40)  
    ORDER BY time ASC";
   }
    }
    else{
        $today_query = "SELECT *,e.id as event_id  FROM events e WHERE e.event_type = 1 AND e.date = '$tomorrow' AND e.prepared_by = " . $_SESSION['userid'] . " ORDER BY e.time ASC";
    }

    $today_result = mysqli_query($conn, $today_query);

    // Get the row count
    $row_count = mysqli_num_rows($today_result);
$_SESSION['COUNT_SESSION'] = $row_count ;
// Change date format

$date = date("Y F j, l", strtotime("+1 day"));
    // Counter to keep track of the number of tables created
    $counter = 0;
 // Output the "Tomorrow's Events" heading with row count
 echo '<h4 class="mb-1 ml-1 mt-3" style="font-weight:bold;">' . ($counter == 0 ? 'Reminders (' . $row_count . ') <br> ' . $date . '' : '<p style="color: rgba(0, 0, 0, 0);">Today</p>') . '</h4>';
 echo '<div class="row">';
    while ($row = mysqli_fetch_assoc($today_result)) {
        $eventid = $row['event_id'];
        $eventName = $row['event_name'];
        $title = $row['title'];
        // Increment the counter for each iteration
        $counter++;

   // Check if the counter is a multiple of 5 or is equal to 1
   if ($counter % 5 == 1 || $counter == 1) {
        // If it is, close the previous col-md-3 div and open a new one
        if ($counter != 1) {
            echo '</div>'; // Close the previous col-md-3 div
        }
        // Determine the number of columns based on the row count
        $numColumns = $row_count <= 5 ? 12 : 6;

        echo '<div class="col-md-' . $numColumns . ' mx-auto mt-4">'; // Open a new col-md div with margin-top


    }

        // Output each event row
        echo '<table class="table table-bordered rounded table-custom" id="event_table">';
        // Output the thead with background color
        outputTableHeader($eventName);

        // Output the table header content
        echo '<tr>';
        echo '<th style="padding: 1px;">';

        // Add icon based on the event name
        IconForEvent($eventid);
        echo '<span class="text-white">' . $title . '</span>';
        echo '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Convert time to 12-hour format
        $time_12_hour = date("h:i A", strtotime($row['time']));

        // Adjusting row values
        $incharge = $row['incharge'];
        $contact_number = $row['contact_number'];
        $host = $row['host'];
        $location = $row['location'];
        $details = $row['details'];



        // Output the table body content
        echo '<tr>';
        echo '<td style="font-size: 17px;padding: 1px;"">';

   

        // Check the event name value and conditionally display the time and location
        if (!in_array($eventName, [1, 2, 5])) {
        echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b><br>';
        VenueColorCoding($location);
        }
            

         // Use a regular expression to find the links in the details
        $pattern = '/(?:^|\s)(https?:\/\/(?:www\.)?(?:\S+\.)?\S{2,}(?:\S+)?(?:\.[a-z]{2,})?\S*)(?:$|\s)/i';
        $contact_number = preg_replace_callback($pattern, function($matches) {
            $url = $matches[1];
        
            if (strpos($url, 't.me') !== false) {
                // Extract username from t.me link
                $username = substr($url, strpos($url, 't.me/') + 5);
                return '<a href="' . $url . '" target="_blank">' . $username . '</a>';
            } else {
                $domain = preg_replace('/^https?:\/\/(?:www\.)?|\/.*$/i', '', $url);
                return '<a href="' . $url . '" target="_blank">' . $domain . '</a>';
            }
        }, $contact_number);

        echo '<span style="color: #231c35; font-weight: 500;">' . $incharge . '</span>, <i>' . $contact_number . '</i><br>';

        echo '</td>';
        echo '</tr>';
        echo '<tr>';
if (!empty($details) && trim($details) !== '') {
    echo '<td style="font-size: 13px;padding: 1px;">';

        // Use a regular expression to find the links in the details
        $pattern = '/(?:^|\s)(https?:\/\/(?:www\.)?(?:\S+\.)?\S{2,}(?:\S+)?(?:\.[a-z]{2,})?\S*)(?:$|\s)/i';
        $details = preg_replace_callback($pattern, function($matches) {
            $url = $matches[1];
          
            if (strpos($url, 'webex.com') !== false) {
                return '<a href="' . $url . '" target="_blank">Webex LINK</a>';
            } else if (strpos($url, 't.me') !== false) {
                return '<a href="' . $url . '" target="_blank">Telegram LINK</a>';
            } else {
                $domain = preg_replace('/^https?:\/\/(?:www\.)?|\/.*$/i', '', $url);
                return '<a href="' . $url . '" target="_blank">' . $domain . '</a>';
            }
    }, $details);

    echo '<span style="margin-left:8px;">Details: </span> <br> ' . "\t" . nl2br($details);
    echo '</td>';
}

echo '</tr>';

        echo '</tbody>';
        echo '</table>';
    }


    // Close the database connection
   // mysqli_close($conn);
    ?>

</div>
<?php
if ($_SESSION['COUNT_SESSION']===0)
{
    echo '<div class="no-events">NO EVENTS</div>';
}
?>


<style>
    @keyframes grow {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .table-custom {
        animation: none; /* Disable animation by default */
    }

    .grow-animation {
        animation: grow 3s infinite;
    }
</style>

<script>
    // Function to enable growing animation for tables with event time within 1 hour from current time
    function enableGrowingAnimation(eventTimes) {
        var currentTime = new Date(); // Get current time
        currentTime.setSeconds(0, 0); // Set seconds and milliseconds to zero for accurate comparison

        // Loop through each table and enable growing animation if event time is within 1 hour from current time
        eventTimes.forEach(function(eventTime) {
            var eventDate = new Date(eventTime); // Convert event time to date object
            eventDate.setSeconds(0, 0); // Set seconds and milliseconds to zero for accurate comparison

            // Calculate time difference in milliseconds
            var timeDiff = eventDate.getTime() - currentTime.getTime();

            // Enable growing animation if event time is within 1 hour from current time
            if (timeDiff >= 0 && timeDiff <= 3600000) { // 3600000 milliseconds = 1 hour
                document.getElementById('table-' + eventTime).classList.add('grow-animation');
            }
        });
    }
</script>


</div>
<?php
if ($_SESSION['COUNT_SESSION']>0)
{
echo '</div>';
}
?>


<div class="container mt-2 ml-2 mr-2 custom-border" id="custom-container3">


<?php
// Get the count of today's events (assuming you have a column `event_date` in your events table)
    if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
    {
    
         // Get the current URL
         $current_url = $_SERVER['REQUEST_URI'];

         // Check if the current URL contains 'userid'
         if (strpos($current_url, 'sectionid') !== false) {
            // Get the count of upcoming events (excluding today and tomorrow)
            $upcoming_event_count_pmd = getEventCount($conn, "DATE(e.date) > CURDATE() + INTERVAL 1 DAY AND e.event_type = 1 AND e.prepared_by NOT IN(14, 40) AND u.section ='$sectionid' ");
         }
         else
         {
            // Get the count of upcoming events (excluding today and tomorrow)
            $upcoming_event_count_pmd = getEventCount($conn, "DATE(e.date) > CURDATE() + INTERVAL 1 DAY AND e.event_type = 1 AND e.prepared_by NOT IN(14, 40) ");
         }
      
        $eventlabel_pmd = "TRG";
        $eventlabel_section = "Section";
    }
    else
    {
        // Get the count of upcoming events (excluding today and tomorrow)
        $upcoming_event_count_pmd = getEventCount($conn, "DATE(e.date) > CURDATE() + INTERVAL 1 DAY AND e.event_type = 1 AND e.prepared_by = $userid");
        $eventlabel_pmd = "My Section";
        $eventlabel_section = "My Schedule";
    }


    // Output the "Upcoming's Events" heading with row count
    echo '<h4 class="mb-2 ml-3 mt-3" style="font-weight:bold;margin-top:-10px;">Upcoming (' . $upcoming_event_count_pmd . ')</h4>';
    
?>

<div class="row">
<?php
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Calculate the date for the day after tomorrow
    $day_after_tomorrow = date("Y-m-d", strtotime("+1 day"));

    // Fetch today's events
   // $today_query = "SELECT date, COUNT(*) AS event_count, event_name FROM events WHERE date > '$day_after_tomorrow' GROUP BY date ORDER BY date ASC";
   
//    $commonQueryPart = "SELECT date, COUNT(*) AS event_count,
//     (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events WHERE date > '$day_after_tomorrow' GROUP BY date) AS subquery) AS total_event_count
//     FROM events WHERE date > '$day_after_tomorrow' GROUP BY date ORDER BY date ASC";
//echo $sectionid;
    if ($_SESSION['role'] == 1 || $_SESSION['role'] == 3) {

        // Get the roleid from the URL
        if (!isset($_GET['role'])) {
            $roleid = 1;
        }
        // Get the current URL
        $current_url = $_SERVER['REQUEST_URI'];

        // Check if the current URL contains 'userid'
        if (strpos($current_url, 'sectionid') !== false) {
            $today_query = "SELECT e.date, e.id,
            (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt 
            FROM events e
            INNER JOIN user u ON u.id = e.prepared_by 
            WHERE event_type = 1 AND e.date > '$day_after_tomorrow' 
            AND u.section = '$sectionid' GROUP BY e.date) AS subquery) AS total_event_count
            FROM events e
            INNER JOIN user u ON u.id = e.prepared_by 
            WHERE event_type = 1 AND date > '$day_after_tomorrow' AND e.prepared_by NOT IN(14, 40) 
            AND u.section = '$sectionid'
            GROUP BY date ORDER BY date ASC";

        }
        else{
            $today_query = "SELECT date, COUNT(e.id) AS event_count, e.id,
            (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events e
            WHERE is_display = 1 AND event_type = 1 AND date > '$day_after_tomorrow' GROUP BY date) AS subquery) AS total_event_count
            FROM events e
            INNER JOIN user u ON u.id = e.prepared_by 
            WHERE is_display = 1 AND event_type = 1 AND date > '$day_after_tomorrow' AND e.prepared_by NOT IN(14, 40) 
            GROUP BY date 
            ORDER BY date ASC";
        }
        } 
        else {
            $today_query = "SELECT date, COUNT(*) AS event_count, id,
            (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events 
            WHERE event_type = 1 AND date > '$day_after_tomorrow' 
            AND prepared_by = " . $_SESSION['userid'] . " GROUP BY date) AS subquery) AS total_event_count
            FROM events WHERE event_type = 1 AND date > '$day_after_tomorrow' AND prepared_by = " . $_SESSION['userid'] . " 
            GROUP BY date ORDER BY date ASC";
        }

        $today_result = mysqli_query($conn, $today_query);

///// Check for errors
if (!$today_result) {
    echo "Error: " . mysqli_error($conn);
} else {
    // Get the row count
    $row_count = mysqli_num_rows($today_result);
    //echo "Number of rows: " . $row_count;
}

         //echo $today_query;
        // Get the row count
        $row_count = mysqli_num_rows($today_result);
        
        //echo $row_count;
        $_SESSION['COUNT_SESSION'] = $row_count ;
        $event_count = 0;
    
        // Counter to keep track of the number of tables created
        $counter = 0;

        // Variable to store the total event count
        while ($row = mysqli_fetch_assoc($today_result)) {
            // Increment the counter for each iteration
            $counter++;

            // Access the event_count value for the current row
            $event_count = $row['total_event_count'];
            $eventid = $row['id'];
            // Check if the counter is a multiple of 5 or is equal to 1
            if ($counter % 7 == 1 || $counter == 1) {
                // If it is, close the previous col-md-3 div and open a new one
                if ($counter != 1) {
                    echo '</div>'; // Close the previous col-md-3 div
                }
                // Determine the number of columns based on the row count
                $numColumns = $row_count <= 7 ? 12 : 6;
                echo '<div class="col-md-' . $numColumns . ' mx-auto mt-2">'; // Open a new col-md div with margin-top

            
            }

            // Change date format
            $date = date("Y F j, l", strtotime($row['date']));
            $upcomingDate = date("Y-m-d", strtotime($row['date']));
            echo '<div class="mt-3">';
            // Output each event row
            echo '<table class="table rounded smaller-table-upcoming" id="event_table">';
                echo '<thead class="table" style="padding:5px;>';
                // Output the table header content
                echo '<tr>';
                echo    '<th style="padding: 1px;">';
                // Add icon based on the event name
      
                echo        '<span style="font-size: 120%;font-weight:bold;color:#3D3B40;">' . $date . '</span>'; // Add the class 'smaller-title'
                echo    '</th>';
                echo '</tr>';
            echo '</thead>';

            echo '<tbody>';

            if ($_SESSION['role'] == 1 || $_SESSION['role'] == 3) 
            {
                // Get the roleid from the URL
                if (!isset($_GET['role'])) 
                {
                    $roleid = 1;
                }
                // Get the current URL
                $current_url = $_SERVER['REQUEST_URI'];

                // Check if the current URL contains 'userid'
                if (strpos($current_url, 'sectionid') !== false) {
                    $dateQuery = "SELECT * , e.id as eventid
                    FROM events e
                    INNER JOIN user u ON u.id = e.prepared_by 
                    WHERE e.event_type = 1 AND e.date = '$upcomingDate' AND  u.section = '$sectionid'
                    ORDER BY e.date ASC, e.time ASC";
                    
                
                }else{
                    $dateQuery = "SELECT *, e.id as eventid
                    FROM events e
                    INNER JOIN user u ON u.id = e.prepared_by 
                    WHERE e.is_Display = 1 AND e.event_type = 1 AND e.date = '$upcomingDate'
                    ORDER BY e.date ASC, e.time ASC";
                
                }
            }
            else
            {
                $dateQuery = "SELECT * FROM events 
                WHERE event_type = 1 AND date = '$upcomingDate' 
                AND prepared_by = " . $_SESSION['userid'] . " ORDER BY date ASC, time ASC";
            }

            $date_result = mysqli_query($conn, $dateQuery);
            while ($rowDate = mysqli_fetch_assoc($date_result)) 
            {
                // Convert time to 12-hour format
                $time_12_hour = date("h:i A", strtotime($rowDate['time']));
                $title = $rowDate['title'];
                $location = $rowDate['location'];
                $my_eventid = $rowDate['eventid'];
                $incharge = $rowDate['incharge'];
                // Output the table body content
                echo '<tr>';
                echo '<td class="smaller-text" style="font-size: 100%;padding: 1px;">';
                IconForEvent($my_eventid);
                echo '<span style="color: #231c35!important;font-weight:bold;">' . $time_12_hour . ' - </span><b><a style="color: #118ab2;">' . $title . '</a></b>, ' . VenueColorCoding1($location) .', ' . $incharge;
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
       
</div>
<?php
if ($_SESSION['COUNT_SESSION']===0)
{
    echo '<div class="no-events">NO EVENTS</div>';
}
?>
<style>
    .no-events {
    font-size: 24px;
    font-weight: bold;
    color: red!important;
    text-align: center;
    margin-top: 50px; /* Adjust as needed */
}

</style>
    </div>
    <?php
if ($_SESSION['COUNT_SESSION']>0)
{
echo '</div>';
}
?>




<?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 3): ?>
    <div class="container mt-3 ml-2 mr-2" id="custom-container0" style="width: 12%; overflow-y: auto;display: none;">
<?php else: ?>
    <!-- For non-admin users, hide the container -->
    <div class="container mt-3 ml-2 mr-2" id="custom-container0" style="width: 12%; overflow-y: auto; display: none; ">
<?php endif; ?>


  <style>

.zoomable-image {
        transition: transform 0.3s; /* Add a smooth transition effect */
    }

    .zoomable-image:hover {
        transform: scale(1.1); /* Increase the scale on hover (adjust the value as needed) */
    }

    
    .user-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 10px;
        }
        .user-name {
            margin-top: 5px;
            font-size:12px;
        }

        #custom-container0 {
        overflow: auto; /* Enable scrolling */
        scrollbar-width: none; /* Hide scrollbar for Firefox */
        -ms-overflow-style: none; /* Hide scrollbar for IE/Edge */
    }

    /* Hide scrollbar for WebKit (Chrome, Safari) */
    #custom-container0::-webkit-scrollbar {
        display: none;
    }

    #custom-container1 {
        overflow: auto; /* Enable scrolling */
        scrollbar-width: none; /* Hide scrollbar for Firefox */
        -ms-overflow-style: none; /* Hide scrollbar for IE/Edge */
    }

    /* Hide scrollbar for WebKit (Chrome, Safari) */
    #custom-container1::-webkit-scrollbar {
        display: none;
    }
  </style>
<script>
    // Function to set max-height of the container based on screen height
    function setMaxHeight() {
        var screenHeight = window.innerHeight; // Get the height of the screen
        var container = document.getElementById('custom-container0'); // Get the container element
        var containerTopMargin = 50; // Set a margin for the container from the top

        // Calculate the max height of the container
        var maxHeight = screenHeight - containerTopMargin;

        // Set the max-height property of the container
        container.style.maxHeight = maxHeight + 'px';
    }

    // Call the function when the window is resized
    window.addEventListener('resize', setMaxHeight);

    // Call the function initially to set the max-height based on the initial screen height
    setMaxHeight();
</script>
  <!-- View All Icon -->
  <div class="user-container" style="position: sticky; top: 0;">
        <div class="zoomable-image" onclick="changeUserId(0, 1)" style="background-color:#96cdee;width:150px;">
            <i class="fas fa-list" style="font-size: 50px; color: #08324b; cursor:pointer;"></i>
            <div class="user-name text-center" style="color:white;">ATG View</div>
        </div>
      
    </div>
    <?php
    // Include the database connection file
    include '../databases/connection/db.php';

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch all users from the database
    $user_query = "SELECT DISTINCT s.name, role, s.id, u.profile_photo FROM user u
    INNER JOIN section s ON s.id = u.section
    WHERE u.id NOT IN(14, 15, 16, 17, 40) ORDER BY s.name ASC";
    $user_result = mysqli_query($conn, $user_query);
    //echo $user_query ;
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        // Loop through each user
        while ($user = mysqli_fetch_assoc($user_result)) 
        {
            // Check if the user has an image path
            $imageSrc = 'images/blank_image.png';

            // Output the circular image HTML
            ?>
                        <div class="user-container">
                            <div class="zoomable-image" onclick="changeUserId(<?php echo $user['id']; ?>, <?php echo $user['role']; ?>)" style="color: #1e6b99; cursor: pointer; width:50px; height:50px;">
            <?php 
                            if (!empty($user['profile_photo'])) {
            ?>
                                <img id="userIcon_<?php echo $user['id']; ?>" src="../uploads/<?php echo $user['profile_photo']; ?>" alt="Profile Picture" style="width:50px; height:50px;">
            <?php 
                            } else {
            ?>
                                <i id="userIcon_<?php echo $user['id']; ?>" class="fas fa-user-circle" style="font-size: 50px; cursor: pointer;"></i>
            <?php 
                            }
            ?>
                </div>
                <div class="user-name text-center" style="color:white;"><?php echo $user['name']; ?></div>
            </div>
<?php
        }
    } else {
        // Handle case where no users are retrieved
        echo "Failed to retrieve users.";
    }
    // Close the database connection
    mysqli_close($conn);
?>

    <script>
            // Function to save the scroll position to localStorage
            function saveScrollPosition() {
            var container = document.getElementById('custom-container0');
            localStorage.setItem('scrollPosition', container.scrollTop);
        }

        // Function to restore the scroll position from localStorage
        function restoreScrollPosition() {
            var container = document.getElementById('custom-container0');
            var scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition !== null) {
                container.scrollTop = scrollPosition;
            }
        }

    window.onload = function() {
        // Parse the URL to extract userId and role parameters
        var urlParams = new URLSearchParams(window.location.search);
        var sectionId = urlParams.get('sectionid');
        var role = urlParams.get('role');

        // Call the changetheme function with the extracted sectionId and role
        changetheme(sectionId, role);
        if(role == 1)
        {
        // Show the custom-container0 div after the page has loaded
        document.getElementById('custom-container0').style.display = 'block';
        }
        
        // Restore the scroll position
        restoreScrollPosition();
        };

    // Save the scroll position when the page is unloaded
    window.onbeforeunload = function() {
            saveScrollPosition();
        };
    var selectedSectionId = null;

    function changetheme(sectionid, role) {
        // Reset the color of the previously selected image
        if (selectedSectionId !== null) {
            var prevIcon = document.getElementById('userIcon_' + selectedSectionId);
            if (prevIcon) {
                prevIcon.style.color = '#1e6b99';    
                prevIcon.style.border = 'none'; // Remove previous border
            }
        }

        // Change the color of the clicked image
        var currentIcon = document.getElementById('userIcon_' + sectionid);
        if (currentIcon) {
            currentIcon.style.color = '#021a29';
            currentIcon.style.border = '2px solid #436850'; // Add solid border
            selectedSectionId = sectionid;
        }
    }

    function changeUserId(sectionid, role) {
        // Get the current URL
        var currentUrl = window.location.href;

        // Split the URL by '?' to separate the base URL from the query string
        var urlParts = currentUrl.split('?');

        // Get the base URL
        var baseUrl = urlParts[0];

        // Construct the new URL
        var newUrl = baseUrl;

        // Add the sectionid parameter if sectionid is not 0
        if (sectionid !== 0) {
            newUrl += '?sectionid=' + sectionid + '&role=' + role;
        }
        
        // Redirect to the new URL
        window.location.href = newUrl;

        changetheme(sectionid, role);
    }

    </script>

    
<script>
         // JavaScript to populate event details in the modal fields when edit icon is clicked
         document.querySelectorAll('#edit-event').forEach(item => {
                    item.addEventListener('click', event => {
                        const eventId = item.getAttribute('data-event-id');
                        //alert(eventId);
                        // Send AJAX request to fetch event details
                        fetch('../databases/queries/get_event_details.php?id=' + eventId)
                            .then(response => response.json())
                            .then(data => {
                                // Populate the modal fields with the fetched data
                                document.getElementById('editEventId').value = data.id;
                                document.getElementById('editEventName').value = data.event_name;
                                document.getElementById('editTitle').value = data.title;
                                document.getElementById('editHost').value = data.host;
                                document.getElementById('editDate').value = data.date;
                                document.getElementById('editTime').value = data.time;

                                // Check if the location is not in the editVenueSelect dropdown
                                const editVenueSelect = document.getElementById('editVenueSelect');
                                const locationNotFound = ![...editVenueSelect.options].some(option => option.value === data.location);
                                if (locationNotFound) {
                                    // Set the location input value and display it
                                    document.getElementById('editLocation').value = data.location;
                                    document.getElementById('editLocation').style.display = 'block';
                                    editVenueSelect.value = "Others";
                                } else {
                                    // Set the dropdown value to the location
                                    editVenueSelect.value = data.location;
                                    // Hide the location input
                                    document.getElementById('editLocation').style.display = 'none';
                                }

                                document.getElementById('editIncharge').value = data.incharge;
                                document.getElementById('editContact_number').value = data.contact_number;
                                document.getElementById('editDetails').value = data.details;
                            })
                            .catch(error => console.error('Error:', error));
                    });
                });
            // JavaScript to handle "Save Changes" button click
            document.getElementById('editEvent').addEventListener('click', function() {
                    // Collect the form data
                    const formData = new FormData(document.getElementById('editEventForm'));

                    // Send AJAX request to update event details
                    fetch('../databases/queries/update_event.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle response (e.g., show success message, update UI)
                        console.log(data);
                        // Reload the page or update UI as needed
                        location.reload(); // For example, reload the page
                    })
                    .catch(error => console.error('Error:', error));
                });
    </script>

</div>
    </div>
    </div>

    

<div id="divSuguan">

</div>
    
</div>











</body>
</html>
