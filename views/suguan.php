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
        return 0; 
    }
}

?>

<?php
// Function to output the thead with background color based on event name
function outputTableHeader($eventName) {
    switch ($eventName) {
        case 'Suguan':
            echo '<thead class="table" style="background-color: #BED7DC;" padding:5px; color:#e8e8e4;!important>';
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

    <title>Event Scheduler - Suguan</title>
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
            background-color: #7ABA78!important; /* Default background color */
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
    .container {
            margin-top: 2rem;
            margin-left: 2rem;
            margin-right: 2rem;
            border: 1px solid #ccc; /* Example border */
        }

        /* Set initial heights */
        #custom-container1,
        #custom-container2,
        #custom-container3,
        #custom-container4 {
            height: calc(100vh - 4rem); /* Adjusting for the margins */
        }
    </style>

<script>
        function adjustContainerHeight() {
            var containers = [
                document.getElementById('custom-container1'),
                document.getElementById('custom-container2'),
                document.getElementById('custom-container3'),
                document.getElementById('custom-container4')
            ];

            containers.forEach(function(container) {
                container.style.height = 'calc(100vh - 4rem)'; // Adjusting for the margins
            });
        }

        // Adjust the container heights on window resize
        window.addEventListener('resize', adjustContainerHeight);

        // Initial adjustment
        adjustContainerHeight();
    </script>
    <!-- <script>
        // Redirect to sched.php after 1 minute (60000 milliseconds)
        setTimeout(function() {
            window.location.href = 'sched.php';
        }, 60000);
    </script> -->

    
</head>

<body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        enterFullscreen();
    });
</script>
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
                            <input type="hidden" id="savetype" name="savetype" value="suguanevent">
                            <input type="hidden" id="current_url" name="current_url" value="suguan.php">

                                <div class="form-group">
                                    <label for="title">Name:</label>
                                    <input type="text" id="title" name="title" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="date">Date:</label>
                                    <input type="date" id="date" name="date" class="form-control" required>
                                </div>

                                <div class="form-group" id="formtime">
                                    <label for="time">Time:</label>
                                    <input type="time" id="time" name="time" class="form-control" required>
                                </div>

                                <div class="form-group" id="formlocal">
                                    <label for="addlocal">Local:</label>
                                    <!-- Input field for location -->
                                    <input type="text" id="addlocal" name="addlocal" class="form-control mt-2" placeholder="Enter Local">
                                </div>

                                <div class="form-group" id="formdistrict">
                                    <label for="adddistrict">District:</label>
                                    <!-- Input field for location -->
                                    <input type="text" id="adddistrict" name="adddistrict" class="form-control mt-2" placeholder="Enter District">
                                </div>

                                <div class="form-group">
                                    <label for="addgampanin">Gampanin:</label>
                                    <select id="addgampanin" name="addgampanin" class="form-control">
                                        <option value="0" disabled selected>Select gampanin</option>
                                        <option value="1">Sugo</option>
                                        <option value="2">Sugo 1</option>
                                        <option value="3">Sugo 2</option>
                                        <option value="4">Reserba</option>
                                        <option value="5">Reserba 1</option>
                                        <option value="6">Reserba 2</option>
                                    </select>
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
                <input type="hidden" id="savetype" name="savetype" value="suguanevent">
                <input type="hidden" id="current_url" name="current_url" value="suguan.php">
                    <input type="hidden" id="editEventId" name="editEventId">
                    
                        <div class="form-group">
                            <label for="editTitle">Name:</label>
                            <input type="text" id="editTitle" name="editTitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editDate">Date:</label>
                            <input type="date" id="editDate" name="editDate" class="form-control" required>
                        </div>

                        <div class="form-group" id="formtime">
                            <label for="editTime">Time:</label>
                            <input type="time" id="editTime" name="editTime" class="form-control" required>
                        </div>

                        <div class="form-group" id="formlocal">
                            <label for="editlocal">Local:</label>
                            <!-- Input field for location -->
                            <input type="text" id="editlocal" name="editlocal" class="form-control mt-2" placeholder="Enter Local">
                        </div>

                        <div class="form-group" id="formdistrict">
                            <label for="editdistrict">District:</label>
                            <!-- Input field for location -->
                            <input type="text" id="editdistrict" name="editdistrict" class="form-control mt-2" placeholder="Enter District">
                        </div>

                        <div class="form-group">
                            <label for="editgampanin">Gampanin:</label>
                            <select id="editgampanin" name="editgampanin" class="form-control">
                                <option value="0" disabled selected>Select gampanin</option>
                                <option value="1">Sugo</option>
                                <option value="2">Sugo 1</option>
                                <option value="3">Sugo 2</option>
                                <option value="4">Reserba</option>
                                <option value="5">Reserba 1</option>
                                <option value="6">Reserba 2</option>
                            </select>
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
$today = new DateTime();

