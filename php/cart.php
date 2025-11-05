<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CandyCraze-Cart</title>
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

    if (!isset($_SESSION['username']) || $_SESSION['username'] == "") {
        echo ("You will need to login/create an account before making a purchase.");
    } else {
        $username = $_SESSION['username'];
    }

    // Fetching Cart information from the database
    $query =  "SELECT cust.Username, c.ProductID, p.Product_Name, p.ProductPrice, c.PurchasedQuantity, p.ImageFile
FROM cart c
JOIN product p ON c.ProductID = p.ProductID
JOIN customer cust ON c.UserID = cust.UserID
WHERE cust.Username = ?";

    // Grabbing the results 
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Set variable for total price and check if the cart is empty
    $total_price = 0;
    $is_cart_empty = $result->num_rows === 0;
    ?>
    <!-- END OF BANNER AND HEADER -->

    <main>
        <!-- ITEMS -->
        <div class="items m-4 d-flex flex-column justify-content-center align-items-center">
            <?php
            if (!$is_cart_empty) {
                while ($row = $result->fetch_assoc()) {
                    $total_item_price = $row["ProductPrice"] * $row["PurchasedQuantity"];
                    $total_price += $total_item_price;
                    echo '
    
    <div class="card m-3" style="max-width: 800px">
    <div class="row g-0">
        <div class="col-md-4">
        <img src="' . $row["ImageFile"] . '" class="img-fluid rounded-start" alt="' . $row["Product_Name"] . '">
        </div>
        <div class="col-md-8">
        <div class="card-body">
            <h5 class="card-title">' . $row["Product_Name"] . '</h5>
            <div class="card-text d-flex justify-content-evenly align-items-center">
            <table class="table">
            <thead>
                <tr>
                    <th scope="col">Price</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Total</th>
                    <th scope="col">Remove</th>
                </tr>   
            </thead>
            <tbody>
                <tr>
                    <td>
                       <p> $' . $row["ProductPrice"] . '</p>
                    </td>
                    <td class="d-flex flex-wrap">
                        <input class="quantity mb-1" type="number" name="quantity" min="0" value="' . $row["PurchasedQuantity"] . '">
                        <button type="button" class="btn btn-info update-quantity ms-sm-3 purchaseBtn" data-product-id="' . $row["ProductID"] . '">Update</button>
                        
                    </td>
                    
                    <td>
                        <p class="price">$' . number_format($total_item_price, 2) . '</p> <!--this will change depending on how many items are in the cart-->
                    </td>
                    <td>
                        <button type="button" class="btn-close p-2 remove-item" data-product-id="' . $row["ProductID"] . '"></button> <!--will remove item from cart-->
                    </td>
                </tr>
            </tbody>
            </table>
               
        </div>
        </div>
        </div>
       
    </div>
    </div>';
                }
            } else {
                echo '<p>Your shopping cart is empty. Click <a href="index.php">here</a> to start shopping.</p>';
            }
            ?>
        </div>
        <!--END OF ITEMS -->

        <!-- TOTAL -->
        <div class="total d-flex justify-content-end">
            <div class="card mt-0">
                <div class="card-body">
                    <p class="h5">TOTAL: $<?php echo number_format($total_price, 2); ?></p>
                </div>
            </div>
        </div>

        <!-- made these two button conditional. If the cart is empty, the user won't see the buttons -->
        <div class="checkout-button d-flex justify-content-end my-4 pb-5">
            <?php
            if (!$is_cart_empty) {
            ?>
                <a href="checkout.php" class="btn btn-primary purchaseBtn">Checkout</a>
                <a href="#" id="delete-cart" class="btn btn-primary ms-2">Delete Cart</a>
            <?php
            }
            ?>
        </div>

        <!--MODALS FOR LOG IN-->
        <?php
        include('includes/log-in-modal.php');
        include('includes/footer.php');
        ?>
        <!--END OF MODALS-->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <script>
            // When the user clicks the "Delete Cart" button, a confirmation dialog will appear.
            document.getElementById('delete-cart').addEventListener('click', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to remove all items from cart?')) {
                    window.location.href = 'cart-action.php?delete=all';
                }
            });

            // Executes the action of removing single items from cart when the X button is clicked
            function removeItem(event) {
                var productId = this.getAttribute("data-product-id");

                if (confirm("Are you sure you want to remove this item from the cart?")) {
                    window.location.href = "cart-action.php?remove=" + productId;
                }
            }

            // Executes the action of updating quantities for single items from cart when the update button is clicked
            function updateQuantity(event) {
                var productId = this.getAttribute("data-product-id");
                var quantityInput = this.previousElementSibling;
                var newQuantity = quantityInput.value;

                if (newQuantity == 0) {
                    if (confirm("Quantity is zero. Do you want to remove this item from the cart?")) {
                        window.location.href = "cart-action.php?remove=" + productId;
                    }
                } else {
                    window.location.href = "cart-action.php?update=" + productId + "&quantity=" + newQuantity;
                }
            }

            document.querySelectorAll(".remove-item").forEach(function(button) {
                button.addEventListener("click", removeItem);
            });

            document.querySelectorAll(".update-quantity").forEach(function(button) {
                button.addEventListener("click", updateQuantity);
            });

            // If the user has disagreed to the privacy agreement, or the agreement is null, disable the purchase button
            $(document).ready(function() {
                let privacyAgreement = <?php echo isset($_SESSION['privacyAccepted']) && ($_SESSION['privacyAccepted'] == 0 || $_SESSION['privacyAccepted'] === null) ? 'true' : 'false'; ?>;

                if (privacyAgreement) {
                    $(".purchaseBtn").addClass("disabled");
                } else {
                    $(".purchaseBtn").removeClass("disabled");
                }
            });
        </script>
    </main>

    <!-- FOOTER -->
    <?php
    include('includes/footer.php');
    ?>


</body>

</html>