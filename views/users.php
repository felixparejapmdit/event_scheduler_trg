<?php
// Include the database connection file
include '../databases/connection/db.php';
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
    <!-- Custom CSS -->
    <style>
          body {
            font-family: Arial, sans-serif; /* Specify a sans-serif font family */
        }
    </style>
</head>

<body>
<?php include '../layouts/sidemenu_bar.php'; ?>
    <div class="container mt-0">
        <h2>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal" style="margin-bottom: 10px;">
                <i class="fas fa-plus"></i> User<!-- Font Awesome folder-plus icon -->
            </button></h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <!-- PHP loop to display users -->
    <?php
    // Include database connection file
    include '../databases/connection/db.php';

    // Fetch users from the database
    $sql = "SELECT * FROM user ORDER BY id DESC, name ASC";
    $result = $conn->query($sql);
    $counter = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $counter++;
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td style='display: flex; align-items: center;'>"; // Use flexbox to align items
            if (!empty($row['profile_photo'])) {
                echo "<img src='../uploads/" . $row['profile_photo'] . "' alt='Profile Picture' style='color: #ADBC9F; cursor: pointer; width:50px; height:50px;'>";
            } else {
                echo "<i class='fas fa-user-circle' style='font-size: 50px; color: #ADBC9F; cursor: pointer;' onmouseover='this.style.color = '#436850';' onmouseout='this.style.color = '#ADBC9F';'></i>";
            }
            echo "<div style='margin-left: 10px;'>" . $row["name"] . "</div>";
            echo "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["contact"] . "</td>";
            echo '<td>
                    <a href="#" data-toggle="modal" id="edit-btn" data-target="#editUserModal" data-id="' . $row["id"] . '"><i class="fas fa-edit"></i></a>
                    <a href="#" data-id="' . $row["id"] . '" id="del-btn"><i class="fas fa-trash"></i></a>
                  </td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No users found</td></tr>";
    }
    ?>
</tbody>


        </table>
    </div>

    <!-- Modal for adding new user details -->
    <div class="modal fade" data-backdrop="static" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing user details -->
                <form method="post" id="addUserForm" name="addUserForm" action="../databases/queries/add_user.php">
                    <div class="form-group">
                        <label for="addName">Name</label>
                        <input type="text" class="form-control" id="addName" name="addName" required>
                    </div>
                    <div class="form-group">
                        <label for="addUserEmail">Email</label>
                        <input type="email" class="form-control" id="addUserEmail" name="addUserEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="addContact">Contact</label>
                        <input type="text" class="form-control" id="addContact" name="addContact" required>
                    </div>
                    <div class="form-group">
                        <label for="addSection">Section</label>
                        <select class="form-control" id="sectionSelect" name="sectionSelect" required onchange="toggleLocationInput1()">
                            <option value="" disabled selected>Select section</option>
                            <?php

                            // Query to fetch sections from the section table
                            $section_query = "SELECT * FROM section";
                            $section_result = mysqli_query($conn, $section_query);

                            // Iterate through each section and create an option in the dropdown
                            while ($row = mysqli_fetch_assoc($section_result)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }

                            // Add "others" option at the end of the dropdown
                            echo "<option value='Others'>Others</option>";

                            // Close the database connection
                        // mysqli_close($conn);
                            ?>
                        </select>


                        <!-- Input field for location -->
                        <input type="text" id="addSection" name="addSection" class="form-control mt-2" placeholder="Enter section" style="display: none;">

                <script>
                    function toggleLocationInput1() {
                        var sectionSelect = document.getElementById('sectionSelect');
                        var locationInput = document.getElementById('addSection');

                        // Check if the selected value is "Others"
                        if (sectionSelect.value === 'Others') {
                            locationInput.style.display = 'block'; // Show the location input field
                            locationInput.setAttribute('required', 'required'); // Make the input field required
                        } else {
                            locationInput.style.display = 'none'; // Hide the location input field
                            locationInput.removeAttribute('required'); // Remove the required attribute
                        }
                    }
                </script>


                    </div>
                    <input type="hidden" id="addUserId" name="addUserId">
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveUserBtn">Save</button>
                    </div>
                </form>

                </div>
                <div class="modal-footer">
                   
                </div>
            </div>
        </div>
    </div>

