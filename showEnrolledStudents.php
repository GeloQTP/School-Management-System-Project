<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

// Fetch all course names for filtering
$courseSql = "SELECT DISTINCT courseName FROM courses";
$courseQuery = $dbh->prepare($courseSql);
$courseQuery->execute();
$courseResults = $courseQuery->fetchAll(PDO::FETCH_OBJ);
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
        .table th, .table td {
            text-align: center;
        }

        .table-wrapper {
            height: 600px;
            max-height: 600px;
            overflow-y: auto;
        }

        table {
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        .filter-container {
            margin-bottom: 20px;
        }
    </style>
</head>
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

    <div style="margin-top:5%; margin-left:20px; margin-right:20px;">
        <div class="h4 pb-2 mb-4 text-success border-bottom border-success" style="display:flex; align-items:center; justify-content: space-between;">
            ENROLLMENTS
            <div style="display:flex;">
                <div class="col-auto" style="margin-right:10px;">
                    <button type="submit" class="btn btn-primary p-2" onclick="window.location.href='enrollStudent.php' ">Enroll Student</button>
                </div>
                <button type="button" class="btn btn-danger p-2 w-40" onclick="window.location.href='deleteEnrollment.php';" style="margin-right:10px;">Remove Enrollment</button>
                <button type="button" class="btn btn-outline-primary p-2 w-40" onclick="window.location.href='newCourseTable.php';">Go Back</button>
            </div>
        </div>

        <div class="filter-container">


            <form method="GET" action="">
                <div class="form-group">


                    <label for="courseFilter">Filter by Course:</label>

                    <select id="courseFilter" name="courseFilter" class="form-control" style="border:1px solid black;" onchange="this.form.submit()">

                        <option value="">All Courses</option>
                        
                        <?php foreach ($courseResults as $course): ?>
                            <option value="<?= htmlentities($course->courseName); ?>"
                                <?= isset($_GET['courseFilter']) && $_GET['courseFilter'] == $course->courseName ? 'selected' : ''; ?>>
                                <?= htmlentities($course->courseName); ?>
                            </option>
                        <?php endforeach; ?>

                        
                    </select>

                </div>
            </form>


        </div>

        <div class="table-wrapper" style="border: 1px solid green;">
            <table class="table table-bordered table-sm table-success border border-success">
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

            <br>

            <table class="table table-bordered table-sm table-primary">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Un-Enrolled Students</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT s.studentID, s.firstName, s.lastName FROM students s LEFT JOIN enrollments e ON s.studentID = e.studentID WHERE e.courseID IS NULL AND isDropped = 'false'";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    ?>
                    <?php foreach ($results as $result) : ?>
                        <tr>
                            <td><?= htmlentities($result->studentID); ?></td>
                            <td><?= htmlentities($result->firstName) . " " . htmlentities($result->lastName); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script src="script.js"></script>
<script src="./assets/sweetalert/sweetalert2.min.js"></script>

</body>
</html>
