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

    <h1>Admin Control Panel</h1>

    <?php if(!$admin_logged_in): ?>
      <form class="" action="admin.php" method="POST">
        Username: <input type="text" name="adminusername" value=""> <br><br>
        Password: <input type="text" name="adminpass" value=""> <br><br>
        <button type="submit" name="button">Log In</button>
      </form>
    <?php else: ?>
      <!-- Logged in as admin; show admin stuff -->
      <h2>Welcome, Admin!</h2>

      <h3>Products</h3>

      <table>
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
                  <button type="submit" name="editbtn_admin">Edit</button>
                  <button type="submit" name="deletebtn_admin">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>

          <tr>
            <form class="" action="admin.php" method="post">
              <td>#</td>
              <td>
                <input type="text" name="pname_admin" >
              </td>
              <td>
                <input type="text" name="pprice_admin" >
              </td>
              <td>
                <button type="submit" name="addprodbtn">Add Product</button>
              </td>
            </form>
          </tr>
        </tbody>
      </table>

      <h3>Customers</h3>

      <table>
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
                  <button type="submit" name="cust_editbtn_admin">Edit</button>
                  <button type="submit" name="cust_deletebtn_admin">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <h3>Orders</h3>

      <table>
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
                  <button type="submit" name="ordereditbtn_admin">Edit</button>
                  <button type="submit" name="orderdeletebtn_admin">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>

        </tbody>
      </table>

    <?php endif; ?>

  </body>
</html>
