<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['Global_ID'])) {
    header('Location: login.php');
    exit;
}

else{
    $globalID = $_SESSION['Global_ID'];
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
            <a href="studentPortal.php">
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
                <li><a href="teacherPortal.php">Grades Table</a></li>
                <li><a href="#.php">Grade Students</a></li>
                <li><a href="myStudents.php">My Students</a></li>
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
        <span class="text">Welcome Student, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    </div>

    <div style="margin-top:5%; margin-left:20px; margin-right:20px;">
        <div class="h4 pb-2 mb-4 text-success border-bottom border-success" style="display:flex; align-items:center; justify-content: space-between;">
            MY COURSES & GRADES
        </div>

        <br>

        <div class="table-wrapper" style="border: 1px solid green;">
            <table class="table table-bordered table-sm table-success border border-success">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Preliminary</th>
                        <th>Mid Term</th>
                        <th>Finals</th>
                        <th>AVG</th>
                    </tr>
                </thead>
                <tbody>

                        <?php

                        $sql = "SELECT c.courseID, c.courseName, g.preLim, g.midTerm, g.Finals, g.AVG 
                        FROM students s 
                        JOIN enrollments e ON s.studentID = e.studentID 
                        JOIN courses c ON c.courseID = e.courseID
                        LEFT JOIN grades g ON s.studentID = g.studentID AND c.courseID = g.courseID
                        WHERE s.studentID = '$globalID'";



                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        ?>

                <?php foreach ($results as $result) :?>
                <tr>
                <td><?=htmlentities($result->courseID); ?></td>
                <td><?=htmlentities($result->courseName); ?></td>
                <td><?=htmlentities($result->preLim); ?></td>
                <td><?=htmlentities($result->midTerm); ?></td>
                <td><?=htmlentities($result->Finals); ?></td>
                <td><?=htmlentities($result->AVG); ?></td>
                </tr>
                <?php endforeach ?>

    </tbody>
  </table>

        </div>
    </div>
</section>

<script src="script.js"></script>

</body>
</html>
