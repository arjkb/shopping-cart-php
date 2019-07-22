<?php
  session_start();
  require_once "pdo.php";

  $orderConfirmed = false;

  if (isset($_POST['cancelbtn'])) {
    // code...
    header('Location: index.php');
    return;

  } elseif (!isset($_SESSION['username'])) {
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
    // echo "<br> User Name: ".$_SESSION['username'];
    // echo "<br> User ID: ".$customer_id;
    // echo "<br> Shipping address: $shippingaddress";

    // got the address. now add to database

    // select from customer's chosen products from cart
    $stmt_select_cart = $pdo->prepare('SELECT cart.idprod FROM cart WHERE cart.idcust = :cust_id');
    $stmt_select_cart->execute(array(':cust_id' => $customer_id));

    $stmt_insert_order = $pdo->prepare('INSERT INTO orders(idcust, idprod, address) VALUES(:cust_id, :prod_id, :address)');

    while ($row = $stmt_select_cart->fetch(PDO::FETCH_ASSOC)) {
      $stmt_insert_order->execute(array(
            ':cust_id' => $customer_id,
            ':prod_id' => $row['idprod'],
            ':address' => $shippingaddress
      ));
    }

    $orderConfirmed = true;

    // order has been confirmed; now delete from cart
    $stmt_delete_cart = $pdo->prepare('DELETE FROM cart WHERE idcust LIKE :cust_id');
    $stmt_delete_cart->execute(array(':cust_id' => $customer_id));
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Purchase Confirmation | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <div class="container">
    <?php if ($orderConfirmed): ?>
      <p class="flash-msg">
        Order confirmed! Thank you for your purchase!
      </p>
    <?php else: ?>
      <form action="purchased.php" method="POST">
        Enter your shipping address: <br>
        <div class="form-group">
          <textarea name="shippingaddress" rows="8" cols="80"></textarea>
        </div>
        <button type="submit" name="order" class="btn btn-outline-primary">Place Order</button>
      </form>
    <?php endif; ?>
    </div>
  </body>
</html>
