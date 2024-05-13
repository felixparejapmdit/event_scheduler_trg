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

        // Execute the SQL query
        $result = $conn->query($sql);
        //echo $sql;
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
            echo '<thead class="table" style="background-color: #a7c957;" padding:5px; color:#e8e8e4;!important>';
            break;
        case 'ATG Appointment':
            echo '<thead class="table" style="background-color: #a8e8f9;" padding:5px;>';
            break;
        case 'Non-ATG':
            echo '<thead class="table" style="background-color: #faab36;" padding:5px;>';
            break;
        case 'Family':
            echo '<thead class="table" style="background-color: #6D2932;" padding:5px;>';
            break;
        default:
            echo '<thead class="table" style="background-color: #436850;" padding:5px;>';
            break;
    }
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
// Example usage:
// Call this function where you output the thead element in your while loop
// outputTableHeader($eventName);aaa

function IconForEvent($eventId)
{
    //echo $eventId;
    if ($_SESSION['role'] != 3)
    {
    echo '<i class="fas fa-edit" id="edit-event" style="margin-right:3px; cursor:pointer;" data-toggle="modal" data-target="#EventEditModal" data-event-id=' . $eventId . '></i>'; // Example: Default calendar icon
    }
        // Add more cases for other events as needed
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
            background-color: #579d6f!important; /* Default background color */
            font-family: "Calibri";
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
            top: 20px;
            right: 20px;
            z-index: 1000;
            cursor: pointer;
            font-size: 24px;
            color: #000000;
        }
        .change-bg-btn.light {
            color: #fffffd; /* Color when background is dark */
        }

        /* Apply rounded corners to the entire table */
        table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px; /* Adjust the value to change the roundness */
            overflow: hidden; /* Ensure rounded corners are visible */
            width: 100%; /* Make table width 100% */
        }

        /* Apply rounded corners to table cells */
        td, th {
            padding: 10px; /* Add padding to improve appearance */
            border: 1px solid #ccc; /* Add border to td and th elements */
            border-radius: 5px; /* Adjust the value to change the roundness */
        }

        /* Remove border from thead */
        thead {
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

<?php include '../layouts/sidemenu_bar.php'; ?>
<?php include '../layouts/footer_section.php'; ?>


<!--Add Daily Events Modal -->
<div class="modal fade" data-backdrop="static" id="EventAddModal" tabindex="-1" aria-labelledby="EventAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EventAddModalLabel">Add Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="addDailyEvent" name="addDailyEvent" action="../databases/queries/add_event.php">
                <input type="hidden" id="addEventId" name="addEventId">
                <!-- <input type="hidden" id="addDate" name="addDate"> -->
                <input type="hidden" id="savetype" name="savetype" value="weekly">
                <input type="hidden" id="current_url" name="current_url" value="sectionhead.php">
                    <input type="hidden" id="week_number" name="week_number">
                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">
                 
                    
                    <script>
    function updateWeekNumber() {
        // Get the selected date value
        var selectedDate = new Date(document.getElementById("date_tosave").value);
        console.log(selectedDate);
        // Check if the selected date is valid
        if (!isNaN(selectedDate.getTime())) {
            // Get the week number of the selected date
            var weekNumber = getWeekNumber(selectedDate);

            // Update the value of the week number input
            document.getElementById("week_number").value = weekNumber;
        } else {
            // Reset the value of the week number input if the date is invalid
            document.getElementById("week_number").value = "";
        }
    }

    function getWeekNumber(date) {
        var d = new Date(date);
        d.setHours(0, 0, 0, 0);
        d.setDate(d.getDate() + 3 - (d.getDay() + 6) % 7);
        var week1 = new Date(d.getFullYear(), 0, 4);
        return 1 + Math.round(((d - week1) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
    }
</script>


<div class="form-group">
                        <label for="add_details">Appointment:</label>
                        <input type="text" class="form-control" id="addDetails" name="addDetails" required></input>
                    </div>

                    <div class="form-group">
                        <label for="date_tosave">Date:</label>
                        <input type="date" id="date_tosave" name="date_tosave" class="form-control" required onchange="updateWeekNumber()">
                    </div>
                    <div class="form-group">
                        <label for="addTime">Time:</label>
                        <input type="time" class="form-control" id="addTime" name="addTime" required>
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

                        <label for="addVenue">Venue:</label>
                        <!-- Select dropdown with colored options -->
                        <select class="form-control" id="venueSelect" name="venueSelect" required onchange="toggleLocationInput1()">
                            <option value="0" disabled selected>Select a venue</option>
                            <option value="MPH" class="MPH">MPH</option>
                            <option value="Chapel 1" class="chapel1">Chapel 1</option>
                            <option value="Chapel 4" class="chapel4">Chapel 4</option>
                            <option value="Studio A" class="studioA">Studio A</option>
                            <option value="Studio B" class="studioB">Studio B</option>
                            <option value="Studio C" class="studioC">Studio C</option>
                            <option value="6F ConferenceRoom" class="ConferenceRoom6F">6F Conference Room</option>
                            <option value="3F CommonArea" class="CommonArea3F">3F Common Area</option>
                            <option value="3F ConferenceRoom" class="ConferenceRoom3F">3F Conference Room</option>
                            <option value="3F" LanguageRoom class="LanguageRoom3F">3F Language Room</option>
                            <option value="3F Dojo Room" class="DojoRoom3F">3F Dojo Room</option>
                            <option value="Auditorium" class="Auditorium">Auditorium</option>
                            <option value="Public Lobby" class="PublicLobby">Public Lobby</option>
                            <option value="Others" class="Others">Others</option>
                        </select>

                        
                        <!-- Input field for location -->
                        <input type="text" id="addVenue" name="addVenue" class="form-control mt-2" placeholder="Enter venue" style="display: none;">
                    
                        <script>
                            function toggleLocationInput1() {
                                var venueSelect = document.getElementById('venueSelect');
                                var locationInput = document.getElementById('addVenue');

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

                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addEvent" name="addEvent">Save</button>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
       
            </div>
        </div>
    </div>
</div>

<!--Edit Daily Events Modal -->
<div class="modal fade" data-backdrop="static" id="EventEditModal" tabindex="-1" aria-labelledby="EventEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EventEditModalLabel">Edit Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="detailsForm" name="detailsForm" action="../databases/queries/update_event.php">
                <input type="hidden" id="editEventId" name="editEventId">
                <input type="hidden" id="savetype" name="savetype" value="weekly">
                <input type="hidden" id="current_url" name="current_url" value="sectionhead.php">
                <!-- <input type="hidden" id="editDate" name="editDate"> -->

                <div class="form-group">
                        <label for="edit_details">Appointment:</label>
                        <input type="text" class="form-control" id="editDetails" name="editDetails" required></input>
                    </div>

                <div class="form-group">
                        <label for="editDate">Date:</label>
                        <input type="date" class="form-control" id="editDate" name="editDate" required>
                    </div>

                    <div class="form-group">
                        <label for="editTime">Time:</label>
                        <input type="time" class="form-control" id="editTime" name="editTime" required>
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

  <label for="editVenue">Venue:</label>
  <!-- Select dropdown with colored options -->
  <select class="form-control" id="editVenueSelect" name="editVenueSelect" required onchange="toggleLocationInput()">
  <option value="0" disabled selected>Select a venue</option>
  <option value="MPH" class="MPH">MPH</option>
        <option value="Chapel 1" class="chapel1">Chapel 1</option>
        <option value="Chapel 4" class="chapel4">Chapel 4</option>
        <option value="Studio A" class="studioA">Studio A</option>
        <option value="Studio B" class="studioB">Studio B</option>
        <option value="Studio C" class="studioC">Studio C</option>
        <option value="6F ConferenceRoom" class="ConferenceRoom6F">6F Conference Room</option>
        <option value="3F CommonArea" class="CommonArea3F">3F Common Area</option>
        <option value="3F ConferenceRoom" class="ConferenceRoom3F">3F Conference Room</option>
        <option value="3F" LanguageRoom class="LanguageRoom3F">3F Language Room</option>
        <option value="3F Dojo Room" class="DojoRoom3F">3F Dojo Room</option>
        <option value="Auditorium" class="Auditorium">Auditorium</option>
        <option value="Public Lobby" class="PublicLobby">Public Lobby</option>
        <option value="Others" class="Others">Others</option>
  </select>

  <!-- Input field for location -->
  <input type="text" id="editVenue" name="editVenue" class="form-control mt-2" placeholder="Enter venue" style="display: none;">

  <script>
      function toggleLocationInput() {
          var venueSelect = document.getElementById('editVenueSelect');
          var locationInput = document.getElementById('editVenue');

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


    <!-- <label for="edit_venue">Venue:</label>
    <input type="text" class="form-control" id="editVenue" name="editVenue" required> -->
</div>

       
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editEvent" name="editEvent">Save</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
       
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

<!-- Script to update the time every 3 seconds -->
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

  <!-- Add button for settings.php -->
  <a style="margin-left:20px;display:none;" href="../views/settings.php" class="btn btn-primary mt-3">Add Event</a>

  <div class="d-flex mt-2">

<div class="container mt-2 ml-2 mr-2 custom-border" id="custom-container1 " >

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
        // Check if the roleid is 1
        if($roleid == 1) {
            // If roleid is 1, remove the condition prepared_by = $userid
            $today_query = "SELECT *,e.id as event_id, u.contact, u.name 
                            FROM events e
                            LEFT JOIN user u ON u.id = e.prepared_by
                            WHERE event_type = 2 AND date = '$today' AND u.id NOT IN(1, 14, 40)
                            ORDER BY time ASC";
        } else {
            // If roleid is not 1, include the condition prepared_by = $userid
            $userid = $_GET['userid'];
            $today_query = "SELECT *,e.id as event_id, u.contact, u.name 
                            FROM events e
                            LEFT JOIN user u ON u.id = e.prepared_by
                            WHERE event_type = 2 AND date = '$today' AND prepared_by = $userid
                            ORDER BY time ASC";
        }
    }
    else{
        $today_query = "SELECT *,e.id as event_id, u.contact, u.name
                        FROM events e
                        LEFT JOIN user u ON u.id = e.prepared_by
                        WHERE event_type = 2 AND date = '$today' AND prepared_by = " . $userid . " 
                        ORDER BY time ASC";
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
        echo '<table class="table table-bordered rounded table-custom">';
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
        $fullname = $row['name'];
        $incharge = $row['incharge'];
        $contact_number = $row['contact'];
        $host = $row['host'];
        $location = $row['location'];
        $details = $row['details'];

        // Output the table body content
        //echo '<tr>';
        echo '<tr data-event-time="' . $row['time'] . '" data-event-title="' . $title . '">';
        echo '<td style="font-size: 17px;padding: 1px;">';
        echo '<b><span style="color: #231c35;margin-bottom: 0;">' . $time_12_hour . '</span></b>, ';
        VenueColorCoding($location);
        echo '<span style="color: #231c35; font-weight: 500;margin-top: 0;">' . $incharge . '</span>, <i>' . $contact_number . '</i><br>';
        // if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
        // {
        //     echo '<span style="color: #231c35; font-weight: 500;margin-top: 0;">' . $fullname . '</span>, <i>' . $contact_number . '</i><br>';
        // }
        
        // else{
        //     echo '<span style="color: #231c35; font-weight: 500;margin-top: 0;">' . $incharge . '</span>, <i>' . $contact_number . '</i><br>';
        // }
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
            return '<a href="' . $url . '" target="_blank">LINK</a>';
        } else {
            $domain = preg_replace('/^https?:\/\/(?:www\.)?|\/.*$/i', '', $url);
            return '<a href="' . $url . '" target="_blank">' . $domain . '</a>';
        }
    }, $details);

    echo '<span style="margin-left:8px;">Details: </span> <br> ' .nl2br($details); // Output the modified details with line breaks
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
        // Get the roleid from the URL
        if (!isset($_GET['role'])) {
            $roleid = 1;
        }
    
    // Check if the roleid is 1
    if($roleid == 1) {
        // If roleid is 1, remove the condition prepared_by = $userid
        $today_query = "SELECT *,e.id as event_id, u.contact, u.name
                        FROM events e
                        LEFT JOIN user u ON u.id = e.prepared_by
                        WHERE event_type = 2 AND date = '$tomorrow' AND u.id NOT IN(1, 14, 40) 
                        ORDER BY time ASC";
    } else {
        // If roleid is not 1, include the condition prepared_by = $userid
        $userid = $_GET['userid'];
        $today_query = "SELECT *,e.id as event_id, u.contact, u.name
                        FROM events e
                        LEFT JOIN user u ON u.id = e.prepared_by
                        WHERE event_type = 2 AND date = '$tomorrow' AND prepared_by = $userid 
                        ORDER BY time ASC";
    }
    }
    else{
        $today_query = "SELECT *,e.id as event_id, u.contact, u.name 
                        FROM events e
                        LEFT JOIN user u ON u.id = e.prepared_by
                        WHERE event_type = 2 AND date = '$tomorrow' AND prepared_by = " . $userid . " 
                        ORDER BY time ASC";
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
        echo '<table class="table table-bordered rounded table-custom">';
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
        $fullname = $row["name"];
        $incharge = $row['incharge'];
        $contact_number = $row['contact'];
        $host = $row['host'];
        $location = $row['location'];
        $details = $row['details'];



        // Output the table body content
        echo '<tr>';
        echo '<td style="font-size: 17px;padding: 1px;"">';
        echo '<b><span style="color: #231c35;">' . $time_12_hour . '</span></b>, ';
        VenueColorCoding($location);
        echo '<span style="color: #231c35; font-weight: 500;">' . $incharge . '</span>, <i>' . $contact_number . '</i><br>';
        // if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
        // {
        //     echo '<span style="color: #231c35; font-weight: 500;margin-top: 0;">' . $fullname . '</span>, <i>' . $contact_number . '</i><br>';
        // }
        
        // else{
        //     echo '<span style="color: #231c35; font-weight: 500;">' . $incharge . '</span>, <i>' . $contact_number . '</i><br>';
        // }
        
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
                return '<a href="' . $url . '" target="_blank">LINK</a>';
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
        if (strpos($current_url, 'userid') !== false) {
            
            // Get the count of upcoming events (excluding today and tomorrow)
            $upcoming_event_count_section = getEventCount($conn, "DATE(e.date) > CURDATE() + INTERVAL 1 DAY AND e.event_type = 2 AND e.prepared_by = $userid AND e.prepared_by NOT IN(1, 14, 40)");

        }
        else{
            // Get the count of upcoming events (excluding today and tomorrow)
            $upcoming_event_count_section = getEventCount($conn, "DATE(date) > CURDATE() + INTERVAL 1 DAY AND event_type = 2  AND e.prepared_by NOT IN(1, 14, 40) ");
        }
        $eventlabel_pmd = "PMD";
        $eventlabel_section = "Section";
    }
    else
    {
        // Get the count of upcoming events (excluding today and tomorrow)
        $upcoming_event_count_section = getEventCount($conn, "DATE(e.date) > CURDATE() + INTERVAL 1 DAY AND e.event_type = 2 AND e.prepared_by = $userid AND e.prepared_by NOT IN(1, 14, 40)");

        $eventlabel_pmd = "My Section";
        $eventlabel_section = "My Schedule";
    }
    // Output the "Upcoming's Events" heading with row count
    echo '<h4 class="mb-2 ml-3 mt-3" style="font-weight:bold;margin-top:-10px;">Upcoming (' . $upcoming_event_count_section . ')</h4>';


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
//                         (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events WHERE event_type = 2 AND date > '$day_after_tomorrow' GROUP BY date) AS subquery) AS total_event_count
//                         FROM events e
//                         LEFT JOIN user u ON u.id = e.prepared_by
//                         WHERE event_type = 2 AND date > '$day_after_tomorrow' GROUP BY date ORDER BY date ASC";




if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
{
    // Get the roleid from the URL
    if (!isset($_GET['role'])) {
        $roleid = 1;
    }

// Check if the roleid is 1
if($roleid == 1) {
    // If roleid is 1, remove the condition prepared_by = $userid
    $today_query = "SELECT date, COUNT(*) AS event_count,
    (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events WHERE event_type = 2 AND date > '$day_after_tomorrow' GROUP BY date) AS subquery) AS total_event_count
    FROM events e
    LEFT JOIN user u ON u.id = e.prepared_by
    WHERE event_type = 2 AND date > '$day_after_tomorrow' AND u.id NOT IN(1, 14, 40) GROUP BY date ORDER BY date ASC";
} else {
    // If roleid is not 1, include the condition prepared_by = $userid
    $userid = $_GET['userid'];
    $today_query = "SELECT date, COUNT(*) AS event_count,
                (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events WHERE event_type = 2 AND date > '$day_after_tomorrow' GROUP BY date) AS subquery) AS total_event_count
                FROM events e
                LEFT JOIN user u ON u.id = e.prepared_by
                WHERE event_type = 2 AND date > '$day_after_tomorrow' AND prepared_by = $userid GROUP BY date ORDER BY date ASC";
}
}
else{
    $today_query = "SELECT date, COUNT(*) AS event_count,
    (SELECT SUM(cnt) FROM (SELECT COUNT(*) AS cnt FROM events WHERE event_type = 2 AND date > '$day_after_tomorrow' AND prepared_by = $userid GROUP BY date) AS subquery) AS total_event_count
    FROM events e
    LEFT JOIN user u ON u.id = e.prepared_by
    WHERE event_type = 2 AND date > '$day_after_tomorrow' AND prepared_by = $userid GROUP BY date ORDER BY date ASC";
}


    // if ($_SESSION['role'] == 1) {
    // $today_query = $commonQueryPart . " GROUP BY date ORDER BY date ASC;";
    // } else {
    // $today_query = $commonQueryPart . " AND prepared_by = " . $_SESSION['userid'] . " GROUP BY date ORDER BY date ASC;";
    // }
    

