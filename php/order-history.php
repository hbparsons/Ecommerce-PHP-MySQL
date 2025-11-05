<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CandyCraze- Order History</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Slackey&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php
    include('includes/header.php');
    include('includes/connection.php');
    include('includes/db-functions.php');
    $userID = $_SESSION['userID']; // Get the user ID from the session

    // Redirects user if not logged in
    if (!isset($userID)) {
        header('Location: index.php');
        exit;
    }

    // Reorder functionality
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reorder'])) {
        $productID = intval($_POST['productID']); // Get product ID from form
        $orderNumber = intval($_POST['orderNumber']); // Get order number from form

        // Get the ordered quantity from the previous order
        $query = "SELECT PurchasedQuantity FROM order_details WHERE OrderNumber = $orderNumber AND ProductID = $productID";
        $result = $dbc->query($query); // Execute the query
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch the result
            $quantity = $row['PurchasedQuantity']; // Get the quantity

            // Check if the product is already in the cart
            $query = "SELECT * FROM cart WHERE userID = $userID AND ProductID = $productID";
            $result = $dbc->query($query);
            if ($result && $result->num_rows > 0) {
                // Update the quantity in the cart
                $query = "UPDATE cart SET PurchasedQuantity = PurchasedQuantity + $quantity WHERE UserID = $userID AND ProductID = $productID";
            } else {
                // Insert the product into the cart
                $query = "INSERT INTO cart (UserID, ProductID, PurchasedQuantity) VALUES ($userID, $productID, $quantity)";
            }
            $dbc->query($query);
        }
    }
    ?>


    <main>


        <div id="account-container d-flex">
            <div class="row" id="account-info">
                <div class="col-xl-10 col-md-12">

                    <!-- Order History -->
                    <h3 class="mx-5 mt-5 mb-4" id="order-title">Order History</h3>
                    <div id="order-box" class="m-2 pb-2 mt-4">

                        <div class="d-inline-flex">
                            <table class="table table-dark mx-5 mb-5 table-bordered border-dark rounded" id="account-menu">
                                <tbody>
                                    <tr class="d-flex justify-content-center">
                                        <td class="account-td"><a href="account.php" class="account-link">My Account</a></td>
                                        <td class="account-td"><a href="privacy-agreement.php" class="account-link">Privacy Agreement</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- PHP FUNCTION TO DISPLAY ALL ORDERS PER CUSTOMER -->
                        <?php
                        $orders = getOrderHistory($dbc, $userID);

                        function createHistoryOrderTable($orderNumber, $date, $products)
                        {
                            $str = <<<STR
                        <div class="container-fluid">
                            <div class="container">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex justify-content-between align-items-center pt-3">
                                            <h2 class="h5 mb-0">Order #$orderNumber</h2>
                                            <span>$date</span>
                                            <span><a href="order-confirmation-pdf.php?orderNumber=$orderNumber">Invoice</a></span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-8">
                                        <div class="card my-3">
                                            <div class="card-body">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Quantity</th>
                                                            <th class="text-end">Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
STR;
                            $subtotal = 0;
                            // Loop through products to generate rows for each product in the order
                            foreach ($products as $product) {
                                $formattedPrice = number_format($product['ProductPrice'], 2);
                                $subtotal += $product['ProductPrice'] * $product['PurchasedQuantity'];
                                $str .= <<<STR
                            <tr>
                                <td>
                                    <div class="d-flex mb-2">
                                        <div class="flex-lg-grow-1 ms-3">
                                           <h6 class="small mb-0" style="text-decoration: underline;">{$product['Product_Name']}</h6>
                                            <span class="small">Product ID: {$product['ProductID']}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{$product['PurchasedQuantity']}</td>
                                <td class="text-end">$$formattedPrice</td>
                            </tr>
                            <tr>
                                <td>
                                    <form class="ms-3 pb-4"  action="order-history.php" method="post">
                                        <input type="hidden" name="orderNumber" value="$orderNumber">
                                        <input type="hidden" name="productID" value="{$product['ProductID']}">
                                        <button type="submit" name="reorder" class="btn btn-primary addItemBtn">Reorder</button>
                                    </form>
                                </td>
                            </tr>
STR;
                            }
                            $tax = $subtotal * 0.12;
                            $total = $subtotal + $tax;
                            $formattedSubtotal = number_format($subtotal, 2);
                            $formattedTax = number_format($tax, 2);
                            $formattedTotal = number_format($total, 2);
                            $str .= <<<STR
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2">Subtotal</td>
                                                            <td class="text-end">$$formattedSubtotal</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Tax (12%)</td>
                                                            <td class="text-end">$$formattedTax</td>
                                                        </tr>
                                                        <tr class="fw-bold">
                                                            <td colspan="2">TOTAL</td>
                                                            <td class="text-end">$$formattedTotal</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
STR;
                            return $str;
                        }
                        // Check if there are any orders
                        if ($orders->num_rows > 0) {
                            $currentOrderNumber = null;
                            $currentOrderDate = null;
                            $currentOrderProducts = [];
                            // Loop through each order row
                            while ($row = mysqli_fetch_array($orders)) {
                                if ($currentOrderNumber !== $row['OrderNumber']) {
                                    if ($currentOrderNumber !== null) {
                                        echo createHistoryOrderTable($currentOrderNumber, $currentOrderDate, $currentOrderProducts);
                                    }
                                    $currentOrderNumber = $row['OrderNumber'];
                                    $currentOrderDate = $row['Date'];
                                    $currentOrderProducts = [];
                                }
                                $currentOrderProducts[] = $row;
                            }

                            // Print the last order table
                            if ($currentOrderNumber !== null) {
                                echo createHistoryOrderTable($currentOrderNumber, $currentOrderDate, $currentOrderProducts);
                            }
                        } else {
                            echo '<p>No Orders found.</p>';
                        }
                        ?>

                    </div>
                </div>

            </div>
        </div>

        <!-- Script to manage reorder button based on privacy agreement status -->
        <script type="text/javascript">
            $(document).ready(function() {
                let privacyAgreement = <?php echo isset($_SESSION['privacyAccepted']) && ($_SESSION['privacyAccepted'] == 0 || $_SESSION['privacyAccepted'] === null) ? 'true' : 'false'; ?>;

                if (privacyAgreement) {
                    $(".addItemBtn").addClass("disabled");

                } else {
                    $(".addItemBtn").removeClass("disabled");
                }


            });
        </script>
    </main>
    <?php
    include('includes/footer.php');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>