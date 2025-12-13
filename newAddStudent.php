<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newUsername = $conn->real_escape_string($_POST['newUserName']);
        $newFirstName = $conn->real_escape_string($_POST['newFirstName']);
        $newLastName = $conn->real_escape_string($_POST['newLastName']);
        $newPassword = $conn->real_escape_string($_POST['newPassword']);
        $newContactNumber = $conn->real_escape_string($_POST['newContactNumber']);
        $newAddress = $conn->real_escape_string($_POST['newAddress']);

        $query = "INSERT INTO students (username, firstName, lastName, contactNumber, studentAddress, passcode, userType, isDropped) 
                  VALUES ('$newUsername', '$newFirstName', '$newLastName', '$newContactNumber', '$newAddress', '$newPassword', 'student', 'false')";
        $conn->query($query);
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
        height:810px;
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

        <div style="margin-left:30px; margin-right:30px;">
            <div style="display:flex;">

                <div style="margin-right:20px; margin-top:6%;">

                <div class="h4 pb-2 mb-4 text-success border-bottom border-success">
                <h3>ADD NEW STUDENTS<h3>
                </div>

                <div style="color:red;">*Matching Usernames is NOT accepted</div>

                    <form method="POST" action="" id="newAddForm">
                        <div style="width:800px;">
                            <br>
                            <div class="input-group flex-nowrap" style="margin-bottom: 15px;">
                                <span class="input-group-text border border-black" id="addon-wrapping">@</span>
                                <input type="text" class="form-control border border-black" name="newUserName" placeholder="Enter Username" required>
                            </div>

                            <div class="input-group" style="margin-bottom: 15px;">
                                <span class="input-group-text border border-black">Enter Name</span>
                                <input type="text" class="form-control border border-black" placeholder="First Name" name="newFirstName" required>
                                <input type="text" class="form-control border border-black" placeholder="Last Name" name="newLastName" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text border border-black" id="inputGroup-sizing-default"> Password</span>
                                <input type="text" class="form-control border border-black" name="newPassword" placeholder="Enter New Password" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text border border-black" id="inputGroup-sizing-default">Contact Number</span>
                                <input type="text" class="form-control border border-black" name="newContactNumber" placeholder="Enter Contact Number" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text border border-black" id="inputGroup-sizing-default">Address</span>
                                <input type="text" class="form-control border border-black" name="newAddress" placeholder="Enter Address" required>
                            </div>

                            <div class="col-auto" style="display:flex; justify-content: flex-end; margin-bottom:10px;">
                                <button type="submit" class="btn btn-success p-2" style="margin-right:10px;">ADD STUDENT</button>
                                <button type="button" class="btn btn-outline-primary p-2 w-40" onclick="window.location.href='newStudentTable.php';">Go Back</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-wrapper" style="width:950px; border: 1px solid black;">
                    <table class="table table-bordered table-hover table-sm table-success border border-black">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM students WHERE isDropped = 'false' ";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            ?>

                            <?php foreach ($results as $result) :?>
                                <tr>
                                    <td><?= htmlentities($result->studentID); ?></td>
                                    <td><?= htmlentities($result->username); ?></td>
                                    <td><?= htmlentities($result->firstName); ?></td>
                                    <td><?= htmlentities($result->lastName); ?></td>
                                    <td><?= htmlentities($result->contactNumber); ?></td>
                                    <td><?= htmlentities($result->studentAddress); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <script src="script.js"></script>
    <script src="./assets/sweetalert/sweetalert2.min.js"></script>

    <script>
        document.getElementById('newAddForm').addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to Add this Student?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Add Student',
                confirmButtonColor: 'green',
                cancelButtonText: 'Cancel',
                cancelButtonColor: 'red'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Student Added!",
                        text: "The Student has been Added!",
                        icon: "success",
                    })
                    .then(() => {
                        this.submit();
                    });
                }
            });
        });
    </script>
</body>
</html>