//echo  $today_query;
    $today_result = mysqli_query($conn, $today_query);
   // echo $today_query ;
    // Get the row count
    $row_count = mysqli_num_rows($today_result);
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
        echo '<table class="table rounded smaller-table-upcoming">';
            echo '<thead class="table" style="padding:5px;>';
                // Output the table header content
                echo '<tr>';
                echo    '<th style="padding: 1px;">';
                echo        '<span style="font-size: 120%;font-weight:bold;color:#3D3B40;">' . $date . '</span>'; // Add the class 'smaller-title'
                echo    '</th>';
                echo '</tr>';
            echo '</thead>';

            echo '<tbody>';

            if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
            {
                // Get the roleid from the URL
                if (!isset($_GET['role'])) {
                    $roleid = 1;
                }
            
            // Check if the roleid is 1
            if($roleid == 1) {


                    $dateQuery = "SELECT *,e.id as event_id, u.name  FROM events e
                                    LEFT JOIN user u ON u.id = e.prepared_by
                                    WHERE event_type = 2 AND date = '$upcomingDate' AND u.id NOT IN(1, 14, 40) ORDER BY date ASC, time ASC";
            }
            else{
                $dateQuery = "SELECT *, e.id as event_id, u.name FROM events e
                LEFT JOIN user u ON u.id = e.prepared_by
                WHERE event_type = 2 AND date = '$upcomingDate' AND prepared_by = $userid ORDER BY date ASC, time ASC";
            }
        }
        else{
            $dateQuery = "SELECT *,e.id as event_id, u.name FROM events e
                LEFT JOIN user u ON u.id = e.prepared_by
                WHERE event_type = 2 AND date = '$upcomingDate' AND prepared_by = $userid ORDER BY date ASC, time ASC";
        }
                    //echo $dateQuery;
                    $date_result = mysqli_query($conn, $dateQuery);
                    while ($rowDate = mysqli_fetch_assoc($date_result)) {
                        // Convert time to 12-hour format
                        $time_12_hour = date("h:i A", strtotime($rowDate['time']));
                        $title = $rowDate['title'];
                        $fullname = $rowDate['incharge'];
                        $location = $rowDate['location'];
                        $my_eventid = $rowDate['event_id'];
                        // Output the table body content
                        echo '<tr>';
                        echo '<td class="smaller-text" style="font-size: 100%;padding: 1px;">';
                        IconForEvent($my_eventid);
                        echo '<span style="color: #231c35!important;font-weight:bold;">' . $time_12_hour . ' - </span><b><a style="color: #118ab2;">' . $title . '</a></b>, ' . VenueColorCoding1($location) . ', <span style="color: #231c35!important;font-weight:normal;">' . $fullname . '</span> ';
                        // if($_SESSION['role'] == 1 || $_SESSION['role'] == 3)
                        // {
                        //     echo '<span style="color: #231c35!important;font-weight:bold;">' . $time_12_hour . ' - </span><b><a style="color: #118ab2;">' . $title . '</a></b>, ' . VenueColorCoding1($location) . ',' . $fullname;
                        // }
                        
                        // else{
                        //     echo '<span style="color: #231c35!important;font-weight:bold;">' . $time_12_hour . ' - </span><b><a style="color: #118ab2;">' . $title . '</a></b>, ' . VenueColorCoding1($location);
                        // }

                        
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
    <div class="container mt-3 ml-2 mr-2" id="custom-container0" style="width: 12%; overflow-y: auto;">
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
        <div class="zoomable-image" onclick="changeUserId(0, 1)" style="background-color:#579d6f;width:150px;">
            <i class="fas fa-list" style="font-size: 50px; color: #040e0b; cursor:pointer;"></i>
            <div class="user-name text-center"  style="color:white;">View All</div>
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
    $user_query = "(SELECT * FROM user WHERE id = 43)
    UNION
    (SELECT * FROM user WHERE id NOT IN (1, 14, 15, 16, 17, 40) ORDER BY name ASC)
    ";
    $user_result = mysqli_query($conn, $user_query);
//echo $user_query ;
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        // Loop through each user
        while ($user = mysqli_fetch_assoc($user_result)) {
            // Check if the user has an image path
            $imageSrc = 'images/blank_image.png';

            // Output the circular image HTML
?>
            <div class="user-container">
                <div class="zoomable-image" onclick="changeUserId(<?php echo $user['id']; ?>, <?php echo $user['role']; ?>)" style="color: #2c4911; cursor: pointer; width:50px; height:50px;">
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
        var userId = urlParams.get('userid');
        var role = urlParams.get('role');

        // Call the changetheme function with the extracted userId and role
        changetheme(userId, role);
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
    var selectedUserId = null;

    function changetheme(userId, role) {
        // Reset the color of the previously selected image
        if (selectedUserId !== null) {
            var prevIcon = document.getElementById('userIcon_' + selectedUserId);
            if (prevIcon) {
                prevIcon.style.color = '#83e12b';    
                prevIcon.style.border = 'none'; // Remove previous border
            }
        }

        // Change the color of the clicked image
        var currentIcon = document.getElementById('userIcon_' + userId);
        if (currentIcon) {
            currentIcon.style.color = '#7de917';
            currentIcon.style.border = '2px solid #478912'; // Add solid border
            selectedUserId = userId;
        }
    }

    function changeUserId(userId, role) {
        // Get the current URL
        var currentUrl = window.location.href;

        // Split the URL by '?' to separate the base URL from the query string
        var urlParts = currentUrl.split('?');

        // Get the base URL
        var baseUrl = urlParts[0];

        // Construct the new URL
        var newUrl = baseUrl;

        // Add the userid parameter if userId is not 0
        if (userId !== 0) {
            newUrl += '?userid=' + userId + '&role=' + role;
        }
        
        // Redirect to the new URL
        window.location.href = newUrl;

        changetheme(userId, role);
    }

    </script>

    <script>
                // JavaScript to populate event details in the modal fields when edit icon is clicked
                document.querySelectorAll('#edit-event').forEach(item => {
                    item.addEventListener('click', event => {
                        const eventId = item.getAttribute('data-event-id');
                        
                        // Send AJAX request to fetch event details
                        fetch('../databases/queries/get_event_details.php?id=' + eventId)
                            .then(response => response.json())
                            .then(data => {
                                // Populate the modal fields with the fetched data
                                document.getElementById('editEventId').value = data.id;
                                document.getElementById('editVenue').value = data.location;
                                document.getElementById('editDate').value = data.date;
                                document.getElementById('editTime').value = data.time;

                                document.getElementById('editDetails').value = data.title;

                                // Check if the location is not in the editVenueSelect dropdown
                    const editVenueSelect = document.getElementById('editVenueSelect');
                    const locationNotFound = ![...editVenueSelect.options].some(option => option.value === data.location);
                    if (locationNotFound) {
                        // Set the location input value and display it
                        document.getElementById('editVenue').value = data.location;
                        document.getElementById('editVenue').style.display = 'block';
                        editVenueSelect.value = "Others";
                    } else {
                        // Set the dropdown value to the location
                        editVenueSelect.value = data.location;
                        // Hide the location input
                        document.getElementById('editVenue').style.display = 'none';
                    }
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

    

</body>
</html>
