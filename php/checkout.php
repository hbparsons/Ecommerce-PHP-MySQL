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
    include('includes/utils.php');


    if (!isset($_SESSION['username']) || $_SESSION['username'] == "") {

        header('Location: index.php');
        exit;
    } else {
        $username = $_SESSION['username'];
        $userEmail = $_SESSION['userEmail'];
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

    // Set variable for total price
    $total_price = 0;
    ?>
    <!-- END OF BANNER AND HEADER -->


    <!-- ITEMS -->
    <div class="items m-4 d-flex flex-column justify-content-center align-items-center">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $total_item_price = $row["ProductPrice"] * $row["PurchasedQuantity"];
                $total_price += $total_item_price;
                echo '
    <div class="card mb-3" style="max-width: 800px">
    <div class="row g-0">
        <div class="col-md-4">
        <img src="' . $row["ImageFile"] . '" class="img-fluid rounded-start" alt="' . $row["Product_Name"] . '">
        </div>
        <div class="col-md-8">
        <div class="card-body">
            <h5 class="card-title">' . $row["Product_Name"] . '</h5>
            <div class="card-text d-flex justify-content-evenly align-items-center">
                <p>Price</p>
                <p>Amount</p>
                <p>Total</p>
              
            </div>
            <div class="card-text d-flex justify-content-evenly align-items-center">
                <p>$' . $row["ProductPrice"] . '</p>
                 <p>' . $row["PurchasedQuantity"] . '</p> <!-- Fixed quantity -->
                <p class="price">$' . number_format($total_item_price, 2) . '</p> <!--this will change depending on how many items are in the cart-->
                
            </div>
        </div>
        </div>
    </div>
    </div>';
            }
        } else {
            echo '<p>Your shopping cart is empty. Click <a href="index.php">here</a> to start shopping.';
        }
        ?>
    </div>
    <!--END OF ITEMS -->

    <!-- TOTAL -->

    <div class="total d-flex justify-content-end">
        <div class="card mt-0">
            <div class="card-body">
                <p class="h5">Subtotal: $<?php echo number_format($total_price, 2); ?></p>
                <p class="h5">Tax (12%): $<?php echo number_format(getTaxAmount($total_price), 2); ?></p>
                <p class="h5">TOTAL: $<?php echo number_format(addTax($total_price), 2); ?></p>
            </div>
        </div>
    </div>

    <div class=" checkout-button d-flex justify-content-end my-4 pb-5  ">

        <form action="charge.php" method="POST">
            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="pk_test_51POtPqP3chMnqoAktaMf9c1vWFMuM3QS6dP7ZqDMIy4TKwHTCrV5Q2tNLkfykXvUqkDF1cVPrkXIRUMYY6sW1AS300OnaecXsS" data-description="Payment Form" data-amount="<?php echo $final_total * 100; ?>" data-locale="auto" data-email="<?php echo $userEmail; ?>">

            </script>
            <input type="hidden" name="totalamt" value="<?php echo addTax($total_price) * 100; ?>">
        </form>


    </div>

    <!-- END OF TOTAL -->

    <!--MODALS FOR LOG IN-->
    <?php
    include('includes/log-in-modal.php');
    include('includes/footer.php');
    ?>
    <!--END OF MODALS-->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



    <!-- FOOTER -->
    <?php
    include('includes/footer.php');
    ?>


</body>

</html>