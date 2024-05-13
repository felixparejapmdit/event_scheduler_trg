<!-- HTML code -->
<div class="week-navigation-container">
    <button onclick="previousWeek()"><i class="fas fa-arrow-left"></i></button>
    <label id="week-number">Week Number: </label>
    <label id="dateperiod_label">Date Period: </label>
    <button onclick="nextWeek()"><i class="fas fa-arrow-right"></i></button>
</div>

<script>
// JavaScript code
function updateDatePeriod(startDate, endDate) {
    // Format the start and end dates
    var startDateFormatted = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    var endDateFormatted = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

    // Update the date period label
    document.getElementById("dateperiod_label").textContent = "Date Period: " + startDateFormatted + " - " + endDateFormatted;
}

function previousWeek() {
    // Get the start and end dates from the date period label
    var datePeriodText = document.getElementById("dateperiod_label").textContent;
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
    document.getElementById("week-number").textContent = "Week Number: " + getWeekNumber(startDate);

    // Update the date period label
    updateDatePeriod(startDate, endDate);
}

function nextWeek() {
    // Get the start and end dates from the date period label
    var datePeriodText = document.getElementById("dateperiod_label").textContent;
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
    document.getElementById("week-number").textContent = "Week Number: " + getWeekNumber(startDate);

    // Update the date period label
    updateDatePeriod(startDate, endDate);
}

function getWeekNumber(date) {
    // Calculate the week number using ISO 8601 standard
    var onejan = new Date(date.getFullYear(), 0, 1);
    var weekNumber = Math.ceil((((date - onejan) / 86400000) + onejan.getDay() + 1) / 7);
    return weekNumber;
}

// Call updateDatePeriod() function on page load to display the current week
window.onload = function() {
    // Get the current date
    var currentDate = new Date();

    // Calculate the start date of the current week (Monday)
    var currentDayOfWeek = currentDate.getDay(); // 0 (Sunday) to 6 (Saturday)
    var daysToSubtract = currentDayOfWeek === 0 ? 6 : currentDayOfWeek - 1; // Subtract days to get to Monday
    var startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() - daysToSubtract);

    // Calculate the end date of the current week (Sunday)
    var endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + 6);

    // Update the week number label
    document.getElementById("week-number").textContent = "Week Number: " + getWeekNumber(startDate);

    // Update the date period label
    updateDatePeriod(startDate, endDate);
};
</script>
