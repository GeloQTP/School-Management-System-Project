<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
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
        height: 500px;
        max-height: 500px; /* Table height */
        overflow-y: auto;  /* Vertical scrolling */
    }

    table {
        width: 100%; /* Ensure table uses the full width */
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

        <div style="margin-top: 5%; margin-left: 20px; margin-right: 20px;"> <!-- Lists Start -->
            <div class="h4 pb-2 mb-4 text-success border-bottom border-success">
                LIST OF COURSES
            </div>

            <div style="display: flex; justify-content: space-between;">
                <div style="display: flex;">
                    <div class="col-auto" style="margin-right: 5px;">
                        <button type="button" class="btn btn-success p-2" onclick="window.location.href='newAddCourse.php';">ADD Course</button>
                    </div>
                    <div class="col-auto" style="margin-right: 5px;">
                        <button type="submit" class="btn btn-primary p-2" onclick="window.location.href='newEditCourse.php';">EDIT Course Name</button>
                    </div>
                    <div class="col-auto" style="margin-right: 5px;">
                        <button type="button" class="btn btn-danger p-2" onclick="window.location.href='newDeleteCourse.php';">DELETE Course</button>
                    </div>
                </div>

                <div style="display: flex;">
                    <div class="col-auto" style="margin-right: 10px;">
                        <button type="submit" class="btn btn-primary p-2" onclick="window.location.href='assignTeacher.php'"> Assign Teacher </button>
                    </div>
                    <div class="col-auto" style="margin-right: 5px;">
                        <button type="submit" class="btn btn-primary p-2" onclick="window.location.href='showEnrolledStudents.php'">See Enrollments Table</button>
                    </div>
                </div>
            </div>
            <br>

            <div class="table-wrapper"> <!-- Wrapper for the table to enable scroll -->
                <table class="table table-bordered table-hover table-sm table-success border border-black">
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>Assigned Teacher</th>
                            <th>Teacher ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM courses c LEFT JOIN teachers t ON c.teacherID = t.teacherID";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        ?>

                        <?php foreach ($results as $result) : ?>
                            <tr class="course-row" data-id="<?= htmlentities($result->courseID); ?>">
                                <td><?= htmlentities($result->courseID); ?></td>
                                <td><?= htmlentities($result->courseName); ?></td>
                                <td><?= htmlentities($result->firstName) . " " . htmlentities($result->lastName); ?></td>
                                <td><?= htmlentities($result->teacherID); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</body>

<script src="script.js"></script>
<script src="./assets/sweetalert/sweetalert2.min.js"></script>

</html>
