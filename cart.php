<?php
  session_start();
  require_once "pdo.php";
  echo "php works!";

  if (isset($_SESSION['chosenitems'])) {
    // code...
    $chosenItems = $_SESSION['chosenitems'];
  }
  if (isset($_POST['items']) && !empty($_POST['items'])) {
    // echo " Items = ".$_POST['items'];
    $chosenItems = implode(', ', json_decode($_POST['items']));
    echo "<br> chosen items: $chosenItems";
    $_SESSION['chosenitems'] = $chosenItems;


  } else {
    die("FATAL: Did you directly come here?");
  }


  if (!empty($chosenItems)) {
    // code...
    // TODO: stop substituting the values directly in the sql
    $stmt_select = $pdo->prepare("SELECT id, pname, unitprice FROM product WHERE id IN ($chosenItems)");
    $stmt_select->execute();

    $stmt_select_sum = $pdo->query("SELECT SUM(unitprice) AS totalprice FROM product WHERE id IN ($chosenItems)");
    $totalPriceRow = $stmt_select_sum->fetch(PDO::FETCH_ASSOC);
    $totalPrice = $totalPriceRow['totalprice'];
  } else {
    echo "Something's wrong!";
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Cart | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <h1>Cart</h1>

    <?php if (!empty($chosenItems)): ?>
      <p>
        Are you sure you wish to purchase the following items:
      </p>
      <ul>
      <?php while($row = $stmt_select->fetch(PDO::FETCH_ASSOC)): ?>
        <li><?= $row['pname']; ?></li>
      <?php endwhile; ?>
      </ul>

      <div class="">
        Total Price: $<?= $totalPrice; ?>
      </div>

      <form class="" action="purchased.php" method="post">
        <input type="hidden" name="purchaseitems" value="<?= $_POST['items'] ?>">
        <button type="button" name="cancelbtn">Cancel</button>
        <button type="submit" name="purchasebtn">Purchase</button>
      </form>
    <?php else: ?>
      <p>Your cart is empty</p>
    <?php endif; ?>


  </body>
</html>
