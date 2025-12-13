<?php

session_start();
include 'db_connect.php';

$message = '';
$showAlert = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']); 
    $password = $conn->real_escape_string($_POST['password']); // '' are from the textbox id inside the html.
    $Global_ID = $conn->real_escape_string($_POST['Global_ID']);

    $query = "SELECT * FROM admins WHERE id = $Global_ID AND username = '$username' AND passcode = '$password' AND userType = 'admin'"; // command message. Checks if the user is an admin.
    $result = $conn->query($query); // like cmd.execute.

    if ($result->num_rows > 0) 
    {
        $_SESSION['username'] = $username;
        $_SESSION['Global_ID'] = $Global_ID;
        header('Location: adminTable.php');
        exit;
    } 

    else 
    {
        $query = "SELECT * FROM teachers WHERE teacherID = $Global_ID AND username = '$username' AND passcode = '$password' AND userType = 'teacher' ";
        $result = $conn->query($query);

        if ($result->num_rows > 0) 
        {
            $_SESSION['username'] = $username;
            $_SESSION['Global_ID'] = $Global_ID;
            header('Location: teacherPortal.php');
            exit;
        } 

        else 
        {
            $query = "SELECT * FROM students WHERE studentID = $Global_ID AND username = '$username' AND passcode = '$password' AND userType = 'student ' "; // checks if the user is a student.
            $result = $conn->query($query);

            if ($result->num_rows > 0) 
            {
                
                $_SESSION['username'] = $username;
                $_SESSION['Global_ID'] = $Global_ID;
                header('Location: studentPortal.php');
                exit;
            } 

            else 
            {
                $message = 'Invalid username or password!';
                $showAlert = true;
            }


        }


    }


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap-5.3.3-dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="./assets/sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="ds_login.css">
    <title>Log-in</title>
</head>

<body>
    <header>
        <h2 class="logo"></h2> <!-- Image source for logo can be added here -->
    </header>

    <div class="wrapper">
        <div class="form-box login">
            <h2>Login</h2>

            <form id="loginForm" method="POST" action="">

            <div class="input-box">
                    <span class="icon">
                        <img src="assets/bootstrap-5.3.3-dist/css/assets/people.svg" alt="Bootstrap" width="32" height="32">
                    </span>
                    <input type="text" id="Global_ID" name="Global_ID" required>
                    <label>ID</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="assets/bootstrap-5.3.3-dist/css/assets/people.svg" alt="Bootstrap" width="32" height="32">
                    </span>
                    <input type="text" id="username" name="username" required>
                    <label>Username</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="assets/bootstrap-5.3.3-dist/css/assets/lock.svg" alt="Bootstrap" width="32" height="32">
                    </span>
                    <input type="password" id="password" name="password" required>
                    <label>Password</label>

                </div>
                
                <p style="color:red;"><?php echo $message; ?></p>

                <button type="submit" class="btn">Login</button>

            </form>

        </div>

    </div>

    <script src="./assets/sweetalert/sweetalert2.min.js"></script>
       

       <?php if ($showAlert == true): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo $message; ?>',
                icon: 'error',
                confirmButtonText: 'Try Again',
                confirmButtonColor: 'red',
            });
        </script>
      <?php endif; ?>

      
</body>

</html>
