<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

// Fetch all course names for filtering
$courseSql = "SELECT DISTINCT courseName FROM courses";
$courseQuery = $dbh->prepare($courseSql);
$courseQuery->execute();
$courseResults = $courseQuery->fetchAll(PDO::FETCH_OBJ);

// Fetch students and grades
$sql = "SELECT c.courseID, c.courseName, s.firstName, s.lastName, g.preLim, g.midTerm, g.Finals, g.AVG, s.studentID, g.remarks, 
        t.firstName AS tfirstName, t.lastName AS tlastName, t.teacherID AS tID
        FROM teachers t RIGHT JOIN courses c 
        ON t.teacherID = c.teacherID JOIN grades g
        ON c.courseID = g.courseID LEFT JOIN students s
        ON s.studentID = g.studentID  WHERE s.isDropped ='false' ORDER BY c.courseID";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
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
            GRADES TABLE
        </div>

        <div class="filter-container">
            <form method="GET" action="">
                <div class="form-group">
                    <label for="courseFilter">Filter by Course:</label>
                    <select id="courseFilter" name="courseFilter" class="form-control" style= "border:1px solid black;" onchange="this.form.submit()">
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
                        <th>Teacher ID</th>
                        <th>Teacher's Name</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Preliminary</th>
                        <th>Midterms</th>
                        <th>Finals</th>
                        <th>AVG</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                    // Filter by selected course if applicable
                    $courseFilter = isset($_GET['courseFilter']) ? $_GET['courseFilter'] : '';
                    foreach ($results as $result):
                        if ($courseFilter && $result->courseName != $courseFilter) {
                            continue; // Skip if course doesn't match filter
                        }
                ?>
                    <tr>
                        <td><?= htmlentities($result->courseID); ?></td>
                        <td><?= htmlentities($result->tID); ?></td>
                        <td><?= htmlentities($result->tfirstName)," ",htmlentities($result->tlastName); ?></td>
                        <td><?= htmlentities($result->studentID); ?></td>
                        <td><?= htmlentities($result->firstName), " ", htmlentities($result->lastName); ?></td>
                        <td><?= htmlentities($result->preLim); ?></td>
                        <td><?= htmlentities($result->midTerm); ?></td>
                        <td><?= htmlentities($result->Finals); ?></td>
                        <td><?= htmlentities($result->AVG); ?></td>
                        <td><?= htmlentities($result->remarks); ?></td>
                    </tr>
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

</body>
</html>
