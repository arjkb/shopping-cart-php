<?php
  session_start();
  if (isset($_SESSION['username'])) {
    // user is logged in
    $loginMessage = "Welcome, ".$_SESSION['username']."!";
  } else {
    $loginMessage = "You are currently not logged in";
  }

  require_once "pdo.php";
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

    <h2><?= $loginMessage ?></h2>

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
              <button class="addcartbtn btn btn-outline-primary btn-sm" value="<?= $row['id'] ?>" type="button">Add to cart</button>
            </form>
          </td>
          <td>
            <form class="" action="" method="POST">
              <input type="hidden" name="buynow_id" value="<?= $row['id'] ?>">
              <button class="buynowbtn btn btn-outline-secondary btn-sm" value="<?= $row['id'] ?>" type="submit">Buy now</button>
            </form>
          </td>
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
