<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <style>
    <?php include 'login.css';
    ?>
  </style>
  <script src="script.js?newversion"></script>
</head>

<?php

$exists = FALSE;
$showAlert = FALSE;
$showError =  FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Include file which makes the Database Connection.
  include 'dbconnect.php';
  include 'php_validate.php';

  // PHP validation.
  $php_check = new validate();
  $php_check->validate_First_Name($_POST['fname']);
  $php_check->validate_Last_Name($_POST['lname']);
  $php_check->validate_Email($_POST['email']);
  $php_check->validatePassword($_POST['password']);

  $username = $_POST['email'];
  $password = $_POST['password'];
  $cpassword = $_POST["cpassword"];
  //if (empty($cpassword))
  $php_check->validatePassword($cpassword);

  // This sql query is use to check if the username is already present or not in our Database.
  $sql = "Select * from user_data where username='$username'";
  $result = mysqli_query($conn, $sql);
  $num = mysqli_num_rows($result);

  if ($num == 0) {
    if (($password == $cpassword) && $exists == FALSE) {
      //$hash = password_hash($password, PASSWORD_DEFAULT);

      // Query to insert data into database. 
      $sql = "INSERT INTO `user_data` ( `username`, 
          `password`) VALUES ('$username', 
          '$password')";

      $result = mysqli_query($conn, $sql);
      if ($result) {
        $showAlert = TRUE;
      } else {
        $showError = "Passwords do not match";
      }
    } 
  }
  if ($num > 0) {
    $exists = "Username not available";
  }
}

if ($showAlert) {
  $_SESSION['log'] = TRUE;
  echo "<script>
  alert('Account Created!! Please Login.');
  </script>";
}
if ($showError) {
  echo $showError;
}
if ($exists) {
  echo "<script>
  alert('Username($username) already exists.');
  </script>";
}

if (isset($_SESSION['log'])) {
  header('location: login.php');
}

?>

<body>
  <div class="back">
    <div class="container">
      <div class="contents">
        <form action="registration.php" method="post" id="form">
          First Name:
          <input type="text" class="box" id="fname" name="fname" required placeholder="Enter first name" onblur="validateFirstName()">
          <span style="color: red;">*</span>
          <br>
          <span id="checkfname" class="checkfname" style="color: red;"></span>
          <br><br>
          Last Name:
          <input type="lname" class="box" id="lname" name="lname" required placeholder="Enter last name" onblur="validateLastName()">
          <span style="color: red;">*</span>
          <br>
          <span id="checklname" class="checklname" style="color: red;"></span>
          <br><br>
          Email:
          <input type="text" class="box" id="email" name="email" required placeholder="Enter your email" onblur="validateEmail()">
          <span style="color: red;">*</span>
          <br>
          <span id="checkemail" class="checkemail" style="color: red;"></span>
          <br><br>
          Password:
          <input type="password" class="box" id="password" name="password" required password placeholder="Enter a password" onblur="validatePassword()">
          <span style="color: red;">*</span>
          <br><br>
          <span id="checkpass" class="checkpass" style="color: red;"></span>
          <br>
          Confirm Password:
          <input type="password" class="box" id="cpassword" name="cpassword" required password placeholder="Please enter password again" onblur="validatePassword() ;matchPassword()">
          <span style="color: red;">*</span>
          <br><br>
          <span id="checkpass2" class="checkpass" style="color: red;"></span>

          <div class="flex">
            <input class="login" type="submit" name="submit" id="submit" value="Register">
          </div>
          <div class="create">
            <p>Already have an account? <a class="error" href="login.php">Login</a></p>
          </div>
        </form>
      </div>
    </div>
</body>
</html>