<!-- Modal for editing user details -->
<div class="modal fade" data-backdrop="static" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing user details -->
                <form method="post" id="editUserForm" name="editUserForm" action="../databases/queries/update_user.php">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="editName">
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="editUserEmail">
                    </div>
                    <div class="form-group">
                        <label for="editContact">Contact</label>
                        <input type="text" class="form-control" id="editContact" name="editContact">
                    </div>

                    <div class="form-group">
                        <label for="editPassword">Password</label>
                        <input type="text" class="form-control" id="editPassword" name="editPassword">
                    </div>

                    <div class="form-group">
                        <label for="editSection">Section</label>
                        <select class="form-control" id="editsectionSelect" name="editsectionSelect" required onchange="toggleLocationInput2()">
                            <option value="" disabled selected>Select section</option>
                            <?php
                          

                            // Query to fetch sections from the section table
                            $section_query_edit = "SELECT * FROM section";
                            $section_result_edit = mysqli_query($conn, $section_query_edit);
                            // Iterate through each section and create an option in the dropdown
                            while ($row = mysqli_fetch_assoc($section_result_edit)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }

                            // Add "others" option at the end of the dropdown
                            echo "<option value='Others'>Others</option>";

                            // Close the database connection
                            mysqli_close($conn);
                            ?>
                        </select>

                         <!-- Input field for location -->
         <input type="text" id="editSection" name="editSection" class="form-control mt-2" placeholder="Enter section" style="display: none;">

                    <script>
                        function toggleLocationInput2() {
                            var editsectionSelect = document.getElementById('editsectionSelect');
                            var locationInput = document.getElementById('editSection');

                            // Check if the selected value is "Others"
                            if (editsectionSelect.value === 'Others') {
                                locationInput.style.display = 'block'; // Show the location input field
                                locationInput.setAttribute('required', 'required'); // Make the input field required
                            } else {
                                locationInput.style.display = 'none'; // Hide the location input field
                                locationInput.removeAttribute('required'); // Remove the required attribute
                            }
                        }
                    </script>
                        </div>
                        <input type="hidden" id="editUserId" name="editUserId">
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateUserBtn">Update</button>
                    </div>
                    </div>

             
                </form>

                    <script>
                    // Edit user modal
                        $(document).on('click', '#edit-btn', function() {
                            var userId = $(this).data('id');
                            
                            $.ajax({
                                url: '../databases/queries/get_user.php',
                                type: 'POST',
                                data: {
                                    id: userId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    // Check if response contains error
                                    if (response.hasOwnProperty('error')) {
                                        alert('Error: ' + response.error);
                                    } else {
                                        // Populate the modal fields with user details
                                        $('#editUserId').val(response.id);
                                        $('#editName').val(response.name);
                                        $('#editUserEmail').val(response.email);
                                        $('#editContact').val(response.contact);
                                        $('#editPassword').val(md5(response.password));
                                        
                                        //$('#editsectionSelect').val(response.section); // Assuming you have a select element with id 'editSection'

                                        // Check if the section is not in the editVenueSelect dropdown
                                        var editVenueSelect = document.getElementById('editsectionSelect');
                                        var editSection = document.getElementById('editSection');
                                        var sectionNotFound = [...editVenueSelect.options].some(option => option.value === response.section);
                                    
                                        if (sectionNotFound) {
                                            // Set the dropdown value to "Others" and the section input value
                                            editVenueSelect.value = response.section;
                                            document.getElementById('editSection').style.display = 'none';
                                        } else {
                                            // Set the dropdown value to the section and hide the section input
                                            editVenueSelect.style.display = 'block';
                                            editVenueSelect.value = "Others";
                                            document.getElementById('editSection').style.display = 'block';
                                            editSection.value = response.section;
                                        }


                                        $('#editUserModal').modal('show');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Handle AJAX error
                                    console.error('AJAX Error:', error);
                                }
                            });
                        });



                        // Update user
                        $('#updateUserBtn').click(function() {
                            var userId = $('#editUserId').val();
                            var userName = $('#editName').val();
                            var userEmail = $('#editUserEmail').val();
                            $.ajax({
                                url: '../databases/queries/update_user.php',
                                type: 'POST',
                                data: {
                                    id: userId,
                                    name: userName,
                                    email: userEmail
                                },
                                success: function(response) {
                                    // Handle success response
                                    $('#editUserModal').modal('hide');
                                    // Reload page or update table
                                }
                            });
                        });
                    // Delete user confirmation
                    $(document).on('click', '#del-btn', function() {
                        var userId = $(this).data('id');

                        if (confirm('Are you sure you want to delete this user?')) {
                            // Proceed with deletion
                            $.ajax({
                                url: '../databases/queries/delete_user.php',
                                type: 'POST',
                                data: {
                                    id: userId
                                },
                                success: function(response) {
                                    // Handle success response
                                    // Reload page or update table
                                    location.reload(); // Example: Reload the page
                                },
                                error: function(xhr, status, error) {
                                    // Handle error response
                                    console.log(xhr.responseText); // Log the error message
                                }
                            });
                        }
                    });

            </script>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>



    
</body>

</html>
