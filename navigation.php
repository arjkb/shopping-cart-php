<nav>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="#">About</a></li>
    <?php if(isset($_SESSION['username'])): ?>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">Login</a></li>
    <?php endif; ?>
    <li><a href="signup.php">Sign Up</a></li>
    <li><a href="admin.php">Admin Login</a></li>
  </ul>
</nav>