// Calculate the current week's Wednesday
$wednesday = new DateTime();
$wednesday->setISODate($today->format('o'), $today->format('W'), 3);

// Format the date as 'Y-m-d'
$wednesdayDate = $wednesday->format('Y-m-d');

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
    WHERE  event_type = 2 AND date = '$wednesdayDate' AND e.prepared_by NOT IN(14, 40) 
    AND u.section = '$sectionid' 
    ORDER BY time ASC";
   }
   else{
    //echo "2";
    $today_query = "SELECT *,e.id as event_id FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE is_display = 1 AND event_type = 2 AND date = '$wednesdayDate' AND e.prepared_by NOT IN(14, 40) 
    ORDER BY time ASC";
   }



    }
    else{
       // echo "3";
        $today_query = "SELECT *,e.id as event_id FROM events e WHERE e.event_type = 2 AND e.date = '$wednesdayDate' AND e.prepared_by = " . $_SESSION['userid'] . " ORDER BY e.time ASC";
    }
   
//echo $today_query;
    $today_result = mysqli_query($conn, $today_query);

    // Get the row count
    $row_count = mysqli_num_rows($today_result);


    $_SESSION['COUNT_SESSION'] = $row_count ;
    // Counter to keep track of the number of tables created
    $counter = 0;
    
    $wednesdayDate = new DateTime();
    $wednesdayDate->setISODate($wednesdayDate->format('o'), $wednesdayDate->format('W'), 3); // 3 stands for Wednesday
    echo '<h4 class="mb-1 ml-1 mt-2" style="font-weight:bold;">' . $wednesdayDate->format("Y F j, l") . '</h4>';
    
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
         echo '<span class="text-black">' . $title . '</span>';
         echo '</th>';
         echo '</tr>';
         echo '</thead>';
         echo '<tbody>';
 
         // Convert time to 12-hour format
         $time_12_hour = date("h:i A", strtotime($row['time']));
 
         //Adjusting row values
         $incharge = $row['incharge'];
         $contact_number = $row['contact_number'];
         $host = $row['host'];
         $location = $row['location'];
         $district = $row['district'];
         $details = $row['details'];
 
         // Output the table body content
         echo '<tr>';
            echo '<td style="font-size: 17px;padding: 1px;"">';
 
            echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b> ';
            echo '<b><span style="color: #519eaa;">' . $location . '</span></b>,';
            // Assuming you have a variable $value that holds the value you want to check
            // Replace $value with the actual variable you are using
            switch ($details) {
                case 1:
                    $details = 'Sugo';
                    break;
                case 2:
                    $details = 'Sugo 1';
                    break;
                case 3:
                    $details = 'Sugo 2';
                    break;
                case 4:
                    $details = 'Reserba';
                    break;
                case 5:
                    $details = 'Reserba 1';
                    break;
                case 6:
                    $details = 'Reserba 2';
                    break;
                default:
                    $details = 'Unknown'; // Default value if none of the cases match
                    break;
            }

            echo '<span style="color: #151515;">' . $details . '</span><br>';
            echo '<span style="color: #151515; background-color: #F1F1F1;">' . $district . '</span><br>';
            echo '</td>';


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
    echo '<div class="no-events">NO SUGUAN</div>';
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
    // Get today's date
    $today = new DateTime();

    // Calculate the current week's Wednesday
    $thursday = new DateTime();
    $thursday->setISODate($today->format('o'), $today->format('W'), 4);

    // Format the date as 'Y-m-d'
    $thursdayDate = $thursday->format('Y-m-d');


    if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
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
      // echo "1";
    $sectionid = $_GET['sectionid'];
  
    $today_query = "SELECT *,e.id as event_id  FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE event_type = 2 AND date = '$thursdayDate' AND e.prepared_by NOT IN(14, 40) 
    AND u.section = '$sectionid' 
    ORDER BY time ASC";
   }
   else{
    $today_query = "SELECT *,e.id as event_id  FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE is_display = 1 AND event_type = 2 AND date = '$thursdayDate' AND e.prepared_by NOT IN(14, 40)  
    ORDER BY time ASC";
   }
    }
    else{
        $today_query = "SELECT *,e.id as event_id  FROM events e WHERE e.event_type = 2 AND e.date = '$thursdayDate' AND e.prepared_by = " . $_SESSION['userid'] . " ORDER BY e.time ASC";
    }

    $today_result = mysqli_query($conn, $today_query);

    // Get the row count
    $row_count = mysqli_num_rows($today_result);
    $_SESSION['COUNT_SESSION'] = $row_count ;
    // Change date format

    // Counter to keep track of the number of tables created
    $counter = 0;
    $thursdayDate = new DateTime();
    $thursdayDate->setISODate($thursdayDate->format('o'), $thursdayDate->format('W'), 4); // 3 stands for thursday
    echo '<h4 class="mb-1 ml-1 mt-2" style="font-weight:bold;">' . $thursdayDate->format("Y F j, l") . '</h4>';

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
      echo '<span class="text-black">' . $title . '</span>';
      echo '</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';

      // Convert time to 12-hour format
      $time_12_hour = date("h:i A", strtotime($row['time']));

      //Adjusting row values
      $incharge = $row['incharge'];
      $contact_number = $row['contact_number'];
      $host = $row['host'];
      $location = $row['location'];
      $district = $row['district'];
      $details = $row['details'];

      // Output the table body content
      echo '<tr>';
         echo '<td style="font-size: 17px;padding: 1px;"">';

         echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b> ';
         echo '<b><span style="color: #519eaa;">' . $location . '</span></b>,';
         // Assuming you have a variable $value that holds the value you want to check
         // Replace $value with the actual variable you are using
         switch ($details) {
             case 1:
                 $details = 'Sugo';
                 break;
             case 2:
                 $details = 'Sugo 1';
                 break;
             case 3:
                 $details = 'Sugo 2';
                 break;
             case 4:
                 $details = 'Reserba';
                 break;
             case 5:
                 $details = 'Reserba 1';
                 break;
             case 6:
                 $details = 'Reserba 2';
                 break;
             default:
                 $details = 'Unknown'; // Default value if none of the cases match
                 break;
         }

         echo '<span style="color: #151515;">' . $details . '</span><br>';
         echo '<span style="color: #151515; background-color: #F1F1F1;">' . $district . '</span><br>';
         echo '</td>';


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
    echo '<div class="no-events">NO SUGUAN</div>';
}
?>

