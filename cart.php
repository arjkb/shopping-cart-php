<?php
  session_start();
  require_once "pdo.php";
  // echo session_id();

  if (isset($_POST['cart_remove_btn'])) {
    // echo "<br> remove button clicked";
    // echo "<br> remove button clicked: ".$_POST['cart_remove_btn'];
    $cart_to_remove = $_POST['cart_remove_btn'];
    $cust_id = $_POST['cartremove_custid'];
    $prod_id = $_POST['cartremove_prodid'];
    // echo "<br> P=$prod_id, C=$cust_id";
    $stmt_cart_remove = $pdo->prepare('DELETE FROM cart WHERE idprod = :prod_id AND idcust LIKE :cust_id');
    $stmt_cart_remove->execute(array(
      ':prod_id' => $prod_id,
      ':cust_id' => $cust_id
    ));
    header('Location: cart.php');
    return;
  }

  $chosenItems = "dsgh";
  $customer_id = isset($_SESSION['userid'])?$_SESSION['userid']:session_id();

  // echo " >>>>>>>> cid: $customer_id";

  $stmt_select_cart = $pdo->prepare('SELECT product.id, product.pname, product.unitprice FROM product WHERE product.id IN (SELECT cart.idprod FROM cart WHERE cart.idcust = :cust_id);');

  $stmt_select_cart->execute(array(':cust_id' => $customer_id));
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Cart | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <div class="container">
    <h1>Cart</h1>

    <?php if (!empty($chosenItems)): ?>
      <p>
        Are you sure you wish to purchase the following items:
      </p>

      <table class="table">
        <thead>
          <!-- <th>ID</th> -->
          <th>Product Name</th>
          <th>Product Price</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php while($row = $stmt_select_cart->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td><?= $row['pname'] ?></td>
              <td><?= $row['unitprice'] ?></td>
              <td>
                <form action="cart.php" method="POST">
                  <input type="hidden" name="cartremove_custid" value="<?= $customer_id ?>">
                  <input type="hidden" name="cartremove_prodid" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-outline-danger btn-sm" name="cart_remove_btn" value="<?= $row['id'] ?>">Remove</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>

        </tbody>
      </table>

      <div class="">
        <!-- Total Price: $<?= $totalPrice; ?> -->
      </div>

      <form class="" action="purchased.php" method="post">
        <input type="hidden" name="purchaseitems" value="<?= $_POST['items'] ?>">
        <button type="submit" class="btn btn-outline-secondary btn-sm" name="cancelbtn">Cancel</button>
        <button type="submit" class="btn btn-outline-primary btn-sm" name="purchasebtn">Purchase</button>
      </form>
    <?php else: ?>
      <p>Your cart is empty</p>
    <?php endif; ?>
    </div>

  </body>
</html>
