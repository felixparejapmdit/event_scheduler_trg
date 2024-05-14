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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/tabs.css">
    <title>Event Scheduler</title>
    <link rel="icon" href="../images/scheduler.ico" type="image/x-icon">

   <!-- Include jQuery before Bootstrap -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
       </style>
</head>

<body>
<?php include '../layouts/sidemenu_bar.php'; ?>
<?php include '../layouts/footer_section.php'; ?>
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
            <form method="post" id="addDailyEvent" name="addDailyEvent">
                    <input type="hidden" id="addEventId" name="addEventId">
                    <input type="hidden" id="savetype" name="savetype" value="weekly">
                    <input type="hidden" id="current_url" name="current_url" value="weekly_task.php">
                    <input type="hidden" id="date_tosave" name="date_tosave">
                    <input type="hidden" id="week_number" name="week_number">
                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">
                        
                    <div class="form-group">
                        <label for="addDetails">Appointment:</label>
                        <input type="text" class="form-control" id="addDetails" name="addDetails" required></input>
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

                    <script>

                        function setEventParameters() {

                            document.getElementById("addDetails").value = "";
                            document.getElementById("addTime").value = "";
                            document.getElementById("venueSelect").value = "0";
                            document.getElementById("addVenue").value = "";
                            document.getElementById("addDetails").focus();

                            // Get the values from the input fields
                            var currentDate = document.getElementById("date_tosave").value;
                            var weekNumber = document.getElementById("week_number").value;
                            var day = getDayFromDate(currentDate);

                            // Call the loadEvents function with the parameters
                            loadEvents(event, 'DailyTabs', currentDate, weekNumber, day);

                            // Close the modal
                            var modal = document.getElementById("EventAddModal");
                            if (modal) {
                                modal.classList.remove("show");
                                modal.style.display = "none";
                                modal.setAttribute("aria-hidden", "true");
                                modal.setAttribute("aria-modal", "false");
                                document.body.classList.remove("modal-open");
                                var modalBackdrop = document.getElementsByClassName("modal-backdrop");
                                if (modalBackdrop[0]) {
                                    modalBackdrop[0].parentNode.removeChild(modalBackdrop[0]);
                                }
                            }
                        }

                        function getDayFromDate(dateString) {
                            var date = new Date(dateString);
                            var dayIndex = date.getDay(); // 0 (Sunday) to 6 (Saturday)
                            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            return days[dayIndex];
                        }

               // JavaScript to handle "Save Changes" button click
    document.getElementById('addEvent').addEventListener('click', function(event) {
        // Prevent the default form submission
        event.preventDefault();

        // Check if the form is valid
        if (document.getElementById('addDailyEvent').checkValidity()) {
            // Collect the form data
            const formData = new FormData(document.getElementById('addDailyEvent'));

            // Send AJAX request to add event details
            fetch('../databases/queries/add_event.php', {
                method: 'POST',
                body: formData
            })
            .then(data => {
                // Handle response (e.g., show success message, update UI)
                console.log(data);
                // Update the events and close the modal
                setEventParameters();
            })
            .catch(error => console.error('Error:', error));
        } else {
            // If the form is invalid, trigger the default form validation
            document.getElementById('addDailyEvent').reportValidity();
        }
    });
                        function deleteEvent(eventId) {
                            if (confirm("Are you sure you want to delete this event?")) {
                                // Send AJAX request to delete the event
                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "../databases/queries/delete_event.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        setEventParameters();
                                    }
                                };
                                xhr.send("eventId=" + eventId);
                            }
                        }
                    </script>

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
                <!-- <form method="post" id="detailsForm" name="detailsForm" action="../databases/queries/update_event.php"> -->
                <form method="post" id="detailsForm" name="detailsForm">
                    <input type="hidden" id="editEventId" name="editEventId">
                    <input type="hidden" id="savetype" name="savetype" value="weekly">
                    <input type="hidden" id="current_url" name="current_url" value="weekly_task.php">
                    <!-- <input type="hidden" id="editDate" name="editDate"> -->

                    <div class="form-group">
                        <label for="editDetails">Appointment:</label>
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

            <script>
                // JavaScript to populate event details in the modal fields when edit icon is clicked
                $(document).ready(function () {
                
                    function setEventParameters() {
                            // Get the values from the input fields
                            var currentDate = document.getElementById("date_tosave").value;
                            var weekNumber = document.getElementById("week_number").value;
                            var day = getDayFromDate(currentDate);

                            // Call the loadEvents function with the parameters
                            loadEvents(event, 'DailyTabs', currentDate, weekNumber, day);

                            // Close the modal
                            var modal = document.getElementById("EventEditModal");
                            if (modal) {
                                modal.classList.remove("show");
                                modal.style.display = "none";
                                modal.setAttribute("aria-hidden", "true");
                                modal.setAttribute("aria-modal", "false");
                                document.body.classList.remove("modal-open");
                                var modalBackdrop = document.getElementsByClassName("modal-backdrop");
                                if (modalBackdrop[0]) {
                                    modalBackdrop[0].parentNode.removeChild(modalBackdrop[0]);
                                }
                            }
                        }

                        function getDayFromDate(dateString) {
                            var date = new Date(dateString);
                            var dayIndex = date.getDay(); // 0 (Sunday) to 6 (Saturday)
                            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            return days[dayIndex];
                        }

                        $('#detailsForm').submit(function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Serialize the form data
            var formData = $(this).serialize();

            // Send AJAX request to update event details
            $.ajax({
                url: '../databases/queries/update_event.php',
                type: 'POST',
                data: formData,
                success: function (response) {
                    // Handle success
                    console.log('Event details updated successfully:', response);
                    setEventParameters();
                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error('Error updating event details:', error);
                }
            });
        });
                });
      // Attach a click event listener to the document
      $(document).on('click', '.edit-event', function () {
                const eventId = $(this).data('event-id');

                // Send AJAX request to fetch event details
                fetch('../databases/queries/get_event_details.php?id=' + eventId)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the modal fields with the fetched data
                        
                        $('#editEventId').val(data.id);
                        $('#editDetails').val(data.title);
                        $('#editDate').val(data.date);
                        $('#editTime').val(data.time);
                        //$('#editVenue').val(data.location);

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
            </script>
            </div>
            <div class="modal-footer">
       
            </div>
        </div>
    </div>
</div>

<div class="d-flex mt-5">
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-5 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="currentWeekDates" style=" font-weight: bold;
                            font-size: 16px;
                            color: #333;
                            padding: 5px 10px;
                            background-color: #8fd19e;
                            border-radius: 5px;">
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-2">
                <div class="d-flex justify-content align-items-center" style="margin-left:200px;">
                    <button class="btn btn-success" id="prevWeekBtn" onclick="previousWeek('prev')" style="margin-right: -162x;">&lt;</button>
                    <div id="week-number" name="week-number" style="font-weight: bold; margin: 5px;"></div>
                    <button class="btn btn-success" id="nextWeekBtn" onclick="nextWeek('next')" style="margin-left: -162x;">&gt;</button>
                </div>
            </div>
            <script>
            // JavaScript code
            function updateDatePeriod(startDate, endDate) {
                // Format the start and end dates
                var startDateFormatted = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                var endDateFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                    
                // Update the date period label
                document.getElementById("currentWeekDates").textContent = "Date Period: " + startDateFormatted + " - " + endDateFormatted;
                
                // Construct the formatted date range
                var formattedDateRange = startDateFormatted + " - " + endDateFormatted;
                // Create a span element for formatting
                var spanElement = document.createElement('span');
                spanElement.innerText = formattedDateRange;
                spanElement.style.fontWeight = 'normal';

                // Update the value of the date_tosave input field
                document.getElementById('date_tosave').value = formatDateToISO(startDateFormatted);
                // Append the span element to the HTML element
                var currentWeekDatesElement = document.getElementById('currentWeekDates');
                currentWeekDatesElement.innerHTML = 'Date Period: ';
                currentWeekDatesElement.appendChild(spanElement);
            }

            function previousWeek() {
                // Get the start and end dates from the date period label
                var datePeriodText = document.getElementById("currentWeekDates").textContent;
                var startDateStr = datePeriodText.split(": ")[1].split(" - ")[0];
                var endDateStr = datePeriodText.split(": ")[1].split(" - ")[1];

                // Parse start and end dates
                var startDate = new Date(startDateStr);
                var endDate = new Date(endDateStr);

                // Calculate the start date of the previous week (Monday)
                startDate.setDate(startDate.getDate() - 7);

                // Calculate the end date of the previous week (Sunday)
                endDate.setDate(endDate.getDate() - 7);

                // Update the week number label
                document.getElementById("week-number").textContent = "Week " + getWeekNumber(startDate);


                // Update  date period label
                updateDatePeriod(startDate, endDate);

                loadEvents(null, 'DailyTabs', formatDateToISO(startDate), getWeekNumber(startDate),"Monday");
                
                tablinks = document.getElementsByClassName("tablinks");
                tablinks[0].classList.add("active");
            }

            function nextWeek() {
                // Get the start and end dates from the date period label
                var datePeriodText = document.getElementById("currentWeekDates").textContent;
                var startDateStr = datePeriodText.split(": ")[1].split(" - ")[0];
                var endDateStr = datePeriodText.split(": ")[1].split(" - ")[1];

                // Parse start and end dates
                var startDate = new Date(startDateStr);
                var endDate = new Date(endDateStr);

                // Calculate the start date of the next week (Monday)
                startDate.setDate(startDate.getDate() + 7);

                // Calculate the end date of the next week (Sunday)
                endDate.setDate(endDate.getDate() + 7);

                // Update the week number label
                document.getElementById("week-number").textContent = "Week " + getWeekNumber(startDate);

                // Update the date period label
                updateDatePeriod(startDate, endDate);

                console.log(getWeekNumber(startDate));
                loadEvents(null, 'DailyTabs', formatDateToISO(startDate), getWeekNumber(startDate),"Monday");

                tablinks = document.getElementsByClassName("tablinks");
                        tablinks[0].classList.add("active");
            }

            function getWeekNumber(date) {
                // Calculate the week number using ISO 8601 standard
                var onejan = new Date(date.getFullYear(), 0, 1);
                var weekNumber = Math.ceil((((date - onejan) / 86400000) + onejan.getDay() + 1) / 7);
                return weekNumber;
            }

            function formatDateToISO(dateString) {
                // Parse the date string into a Date object
                var date = new Date(dateString);

                // Get the year, month, and day components of the date
                var year = date.getFullYear();
                var month = ('0' + (date.getMonth() + 1)).slice(-2); // Add leading zero if necessary
                var day = ('0' + date.getDate()).slice(-2); // Add leading zero if necessary

                // Assemble the ISO-formatted date string
                var isoDate = year + '-' + month + '-' + day;

                return isoDate;
            }

            // Call updateDatePeriod() function on page load to display the current week
            window.onload = function() {
                // Get the current date in Philippine Standard Time
                var currentDate = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"}));

                // Calculate the start date of the current week (Monday)
                var currentDayOfWeek = currentDate.getDay(); // 0 (Sunday) to 6 (Saturday)
                var daysToSubtract = currentDayOfWeek === 1 ? 0 : currentDayOfWeek === 0 ? 6 : currentDayOfWeek -1; // Adjust days to get to Monday
                var startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() - daysToSubtract);

               
                // Calculate the end date of the current week (Sunday)
                var endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 6);
                
                // Update the week number label
                document.getElementById("week-number").textContent = "Week " + getWeekNumber(startDate);
                
                // Update the date period label
                updateDatePeriod(startDate, endDate);
               
                //Call the loadEvents function with the cityName as "DailyTabs" and currentDate as formatted Monday date
                loadEvents(null, 'DailyTabs', formatDateToISO(startDate), getWeekNumber(new Date()), "Monday");
                window.pageLoad = true;
            };
            </script>

            <div class="col-md-12">
                <script>
                    function loadEvents(evt, tabName, currentdate, weeknumber, currentDay) 
                    {
                        if (window.pageLoad === true) {
                            // Get all elements with class="tablinks" and remove the class "active"
                            tablinks = document.getElementsByClassName("tablinks");
                            for (i = 0; i < tablinks.length; i++) {
                                tablinks[i].classList.remove("active");
                                window.pageLoad = true;
                                    
                                }
                        }
                       
                        //Update week number and current week dates
                        var weekNumber = document.getElementById('week-number').textContent; // Get the week number from the DOM
                        var currentWeekDates = document.getElementById('currentWeekDates').textContent; // Get the current week dates from the DOM

                        // Extract the start date from currentWeekDates
                        var startDateString = currentWeekDates.split(" - ")[0];
                        // Convert the start date to a JavaScript Date object
                        var startDate = new Date(startDateString.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
                        var DayCounter = 0;
                        // Calculate the date based on the selected day and the current week's start date
                        var selectedDate = startDate;
                        switch (currentDay) {
                            case "Monday":
                                DayCounter=0;
                                selectedDate.setDate(startDate.getDate());
                                break;
                            case "Tuesday":
                                DayCounter=1;
                                selectedDate.setDate(startDate.getDate() + 1);
                                break;
                            case "Wednesday":
                                DayCounter=2;
                                selectedDate.setDate(startDate.getDate() + 2);
                                break;
                            case "Thursday":
                                DayCounter=3;
                                selectedDate.setDate(startDate.getDate() + 3);
                                break;
                            case "Friday":
                                DayCounter=4;
                                selectedDate.setDate(startDate.getDate() + 4);
                                break;
                            case "Saturday":
                                DayCounter=5;
                                selectedDate.setDate(startDate.getDate() + 5);
                                break;
                            case "Sunday":
                                DayCounter=6;
                                selectedDate.setDate(startDate.getDate() + 6);
                                break;
                        }

                        // Update the value of the date_tosave input field
                        document.getElementById('date_tosave').value = formatDateToISO(selectedDate);
                      
                        document.getElementById('week_number').value = weeknumber;

                        console.log("weekNumber: " + weekNumber);
                        var date_tosave = currentWeekDates === "" ? currentdate : formatDateToISO(selectedDate);
                        console.log("currentWeekDates:" + currentWeekDates);
                        console.log("DATE:" + date_tosave);
                        // Create a new XMLHttpRequest object
                        var xhr = new XMLHttpRequest();
                        
                        // Configure the request
                        xhr.open("POST", "../databases/queries/daily_output.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                        // Define the function to handle the response
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Update the table with the fetched data
                                document.getElementById("taskTableBodyMonday").innerHTML = xhr.responseText;
                            }
                        };

                        // Send the AJAX request with the date_tosave parameter
                        xhr.send("date_tosave=" + date_tosave);

                        // Declare all variables
                        var i, tabcontent, tablinks;
                        // Get all elements with class="tabcontent" and hide them
                        tabcontent = document.getElementsByClassName("tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                            tabcontent[i].style.display = "none";
                        }
                   
                        // Show the current tab, and add an "active" class to the link that opened the tab
                        document.getElementById(tabName).style.display = "block";
                        console.log(DayCounter);
                        
                        tablinks = document.getElementsByClassName("tablinks");
                        tablinks[DayCounter].classList.add("active");
                        if (evt) {
                            // Access event properties
                            var currentTarget = evt.currentTarget;
                            // Rest of your code that depends on the event object
                            //evt.currentTarget.classList.add("active");
                        }
                    }
                
                    </script>


                    <div id="weekly_events">
            
                    <div class="tab" style="height: 80px;width:100%; display: flex; justify-content: space-between;">
                        <?php
                        // Define an array of days to use in the loop
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                        // Define an array of corresponding Font Awesome icons
                        $icons = ['fa-calendar-alt', 'fa-calendar-alt', 'fa-calendar-alt', 'fa-calendar-alt', 'fa-calendar-alt', 'fa-calendar-alt', 'fa-calendar-alt'];

                        // Get the start date of the current week (Monday)
                        $startOfWeek = new DateTime('monday this week');

                        // Get the week number of the current week
                        $weekNumber = $startOfWeek->format('W');

                        // Loop through the days array to generate buttons
                        foreach ($days as $index => $day) {
                            // Calculate the date for the current day
                            $currentDay = clone $startOfWeek;
                            $currentDay->modify("+$index days");
                            $currentDate = $currentDay->format('Y-m-d');

                            // Output each button with the respective day, icon, and onclick event
                            echo '<button style="border: 1px solid #50727B;" class="tablinks" onclick="loadEvents(event, \'DailyTabs\', \'' . $currentDate . '\', ' . $weekNumber . ', \''. $day .'\')" style="flex: 1;">';
                            echo '<i class="fas ' . $icons[$index] . '"></i> ' . $day;
                            echo '</button>';
                        }
                        ?>
                    </div>


                        <div id="DailyTabs" class="tabcontent" style="height:764px; width:100%;">
                                
                                <!-- <div class="input-group">
                                    <button type="submit" class="btn btn-success" id="addEvent" name="addEvent" data-toggle="modal" data-target="#EventAddModal"><i class="fas fa-plus"></i> Event</button>
                                </div> -->

                              
                                <table class="table table-bordered" style="width: 100%;
                                        table-layout: fixed;">
                                    <thead class="table-success" style=" width: 40px;
                                        text-overflow: ellipsis;
                                        white-space: nowrap;  overflow-z: auto;">
                                        <tr>
                                        <th class="text-center">Appointment</th>
                                            <th class="text-center" style="width:20%;">Date</th>
                                            <th class="text-center" style="width:15%;">Time</th>
                                            <th class="text-center" style="width:20%;">Venue</th>
                                            
                                            <th class="text-center" style="width:8%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="taskTableBodyMonday">
                                    </tbody>
                                </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>