</div>
<?php
if ($_SESSION['COUNT_SESSION']>0)
{
echo '</div>';
}
?>


<div class="container mt-2 ml-2 mr-2 custom-border" id="custom-container3">

<!-- <div class="row"> -->
    <?php
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

// Get today's date
$today = new DateTime();

// Calculate the current week's Saturday
$saturday = new DateTime();
$saturday->setISODate($today->format('o'), $today->format('W'), 6);

// Format the date as 'Y-m-d'
$saturdayDate = $saturday->format('Y-m-d');

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
    WHERE  event_type = 2 AND date = '$saturdayDate' AND e.prepared_by NOT IN(14, 40) 
    AND u.section = '$sectionid' 
    ORDER BY time ASC";
   }
   else{
    //echo "2";
    $today_query = "SELECT *,e.id as event_id FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE is_display = 1 AND event_type = 2 AND date = '$saturdayDate' AND e.prepared_by NOT IN(14, 40) 
    ORDER BY time ASC";
   }



    }
    else{
       // echo "3";
        $today_query = "SELECT *,e.id as event_id FROM events e WHERE e.event_type = 2 AND e.date = '$saturdayDate' AND e.prepared_by = " . $_SESSION['userid'] . " ORDER BY e.time ASC";
    }
   
//echo $today_query;
    $today_result = mysqli_query($conn, $today_query);

    // Get the row count
    $row_count = mysqli_num_rows($today_result);


    $_SESSION['COUNT_SESSION'] = $row_count ;
    // Counter to keep track of the number of tables created
    $counter = 0;
    
    $saturdayDate = new DateTime();
    $saturdayDate->setISODate($saturdayDate->format('o'), $saturdayDate->format('W'), 6); // 6 stands for saturday
    echo '<h4 class="mb-1 ml-1 mt-2" style="font-weight:bold;">' . $saturdayDate->format("Y F j, l") . '</h4>';
    
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
         echo '<span class="text-black">' . $title . '</span>';
         echo '</th>';
         echo '</tr>';
         echo '</thead>';
         echo '<tbody>';
 
         // Convert time to 12-hour format
         $time_12_hour = date("h:i A", strtotime($row['time']));
 
         //Adjusting row values
         $incharge = $row['incharge'];
         $contact_number = $row['contact_number'];
         $host = $row['host'];
         $location = $row['location'];
         $district = $row['district'];
         $details = $row['details'];
 
         // Output the table body content
         echo '<tr>';
            echo '<td style="font-size: 17px;padding: 1px;"">';
 
            echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b> ';
            echo '<b><span style="color: #519eaa;">' . $location . '</span></b>,';
            // Assuming you have a variable $value that holds the value you want to check
            // Replace $value with the actual variable you are using
            switch ($details) {
                case 1:
                    $details = 'Sugo';
                    break;
                case 2:
                    $details = 'Sugo 1';
                    break;
                case 3:
                    $details = 'Sugo 2';
                    break;
                case 4:
                    $details = 'Reserba';
                    break;
                case 5:
                    $details = 'Reserba 1';
                    break;
                case 6:
                    $details = 'Reserba 2';
                    break;
                default:
                    $details = 'Unknown'; // Default value if none of the cases match
                    break;
            }

            echo '<span style="color: #151515;">' . $details . '</span><br>';
            echo '<span style="color: #151515; background-color: #F1F1F1;">' . $district . '</span><br>';
            echo '</td>';


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
    echo '<div class="no-events">NO SUGUAN</div>';
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




