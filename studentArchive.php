<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

try {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enrollID = $conn->real_escape_string($_POST['enrollID']);

        // Check if student exists
        $query = "UPDATE students SET isDropped = 'false' WHERE studentID = '$enrollID' ";
        $result = $conn->query($query);
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

    <style>
        /* Centering text in table cells */
        .table th,
        .table td {
            text-align: center;
        }

        /* Table Wrapper for vertical scrolling */
        .table-wrapper {
            height:500px;
            max-height: 500px;
            overflow-y: auto;
        }

        table {
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }
    </style>
</head>

<body>

    <!-- Sidebar Section -->
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

    <!-- Main Content Section -->
    <section class="home-section">


        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>


        <div style="margin-top:8%; display:flex; justify-content:center;">



            <!-- Enroll student Form Section -->
            <div style="margin-right: 1%; margin-top:8%;">
            <div class="h4 pb-2 mb-4 text-primary border-bottom border-primary">
            RE-ENROLL STUDENTS
            </div>
                <form method="POST" id="newEnrollForm">
                    <!-- Student ID Input -->
                    <div class="input-group input-group-sm mb-3" style="width: 300px;">
                        <span class="input-group-text border border-primary" id="inputGroup-sizing-sm">Student ID</span>
                        <input type="text" class="form-control border border-primary" name="enrollID" placeholder="Enter ID" required>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary p-2" style="margin-right:10px;">Re-Enroll Student</button>
                        <button type="button" class="btn btn-outline-primary p-2 w-40" onclick="window.location.href='newDeleteStudent.php';">Go Back</button>
                    </div>
                </form>
            </div>




            <!-- Student Records Table Section -->
            <div style = "width: 40%;">
                <div style="color:red;">*Click a record to automatically get the ID</div>
                <div class="table-wrapper" style="border: 1px solid blue;">
                    <table class="table table-bordered table-hover table-sm table-primary border border-primary">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetching students from the database
                            $sql = "SELECT * FROM students WHERE isDropped = 'true' ";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            ?>

                            <?php foreach ($results as $result) : ?>
                            <tr class="student-row" 
                                data-id="<?= htmlentities($result->studentID); ?>" 
                                data-username="<?= htmlentities($result->username); ?>" 
                                data-firstname="<?= htmlentities($result->firstName); ?>" 
                                data-lastname="<?= htmlentities($result->lastName); ?>" >
                                <td><?= htmlentities($result->studentID); ?></td>
                                <td><?= htmlentities($result->username); ?></td>
                                <td><?= htmlentities($result->firstName); ?></td>
                                <td><?= htmlentities($result->lastName); ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
        
    </section>

    <!-- Scripts -->
    <script src="script.js"></script>
    <script src="./assets/sweetalert/sweetalert2.min.js"></script>

    <script>
        // Enroll confirmation and submission
        document.getElementById('newEnrollForm').addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to Enroll this Student?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Enroll Student',
                confirmButtonColor: 'red',
                cancelButtonText: 'Cancel'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Student Enrolled!",
                        text: "The Student has been Enrolled!",
                        icon: "success"
                    })
                    .then(() => {
                        this.submit();
                    });
                }
            });
        });

        // Assign student ID on row click
        const rows = document.querySelectorAll('.student-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                const studentID = row.getAttribute('data-id');
                document.querySelector('input[name="enrollID"]').value = studentID;
            });
        });
    </script>

</body>

</html>
