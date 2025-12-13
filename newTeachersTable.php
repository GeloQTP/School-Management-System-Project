<?php

session_start();

include 'db_connect.php';

if(!isset($_SESSION['username']))
{
    header('Location:login.php');
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
      height: 500px;;
    max-height: 500px; /*  table height   */
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
          <li><a class="link_name" href="adminTable.php">Dashboard</a></li>
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

    <div style="margin-top:5%; margin-left:20px; margin-right:20px;"> <!--Lists Start--> 

    <div class="h4 pb-2 mb-4 text-success border-bottom border-success">
            LIST OF TEACHERS
    </div>

    <div style="display:flex;">
    <div class="col-auto" style="margin-right:5px;">
    <button type="button" class="btn btn-success p-2" onclick="window.location.href='newAddTeacher.php';">ADD Teacher</button>
   </div>

  <div class="col-auto" style="margin-right:5px;">
    <button type="button" class="btn btn-primary p-2" onclick="window.location.href='newUpdateTeacher.php';">EDIT Teacher</button>
  </div>

  <div class="col-auto" style="margin-right:5px;">
    <button type="button" class="btn btn-danger p-2" onclick="window.location.href='newDeleteTeacher.php';">DELETE Teacher</button>
</div>

</div>

<br>

<div class="table-wrapper" style="border: 1px solid black;"> <!-- Wrapper for the table to enable scroll -->
  <table class="table table-bordered table-hover table-sm table-success border border-black">
    <thead>
      <tr>
        <th>Teacher ID</th>
        <th>Username</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Contact Number</th>
        <th>Address</th>
        <th>Password</th>
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
        <tr>
          <td><?=htmlentities($result->teacherID); ?></td>
          <td><?=htmlentities($result->username); ?></td>
          <td><?=htmlentities($result->firstName); ?></td>
          <td><?=htmlentities($result->lastName); ?></td>
          <td><?=htmlentities($result->contactNumber); ?></td>
          <td><?=htmlentities($result->teacherAddress); ?></td>
          <td><?=htmlentities($result->passcode); ?></td>
        </tr>
      <?php endforeach ?>

    </tbody>
  </table>
</div>

  </section>

</body>
<script src="script.js"></script>

</html>