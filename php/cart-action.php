<?php
session_start();
include('includes/connection.php');

// Add products into the cart table
if (isset($_POST['pid'])) {
  $userID = $_SESSION['userID'];
  $pid = $_POST['pid'];
  $pname = $_POST['pname'];
  $pprice = $_POST['pprice'];
  $pimage = $_POST['pimage'];
  $pcode = $_POST['pcode'];
  $pqty = $_POST['pqty'];
  $total_price = $pprice * $pqty;

  // Check if the quantity is greater than 0
  if ($pqty > 0) {
    $stmt = $dbc->prepare('SELECT ProductID FROM cart WHERE UserID = ? AND ProductID = ?');
    $stmt->bind_param('ss', $userID, $pid);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();
    $code = $r['ProductID'] ?? '';

    if (!$code) {
      // Insert new product into the cart
      $query = $dbc->prepare('INSERT INTO cart (UserID,ProductID,PurchasedQuantity) VALUES (?,?,?)');
      $query->bind_param('sss', $userID, $pid, $pqty);
      $query->execute();

      echo '<div class="alert alert-success alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Item added to your cart!</strong>
						</div>';
    } else {
      // Update quantity of the existing product in the cart
      $query = $dbc->prepare('UPDATE cart SET PurchasedQuantity = PurchasedQuantity + ? WHERE UserID = ? and ProductID =?');
      $query->bind_param('sss', $pqty, $userID, $pid);
      $query->execute();
      echo '<div class="alert alert-danger alert-dismissible mt-2">
						  <button type="button" class="close" data-dismiss="alert">&times;</button>
						  <strong>Item already added to your cart!</strong>
						</div>';
    }
  }
}

// Update quantity of a specific item in the cart
if (isset($_GET['update']) && isset($_GET['quantity'])) {
  $pidToUpdate = $_GET['update'];
  $newQuantity = $_GET['quantity'];
  $userID = $_SESSION['userID'];

  if ($newQuantity > 0) {
    $stmt = $dbc->prepare('UPDATE cart SET PurchasedQuantity=? WHERE UserID=? AND ProductID=?');
    $stmt->bind_param('sss', $newQuantity, $userID, $pidToUpdate);
    $stmt->execute();

    $_SESSION['message'] = 'Cart updated successfully!';
  } else {
    $_SESSION['message'] = 'Quantity must be greater than zero!';
  }

  header('location:cart.php');
  exit();
}

//Remove single items from cart
if (isset($_GET['remove'])) {
  $pidToRemove = $_GET['remove'];
  $userID = $_SESSION['userID'];

  $stmt = $dbc->prepare('DELETE FROM cart WHERE UserID=? AND ProductID=?');
  $stmt->bind_param('ss', $userID, $pidToRemove);
  $stmt->execute();

  // $_SESSION['showAlert'] = 'block';
  $_SESSION['message'] = 'Item removed from the cart!';
  header('location:cart.php');
  exit();
}

// Remove all items at once from cart
if (isset($_GET['delete']) && $_GET['delete'] == 'all') {
  $userID = $_SESSION['userID'];

  $stmt = $dbc->prepare('DELETE FROM cart WHERE UserID=?');
  $stmt->bind_param('s', $userID);
  $stmt->execute();

  $_SESSION['message'] = 'All items removed from the cart!';
  header('location:cart.php');
  exit();
}


// Get no.of items available in the cart table
if (isset($_GET['cartItems']) && isset($_GET['cartItems']) == 'cart_items') {
  if (!isset($_SESSION['userID'])) {
    echo 0;
    exit();
  }

  $userID = $_SESSION['userID'];
  $stmt = $dbc->prepare('SELECT * FROM cart WHERE UserID=?');
  $stmt->bind_param('s', $userID);
  $stmt->execute();
  $stmt->store_result();
  $rows = $stmt->num_rows;

  echo $rows;
  exit();
}
