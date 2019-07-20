<?php
  echo "php works!";
  if (isset($_POST['items'])) {
    // code...
    if (empty($_POST['items'])) {
      // code...
      echo "stuff is empty";
    } else {
      echo "not empty";
    }
    echo " Items = ".$_POST['items'];
    // echo " Items = ".json_decode($_POST['items']);

    // $chosenItems = json_decode($_POST['items']);
    $chosenItems = implode(', ', json_decode($_POST['items']));
    print_r($chosenItems);
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Buy Confirmation | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>

    <h1>Buy Confirmation</h1>
  </body>
</html>
