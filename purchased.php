<?php
  session_start();
  require_once "pdo.php";

  $orderConfirmed = false;

  if (!isset($_SESSION['username'])) {
    // user is not logged in.
    echo "<br> You are not logged in";
    $_SESSION['return_addr'] = 'purchased.php'; // return here after log in
    header('Location: login.php');
    return;

  }
  unset($_SESSION['return_addr']);

  if (isset($_POST['shippingaddress']) && !empty($_POST['shippingaddress'])) {
    // code...
    $shippingaddress = $_POST['shippingaddress'];

    $customer_id = $_SESSION['userid'];
    echo "<br> User Name: ".$_SESSION['username'];
    echo "<br> User ID: ".$customer_id;
    echo "<br> Shipping address: $shippingaddress";

    // got the address. now add to database

    $stmt_insert_order = $pdo->prepare('INSERT INTO orders(idcust, idprod, address) VALUES(:cust_id, :prod_id, :address)');

    $chosenItems = explode(',',$_SESSION['chosenitems']);
    foreach ($chosenItems as $item) {
      echo "<br> item: $item";
      $stmt_insert_order->execute(array(
        ':cust_id' => $customer_id,
        ':prod_id' => $item,
        ':address' => $shippingaddress
      ));
      $orderConfirmed = true;
    }
  }

  echo "<br> You are logged in";
  // echo "<br> chosen items: ".$chosenItems;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Purchase Confirmation | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <?php if ($orderConfirmed): ?>
      <h2>Order confirmed! Thank you for your purchase!</h2>
    <?php else: ?>
      <form class="" action="purchased.php" method="POST">
        Enter your shipping address: <br>
        <textarea name="shippingaddress" rows="8" cols="80"></textarea>
        <button type="submit" name="order">Place Order</button>
      </form>
    <?php endif; ?>

  </body>
</html>
