<?php
// Include the database connection file
include '../databases/connection/db.php';
date_default_timezone_set('Asia/Manila');
// Now you can use the $conn variable to perform database operations


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


// Check if the session role is set and equal to 1
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $displayStyle = "block"; // Display the form for role 1
    $isRequired = "required"; // Event name is required for role 1
} else {
    $displayStyle = "none"; // Hide the form for other roles
    $isRequired = ""; // Event name is not required for other roles
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Event Scheduler - PMD Event</title>
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
            background-color: #ffffff!important; /* Default background color */
            font-family: Arial, sans-serif; /* Specify a sans-serif font family */
        }
        /* CSS for success message */
        .success-message {
            display: none; /* Hide by default */
            color: green; /* Green color for success */
            margin-top: 10px; /* Add some spacing */
        }
        @media print {
            #dataTable td:nth-child(9),
            .btn,
            h4 {
                display: none !important;
            }
            .table-action-column {
                    display: none;
                }
        }
    </style>
</head>
<body>
<?php include '../layouts/sidemenu_bar.php'; ?>
<?php include '../layouts/footer.php'; ?>
<input type="hidden" id="savetype" name="savetype" value="daily">
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">

<!-- Add Event Modal -->
<div class="modal fade" data-backdrop="static" id="EventAddModal" tabindex="-1" aria-labelledby="EventAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                <input type="hidden" id="current_url" name="current_url" value="settings.php">
                    <div class="form-group"  style="display: <?php echo $displayStyle; ?>;">
                        <label for="eventName">Category:</label>
                        <select id="eventName" name="eventName" class="form-control" <?php echo $isRequired; ?>>
                        <option value="0" disabled selected>Select category</option>
                                        <option value="1">Birthdays & Anniversary</option>
                                        <option value="2">Weekly Reminders  (Buwanang Pulong, TRG Activities, Holidays, VSWS)</option>
                                        <option value="3">Weekly Meeting Schedule</option>
                                        <option value="4">Weekly Visitation</option>
                                        <option value="5">Suguan Reminders (WS Suguan ng mga Min/Mwa)</option>
                                        <option value="Others">Others</option>
                        </select>
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

                        <label for="location">Venue:</label>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EventEditModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <form method="post" id="editEventForm" name="editEventForm" action="../databases/queries/update_event.php">
                    <input type="hidden" id="editEventId" name="editEventId">
                    <input type="hidden" id="savetype" name="savetype" value="pmdevent">
                    <input type="hidden" id="current_url" name="current_url" value="settings.php">
                    <div class="form-group"  style="display: <?php echo $displayStyle; ?>;">
                        <label for="editEventName">Category</label>
                        <!-- <select id="editEventName" name="editEventName" class="form-control" required>
                            <option value="" disabled>Select an event</option>
                            <option value="Worship Service">Worship Service</option>
                            <option value="Meeting">Meeting</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Devotional Prayer">Devotional Prayer</option>
                            <option value="Choir Practice">Choir Practice</option>
                            <option value="Others">Others</option>
                        </select> -->
                        <select id="editEventName" name="editEventName" class="form-control" <?php echo $isRequired; ?>>
                            <option value="" disabled selected>Select category</option>
                            <option value="Suguan">Suguan</option>
                            <option value="ATG Appointment">ATG Appointment</option>
                            <option value="Non-ATG">Non-ATG</option>
                            <option value="Family">Family</option>
                            <option value="Others">Others</option>
                        </select>
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

