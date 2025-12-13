<?php

session_start();
include 'db_connect.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['Global_ID'])) {
    header('Location: login.php');
    exit;
}

else{
    $globalID = $_SESSION['Global_ID'];

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $courseID = $conn->real_escape_string($_POST['courseID']);
            $studentID = $conn->real_escape_string($_POST['studentID']);
            $preLim = $conn->real_escape_string($_POST['preLimGrade']);
            $midTerm = $conn->real_escape_string($_POST['midTermGrade']);
            $Finals = $conn->real_escape_string($_POST['finalGrade']);
            $sum = $preLim + $midTerm + $Finals;
            $AVG = $sum / 3;

            $remarks = ($AVG < 75) ? 'Failure' : 'Passed';
    
            $query = "UPDATE grades SET preLim = '$preLim', midTerm = '$midTerm', Finals = '$Finals', AVG = '$AVG', remarks = '$remarks' WHERE studentID = '$studentID' AND courseID = '$courseID' ";
            $result = $conn->query($query);
      
        }
    } 
    
    catch (Exception $e) {
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
            <a href="teacherPortal.php">
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
                <li><a href="editGrades.php">Edit Grades</a></li>
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
            <span class="text">Welcome Teacher, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>
        <br><br>

        <div style="margin-left:30px; margin-right:30px;"> <!--margin-->
        <div style="display:flex;">  <!--flex div-->
            
                <div style="margin-right:20px; margin-top:6%;">  <!--group the label and inputs-->
                    
                <div class="h4 pb-2 mb-4 text-primary border-bottom border-primary">
                <h3>EDIT STUDENT GRADE<h3>
                </div>

                <form method="POST" action="" id="newUpdateForm">
                    <div style="width:800px;">
                    
                    <div class="input-group input-group-sm mb-3" style="width:210px;">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-sm">Course ID</span>
                            <input type="text" class="form-control border border-black " name="courseID" placeholder="Click a Record."  readonly>
                        </div>

                        <div class="input-group input-group-sm mb-3" style="width:210px;">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-sm">Student ID</span>
                            <input type="text" class="form-control border border-black " name="studentID" placeholder="Click a Record."  readonly>
                        </div>

                        <div class="input-group" style="margin-bottom: 15px;">
                            <span class="input-group-text border border-black">Student Name</span>
                            <input type="text" class="form-control border border-black" placeholder="Click a Record." name="sFirstName" readonly>
                            <input type="text" class="form-control border border-black" placeholder="Click a Record." name="sLastName" readonly>
                        </div>

                        <div class="input-group input-group-sm mb-3" style="width:250px;">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-sm">Prelim Grade</span>
                            <input type="text" class="form-control border border-black " name="preLimGrade" placeholder="Click a Record." required>
                        </div>

                        <div class="input-group input-group-sm mb-3" style="width:250px;">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-sm">Midterm Grade</span>
                            <input type="text" class="form-control border border-black " name="midTermGrade" placeholder="Click a Record." required>
                        </div>

                        <div class="input-group input-group-sm mb-3" style="width:250px;">
                            <span class="input-group-text border border-black" id="inputGroup-sizing-sm">Final Grade</span>
                            <input type="text" class="form-control border border-black " name="finalGrade" placeholder="Click a Record."required>
                        </div>

                        <div class="col-auto" style="display:flex; justify-content: flex-end; margin-bottom:10px;">
                            <button type="submit" class="btn btn-primary p-2" style="margin-right:10px;">Update</button>
                            <button type="button" class="btn btn-outline-primary p-2 w-40" onclick="window.location.href='teacherPortal.php';">Go Back</button>
                        </div>
                    </div>
                </form>
                </div>

                <div>
                <div style="color:red;">*Click on any record in the Table below to see its details fill in the input fields.</div>
                <div class="table-wrapper" style="width: 950px;">
                <table class="table table-bordered table-sm table-success border border-success">
                    
        <?php

        $sql = "SELECT c.courseID, c.courseName, s.studentID, s.firstName, s.lastName, g.preLim, g.midTerm, g.Finals, g.AVG, g.remarks
                FROM teachers t 
                JOIN courses c ON t.teacherID = c.teacherID 
                JOIN enrollments e ON c.courseID = e.courseID 
                JOIN grades g ON e.courseID = g.courseID 
                JOIN students s ON s.studentID = e.studentID AND e.studentID = g.studentID
                WHERE t.teacherID = '$globalID' AND s.isDropped = 'false'
                ORDER BY c.courseID, s.studentID";

        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

       
        $currentCourseID = null;

      
        foreach ($results as $result) :
           
            if ($currentCourseID != $result->courseID) :
               
                if ($currentCourseID != null) :
                    echo "</tbody></table>"; 
                endif;
                $currentCourseID = $result->courseID; 
                ?>
                
                <table class="table table-bordered table-sm table-primary border border-black table-hover">
                    <thead>
                        <tr>
                            <th colspan="8" class="text-center" style="background-color:white;"><?= htmlentities($result->courseName) ?></th>
                        </tr>
                        <tr>
                            <th>Course ID</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Preliminary</th>
                            <th>Mid Term</th>
                            <th>Finals</th>
                            <th>AVG</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
            endif;
        ?>
            <tr class="grade-row" data-id="<?=htmlentities($result->studentID); ?>" 
                                  data-courseID="<?=htmlentities($result->courseID); ?>" 
                                  data-firstname="<?=htmlentities($result->firstName); ?>" 
                                  data-lastname="<?=htmlentities($result->lastName); ?>" 
                                  data-prelim="<?=htmlentities($result->preLim); ?>" 
                                  data-midterm="<?=htmlentities($result->midTerm); ?>" 
                                  data-finals="<?=htmlentities($result->Finals); ?>" >

                <td><?= htmlentities($result->courseID); ?></td>
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
                </div>
                </div>

            </div>
        </div>

    </section>

    <script src="script.js"></script>
    <script src="./assets/sweetalert/sweetalert2.min.js"></script>

    <script>
        document.getElementById('newUpdateForm').addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to save the Grade?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                confirmButtonColor: 'green',
                cancelButtonText: 'Cancel',
                cancelButtonColor: 'red'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Grade Updated!",
                        text: "The Grade has been Updated!",
                        icon: "success",
                    })
                    .then(() => {
                        this.submit();
                    });
                }
            });
        });

        const rows = document.querySelectorAll('.grade-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                const studentID = row.getAttribute('data-id');
                const courseID = row.getAttribute('data-courseID');
                const firstName = row.getAttribute('data-firstname');
                const lastName = row.getAttribute('data-lastname');


                const preLim = row.getAttribute('data-prelim');
                const midTerm = row.getAttribute('data-midterm');
                const Finals = row.getAttribute('data-finals');
                

                document.querySelector('input[name="studentID"]').value = studentID;
                document.querySelector('input[name="courseID"]').value = courseID;
                document.querySelector('input[name="sFirstName"]').value = firstName;
                document.querySelector('input[name="sLastName"]').value = lastName;
                document.querySelector('input[name="preLimGrade"]').value = preLim;
                document.querySelector('input[name="midTermGrade"]').value = midTerm;
                document.querySelector('input[name="finalGrade"]').value = Finals;
            });
        });

    </script>
</body>
</html>
