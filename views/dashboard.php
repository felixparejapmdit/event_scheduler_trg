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

// Function to get count of events
function getEventCount($conn, $condition = "") {
    // SQL query to count the number of events
    $sql = "SELECT COUNT(*) AS event_count FROM events";

    // Add condition if provided
    if (!empty($condition)) {
        $sql .= " WHERE " . $condition;
    }

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

// Function to get count of users
function getUserCount($conn) {
    // SQL query to count the number of users
    $sql = "SELECT COUNT(*) AS user_count FROM user";

    // Execute the SQL query
    $result = $conn->query($sql);

    // Check if result is not null
    if ($result->num_rows > 0) {
        // Fetch and return the user count
        $row = $result->fetch_assoc();
        return $row["user_count"];
    } else {
        return 0; // No users found
    }
}



// Get the count of today's events (assuming you have a column `event_date` in your events table)
if($_SESSION['role'] == 1)
{
    $today_event_count_pmd = getEventCount($conn, "is_display = 1 AND DATE(date) = CURDATE() AND event_type = 1");
    $today_event_count_section = getEventCount($conn, "DATE(date) = CURDATE() AND event_type = 2");

    // Get the count of tomorrow's events
    $tomorrow_event_count_pmd = getEventCount($conn, "is_display = 1 AND DATE(date) = CURDATE() + INTERVAL 1 DAY AND event_type = 1");
    $tomorrow_event_count_section = getEventCount($conn, "DATE(date) = CURDATE() + INTERVAL 1 DAY AND event_type = 2");

    // Get the count of upcoming events (excluding today and tomorrow)
    $upcoming_event_count_pmd = getEventCount($conn, "is_display = 1 AND DATE(date) > CURDATE() + INTERVAL 1 DAY AND event_type = 1");
    $upcoming_event_count_section = getEventCount($conn, "DATE(date) > CURDATE() + INTERVAL 1 DAY AND event_type = 2");

    // SQL query to count the number of previous events
    $prev_event_count_pmd = getEventCount($conn, "is_display = 1 AND DATE(date) < CURDATE() AND event_type = 1");
    $prev_event_count_section = getEventCount($conn, "DATE(date) < CURDATE() AND event_type = 2");

    $eventlabel_pmd = "PMD";
    $eventlabel_section = "Section";
}
else
{
    // Get the user ID from session
    // Get the count of today's events for PMD and Section filtered by prepared_by user ID
    $today_event_count_pmd = getEventCount($conn, "DATE(date) = CURDATE() AND event_type = 1 AND prepared_by = $userid");
    $today_event_count_section = getEventCount($conn, "DATE(date) = CURDATE() AND event_type = 2 AND prepared_by = $userid");

    $tomorrow_event_count_pmd = getEventCount($conn, "DATE(date) = CURDATE() + INTERVAL 1 DAY AND event_type = 1 AND prepared_by = $userid");
    $tomorrow_event_count_section = getEventCount($conn, "DATE(date) = CURDATE() + INTERVAL 1 DAY AND event_type = 2 AND prepared_by = $userid");

    // Get the count of upcoming events (excluding today and tomorrow)
    $upcoming_event_count_pmd = getEventCount($conn, "DATE(date) > CURDATE() + INTERVAL 1 DAY AND event_type = 1 AND prepared_by = $userid");
    $upcoming_event_count_section = getEventCount($conn, "DATE(date) > CURDATE() + INTERVAL 1 DAY AND event_type = 2 AND prepared_by = $userid");

    // SQL query to count the number of previous events
    $prev_event_count_pmd = getEventCount($conn, "DATE(date) < CURDATE() AND event_type = 1 AND prepared_by = $userid");
    $prev_event_count_section = getEventCount($conn, "DATE(date) < CURDATE() AND event_type = 2 AND prepared_by = $userid");

    $eventlabel_pmd = "My Section";
    $eventlabel_section = "My Schedule";
}

// Get the count of users
$user_count = getUserCount($conn);
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
    
        /* Add custom CSS for dashboard styling and animations */
        body {
            transition: background-color 0.5s ease;
            transition: opacity 1s ease-in-out;
            background-color: #ffffff!important; /* Default background color */
            font-family: Arial, sans-serif; /* Specify a sans-serif font family */
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 24px;
            margin-bottom: 0;
            background-color:#f8f9fa!important;
        }
        .card-text{
            color: #212529;
        }
        .number {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            transition: color 0.3s ease;
        }

        .card:hover .number {
            color: #007bff;
        }
        .number-hover {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            transition: transform 0.3s ease;
        }

        .number-hover:hover {
            transform: scale(1.1);
            color: #007bff;
            cursor: pointer;
        }

        .event-count {
        display: flex;
        justify-content: space-around;
        align-items: revert;
        margin-bottom: 8px; /* Adjust as needed */
        }

        .event-count p {
            margin: 0;
        }

        .card-title {
            margin-bottom: 12px; /* Adjust as needed */
        }

</style>
</head>
<body>
<?php include '../layouts/sidemenu_bar.php'; ?>
    <div class="container">
        <h2 class="mt-0 mb-4">Dashboard</h2>
        
        <div class="row">

        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Today</h5>
                    <div class="event-count">

                    <table class="table table-bordered" style="margin-right:15px;">
                        <thead class="table-dark">
                            <tr>
                                <th colspan=3>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include database connection file
                            include '../databases/connection/db.php';

                            // Fetch users from the database
                            $sql = "SELECT s.id,u.role, s.name, COUNT(CASE WHEN DATE(e.date) = CURDATE() AND e.event_type = 1 AND u.section <> '' THEN e.prepared_by END) AS event_count 
                            FROM user u 
                            LEFT JOIN events e ON u.id = e.prepared_by 
                            LEFT JOIN section s ON s.id = u.section
                            WHERE u.section <> '' AND u.id NOT IN(1, 14, 40) AND s.id <> 1
                            GROUP BY u.section 
                            ORDER BY u.section ASC";
                            
                            //echo $sql ;
                            $result = $conn->query($sql);
                            $counter = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $counter++;
                                    echo "<tr>";
                                    echo "<td>" . $counter . "</td>";
                                    echo "<td>" . $row["name"] . "</td>";
                                    echo "<td><a href='../views/sched.php?sectionid=" . $row["id"] . "&role=" . $row["role"] . "'>" . $row["event_count"] . "</a></td>";
                                    echo "</tr>";
                                }
                                } 
                            ?>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan=3>Staff</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Include database connection file
                                include '../databases/connection/db.php';

                                // Fetch users from the database
                                    $sql = "SELECT u.name, u.id, u.role, 
                                            COUNT(CASE WHEN DATE(e.date) = CURDATE() AND e.event_type = 2 AND u.section <> '' THEN e.prepared_by END) AS event_count 
                                            FROM user u 
                                            LEFT JOIN events e ON u.id = e.prepared_by 
                                            WHERE u.section <> '' AND u.id NOT IN(1, 14, 15, 16, 17, 40) 
                                            GROUP BY u.name, u.id, u.role 
                                            ORDER BY CASE WHEN u.id = 43 THEN 0 ELSE 1 END, u.name ASC";
                                            //echo $sql;
                                            $result = $conn->query($sql);
                                            $counter = 0;
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td>" . $counter . "</td>";
                                                    echo "<td>" . $row["name"] . "</td>";
                                                    echo "<td><a href='../views/sectionhead.php?userid=" . $row["id"] . "&role=" . $row["role"] . "'>" . $row["event_count"] . "</a></td>";
                                                    echo "</tr>";
                                                }
                                                        } 
                                    ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="event-count">
                        
                    </div>
                </div>
            </div>
        </div>
        <script>
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
        }
        </script>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
