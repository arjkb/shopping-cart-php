<?php
  session_start();
  require_once "pdo.php";

  if (isset($_SESSION['admin'])) {
    // logged in as admin; redirect to admin panel
    $_SESSION['FLASH_MESSAGE'] = "You can't view index.php as admin";
    header('Location: admin.php');
    return;

  } elseif (isset($_SESSION['username'])) {
    // user is logged in
    $customer_id = $_SESSION['userid'];
    $loginMessage = "Welcome, ".$_SESSION['username']."!";
  } else {
    $customer_id = session_id();
    $loginMessage = "You are currently not logged in";
  }

  if (isset($_POST['addcartbtn']) ) {
    // code...
    echo "<br> add to cart button clicked ".$_POST['addtcartbtn'];
    $stmt_insert_cart = $pdo->prepare('INSERT INTO cart(idcust, idprod)VALUES(:cust_id, :prod_id)');
    // $stmt_insert_cart = $pdo->prepare('INSERT INTO cart(idcust, idprod)VALUES('2', 3)');
    // $stmt_insert_cart->execute();
    // $stmt_insert_cart->execute(array(
    //   ':cust_id' => '2',
    //   ':prod_id' => 57
    // ));
    $stmt_insert_cart->execute(array(
      ':cust_id' => $_POST['customer_id'],
      ':prod_id' => $_POST['addcartbtn']
    ));
  }

  $stmt_select = $pdo->query('SELECT id, pname, unitprice FROM product');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>
    <div class="container">
    <h1>Product List</h1>
    <h3><small class="text-muted"><?= $loginMessage ?></small></h3>

    <table class="table">
      <thead>
        <th>Product Name</th>
        <th>Unit Price</th>
        <th>Action</th>
      </thead>
      <tbody>
      <?php while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><?= $row['pname'] ?></td>
          <td><?= $row['unitprice'] ?></td>
          <td>
            <form class="" action="" method="POST">
              <input type="hidden" name="addtocart_id" value="<?= $row['id'] ?>">
              <button class="addcartbtn btn btn-outline-primary btn-sm" name="addcartbtn" value="<?= $row['id']?>;<?= $customer_id ?>" type="button">Add to cart</button>
              <button class="buynowbtn btn btn-outline-secondary btn-sm" value="<?= $row['id'] ?>" type="submit">Buy now</button>
            </form>
          </td>
          <!-- <td>
            <form class="" action="" method="POST">
              <input type="hidden" name="buynow_id" value="<?= $row['id'] ?>">
              <button class="buynowbtn btn btn-outline-secondary btn-sm" value="<?= $row['id'] ?>" type="submit">Buy now</button>
            </form>
          </td> -->
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

    <form class="" action="cart.php" method="POST">
      <input type="hidden" id="chosenitems" name="items">
      <button type="submit" name="button" id="buybtn" class="btn btn-outline-primary">Buy</button>
    </form>

    </div>

  </body>
</html>
