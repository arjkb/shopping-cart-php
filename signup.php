<?php
  require_once "pdo.php";
  if (isset($_POST['signup_name']) &&
      isset($_POST['signup_email']) &&
      isset($_POST['signup_pass1']) &&
      isset($_POST['signup_pass2'])) {

    print_r($_POST['signup_name']);
    print_r($_POST['signup_email']);
    print_r($_POST['signup_pass1']);
    print_r($_POST['signup_pass2']);

    if (empty($_POST['signup_name']) ||
        empty($_POST['signup_email']) ||
        empty($_POST['signup_pass1']) ||
        empty($_POST['signup_pass2'])) {
        // code...
      // echo " some fields are empty";
    } else {

      if ($_POST['signup_pass1'] !== $_POST['signup_pass2']) {
        // code...
        echo "Both passwords do not match!";
      } else {
        // do the signup
        echo "passwords match!";

        $cust_name = $_POST['signup_name'];
        $cust_email = $_POST['signup_email'];
        $cust_passhash = password_hash($_POST['signup_pass1'], PASSWORD_DEFAULT);

        $stmt_insert_customer = $pdo->prepare('INSERT INTO customer(cname, cemail, cpasshash) VALUES (:name,:email, :hash)');
        $stmt_insert_customer->execute(array(
          ':name' => $cust_name,
          ':email' => $cust_email,
          ':hash' => $cust_passhash
        ));
        // TODO: check whether the above was successful
        header('Location: cart.php');
        return;
      }
    }
  } // end of outermost if
  else {
    // echo "no post data";
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Sign Up | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <div class="container">
    <h1>Sign Up</h1>
    <form class="" action="signup.php" method="POST">
      <div class="form-group">
        Name: <input type="text" class="form-control" name="signup_name" value="">
      </div>
      <div class="form-group">
        Email: <input type="text" class="form-control" name="signup_email" value="">
      </div>
      <div class="form-group">
        Password: <input type="text" class="form-control" name="signup_pass1" value="">
      </div>
      <div class="form-group">
        Retype Password: <input type="text" class="form-control" name="signup_pass2" value="">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-outline-primary">Sign Up</button>
      </div>
    </form>
    </div>
  </body>
</html>
