<?php
  session_start();
  require_once "pdo.php";

  $admin_logged_in = false;

  if (isset($_SESSION['admin'])) {
    $admin_logged_in = true;

    // set up database connections
    $stmt_selectall_product = $pdo->query('SELECT id, pname, unitprice FROM product');
    $stmt_selectall_customer = $pdo->query('SELECT id, cname, cemail FROM customer');
    $stmt_selectall_orders = $pdo->query('SELECT orders.id, customer.cname, product.pname, product.unitprice, orders.address FROM orders, customer, product WHERE orders.idcust = customer.id AND orders.idprod = product.id');

  } elseif (isset($_POST['adminusername']) && isset($_POST['adminpass'])) {
    if (empty($_POST['adminusername']) || empty($_POST['adminpass'])) {
      // code...
      // invalid login
      echo "Invalid login";
      $_SESSION['FLASH_ERROR'] = "All fields must be entered!";
      header('Location: admin.php');
      return;
    }

    $uname = $_POST['adminusername'];
    $upass = $_POST['adminpass'];

    $stmt_admin_pass = $pdo->prepare('SELECT passhash FROM admins WHERE adminname LIKE :aname');
    $stmt_admin_pass->execute(array(':aname' => $uname));
    $row_stmt_admin_pass = $stmt_admin_pass->fetch(PDO::FETCH_ASSOC);
    $actualpasshash = $row_stmt_admin_pass['passhash'];

    if (password_verify($upass, $actualpasshash)) {
      echo "<br> admin password is correct!";
      $_SESSION['admin'] = $uname;

      header('Location: admin.php'); // reload this page as GET
      return;
    } else {
      $_SESSION['FLASH_ERROR'] = "Invalid login credentials!";
    }

  }

  if (isset($_POST['editbtn_admin'])) {
    // echo "edit button was clicked!";

  } elseif (isset($_POST['deletebtn_admin'])) {
    // echo "delete button was clicked!";

    $id = $_POST['product_id'];

    $stmt_delete_product = $pdo->prepare('DELETE FROM product WHERE id = :id');
    $stmt_delete_product->execute(array(':id' => $id));

    header('Location: admin.php');
    return;

  } elseif(isset($_POST['addprodbtn']) && isset($_POST['pname_admin']) && isset($_POST['pprice_admin'])) {
    echo "add product button clicked";
    if (!empty($_POST['pname_admin']) && !empty($_POST['pprice_admin'])) {
      // code...
      $stmt_insert_product = $pdo->prepare('INSERT INTO product(pname, unitprice) VALUES(:name, :price)');
      $stmt_insert_product->execute(array(
        ':name' => $_POST['pname_admin'],
        ':price' => $_POST['pprice_admin']
      ));
    }
    header('Location: admin.php');
    return;
  } elseif (isset($_POST['cust_editbtn_admin'])) {
    // code...
    echo "customer edit button was pressed";
  } elseif (isset($_POST['cust_deletebtn_admin'])) {
    // customer delete button was pressed
    $id = $_POST['customer_id'];
    $stmt_delete_customer = $pdo->prepare('DELETE FROM customer WHERE id = :id');
    $stmt_delete_customer->execute(array(':id' => $id));

    header('Location: admin.php');
    return;

  } elseif (isset($_POST['order_editbtn_admin'])) {
    // code...
  } elseif (isset($_POST['order_deletebtn_admin'])) {
    // code...

    $id = $_POST['order_id'];
    $stmt_delete_order = $pdo->prepare('DELETE FROM orders WHERE id = :id');
    $stmt_delete_order->execute(array(':id' => $id));
    header('Location: admin.php');
    return;

  }
  // elseif (isset($_POST['addcustbtn'])) {
  //   // add customer button was pressed
  //   if (!empty($_POST['cname_admin']) && !empty($_POST['cemail_admin'])) {
  //     $stmt_insert_customer = $pdo->prepare();
  //
  //   }
  // }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Admin</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>
    <div class="container">

    <h1>Admin Control Panel</h1>
    <?php if(isset($_SESSION['FLASH_ERROR'])): ?>
      <p class="flash-msg-err">
        <?= $_SESSION['FLASH_ERROR']; ?>
      </p>
    <?php elseif(isset($_SESSION['FLASH_MESSAGE'])): ?>
      <p class="flash-msg">
        <?= $_SESSION['FLASH_MESSAGE']; ?>
      </p>
    <?php
      endif;
      unset($_SESSION['FLASH_ERROR']);
    ?>
    <?php if(!$admin_logged_in): ?>
      <form class="" action="admin.php" method="POST">
        <div class="form-group">
          Username: <input type="text" class="form-control" name="adminusername">
        </div>
        <div class="form-group">
          Password: <input type="text" class="form-control" name="adminpass">
        </div>
        <div class="form-group">
          <button type="submit" name="button" class="btn btn-outline-primary">Log In</button>
        </div>
      </form>
    <?php else: ?>
      <!-- Logged in as admin; show admin stuff -->
      <h3><small class="text-muted">Welcome, <?= $_SESSION['admin']; ?>!</small></h3>

      <h3>Products</h3>

      <table class="table">
        <thead>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Unit Price</th>
          <th></th>
        </thead>
        <tbody>
          <?php while($row = $stmt_selectall_product->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td>#<?= $row['id'] ?></td>
              <td><?= $row['pname'] ?></td>
              <td><?= $row['unitprice'] ?></td>
              <td>
                <form class="" action="admin.php" method="post">
                  <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="editbtn_admin" class="btn btn-outline-primary btn-sm">Edit</button>
                  <button type="submit" name="deletebtn_admin" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>

          <tr>
            <form action="admin.php" method="post">
              <td>#</td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control" name="pname_admin" placeholder="Product name">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control" name="pprice_admin" placeholder="Product unit price" >
                </div>
              </td>
              <td>
                <div class="form-group">
                  <button type="submit" name="addprodbtn" class="btn btn-outline-warning">Add Product</button>
                </div<
              </td>
            </form>
          </tr>
        </tbody>
      </table>

      <h3>Customers</h3>

      <table class="table">
        <thead>
          <th>Customer ID</th>
          <th>Customer Name</th>
          <th>Customer Email</th>
          <th></th>
        </thead>
        <tbody>
          <?php while($row = $stmt_selectall_customer->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td>#<?= $row['id'] ?></td>
              <td><?= $row['cname'] ?></td>
              <td><?= $row['cemail'] ?></td>
              <td>
                <form class="" action="admin.php" method="post">
                  <input type="hidden" name="customer_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="cust_editbtn_admin" class="btn btn-outline-primary btn-sm">Edit</button>
                  <button type="submit" name="cust_deletebtn_admin" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <h3>Orders</h3>

      <table class="table">
        <thead>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Product Name</th>
          <th>Product Name</th>
          <th>Shipping Address</th>
        </thead>
        <tbody>
          <?php while($row = $stmt_selectall_orders->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td>#<?= $row['id'] ?></td>
              <td><?= $row['cname'] ?></td>
              <td><?= $row['pname'] ?></td>
              <td><?= $row['unitprice'] ?></td>
              <td><?= $row['address'] ?></td>
              <td>
                <form class="" action="admin.php" method="post">
                  <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="order_editbtn_admin" class="btn btn-outline-primary btn-sm">Edit</button>
                  <button type="submit" name="order_deletebtn_admin" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>

        </tbody>
      </table>

    <?php endif; ?>
    </div>

  </body>
</html>