<div class="col-md-12 mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
   
        <div class="d-flex align-items-center mb-4">
            <a href="../views/sched.php" class="btn btn-link"  style="display:none;">
                <i class="fas fa-arrow-left"></i> <!-- Font Awesome arrow-left icon -->
            </a>
            <h4 class="mb-0 ml-2"  style="display:none;">PMD Events List</h4>
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
            <button type="button" class="btn btn-success" id="printButton" style="margin-bottom: 10px; margin-left: 10px;display:none;">
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
    document.getElementById("pastEventsButton").innerHTML = "<i class='fas fa-eye-slash'></i> Hide Past Events";
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

        <table class="table table-bordered" id="eventTable">
                <thead class="table-dark">
                    <tr>
                    <th>#</th>
                        <!-- <th>Category</th> -->
                        <?php
                            // Check if the session role is equal to 1
                            if ($_SESSION['role'] == 1) {
                                // Display the "Display?" header
                                echo "<th>Category</th>";
                            }
                        ?>
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
                        ? "SELECT * FROM events WHERE date < '$today' $whereClause ORDER BY date ASC, time ASC, id DESC" 
                        : "SELECT * FROM events WHERE date >= '$today' $whereClause ORDER BY date ASC, time ASC, id DESC";

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
                        if ($_SESSION['role'] == 1) {
                            //echo "<td><select id='category_update' data-id='" . $eventId . "' class='form-control' name='categorySelect[]'>";
                            echo "<td><select class='category-update form-control' data-id='" . $eventId . "' name='categorySelect[]'>";
                            // Fetch event names from events table
                            $eventNamesQuery = "SELECT DISTINCT name FROM category ORDER BY id ASC ";
                            $eventNamesResult = mysqli_query($conn, $eventNamesQuery);
                            echo "<option value='0' disabled selected>Select category</option>";
                            while ($eventNameRow = mysqli_fetch_assoc($eventNamesResult)) {
                                $eventNamesql = $eventNameRow['name'];
                                // Output each event name as an option in the dropdown
                                echo "<option value='$eventNamesql' " . ($eventNamesql == $eventName ? 'selected' : '') . ">$eventNamesql</option>";
                            }
                            echo "</select></td>";
                        }
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
                        echo "<td class='table-action-column'><a href='#' class='edit-event' data-toggle='modal' data-target='#EventEditModal' data-event-id='" . $eventId . "'><i class='fas fa-edit'></i></a>";
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
             <!-- Pagination -->
            <nav aria-label="Page navigation example" style="display:none;">
                <ul class="pagination justify-content-center">
                     <li class="page-item disabled" id="prevPage">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item" id="nextPage">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        </div>
    </div>
</div>


<!-- JavaScript for pagination -->
<script>
    $(document).ready(function () {
        var table = $('#eventTable').DataTable({
            "paging": false, // Disable default pagination
            "ordering": false, // Disable column sorting
            "info": false // Disable showing table information
        });

        // Set initial page
        var currentPage = 0;
        var rowsPerPage = 10;

        // Function to show rows based on current page
        function showRows() {
            var start = currentPage * rowsPerPage;
            var end = start + rowsPerPage;

            table.rows().every(function (rowIdx) {
                if (rowIdx >= start && rowIdx < end) {
                    $(this.node()).show();
                } else {
                    $(this.node()).hide();
                }
            });
        }

        // Show initial rows
        showRows();

        // Pagination event handlers
        $('#prevPage').click(function () {
            if (currentPage > 0) {
                currentPage--;
                showRows();
            }
        });

        $('#nextPage').click(function () {
            if (currentPage < table.rows()[0].length / rowsPerPage - 1) {
                currentPage++;
                showRows();
            }
        });
    });
</script>


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

// Change event listener to target the class
$('.category-update').change(function() {
    // Get the selected category value
    var category = $(this).val();
    
    // Get the event ID from the data-id attribute of the select element
    var eventId = $(this).data('id');

    // Make an AJAX request to update the event_name column in the events table
    $.ajax({
        url: '../databases/queries/update_category.php',
        type: 'POST',
        data: {
            eventId: eventId,
            category: category
        },
        success: function(response) {
            // Handle success response
            console.log('Category updated successfully' + response);
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error('Error updating category:', error);
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

     // JavaScript to populate event details in the modal fields when edit icon is clicked
     document.querySelectorAll('.edit-event').forEach(item => {
        item.addEventListener('click', event => {
            const eventId = item.getAttribute('data-event-id');
            
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

</body>
</html>
