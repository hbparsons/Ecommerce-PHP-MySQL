<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CandyCraze-Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Slackey&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

    <!-- BANNER AND HEADER -->

    <?php
    include('includes/header.php');
    include('includes/connection.php');
    include('includes/db-functions.php');
    include('includes/log-in-modal.php');
    include('includes/utils.php');



    // Retrieve customer name from session or database
    $userID = $_SESSION['userID'];
    $query = "SELECT Firstname, Lastname FROM customer WHERE UserID = ?";
    $stmt = $dbc->prepare($query);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $customerName = $row['Firstname'] . ' ' . $row['Lastname'];


    // Retrieve order details
    if (isset($_GET['orderNumber'])) {


        $orderNumber = $_GET['orderNumber'];
        $query = "SELECT 
    oh.OrderNumber, 
    oh.Date, 
    SUM(od.PurchasedQuantity * od.ProductPrice) AS TotalAmount
FROM 
    order_history oh
JOIN 
    order_details od ON oh.OrderNumber = od.OrderNumber
WHERE 
    oh.UserID = ? AND oh.OrderNumber = ?
GROUP BY 
    oh.OrderNumber, 
    oh.Date
ORDER BY oh.Date";

        $stmt = $dbc->prepare($query);
        $stmt->bind_param('ii', $userID, $orderNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $date = date('Y-m-d H:i:s', strtotime($row['Date']));
        $totalAmount = $row['TotalAmount'];
    ?>

        <!doctype html>
        <html lang="en">

        <head>

        </head>

        <body>

        <main>
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="card" style="width: 80vw;">
                        <div class="card-header bg-dark" id="order-confirm-title">
                            Order Confirmation
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                <p><strong>Thank you for your order, <?php echo htmlspecialchars($customerName); ?>!</strong></p>
                                <p>Your order details:</p>
                                <table class="table">
                                    <tr>
                                        <td><strong>Order Number:</strong></td>
                                        <td><?php echo $orderNumber; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date:</strong></td>
                                        <td><?php echo $date; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount Paid:</strong></td>
                                        <td>$<?php echo number_format(addTax($totalAmount), 2); ?></td>
                                    </tr>
                                </table>
                                <p>If you would like a receipt, please press the download button below</p>
                            </div>
                            <button type="button" class="btn btn-primary" id="pdf-download">Download</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $("#pdf-download").click(function() {
                        window.location.href = 'order-confirmation-pdf.php?orderNumber=<?php echo $orderNumber; ?>';
                    });
                });
            </script>
        <?php
    } else {
        ?>

            <script>
                alert("This page is not available");
                window.location.href = "index.php";
            </script>
        <?php
    }
        ?>

</main>
        <!-- FOOTER -->
        <?php
        include('includes/footer.php');
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        </body>

        </html>