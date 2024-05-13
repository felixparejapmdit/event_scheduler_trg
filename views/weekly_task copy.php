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
<?php include '../layouts/menu_bar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/tabs.css">
    <title>My Schedule</title>
    <link rel="icon" href="images/scheduler.ico" type="image/x-icon">

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
            background-color: #ffffff; /* Default background color */
            font-family: "Calibri";
        }
       </style>
</head>

<body>

<!-- Add Week Goals Modal -->
<div class="modal fade" data-backdrop="static" id="EventAddModal" tabindex="-1" aria-labelledby="EventAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EventAddModalLabel">Add Weekly Goal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="goalForm" name="goalForm" action="../views/weekly_task.php">
                    <div class="form-group">
                        <label for="goal" style="font-weight:bold;">Goal:</label>
                        <textarea class="form-control" id="goal" name="goal" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status" style="font-weight:bold;">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                        <option value="" disabled selected>Select status</option>
                            <option value="Pending">Pending</option>
                            <option value="InProgress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="addnotes" style="font-weight:bold;">Additional Notes:</label>
                        <textarea class="form-control" id="addnotes" name="addnotes" required></textarea>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveGoal">Save</button>
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
                <input type="hidden" id="editDate" name="editDate">
                    <div class="form-group">
                        <label for="goal">Time:</label>
                        <input type="time" class="form-control" id="editTime" name="editTime" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_venue">Venue:</label>
                        <input type="text" class="form-control" id="editVenue" name="editVenue" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_details">Details:</label>
                        <textarea type="text" class="form-control" id="editDetails" name="editDetails" required></textarea>
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

