<?php
session_start();

require_once('./config.php');
require_once('./vendor/autoload.php'); // Ensure Stripe's PHP library is included
include('order-save-pdf.php');

$token = $_POST['stripeToken'];
$email = $_POST['stripeEmail'];
$totalamt = $_POST['totalamt'];


// Ensure the total amount is converted to cents and is an integer
$totalamt = (int)($totalamt);


// Use Stripe secret key for server-side operations
  //Stripe Key would go here!

// Create a customer and charge
$customer = \Stripe\Customer::create([
  'email' => $email,
  'source' => $token
]);

$charge = \Stripe\Charge::create([
  'customer' => $customer->id,
  'amount' => $totalamt, // Pass the amount in cents as an integer
  'currency' => 'cad'
]);

// Format the amount for display purposes
$amount = number_format($totalamt / 100, 2);

// Start session and include database connection
session_start();
include('includes/connection.php');

$userID = $_SESSION['userID'];

// Step 1: Insert a new record into the order_history table
$stmt = $dbc->prepare('INSERT INTO order_history (UserID, Date, Status) VALUES (?, NOW(), ?)');
$status = 'Completed';
$stmt->bind_param('is', $userID, $status);
$stmt->execute();

// Step 2: Retrieve the generated OrderNumber
$orderNumber = $stmt->insert_id;

// Step 3: Insert records into the order_details table for each product in the user's cart
$query = 'SELECT ProductID, PurchasedQuantity FROM cart WHERE UserID=?';
$stmt = $dbc->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $productID = $row['ProductID'];
  $purchasedQuantity = $row['PurchasedQuantity'];

  // Get the product price
  $productStmt = $dbc->prepare('SELECT ProductPrice FROM product WHERE ProductID=?');
  $productStmt->bind_param('i', $productID);
  $productStmt->execute();
  $productResult = $productStmt->get_result();
  $product = $productResult->fetch_assoc();
  $productPrice = $product['ProductPrice'];

  // Insert into order_details
  $detailsStmt = $dbc->prepare('INSERT INTO order_details (OrderNumber, ProductID, PurchasedQuantity, ProductPrice) VALUES (?, ?, ?, ?)');
  $detailsStmt->bind_param('iiid', $orderNumber, $productID, $purchasedQuantity, $productPrice);
  $detailsStmt->execute();
}

savePDF($dbc, $orderNumber);

// Step 4: Remove all items from the cart for the user
$deleteStmt = $dbc->prepare('DELETE FROM cart WHERE UserID=?');
$deleteStmt->bind_param('i', $userID);
$deleteStmt->execute();

// Redirect to order-confirmation.php
header('Location: order-confirmation.php?orderNumber=' . $orderNumber);
// Set the session variable to block the page
$_SESSION['block_page'] = true;

exit;
