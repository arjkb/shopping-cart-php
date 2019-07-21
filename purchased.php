<?php
  session_start();
  require_once "pdo.php";

  if (!isset($_SESSION['username'])) {
    // user is not logged in.
    echo "<br> You are not logged in";
    $_SESSION['return_addr'] = 'purchased.php'; // return here after log in
    header('Location: login.php');
    return;

  }
  unset($_SESSION['return_addr']);

  echo "<br> You are logged in";
  echo "<br> chosen items: ".$_SESSION['chosenitems'];
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Purchase Confirmation | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <h1>Thank you for your purchase!</h1>

  </body>
</html>
