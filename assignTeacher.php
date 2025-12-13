<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

try {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $courseID = $conn->real_escape_string($_POST['courseID']);
        $teacherID = $conn->real_escape_string($_POST['teacherID']);
        $query = "UPDATE courses SET teacherID = '$teacherID' WHERE courseID = '$courseID'";
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
        height: 300px;
        max-height: 800px;
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
        <div style="margin-top: 4%; height:100px; display:flex; justify-content:center;">
            <div style="margin-right: 4%;">
                <h3>ASSIGN </h3>
                <div class="input-group flex-nowrap" style="margin-bottom: 5px; width: 300px;">
                    <span class="input-group-text border border-primary" id="addon-wrapping">Course ID</span>
                    <input type="text" class="form-control border border-primary" name="courseID" placeholder="Enter Course ID">
                </div>

                <h3>TO<h3>

                <div class="input-group flex-nowrap" style="margin-bottom: 5px; width: 300px;">
                    <span class="input-group-text border border-primary" id="addon-wrapping">Teacher ID</span>
                    <input type="text" class="form-control border border-primary" name="teacherID" placeholder="Enter Teacher ID">
                </div>

                <div class="col-auto" style="display:flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary p-2" style="margin-right:5px;">Assign</button>
                    <button type="button" class="btn btn-outline-success p-2 w-40" onclick="window.location.href='newCourseTable.php';">Go Back</button>
                </div>
            </div>

            <div style="width: 700px;">
                <h3>Course List </h3>
                <div style="color:red; display:flex; flex-direction: column;">*Click a record to automatically get the ID</div>

                <div class="table-wrapper">
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
                                $sql = "SELECT * FROM courses LEFT JOIN teachers ON courses.teacherID = teachers.teacherID";
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

                <br>

                <h3>Teachers List </h3>

                <div class="table-wrapper">
                    <table class="table table-bordered table-hover table-sm table-success border border-black">
                        <thead>
                            <tr>
                                <th>Teacher ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
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
                                <tr class="teacher-row" data-id="<?= htmlentities($result->teacherID); ?>">
                                    <td><?=htmlentities($result->teacherID); ?></td>
                                    <td><?=htmlentities($result->firstName); ?></td>
                                    <td><?=htmlentities($result->lastName); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</section>

<script src="script.js"></script>
<script src="./assets/sweetalert/sweetalert2.min.js"></script>

<script>
    document.getElementById('newEditForm').addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: 'Assign this Teacher to this Course?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Assign Teacher',
            cancelButtonText: 'Cancel'
        })

        .then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Teacher Assigned!",
                    text: "Assigned Teacher Successfully!",
                    icon: "success"
                })

                .then(() => {
                    this.submit();
                });
            }
        });
    });

    const courseRow = document.querySelectorAll('.course-row');
    courseRow.forEach(row => {
        row.addEventListener('click', function() {
            const courseID = row.getAttribute('data-id');
            document.querySelector('input[name="courseID"]').value = courseID;
        });
    });

    const teacherRow = document.querySelectorAll('.teacher-row');
    teacherRow.forEach(row => {
        row.addEventListener('click', function() {
            const teacherID = row.getAttribute('data-id');
            document.querySelector('input[name="teacherID"]').value = teacherID;
        });
    });
</script>

</body>
</html>
