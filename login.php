<?php
  session_start();
  require_once "pdo.php";
  echo "php works!";

  if (isset($_POST['loginemail']) &&
      isset($_POST['loginpass']) ) {
    $useremail = $_POST['loginemail'];
    $userpass = $_POST['loginpass'];

    $userpasshash = password_hash($userpass, PASSWORD_DEFAULT);
    echo "<br> Password: ".$userpass;
    echo "<br> Hash: ".$userpasshash;

    $stmt_user_credentials = $pdo->prepare('SELECT id, cname, cpasshash FROM customer WHERE cemail LIKE :email');
    $stmt_user_credentials->execute(array(':email' => $useremail));

    $row_user_credentials = $stmt_user_credentials->fetch(PDO::FETCH_ASSOC);
    $username_fromdb = $row_user_credentials['cname'];
    $actualpasshash = $row_user_credentials['cpasshash'];
    echo "<br> Hello ".$username_fromdb;
    echo "<br> Passhash from DB: ".$actualpasshash;

    if (password_verify($userpass, $actualpasshash)) {
      echo "<br> Passwords match!";
      $_SESSION['username'] = $username_fromdb;
    } else {
      echo "<br> Passwords DO NOT match!";
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Login | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <h1>Login</h1>

    <form class="" action="login.php" method="POST">
      Email: <input type="text" name="loginemail" value=""> <br><br>
      Password: <input type="text" name="loginpass" value=""> <br><br>
      <button type="submit" name="button">Log In</button>
    </form>
  </body>
</html>
