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
    //echo $eventId;
    
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
        case "MPH":
            echo '<b><a style="color: #519eaa;">' . $venue . '</a></b><br>';
            break;
        case "Chapel 1":
            echo '<b><a style="color: #d09e6a;">' . $venue . '</a></b><br>';
            break;
        case "Chapel 4":
            echo '<b><a style="color: #248fb2;">' . $venue . '</a></b><br>';
            break;
        case "Studio A":
            echo '<b><a style="color: #5c65bd;">' . $venue . '</a></b><br>';
            break;
        case "Studio B":
            echo '<b><a style="color: #af479f;">' . $venue . '</a></b><br>';
            break;
        case "Studio C":
            echo '<b><a style="color: #0083cd;">' . $venue . '</a></b><br>';
            break;
        case "6F Conference Room":
            echo '<b><a style="color: #238fb6;">' . $venue . '</a></b><br>';
            break;
        case "3F Common Area":
            echo '<b><a style="color: #5567a9;">' . $venue . '</a></b><br>';
            break;
        case "3F Conference Room":
            echo '<b><a style="color: #5c64ae;">' . $venue . '</a></b><br>';
            break;
        case "3F Language Room":
            echo '<b><a style="color: #b7be64;">' . $venue . '</a></b><br>';
            break;
        case "3F Dojo Room":
            echo '<b><a style="color: #c78171;">' . $venue . '</a></b><br>';
            break;
        case "Auditorium":
            echo '<b><a style="color: #7a5aab;">' . $venue . '</a></b><br>';
            break;
        case "Public Lobby":
            echo '<b><a style="color: #e0c750;">' . $venue . '</a></b><br>';
            break;
        default:
            echo '<b><a style="color: #6e9f8a;">' . $venue . '</a></b><br>';
            break;
    }
}