<script>
    // JavaScript to populate event details in the modal fields when edit icon is clicked
    $(document).ready(function () {
     
        // Attach a click event listener to the document
        $(document).on('click', '.edit-event', function () {
            const eventId = $(this).data('event-id');

            // Send AJAX request to fetch event details
            fetch('../databases/queries/get_event_details.php?id=' + eventId)
                .then(response => response.json())
                .then(data => {
                    // Populate the modal fields with the fetched data
                    $('#editEventId').val(data.id);
                    $('#editDate').val(data.date);
                    $('#editTime').val(data.time);
                    $('#editVenue').val(data.location);
                    $('#editDetails').val(data.title);
                })
                .catch(error => console.error('Error:', error));
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


    });

</script>

<script>
    $(document).ready(function () {
        window.pageLoad = true;
        $('#saveGoal').click(function () {
            var goal = $('#goal').val();
            var status = $('#status').val();

            var addnotes = $('#addNotes').val();
            // Append new row to the table
            $('#goalTableBody').append('<tr><td>' + goal + '</td><td>' + status + '</td></tr>');
            $('#notesTableBody').append('<tr><td>' + addnotes + '</td></tr>');

        });
        // $('#saveEvent').click(function () {
        //     var time = $('#time_Monday_0').val();
        //     var event = $('#details_Monday_0').val();
        //     var action = "edit/delete";

        //     // Append new row to the table
        //     $('#taskTableBody').append('<tr><td>' + time + '</td><td>' + event + '</td><td>' + action + '</td></tr>');

        // });
    });
</script>

<div class="d-flex mt-2">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="currentWeekDates" style="font-weight: bold;"></div>
                    <button class="btn btn-link" id="prevWeekBtn" onclick="navigateWeek('prev')" style="margin-right: -162x;">&lt;</button>
                    <div class="week-number" style="font-weight: bold; margin: 5px;"></div>
                    <button class="btn btn-link" id="nextWeekBtn" onclick="navigateWeek('next')" style="margin-left: -162x;">&gt;</button>
                </div>
            </div>
            
            <script>
                // Function to get the start and end dates of the current week
                function getCurrentWeekDates() {
                    var today = new Date();
                    var dayOfWeek = today.getDay();
                    var startDate = new Date(today); // Create a copy of the current date
                    startDate.setDate(today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1)); // Set to Monday of the current week
                    var endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + 6); // Set to Sunday of the current week

                    return { startDate: startDate, endDate: endDate };
                }

                // Function to format the date as "Month Day, Year" (e.g., "February 9, 2024")
                function formatDate(date) {
                    var options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return date.toLocaleDateString('en-US', options);
                }

                // Function to insert the current week's date period into the HTML
                function insertCurrentWeekDates(startDate) {
                    var endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + 6);

                    // Format start date
                    var startDateFormatted = formatDate(startDate);
                    var startDateMonth = getMonthName(startDate.getMonth());
                    var startDateDay = startDate.getDate();

                    // Format end date
                    var endDateFormatted = formatDate(endDate);
                    var endDateMonth = getMonthName(endDate.getMonth());
                    var endDateDay = endDate.getDate();

                    // Construct the formatted date range
                    var formattedDateRange = startDateMonth + ' ' + startDateDay + ' - ' + endDateMonth + ' ' + endDateDay + ', ' + startDate.getFullYear();

                    // Create a span element for formatting
                    var spanElement = document.createElement('span');
                    spanElement.innerText = formattedDateRange;
                    spanElement.style.fontWeight = 'normal';

                    // Update the value of the date_tosave input field
                    //document.getElementById('date_tosave').value = startDate;
                    // Append the span element to the HTML element
                    var currentWeekDatesElement = document.getElementById('currentWeekDates');
                    currentWeekDatesElement.innerHTML = 'Date Period: ';
                    currentWeekDatesElement.appendChild(spanElement);
                }

                // Function to get the month name from the month index
                function getMonthName(monthIndex) {
                    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    return months[monthIndex];
                }

                // Function to navigate to the previous or next week
                function navigateWeek(direction) {
                    var currentDate = getCurrentWeekDates().startDate;
                    var newDate = new Date(currentDate); // Create a copy of the current date
                    console.log("New Date: " + newDate);
                    if (direction === 'prev') 
                    {
                        newDate.setDate(newDate.getDate() - 7); // Subtract 7 days to go to the previous week
                    } else if (direction === 'next') {
                        newDate.setDate(newDate.getDate() + 7); // Add 7 days to go to the next week
                    }

                    console.log("Adjusted Date: " + newDate);

                    insertCurrentWeekDates(newDate);
                    updateWeekNumber(newDate); // Update the week number display
                }

                // Function to update the week number display
                function updateWeekNumber(startDate) {
                    var weekNumber = getWeekNumber(startDate);
                    document.querySelector('.week-number').innerText = 'Week ' + weekNumber;
                }

                // Function to get the ISO week number of a date
                function getWeekNumber(date) {
                    var d = new Date(date);
                    d.setHours(0, 0, 0, 0);
                    d.setDate(d.getDate() + 4 - (d.getDay() || 7));
                    var yearStart = new Date(d.getFullYear(), 0, 1);
                    var weekNumber = Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
                    return weekNumber;
                }

                // Call the function to insert the current week's dates when the page loads
                window.onload = function() {
                    var today = new Date();
                    var dayOfWeek = today.getDay();
                    var diffToMonday = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust for Sunday
                    var startOfWeek = new Date(today.setDate(diffToMonday));
                    insertCurrentWeekDates(startOfWeek);
                    updateWeekNumber(startOfWeek);
                };
            </script>

            <div class="col-md-3" style="display:none;">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#EventAddModal" style="margin-bottom: 10px;">
                    <i class="fas fa-plus"></i> Weekly Goal
                </button>
                <table class="table table-bordered" style="height:400px;">
                    <thead class="table-success" style=" width: 40px;
                                    text-overflow: ellipsis;
                                    white-space: nowrap;  overflow-z: auto;">
                    <tr>
                        <th class="text-center" style="width:80%;">Goal</th>
                        <th class="text-center" style="width:20%;">Status</th>
                    </tr>
                    </thead>
                    <tbody id="goalTableBody">
                        <tr>
                        </tr>
                    </tbody>
                </table>


                <table class="table table-bordered" style="height:198px;">
                    <thead class="table-success" style=" width: 40px;
                                    text-overflow: ellipsis;
                                    white-space: nowrap;  overflow-z: auto;">
                    <tr>
                        <th class="text-center" style="width:80%;">Additional Notes</th>
                    </tr>
                    </thead>
                    <tbody id="notesTableBody">
                        <tr>
                        </tr>
                    </tbody>
                </table>


                <!-- <form id="goalForm">
                    <div class="form-group">
                        <label for="addnotes" style="font-weight:bold; color:#12372A;">Additional Notes:</label>
                        <textarea type="text" class="form-control custom-cursor" id="addnotes" name="addnotes" style="height:168px; resize: none; overflow: auto; font-family: Arial; font-size: 14px; color: black; border: 1px solid #cccccc;"></textarea>
                    </div>
                    <style>
                        .custom-cursor {
                            cursor: url('images/pencil_cursor.png'), auto;
                        }
                    </style>
                </form> -->
            </div>

            <div class="col-md-12">
                    <script>
                        document.addEventListener('DOMContentLoaded', function() 
                        {
                            // Initially open the first tab (Monday)
                            document.getElementById('DailyTabs').style.display = "block";

                            // Get the current week's start date
                            var currentWeekStartDate = getCurrentWeekDates().startDate;

                            // Calculate the date for Monday in the current week
                            var mondayDate = new Date(currentWeekStartDate);

                            // Calculate the week number based on the current date
                            var weekNumber = getWeekNumber(mondayDate);

                            // Call the loadEvents function with the cityName as "DailyTabs" and currentDate as formatted Monday date
                            loadEvents(null, 'DailyTabs', formatDate(mondayDate), weekNumber);
                        });

                        // Function to format the date
                        function formatDate(date) {
                            return date.toISOString().slice(0,10); // Format as YYYY-MM-DD for input type date
                        }

                        function loadEvents(evt, cityName, currentdate, weeknumber) {
                    
                        // Declare all variables
                        var i, tabcontent, tablinks;
                    
                        // Get the day of the selected city
                        var selectedDay = cityName.substring(0, cityName.indexOf("Tabs"));

                        // Get the current week's start date
                        var currentWeekStartDate = getCurrentWeekDates().startDate;
                        
                        // Calculate the date based on the selected day and the current week's start date
                        var selectedDate = new Date(currentWeekStartDate);
                        switch (selectedDay) {
                            case "Monday":
                                selectedDate.setDate(currentWeekStartDate.getDate());
                                break;
                            case "Tuesday":
                                selectedDate.setDate(currentWeekStartDate.getDate() + 1);
                                break;
                            case "Wednesday":
                                selectedDate.setDate(currentWeekStartDate.getDate() + 2);
                                break;
                            case "Thursday":
                                selectedDate.setDate(currentWeekStartDate.getDate() + 3);
                                break;
                            case "Friday":
                                selectedDate.setDate(currentWeekStartDate.getDate() + 4);
                                break;
                            case "Saturday":
                                selectedDate.setDate(currentWeekStartDate.getDate() + 5);
                                break;
                            case "Sunday":
                                selectedDate.setDate(currentWeekStartDate.getDate() + 6);
                                break;
                        }

                        // Update the value of the date_tosave input field
                        document.getElementById('date_tosave').value = currentdate;
                        document.getElementById('week_number').value = weeknumber;

                        var date_tosave = currentdate;
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

                        // Get all elements with class="tabcontent" and hide them
                        tabcontent = document.getElementsByClassName("tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                            tabcontent[i].style.display = "none";
                        }
                        if (window.pageLoad === true) {
                        // Get all elements with class="tablinks" and remove the class "active"
                        tablinks = document.getElementsByClassName("tablinks");
                        for (i = 0; i < tablinks.length; i++) {
                            tablinks[i].classList.remove("active");
                            window.pageLoad = true ;
                            
                        }
                    }
                        // Show the current tab, and add an "active" class to the link that opened the tab
                        document.getElementById(cityName).style.display = "block";
                        evt.currentTarget.classList.add("active");
                    }
                
                    </script>


                    <div id="weekly_events">
            
                <div class="tab" style="height: 664px;width:20%;">
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
                            echo '<button class="tablinks' . ($index === 0 ? ' active' : '') . '" onclick="loadEvents(event, \'DailyTabs\', \'' . $currentDate . '\', ' . $weekNumber . ')">';
                            echo '<i class="fas ' . $icons[$index] . '"></i> ' . $day;
                            echo '</button>';
                        }
                    ?>
                </div>

                        <div id="DailyTabs" class="tabcontent" style="height:664px; width:80%;">
                                <form method="post" id="addDailyEvent" name="addDailyEvent" action="../databases/queries/add_event.php">
                                    
                                    <input type="hidden" id="savetype" name="savetype" value="weekly">
                                    <input type="hidden" id="date_tosave" name="date_tosave">
                                    <input type="hidden" id="week_number" name="week_number">
                                    
                                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>">
                                    <script>
                                        // Function to format the date
                                        function formatDate(date) {
                                            return date.toISOString().slice(0,10); // Format as YYYY-MM-DD for input type date
                                        }
                                        // Get the current week's start date
                                        var currentWeekStartDate = getCurrentWeekDates().startDate;
                                        // Calculate the date based on the selected day and the current week's start date
                                        var selectedDate = new Date(currentWeekStartDate);
                                        selectedDate.setDate(currentWeekStartDate.getDate());
                                        // Format the selected date
                                        var formattedDate = selectedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                                    
                                        // Update the value of the datetosave input field
                                       // document.getElementById('date_tosave').value = formatDate(selectedDate);
                                    </script>

                                    <table class="table table-bordered" style="padding:1px;">

                                    <div class="input-group">
                                        <label  for="allDayEventsCheckbox" style="font-weight:bold;color:red">All Day Event?</label>
                                        <input type="checkbox" id="allDayEventsCheckbox">
                                    </div>
                                    <div class="input-group" style="display:none;">
                                        <label for="daily_notes" style="font-weight:bold; color:#12372A;">Notes:</label>
                                        <textarea id="daily_notes" class="form-control" name="daily_notes" style="resize: none; overflow: auto; font-family: Arial; font-size: 14px; color: black; border: 1px solid #cccccc;" disabled></textarea>
                                    </div>

                                    <script>
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
                                    </script>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var allDayEventsCheckbox = document.getElementById('allDayEventsCheckbox');

                                            var timelabel = document.getElementById('labeltime');
                                            var time = document.getElementById('event_time');
                                            var venue = document.getElementById('event_venue');
                                            var details = document.getElementById('event_details');
                                            var addbutton = document.getElementById('addEvent');

                                            allDayEventsCheckbox.addEventListener('change', function() {
                                    
                                                // Update time required attribute based on checkbox state
                                                if (this.checked) {
                                                    time.removeAttribute('required');
                                                    timelabel.style.display = 'none';
                                                    time.style.display = 'none';
                                                    time.value = "All Day Event";
                                                    venue.style.display = 'block';
                                                    details.style.display = 'block';
                                                    addbutton.style.display = 'block';
                                                    venue.focus();
                                                } else {
                                                    time.setAttribute('required', 'required');
                                                    timelabel.style.display = 'block';
                                                    time.style.display = 'block';
                                                    venue.style.display = 'block';
                                                    details.style.display = 'block';
                                                    addbutton.style.display = 'block';
                                                }
                                            });

                                        
                                        });
                                    </script>
                                    <thead >
                                        <tr style="padding:1px;">
                                            <th>UPCOMING EVENTS/DEADLINES</th>
                                        </tr>
                                    </thead>
                                    <tbody id="taskTableBody1">
                                        <tr>
                                            <td>
                                                <div id="inputGroupContainerMonday">
                                                        <div class="input-group">
                                                            <label for="event_time" id="labeltime" style="font-weight:bold; color:#12372A;">Time: </label>
                                                            <input type="time" class="form-control" id="event_time" name="event_time" style="margin-right:4px;" required>
                                                            <label for="venue" style="font-weight:bold; color:#12372A;">Venue: </label>
                                                            <input type="text" class="form-control" id="event_venue" name="event_venue" required>
                                                        </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <label for="event_details" style="font-weight:bold; color:#12372A;">Details:</label>
                                                    <textarea type="text" class="form-control" id="event_details" name="event_details" style="resize: none;" required></textarea>
                                                </div>
                                                <div class="input-group">
                                                    <button class="btn btn-primary" style="display:none;">Add Row</button>
                                                    <button type="submit" class="btn btn-success" id="addEvent" name="addEvent">Add</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </form>
                                <table class="table table-bordered" style="width: 100%;
                                        table-layout: fixed;">
                                    <thead class="table-success" style=" width: 40px;
                                        text-overflow: ellipsis;
                                        white-space: nowrap;  overflow-z: auto;">
                                        <tr>
                                            <th class="text-center">Time</th>
                                            <th class="text-center">Venue</th>
                                            <th class="text-center">Details</th>
                                            <th class="text-center">Action</th>
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