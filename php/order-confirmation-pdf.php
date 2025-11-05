<?php
require_once('includes/fpdf186/fpdf.php');        // The PDF class library 
include('includes/connection.php');

session_start();

// Fetching order details from DB
$order_id = $_GET['orderNumber'];
$user_id = $_SESSION['userID'];


$query = "SELECT 
    oh.OrderNumber AS `Order ID`,
    CONCAT(c.Firstname, ' ', c.Lastname) AS `Customer Name`,
    oh.Date AS `Order Date`,
    p.Product_Name AS `Item Name`,
    od.PurchasedQuantity AS `Quantity`,
    p.ProductPrice AS `Price`,
    (od.PurchasedQuantity * p.ProductPrice) AS `Subtotal`
FROM 
    order_history oh
JOIN 
    customer c ON oh.UserID = c.UserID
JOIN 
    order_details od ON oh.OrderNumber = od.OrderNumber
JOIN 
    product p ON od.ProductID = p.ProductID
WHERE 
    oh.OrderNumber = ? AND oh.UserID = ?";

// prepare Statement
$stmt = $dbc->prepare($query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch order details
$order_details = $result->fetch_assoc();
if (!$order_details) {
    die("Order not found.");
}

// create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Set content
$pdf->Cell(0, 10, 'Order Receipt', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Order ID: ' . $order_details['Order ID'], 0, 1);
$pdf->Cell(0, 10, 'Customer Name: ' . $order_details['Customer Name'], 0, 1);
$pdf->Cell(0, 10, 'Order Date: ' . $order_details['Order Date'], 0, 1);

$pdf->Ln(10); // line break

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Item', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(40, 10, 'Price ($)', 1);
$pdf->Cell(40, 10, 'Subtotal ($)', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);

// Fetch all items
$total_amount = 0;
do {
    $pdf->Cell(80, 10, $order_details['Item Name'], 1);
    $pdf->Cell(30, 10, $order_details['Quantity'], 1);
    $pdf->Cell(40, 10, '$' . number_format($order_details['Price'], 2), 1);
    $pdf->Cell(40, 10, '$' . number_format($order_details['Subtotal'], 2), 1);
    $pdf->Ln();

    $total_amount += $order_details['Subtotal'];
} while ($order_details = $result->fetch_assoc());

// Calculate tax
$tax_amount = $total_amount * 0.12;
$total_with_tax = $total_amount + $tax_amount;

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'Grand Subtotal ($)', 1);
$pdf->Cell(40, 10, '$' . number_format($total_amount, 2), 1);
$pdf->Ln();

// Tax line
$pdf->Cell(150, 10, 'Tax (12%) ($)', 1);
$pdf->Cell(40, 10, '$' . number_format($tax_amount, 2), 1);
$pdf->Ln();

// Total with tax
$pdf->Cell(150, 10, 'Total with Tax ($)', 1);
$pdf->Cell(40, 10, '$' . number_format($total_with_tax, 2), 1);

// Output the PDF
$pdf->Output('D', 'order_receipt.pdf');

$dbc->close();
