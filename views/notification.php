<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Banner</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">

    <style>
        .notification-banner {
    position: fixed;
    bottom: 20px;
    left: 20px;
    width: 300px;
    background-color: #007bff;
    color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    display: none;
    z-index: 9999;
}

.notification-content {
    display: flex;
    align-items: center;
    padding: 10px;
}

.notification-icon {
    margin-right: 10px;
}

.notification-message {
    flex-grow: 1;
}

.notification-close {
    cursor: pointer;
}

    </style>
    
</head>
<body>

<!-- Notification Banner -->
<div id="notificationBanner" class="notification-banner">
    <div class="notification-content">
        <div class="notification-icon">
            <i class="fas fa-bell"></i>
        </div>
        <div class="notification-message">
            Your event is starting soon!
        </div>
        <div class="notification-close">
            <i class="fas fa-times"></i>
        </div>
    </div>
</div>

<!-- Rest of your HTML content -->

<!-- JavaScript -->
<script>
    // Event time (replace with your event time)
    var eventTime = new Date('2024-02-13T16:24:00'); // Event time is 4:13 PM

    // Calculate notification time (one hour before event time)
    var notificationTime = new Date(eventTime.getTime() - (60 * 60 * 1000)); // One hour before event time

    // Current time
    var currentTime = new Date();
    // Check if it's time to show the notification
    if ( currentTime <= eventTime) {
        // Show notification banner
        
        document.getElementById('notificationBanner').style.display = 'block';
    }

    // Close notification banner
    document.querySelector('.notification-close').addEventListener('click', function() {
        document.getElementById('notificationBanner').style.display = 'none';
    });
</script>


</body>
</html>
