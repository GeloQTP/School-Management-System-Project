<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $delID = $conn->real_escape_string($_POST['delID']);
        $s_delID = $conn->real_escape_string($_POST['delStudentID']);

        $query = "DELETE FROM enrollments WHERE enrollmentID = '$delID'";
        $result = $conn->query($query);

        $query = "DELETE FROM grades WHERE studentID = '$s_delID'";
        $result = $conn->query($query);

    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
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
            height: 400px;
            max-height: 800px;
            overflow-y: auto;
            border: 1px solid green;
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
            <li><a href="adminTable.php"><i class='bx bx-grid-alt'></i><span class="link_name">Dashboard</span></a></li>
            <li>
                <div class="iocn-link">
                    <a href="#"><i class='bx bx-collection'></i><span class="link_name">See Tables</span></a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Tables</a></li>
                    <li><a href="newTeachersTable.php">Teachers Table</a></li>
                    <li><a href="newStudentTable.php">Students Table</a></li>
                    <li><a href="newCourseTable.php">Courses Table</a></li>
                </ul>
            </li>
            <li>
                <div class="profile-details">
                    <div class="profile-content"><img src="image/profile.jpg" alt="profileImg"></div>
                    <div class="name-job">
                        <div class="profile_name">Ernest</div>
                        <div class="job">Web Developer</div>
                    </div>
                    <a href="logout.php"><i class='bx bx-log-out'></i></a>
                </div>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>

        <div style="margin-top: 8%; display: flex; justify-content: center;">
            <div style="margin-right: 1%; margin-top: 8%;">
                <div class="h4 pb-2 mb-4 text-danger border-bottom border-danger">DELETE ENROLLMENT</div>
                <form method="POST" id="newDropForm">
                    <div class="input-group input-group-sm mb-3" style="width: 300px;">
                        <span class="input-group-text border border-danger" id="inputGroup-sizing-sm">Course ID</span>
                        <input type="text" class="form-control border border-danger" name="delID" placeholder="Enter Enrollment ID" required>
                    </div>

                    <div class="input-group input-group-sm mb-3" style="width: 300px;">
                        <span class="input-group-text border border-danger" id="inputGroup-sizing-sm">Student ID</span>
                        <input type="text" class="form-control border border-danger" name="delStudentID" placeholder="Enter Student ID" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-danger p-2" style="margin-right: 10px;">DELETE</button>
                        <button type="button" class="btn btn-outline-primary p-2 w-40" onclick="window.location.href='showEnrolledStudents.php';">Go Back</button>
                    </div>
                </form>
            </div>

            <div style="width: 35%;">
                <div style="color: red; display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">*Click a record to automatically get the ID</div>
                <div class="table-wrapper">
                    <table class="table table-bordered table-sm table-success table-hover border border-success">
                        <thead>
                            <tr>
                                <th>Enrollment ID</th>
                                <th></th>
                                <th>Student ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT * FROM courses c RIGHT JOIN enrollments e 
                                        ON c.courseID = e.courseID LEFT JOIN students s 
                                        ON s.studentID = e.studentID WHERE isDropped = 'false'";

                                $query = $dbh->prepare($sql);
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
                                <td colspan="3" style="font-weight: bold; text-align: center; background-color: white;" class="border border-success"><?= $courseName ?></td>
                            </tr>

                                <?php foreach ($students as $student) : ?>
                                <tr class="enrollment-row" data-id="<?= htmlentities($student->enrollmentID); ?>" 
                                    data-delStudentID="<?= htmlentities($student->studentID); ?>" >
                                    <td><?= htmlentities($student->enrollmentID); ?></td>
                                    <td><?= htmlentities($student->firstName) . " " . htmlentities($student->lastName); ?></td>
                                    <td><?= htmlentities($student->studentID); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
    <script src="./assets/sweetalert/sweetalert2.min.js"></script>
    <script>
        document.getElementById('newDropForm').addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to Delete this Course?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete Course',
                confirmButtonColor: 'red',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Course Deleted!",
                        text: "The Course has been Deleted!",
                        icon: "success"
                    }).then(() => {
                        this.submit();
                    });
                }
            });
        });

        const rows = document.querySelectorAll('.enrollment-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                const enrollmentID = row.getAttribute('data-id');
                const s_delID = row.getAttribute('data-delStudentID');
                document.querySelector('input[name="delStudentID"]').value = s_delID;
                document.querySelector('input[name="delID"]').value = enrollmentID;
            });
        });
    </script>
</body>
</html>
