<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newCourse = $conn->real_escape_string($_POST['newCourse']);
        $query = "INSERT INTO courses (courseName) VALUES ('$newCourse')";
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
    <br><br>
    <form method="POST" action="" id="newAddCourse">
        <div style="margin-top: 6%; height:100px; display:flex; justify-content: center;">
            <div style="margin-right: 4%;">
                <div class="h4 pb-2 mb-4 text-success border-bottom border-success">
                    <h3>ADD NEW COURSE</h3>
                </div>
                <br>
                <div class="input-group flex-nowrap" style="margin-bottom: 5px; width: 500px;">
                    <span class="input-group-text border border-success" id="addon-wrapping">Course Name</span>
                    <input type="text" class="form-control border border-success" name="newCourse" placeholder="Enter Course Name" required>
                </div>
                <div class="col-auto" style="display:flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-success p-2" style="margin-right:5px;">Add Course</button>
                    <button type="button" class="btn btn-outline-success p-2 w-40" onclick="window.location.href='newCourseTable.php';">Go Back</button>
                </div>
            </div>
            <style>
                .table th, .table td {
                    text-align: center;
                }

                .table-wrapper {
                    height: 400px;
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
            <div class="table-wrapper">
                <table class="table table-bordered table-hover table-sm table-success border border-black">
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM courses";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        ?>
                        <?php foreach ($results as $result) : ?>
                            <tr>
                                <td><?= htmlentities($result->courseID); ?></td>
                                <td><?= htmlentities($result->courseName); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</section>

<script src="script.js"></script>
<script src="./assets/sweetalert/sweetalert2.min.js"></script>

<script>
document.getElementById('newAddCourse').addEventListener('submit', function(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to add this Course?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Add Course',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Course Added!",
                text: "The Course has been added!",
                icon: "success"
            }).then(() => {
                this.submit();
            });
        }
    });
});
</script>

</body>
</html>
