<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}


try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $studentID = $conn->real_escape_string($_POST['studentID']);
        $courseID = $conn->real_escape_string($_POST['courseID']);

        $query = "SELECT * FROM enrollments WHERE studentID = '$studentID' AND courseID = '$courseID' ";
        $result = $conn->query($query);

       if($result->num_rows > 0){   
        echo ('...............................................................The Student is already Enrolled to this Course!..........................................');
       }

       else{

        $query = "INSERT INTO enrollments (studentID, courseID) VALUES ('$studentID', '$courseID')";
        $conn->query($query);

        $query = "INSERT INTO grades (courseID, studentID) VALUES ('$courseID', '$studentID')";
        $conn->query($query);

       }

    }
} catch (Exception $e) {
    // Handle any exceptions or errors that occur
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
    /* Centering text in table cells */
    .table th, .table td {
        text-align: center;
    }

    .table-wrapper {
        height: 300px;
        max-height: 800px; /* table height */
        overflow-y: auto;  /* vertical scrolling */
    }

    .table-wrapper2 {
        height: 690px;
        max-height: 800px; /* table height */
        overflow-y: auto;  /* vertical scrolling */
    }

    table {
        width: 100%; /* Ensure table uses the full width */
    }

    .table2 {
        width: 150%; /* Ensure table uses the full width */
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
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="adminTable.php">Dashboard</a></li>
                </ul>
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

        <br>

        <form method="POST" action="" id="newEditForm">

            <div style="margin-top: 4%; height:100px; display:flex; justify-content:center;"> <!--This Covers Everything to the course table-->

                <div style="margin-right: 4%;"> <!--This Covers the heading, inputs and the buttons-->

                    <h3>ENROLL</h3>

                    <div class="input-group flex-nowrap" style="margin-bottom: 5px; width: 300px;">
                        <span class="input-group-text border border-primary" id="addon-wrapping">Student ID</span>
                        <input type="text" class="form-control border border-primary" name="studentID" placeholder="Enter Student ID" required>
                    </div>

                    <h3>TO</h3>

                    <div class="input-group flex-nowrap" style="margin-bottom: 5px; width: 300px;">
                        <span class="input-group-text border border-primary" id="addon-wrapping">Course ID</span>
                        <input type="text" class="form-control border border-primary" name="courseID" placeholder="Enter Course ID" required>
                    </div>

                    <div class="col-auto" style="display:flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary p-2" style="margin-right:5px;">Assign</button>
                        <button type="button" class="btn btn-outline-success p-2 w-40" onclick="window.location.href='showEnrolledStudents.php';">Go Back</button>
                    </div>

                </div> <!--This Covers the inputs and the buttons-->

                <div style="width: 700px;">
                    <h3>Student List</h3>
                    <div style="color:red; display:flex; flex-direction: column;">*Click a record to automatically get the ID</div>

                    <div class="table-wrapper">
                        <table class="table table-bordered table-hover table-sm table-success border border-black">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM students WHERE isDropped = 'false'";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                ?>
                                <?php foreach ($results as $result) : ?>
                                    <tr class="student-row" data-id="<?= htmlentities($result->studentID); ?>">
                                        <td><?= htmlentities($result->studentID); ?></td>
                                        <td><?= htmlentities($result->firstName) . " " . htmlentities($result->lastName); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <br>

                    <h3>Course List</h3>

                    <div class="table-wrapper">
                        <table class="table table-bordered table-hover table-sm table-success border border-black">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Teacher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM courses c LEFT JOIN teachers t ON c.teacherID = t.teacherID";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                ?>
                                <?php foreach ($results as $result) :?>
                                    <tr class="course-row" data-id="<?= htmlentities($result->courseID); ?>">
                                        <td><?= htmlentities($result->courseID); ?></td>
                                        <td><?= htmlentities($result->courseName); ?></td>
                                        <td><?= htmlentities($result->firstName)," ",htmlentities($result->lastName); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                        
                    </div>

                </div>

                <div style="margin-left:10px;"> <!--enrollments table-->

                <h3>Enrollments List</h3>

                                <div class="table-wrapper2 table2">
                                <table class="table table-bordered table-sm table-success border border-black">
                                        <thead>
                                            <tr>
                                                <th>Course ID</th>
                                                <th></th>
                                                <th>Student ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                        
                                            $courseFilter = isset($_GET['courseFilter']) ? $_GET['courseFilter'] : '';
                                            $sql = "SELECT * FROM courses c RIGHT JOIN enrollments e ON c.courseID = e.courseID LEFT JOIN students s ON s.studentID = e.studentID WHERE isDropped = 'false'";

                                            if ($courseFilter) {
                                                $sql .= " AND c.courseName = :courseFilter";
                                            }

                                            $query = $dbh->prepare($sql);
                                            if ($courseFilter) {
                                                $query->bindParam(':courseFilter', $courseFilter);
                                            }
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            $groupedResults = [];
                                            foreach ($results as $result) {
                                                $groupedResults[$result->courseID][] = $result;
                                            }

                                            foreach ($groupedResults as $courseID => $students) :
                                                $courseName = htmlentities($students[0]->courseName);
                                            ?>
                                                <tr>
                                                    <td colspan="3" style="font-weight: bold; text-align: center; background-color: white;"><?= $courseName ?></td>
                                                </tr>

                                                <?php foreach ($students as $student) : ?>

                                                    <tr>
                                                        <td><?= htmlentities($student->courseID); ?></td>
                                                        <td><?= htmlentities($student->firstName) . " " . htmlentities($student->lastName); ?></td>
                                                        <td><?= htmlentities($student->studentID); ?></td>
                                                    </tr>

                                                <?php endforeach; ?>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>

                </div>

            </div> <!--This Covers Everything to the course table-->

        </form>

    </section>

</body>

<script src="script.js"></script>
<script src="./assets/sweetalert/sweetalert2.min.js"></script>

<script>
    document.getElementById('newEditForm').addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: 'Enroll this Student to this Course?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Enroll Student',
            cancelButtonText: 'Cancel'
        })
        .then((result) => {
            if (result.isConfirmed) {
                    this.submit();
            }
        });
    });

    const studentRow = document.querySelectorAll('.student-row');
    studentRow.forEach(row => {
        row.addEventListener('click', function() {
            const studentID = row.getAttribute('data-id');
            document.querySelector('input[name="studentID"]').value = studentID;
        });
    });

    const courseRow = document.querySelectorAll('.course-row');
    courseRow.forEach(row => {
        row.addEventListener('click', function() {
            const courseID = row.getAttribute('data-id');
            document.querySelector('input[name="courseID"]').value = courseID;
        });
    });
</script>

</html>