<div class="container mt-2 ml-2 mr-2 custom-border" id="custom-container4">

<!-- <div class="row"> -->
    <?php
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

// Get today's date
$today = new DateTime();

// Calculate the current week's sunday
$sunday = new DateTime();
$sunday->setISODate($today->format('o'), $today->format('W'), 7);

// Format the date as 'Y-m-d'
$sundayDate = $sunday->format('Y-m-d');

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
    WHERE  event_type = 2 AND date = '$sundayDate' AND e.prepared_by NOT IN(14, 40) 
    AND u.section = '$sectionid' 
    ORDER BY time ASC";
   }
   else{
    //echo "2";
    $today_query = "SELECT *,e.id as event_id FROM events e 
    INNER JOIN user u ON u.id = e.prepared_by 
    WHERE is_display = 1 AND event_type = 2 AND date = '$sundayDate' AND e.prepared_by NOT IN(14, 40) 
    ORDER BY time ASC";
   }



    }
    else{
       // echo "3";
        $today_query = "SELECT *,e.id as event_id FROM events e WHERE e.event_type = 2 AND e.date = '$sundayDate' AND e.prepared_by = " . $_SESSION['userid'] . " ORDER BY e.time ASC";
    }
   
//echo $today_query;
    $today_result = mysqli_query($conn, $today_query);

    // Get the row count
    $row_count = mysqli_num_rows($today_result);


    $_SESSION['COUNT_SESSION'] = $row_count ;
    // Counter to keep track of the number of tables created
    $counter = 0;
    
    $sundayDate = new DateTime();
    $sundayDate->setISODate($sundayDate->format('o'), $sundayDate->format('W'), 7); // 7 stands for sunday
    echo '<h4 class="mb-1 ml-1 mt-2" style="font-weight:bold;">' . $sundayDate->format("Y F j, l") . '</h4>';
    
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
         echo '<span class="text-black">' . $title . '</span>';
         echo '</th>';
         echo '</tr>';
         echo '</thead>';
         echo '<tbody>';
 
         // Convert time to 12-hour format
         $time_12_hour = date("h:i A", strtotime($row['time']));
 
         //Adjusting row values
         $incharge = $row['incharge'];
         $contact_number = $row['contact_number'];
         $host = $row['host'];
         $location = $row['location'];
         $district = $row['district'];
         $details = $row['details'];
 
         // Output the table body content
         echo '<tr>';
            echo '<td style="font-size: 17px;padding: 1px;"">';
 
            echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b> ';
            echo '<b><span style="color: #519eaa;">' . $location . '</span></b>,';
            // Assuming you have a variable $value that holds the value you want to check
            // Replace $value with the actual variable you are using
            switch ($details) {
                case 1:
                    $details = 'Sugo';
                    break;
                case 2:
                    $details = 'Sugo 1';
                    break;
                case 3:
                    $details = 'Sugo 2';
                    break;
                case 4:
                    $details = 'Reserba';
                    break;
                case 5:
                    $details = 'Reserba 1';
                    break;
                case 6:
                    $details = 'Reserba 2';
                    break;
                default:
                    $details = 'Unknown'; // Default value if none of the cases match
                    break;
            }

            echo '<span style="color: #151515;">' . $details . '</span><br>';
            echo '<span style="color: #151515; background-color: #F1F1F1;">' . $district . '</span><br>';
            echo '</td>';


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
    echo '<div class="no-events">NO SUGUAN</div>';
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
                            document.getElementById('editTitle').value = data.title;
                            document.getElementById('editDate').value = data.date;
                            document.getElementById('editTime').value = data.time;

                            document.getElementById('editlocal').value = data.location;
                            document.getElementById('editdistrict').value = data.district;
                            document.getElementById('editgampanin').value = data.details;
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