function VenueColorCoding1($venue)
{
    switch($venue)
    {
        case "MPH":
            return '<b><a style="color: #519eaa;">' . $venue . '</a></b>';
            break;
        case "Chapel 1":
            return '<b><a style="color: #d09e6a;">' . $venue . '</a></b>';
            break;
        case "Chapel 4":
            return '<b><a style="color: #248fb2;">' . $venue . '</a></b>';
            break;
        case "Studio A":
            return '<b><a style="color: #5c65bd;">' . $venue . '</a></b>';
            break;
        case "Studio B":
            return '<b><a style="color: #af479f;">' . $venue . '</a></b>';
            break;
        case "Studio C":
            return '<b><a style="color: #0083cd;">' . $venue . '</a></b>';
            break;
        case "6F Conference Room":
            return '<b><a style="color: #238fb6;">' . $venue . '</a></b>';
            break;
        case "3F Common Area":
            return '<b><a style="color: #5567a9;">' . $venue . '</a></b>';
            break;
        case "3F Conference Room":
            return '<b><a style="color: #5c64ae;">' . $venue . '</a></b>';
            break;
        case "3F Language Room":
            return '<b><a style="color: #b7be64;">' . $venue . '</a></b>';
            break;
        case "3F Dojo Room":
            return '<b><a style="color: #c78171;">' . $venue . '</a></b>';
            break;
        case "Auditorium":
            return '<b><a style="color: #7a5aab;">' . $venue . '</a></b>';
            break;
        case "Public Lobby":
            return '<b><a style="color: #e0c750;">' . $venue . '</a></b>';
            break;
        default:
            return '<b><a style="color: #6e9f8a;">' . $venue . '</a></b>';
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
            top: 12px;
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

</head>

<body>
<!-- Logout Button -->
<a href="#" data-toggle="modal" data-target="#confirmationModal" class="logout-btn btn btn-danger">Logout</a>

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
                                        $eventNamesQuery = "SELECT DISTINCT name FROM category ORDER BY id ASC";
                                        $eventNamesResult = mysqli_query($conn, $eventNamesQuery);

                                        // The variable $eventName should be defined earlier in your script
                                        // It could be the currently selected event name for comparison

                                        echo "<select class='form-control' id='eventName' name='eventName' " . $isRequired . " style='width: 100%;'>";
                                        echo "<option value='0' disabled selected>Select category</option>";

                                        while ($eventNameRow = mysqli_fetch_assoc($eventNamesResult)) {
                                            $eventNamesql = $eventNameRow['name'];
                                            // Output each event name as an option in the dropdown
                                            echo "<option value='" . htmlspecialchars($eventNamesql, ENT_QUOTES, 'UTF-8') . "'" . ($eventNamesql == $eventName ? ' selected' : '') . ">" . htmlspecialchars($eventNamesql, ENT_QUOTES, 'UTF-8') . "</option>";
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

                                <div class="form-group">
                                    <label for="time">Time:</label>
                                    <input type="time" id="time" name="time" class="form-control" required>
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

                                    <label for="venueSelect">Venue:</label>
                                    <!-- Select dropdown with colored options -->
                                    <select class="form-control" id="venueSelect" name="venueSelect" required onchange="toggleLocationInput1()">
                                        <option value="0" disabled selected>Select a venue</option>
                                        <option value="1">TRG Conference room</option>
                                        <option value="2">ECD Office</option>
                                        <option value="3">SFM - TRG Satellite Office</option>
                                        <option value="Others" class="Others">Others</option>
                                    </select>

                                    
                                    <!-- Input field for location -->
                                    <input type="text" id="location" name="location" class="form-control mt-2" placeholder="Enter venue" style="display: none;">

                                    <script>
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
                                        // Assuming you have already established a connection to the database in $conn

                                        // Fetch event names from the events table
                                        $edit_eventNameQuery = "SELECT DISTINCT name FROM category ORDER BY id ASC";
                                        $edit_eventNamesResult = mysqli_query($conn, $edit_eventNameQuery);

                                        // The variable $eventName should be defined earlier in your script
                                        // It could be the currently selected event name for comparison

                                        echo "<select class='form-control' id='editEventName' name='editEventName' " . $isRequired . " style='width: 100%;'>";
                                        echo "<option value='0' disabled selected>Select category</option>";

                                        while ($eventNameRow = mysqli_fetch_assoc($edit_eventNamesResult)) {
                                            $eventNamesql = $eventNameRow['name'];
                                            // Output each event name as an option in the dropdown
                                            echo "<option value='" . htmlspecialchars($eventNamesql, ENT_QUOTES, 'UTF-8') . "'" . ($eventNamesql == $eventName ? ' selected' : '') . ">" . htmlspecialchars($eventNamesql, ENT_QUOTES, 'UTF-8') . "</option>";
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

                    <label for="location">Venue:</label>
                    <!-- Select dropdown with colored options -->
                    <select class="form-control" id="editVenueSelect" name="editVenueSelect" required onchange="toggleLocationInput()">
                    <option value="0" disabled selected>Select a venue</option>
                    <option value="1">TRG Conference room</option>
                    <option value="2">ECD Office</option>
                    <option value="3">SFM - TRG Satellite Office</option>
                    <option value="Others" class="Others">Others</option>
                    </select>

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



    
<!-- Main content area -->
<div class="d-contents mt-2" id="mainContent">
    <div class="row">
        <div id="dynamicContent" class="col-md-12 mt-2" style="display:none;">
            
            <input type="hidden" id="savetype" name="savetype" value="daily">
            <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">


            <div class="col-md-12 mt-2">
                <div class="row justify-content-center">
                    <div class="col-md-12">
            
                    <div class="d-flex align-items-center mb-4">
                        <a href="../views/sched.php" class="btn btn-link"  style="display:none;">
                            <i class="fas fa-arrow-left"></i> <!-- Font Awesome arrow-left icon -->
                        </a>
                        <h4 class="mb-0 ml-2"  style="display:none;">TRG Events List</h4>
                    </div>


                    <div class="d-flex align-items-center md-6">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#EventAddModal" style="margin-bottom: 10px;">
                            <i class="fas fa-plus"></i> Event<!-- Font Awesome folder-plus icon -->
                        </button>

                        <!-- Button to open the iframe modal -->
                        <button type="button" class="btn btn-success" id="viewButton" style="margin-bottom: 10px; margin-left: 10px;display:none;">
                            <i class="fas fa-eye"></i> Event Viewer
                        </button>

                        <!-- Button to toggle past events -->
                        <button type="button" class="btn btn-success" id="pastEventsButton" style="margin-bottom: 10px; margin-left: 10px;">
                            <i class="fas fa-eye"></i> View Past Events
                        </button>

                        <!-- Section to display past events (initially hidden) -->
                        <div id="pastEventsSection" style="display: none;">
                            <!-- Past events content goes here -->
                            <!-- You can populate this section with past events using PHP or JavaScript -->
                        </div>

                        <!-- Button to print table data -->
                        <button type="button" class="btn btn-success" id="printButton" style="margin-bottom: 10px; margin-left: 10px;">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                <script>
                    // Function to print the table data
                    function printTable() {
                        // Hide the print button and past events section for printing
                        document.getElementById("printButton").style.display = "none";
                        document.getElementById("pastEventsSection").style.display = "none";

                        // Open the print dialog
                        window.print();

                        // Restore the visibility of the print button and past events section after printing
                        document.getElementById("printButton").style.display = "block";
                        document.getElementById("pastEventsSection").style.display = "block";
                    }

                    // Add event listener to the print button
                    document.getElementById("printButton").addEventListener("click", printTable);
                </script>




                <script>
                    // Function to toggle visibility of past events section and update URL
                    function togglePastEvents() {
                        var pastEventsVisible = !getParameterByName('show_past_events');
                        var url = window.location.href.split('?')[0]; // Get the base URL without query parameters
                        if (pastEventsVisible) {
                            url += '?show_past_events=1'; // Add query parameter to show past events
                        }
                        window.location.href = url; // Redirect to the updated URL
                    }

                    // Function to get query parameter value by name
                    function getParameterByName(name, url) 
                    {
                        if (!url) url = window.location.href;
                        name = name.replace(/[\[\]]/g, '\\$&');
                        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                            results = regex.exec(url);
                        if (!results) return null;
                        if (!results[2]) return '';
                        return decodeURIComponent(results[2].replace(/\+/g, ' '));
                    }

                    // Add event listener to the button to toggle past events visibility
                    document.getElementById("pastEventsButton").addEventListener("click", togglePastEvents);

                    // Check if past events section is initially visible and adjust button text accordingly
                    if (getParameterByName('show_past_events')) {
                        document.getElementById("pastEventsButton").innerText = "Hide Past Events";
                    }
                </script>



                <!-- JavaScript to create modal and iframe -->
                <script>
                    document.getElementById('viewButton').addEventListener('click', function() {
                        // Create the modal overlay
                        var modalOverlay = document.createElement('div');
                        modalOverlay.style.position = 'fixed';
                        modalOverlay.style.top = '0';
                        modalOverlay.style.left = '0';
                        modalOverlay.style.width = '100%';
                        modalOverlay.style.height = '100%';
                        modalOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // Semi-transparent black background
                        modalOverlay.style.display = 'flex';
                        modalOverlay.style.justifyContent = 'center';
                        modalOverlay.style.alignItems = 'center';
                        modalOverlay.style.zIndex = '1000'; // Ensure it appears above other content

                        // Create the iframe container
                        var iframeContainer = document.createElement('div');
                        iframeContainer.style.backgroundColor = '#fff'; // White background
                        iframeContainer.style.padding = '20px'; // Add some padding
                        iframeContainer.style.borderRadius = '8px'; // Rounded corners
                        iframeContainer.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)'; // Drop shadow
                        iframeContainer.style.width = '80%'; // Adjust width as needed

                        // Calculate the height of the iframe
                        var screenHeight = window.innerHeight;
                        var iframeHeight = screenHeight * 0.85; // 75% of the screen height
                        iframeContainer.style.height = iframeHeight + 'px'; // Set the height dynamically

                        // Create the close button
                        var closeButton = document.createElement('button');
                        closeButton.innerHTML = '&times;'; // Times symbol (close icon)
                        closeButton.style.position = 'absolute';
                        closeButton.style.top = '10px';
                        closeButton.style.right = '10px';
                        closeButton.style.border = 'none';
                        closeButton.style.background = '#f44336'; // Red background color
                        closeButton.style.color = '#fff'; // White text color
                        closeButton.style.width = '30px'; // Set width and height to create a circle
                        closeButton.style.height = '30px';
                        closeButton.style.borderRadius = '50%'; // Make it a circle
                        closeButton.style.fontSize = '16px'; // Increase font size
                        closeButton.style.lineHeight = '1'; // Center the symbol vertically
                        closeButton.style.textAlign = 'center'; // Center the symbol horizontally
                        closeButton.style.cursor = 'pointer';

                        // Close button click event handler
                        closeButton.addEventListener('click', function() {
                            // Remove the modal overlay and iframe container
                            document.body.removeChild(modalOverlay);
                        });

                        // Create the iframe
                        var iframe = document.createElement('iframe');
                        iframe.src = '../views/sched.php'; // Set the src attribute to index.php
                        iframe.style.width = '100%';
                        iframe.style.height = '100%';
                        iframe.style.border = 'none'; // Remove border
                        iframe.setAttribute('allowfullscreen', ''); // Allow fullscreen mode

                        // Append the close button and iframe to the iframe container
                        iframeContainer.appendChild(closeButton);
                        iframeContainer.appendChild(iframe);

                        // Append the iframe container to the modal overlay
                        modalOverlay.appendChild(iframeContainer);

                        // Append the modal overlay to the document body
                        document.body.appendChild(modalOverlay);
                    });
                </script>

                    <div class="table-responsive" style="margin-top:20px;">

                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                <th>#</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Person In charge</th>
                                    <th>Contact #</th>
                                    <th>Details</th>
                                    <?php
                                        // Check if the session role is equal to 1
                                        if ($_SESSION['role'] == 1) {
                                            // Display the "Display?" header
                                            echo "<th>Display?</th>";
                                        }
                                    ?>
                                    <th class="table-action-column">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                // Initialize row counter
                                $rowNumber = 1;
                                // Check connection
                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }

                                // Get today's date
                                $today = date("Y-m-d");

                                // Check if past events section is visible based on the URL query parameter
                                $showPastEvents = isset($_GET['show_past_events']) && $_GET['show_past_events'] == '1';

                                // Initialize the WHERE clause for the query based on the user's role
                                $whereClause = $_SESSION['role'] == 1 ? "" : "AND prepared_by = " . $_SESSION['userid'];

                                // Add condition to exclude events with event_name 'weekly_update'
                                $whereClause .= " AND event_name != 'weekly_update'";

                                // Build the query based on the user's role and the visibility of past events
                                $today_query = $showPastEvents 
                                    ? "SELECT * FROM events WHERE date < '$today' $whereClause ORDER BY id DESC, date DESC" 
                                    : "SELECT * FROM events WHERE date >= '$today' $whereClause ORDER BY id DESC, date DESC";

                                $today_result = mysqli_query($conn, $today_query);

                                while ($row = mysqli_fetch_assoc($today_result)) {
                                    // Adjusting row values
                                    $eventName = $row['event_name'];
                                    $title = $row['title'];
                                
                                            // Change date format
                                $date = date("F j, Y", strtotime($row['date']));
                                    // Convert time to 12-hour format
                                    $time_12_hour = date("h:i A", strtotime($row['time']));
                                    $location = $row['location'];
                                    $incharge = $row['incharge'];
                                    $contact_number = $row['contact_number'];
                                    $details = $row['details'];
                                    $eventId = $row['id']; // Add event ID
                                    $display = $row['is_display']; 

                                    echo "<tr>";
                                    echo "<td>" . $rowNumber . "</td>";
                                    echo "<td>" . $eventName . "</td>";
                                    echo "<td>" . $title . "</td>";
                                    echo "<td>" . $date . "</td>";
                                    echo "<td>" . $time_12_hour . "</td>";
                                    echo "<td>" . $location . "</td>";
                                    echo "<td>" . $incharge . "</td>";
                                    echo "<td>" . $contact_number . "</td>";
                                    
                                    echo "<td>" . nl2br($details) . "</td>";
                                    
                                    // Check if the session role is equal to 1
                                    if ($_SESSION['role'] == 1) {
                                        // Display the checkbox for "Display?" column
                                        echo "<td class='text-center'><input type='checkbox' class='display-checkbox' name='displayCheckbox[]' data-id='" . $eventId . "' value='1' " . ($display == 1 ? 'checked' : '') . "></td>";
                                    }
                                    // Edit icon to trigger modal
                                    echo "<td class='table-action-column'><a href='#' class='edit-event' id='edit-event' data-toggle='modal' data-target='#EventEditModal' data-event-id='" . $eventId . "'><i class='fas fa-edit'></i></a>";
                                            // Delete icon with JavaScript function to handle deletion
                                            echo "<a href='#' onclick='deleteEvent(" . $eventId . ")'><i class='fas fa-trash'></i></a>
                                        </td>";
                                echo "</tr>";
                                // Increment row number for the next iteration
                            $rowNumber++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript code to handle checkbox changes -->
            <script>
            $(document).ready(function() {
                // Listen for changes in checkboxes with the class 'display-checkbox'
                $('.display-checkbox').change(function() {
                    // Get the value of the checkbox (1 if checked, 0 if unchecked)
                    var isChecked = $(this).prop('checked') ? 1 : 0;
                
                    // Get the event ID from the data-id attribute of the checkbox
                    var eventId = $(this).data('id');
                    
                    // Make an AJAX request to update the is_display column in the events table
                    $.ajax({
                        url: '../databases/queries/update_display.php',
                        type: 'POST',
                        data: {
                            eventId: eventId,
                            isChecked: isChecked
                        },
                        success: function(response) {
                            // Handle success response
                            console.log('Checkbox updated successfully');
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            console.error('Error updating checkbox:', error);
                        }
                    });
                });
            });

            function deleteEvent(eventId) {
                    if (confirm("Are you sure you want to delete this event?")) {
                        // Send AJAX request to delete the event
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "../databases/queries/delete_event.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Reload the page after successful deletion
                                location.reload();
                            }
                        };
                        xhr.send("eventId=" + eventId);
                    }
                }

                function cancel() {
                    // Redirect back to index.php
                    window.location.href = "../views/sched.php";
                }

               
            </script>
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
        echo '<b><span style="color: #231c35;margin-bottom: 0;">' . $time_12_hour . '</span></b><br>';
        VenueColorCoding($location);
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
    if (!isset($_GET['role'])) {
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
 echo '<h4 class="mb-1 ml-1 mt-3" style="font-weight:bold;">' . ($counter == 0 ? 'Tomorrow (' . $row_count . ') <br> ' . $date . '' : '<p style="color: rgba(0, 0, 0, 0);">Today</p>') . '</h4>';
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
        echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b><br>';
        VenueColorCoding($location);
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
            $today_query = "SELECT date, COUNT(*) AS event_count, e.id,
            (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events 
            WHERE is_display = 1 AND event_type = 1 AND date > '$day_after_tomorrow' GROUP BY date) AS subquery) AS total_event_count
            FROM events e
            INNER JOIN user u ON u.id = e.prepared_by 
            WHERE is_display = 1 AND event_type = 1 AND date > '$day_after_tomorrow' AND e.prepared_by NOT IN(14, 40) 
            GROUP BY date ORDER BY date ASC";
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
         // echo $today_query;
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

            if ($_SESSION['role'] == 1 || $_SESSION['role'] == 3) {
            
            // Get the roleid from the URL
            if (!isset($_GET['role'])) {
                $roleid = 1;
                }

        // Get the current URL
        $current_url = $_SERVER['REQUEST_URI'];

        // Check if the current URL contains 'userid'
        if (strpos($current_url, 'sectionid') !== false) {
            $dateQuery = "SELECT * 
            FROM events e
            INNER JOIN user u ON u.id = e.prepared_by 
            WHERE e.event_type = 1 AND e.date = '$upcomingDate' AND  u.section = '$sectionid'
            ORDER BY e.date ASC, e.time ASC";
           
        }else{
            $dateQuery = "SELECT * 
            FROM events e
            INNER JOIN user u ON u.id = e.prepared_by 
            WHERE e.is_Display = 1 AND e.event_type = 1 AND e.date = '$upcomingDate'
            ORDER BY e.date ASC, e.time ASC";
        }
             
            }
            else{
            $dateQuery = "SELECT * FROM events 
            WHERE event_type = 1 AND date = '$upcomingDate' 
            AND prepared_by = " . $_SESSION['userid'] . " ORDER BY date ASC, time ASC";
            }
           
           
            $date_result = mysqli_query($conn, $dateQuery);
            while ($rowDate = mysqli_fetch_assoc($date_result)) {
                // Convert time to 12-hour format
                $time_12_hour = date("h:i A", strtotime($rowDate['time']));
                $title = $rowDate['title'];
                $location = $rowDate['location'];
                $my_eventid = $rowDate['id'];
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

    


    
</div>











</body>
</html>
