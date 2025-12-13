<?php

session_start();

include 'db_connect.php';

if(!isset($_SESSION['username']))
{
    header('Location:login.php');
    exit;
}

try
{

  if($_SERVER ['REQUEST_METHOD'] === 'POST')
  {

    $newCourseName = $conn->real_escape_string($_POST['newCourseName']);
    $newCourseID = $conn->real_escape_string($_POST['editCourseID']);
  
      $query = "UPDATE courses SET courseName = '$newCourseName' WHERE courseID = '$newCourseID' ";
      $conn->query($query);

  }

}
  catch (Exception $e) {
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
<body>

  <div class="sidebar close">
    <div class="logo-details">
      <i class='bx bxl-c-plus-plus'></i>
      <span class="logo_name">Navigation</span>
    </div>
    
    <ul class="nav-links">
      <li>
        <a href="adminTable.php">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="adminTable.php">Dashboard </a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-collection' ></i>
            <span class="link_name">See Tables</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
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
      <i class='bx bx-log-out' ></i>
      </a>
    </div>
  </li>
</ul>

  </div>

  <!-- style="display:flex; justify-content: flex-end;" -->

  <section class="home-section">

    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
    </div>

    <br><br>

    <form method="POST" action="" id="newEditForm">

    <div style="margin-top: 6%; height:100px; display:flex; justify-content: center;"> <!--This Covers Everything to the course table-->

        <div style="margin-right: 4%;"> <!--This Covers the heading, inputs and the buttons-->

                <div> 
                <div class="h4 pb-2 mb-4 text-primary border-bottom border-primary">
                <h3>EDIT COURSE NAME<h3>
                </div>
                    <br>
                </div>

                <div class="input-group flex-nowrap " style="margin-bottom: 5px; width: 300px;"> 
                    <span class="input-group-text border border-primary" id="addon-wrapping">Course ID</span>
                    <input type="text" class="form-control border border-primary" name="editCourseID" placeholder="Enter Course ID" required>
                </div>

                <div class="input-group flex-nowrap " style="margin-bottom: 5px; width: 500px;"> 
                    <span class="input-group-text border border-primary" id="addon-wrapping">Course Name</span>
                    <input type="text" class="form-control border border-primary" name="newCourseName" placeholder="Update Course Name" required>
                </div>

                <div class="col-auto" style="display:flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary p-2" style="margin-right:5px;">Edit Course</button>
                    <button type="button" class="btn btn-outline-success p-2 w-40" onclick="window.location.href='newCourseTable.php';">Go Back</button>
                </div>

        </div> <!--This Covers the inputs and the buttons-->

        <style>
    /* Centering text in table cells */
    .table th, .table td {
        text-align: center;
    }

    .table-wrapper {
      height: 400px;
    max-height: 800px; /*  table height   */
    overflow-y: auto;  /*  vertical scrolling */
  }

  table {
    width: 100%; /* Ensure table uses the full width */
  }

  th, td {
    text-align: left;
    padding: 8px;
  }
</style> 

              <div style="width: 600px;">
              <div style="color:red;">*Click on any record to easily get the ID</div>
              <table class="table table-bordered table-hover table-sm table-primary border border-primary">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
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
                            <tr class="course-row" 
                                data-courseID="<?= htmlentities($result->courseID); ?>" 
                                data-courseName="<?= htmlentities($result->courseName); ?>" >
                                <td><?= htmlentities($result->courseID); ?></td>
                                <td><?= htmlentities($result->courseName); ?></td>
                                <td><?= htmlentities($result->firstName)," ",htmlentities($result->lastName); ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
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
        text: 'Do you want to EDIT this Course?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, EDIT Course',
        cancelButtonText: 'Cancel'
    })

    .then((result) => {

        if (result.isConfirmed) {
            
            Swal.fire({
                title: "Course Name Updated!",
                text: "The Course Name has been Updated!",
                icon: "success"
            })

            .then(() => {
               
                this.submit();
            });
        }
    });

  });

  const rows = document.querySelectorAll('.course-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                const courseID = row.getAttribute('data-courseID');
                const courseName = row.getAttribute('data-courseName');
                document.querySelector('input[name="editCourseID"]').value = courseID;
                document.querySelector('input[name="newCourseName"]').value = courseName;
            });
        });

</script>


</html>