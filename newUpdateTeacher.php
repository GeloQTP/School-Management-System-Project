<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updateID = $conn->real_escape_string($_POST['updateID']);
        $newUsername = $conn->real_escape_string($_POST['newUserName']);
        $newFirstName = $conn->real_escape_string($_POST['newFirstName']);
        $newLastName = $conn->real_escape_string($_POST['newLastName']);
        $newPassword = $conn->real_escape_string($_POST['newPassword']);
        $newContactNumber = $conn->real_escape_string($_POST['newContactNumber']);
        $newAddress = $conn->real_escape_string($_POST['newAddress']);

        $query = "SELECT * FROM teachers WHERE teacherID = '$updateID'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $query = "UPDATE teachers SET username='$newUsername', firstName='$newFirstName', lastName='$newLastName', contactNumber='$newContactNumber', teacherAddress='$newAddress', passcode='$newPassword', userType = 'teacher' WHERE teacherID = $updateID";  
            $conn->query($query);
        } else {
            echo "ID does not exist.";
        }         
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>School Portal</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/sweetalert/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
    .table th, .table td {
        text-align: center;
    }
    .table-wrapper {
        height: 700px;
        max-height: 810px;
        overflow-y: auto;
    }
    table {
        width: 100%;
    }
    th, td {
        text-align: left;
        padding: 8px;
    }
</style>

<body>
    <div class="sidebar close">
        <div class="logo-details">
            <i class='bx bxl-c-plus-plus'></i>
            <span class="logo_name">Navigation</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="adminTable.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
                </a>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-collection'></i>
                        <span class="link_name">See Tables</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Tables</a></li>
                    <li><a href="newTeachersTable.php">Teachers Table</a></li>
                    <li><a href="newStudentTable.php">Students Table</a></li>
                    <li><a href="newCourseTable.php">Courses Table</a></li>
                    <li><a href="grades.php">Grades</a></li>
                </ul>
            </li>
            <li>
                <div class="profile-details">
                    <div class="profile-content">
                        <img src="image/profile.jpg" alt="profileImg">
                    </div>
                    <div class="name-job">
                        <div class="profile_name">Ernest</div>
                        <div class="job">Web Developer</div>
                    </div>
                    <a href="logout.php">
                        <i class='bx bx-log-out'></i>
                    </a>
                </div>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>
        <br><br>

        <div style="margin-left:30px; margin-right:30px;"> <!--margin-->
        <div style="display:flex;">  <!--flex div-->
            
                <div style="margin-right:20px; margin-top:6%;">  <!--group the label and inputs-->
                    
                <div class="h4 pb-2 mb-4 text-primary border-bottom border-primary">
                <h3>UPDATE TEACHER INFORMATION<h3>
                </div>

                <form method="POST" action="" id="newUpdateForm">
                    <div style="width:800px;">
                        
                        <div class="input-group input-group-sm mb-3" style="width:250px;">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-sm">Teacher ID</span>
                            <input type="text" class="form-control border border-black " name="updateID" placeholder=" Teacher ID"  readonly>
                        </div>

                        <div style="color:red;">*Matching Usernames is NOT accepted</div>

                        <div class="input-group flex-nowrap" style="margin-bottom: 15px;">
                            <span class="input-group-text border border-black" id="addon-wrapping">@</span>
                            <input type="text" class="form-control border border-black" name="newUserName" placeholder="Update Username" required>
                        </div>

                        <div class="input-group" style="margin-bottom: 15px;">
                            <span class="input-group-text border border-black">Update Name</span>
                            <input type="text" class="form-control border border-black" placeholder="First Name" name="newFirstName" required>
                            <input type="text" class="form-control border border-black" placeholder="Last Name" name="newLastName" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-default">Update Password</span>
                            <input type="text" class="form-control border border-black" name="newPassword" placeholder="Enter New Password" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-default">Contact Number</span>
                            <input type="text" class="form-control border border-black" name="newContactNumber" placeholder="Update Contact Number" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-default">Address</span>
                            <input type="text" class="form-control border border-black" name="newAddress" placeholder="Update Address" required>
                        </div>

                        <div class="col-auto" style="display:flex; justify-content: flex-end; margin-bottom:10px;">
                            <button type="submit" class="btn btn-primary p-2" style="margin-right:10px;">Update</button>
                            <button type="button" class="btn btn-outline-primary p-2 w-40" onclick="window.location.href='newTeachersTable.php';">Go Back</button>
                        </div>
                    </div>
                </form>
                </div>

                <div>
                <div style="color:red;">*Click on any record in the Table below to see its details fill in the input fields.</div>
                <div class="table-wrapper" style="width: 950px; border: 1px solid black;">
                    <table class="table table-bordered table-hover table-sm table-primary border border-black">
                        <thead>
                            <tr>
                                <th>Teacher ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM teachers";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            ?>

                            <?php foreach ($results as $result) :?>
                                <tr class="teacher-row" data-id="<?=htmlentities($result->teacherID); ?>" 
                                    data-username="<?=htmlentities($result->username); ?>" 
                                    data-firstname="<?=htmlentities($result->firstName); ?>" 
                                    data-lastname="<?=htmlentities($result->lastName); ?>" 
                                    data-contact="<?=htmlentities($result->contactNumber); ?>" 
                                    data-address="<?=htmlentities($result->teacherAddress); ?>" 
                                    data-password="<?=htmlentities($result->passcode); ?>">
                                    <td><?=htmlentities($result->teacherID); ?></td>
                                    <td><?=htmlentities($result->username); ?></td>
                                    <td><?=htmlentities($result->firstName); ?></td>
                                    <td><?=htmlentities($result->lastName); ?></td>
                                    <td><?=htmlentities($result->contactNumber); ?></td>
                                    <td><?=htmlentities($result->teacherAddress); ?></td>
                                    <td><?=htmlentities($result->passcode); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                </div>

            </div>
        </div>

    </section>

    <script src="script.js"></script>
    <script src="./assets/sweetalert/sweetalert2.min.js"></script>

    <script>
        document.getElementById('newUpdateForm').addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to Update this teacher?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update Teacher',
                confirmButtonColor: 'green',
                cancelButtonText: 'Cancel',
                cancelButtonColor: 'red'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Teacher Updated!",
                        text: "The Teacher has been Updated!",
                        icon: "success",
                    })
                    .then(() => {
                        this.submit();
                    });
                }
            });
        });

        const rows = document.querySelectorAll('.teacher-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                const teacherID = row.getAttribute('data-id');
                const username = row.getAttribute('data-username');
                const firstName = row.getAttribute('data-firstname');
                const lastName = row.getAttribute('data-lastname');
                const contactNumber = row.getAttribute('data-contact');
                const address = row.getAttribute('data-address');
                const password = row.getAttribute('data-password');
                
                document.querySelector('input[name="updateID"]').value = teacherID;
                document.querySelector('input[name="newUserName"]').value = username;
                document.querySelector('input[name="newFirstName"]').value = firstName;
                document.querySelector('input[name="newLastName"]').value = lastName;
                document.querySelector('input[name="newContactNumber"]').value = contactNumber;
                document.querySelector('input[name="newAddress"]').value = address;
                document.querySelector('input[name="newPassword"]').value = password;
            });
        });
    </script>
</body>
</html>
