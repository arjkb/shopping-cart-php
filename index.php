<?php
  require_once "pdo.php";
  $stmt_select = $pdo->query('SELECT id, pname, unitprice FROM product');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <h1>Product List</h1>

    <table>
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
              <button type="submit">Add to cart</button>
            </form>
          </td>
          <td>
            <form class="" action="" method="POST">
              <input type="hidden" name="buynow_id" value="<?= $row['id'] ?>">
              <button type="submit">Buy now</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </body>
</html>
