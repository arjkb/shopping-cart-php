<?php
  session_start();
  require_once "pdo.php";

  $admin_logged_in = false;

  if (isset($_SESSION['admin'])) {
    $admin_logged_in = true;

    // set up database connections
    $stmt_selectall_product = $pdo->query('SELECT id, pname, unitprice FROM product');

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

        </thead>
        <tbody>
          <?php while($row = $stmt_selectall_product->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['pname'] ?></td>
              <td><?= $row['unitprice'] ?></td>
              <td>
                <form class="" action="" method="post">
                  <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="editbtn_admin">Edit</button>
                  <button type="submit" name="deletebtn_admin">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <h3>Customers</h3>

      <h3>Orders</h3>



    <?php endif; ?>

  </body>
</html>
