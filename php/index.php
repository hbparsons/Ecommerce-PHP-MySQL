<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CandyCraze-Home</title>
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



    ?>
    <!-- END OF BANNER AND HEADER -->

    <div id="message"></div>
    <main>
        <!-- FILTER -->
        <?php
        $categoriesDesc = getCategoryDescription($dbc);
        function createFilterRadioButtons($categoryID, $categoryName)
        {
            $categoryID = htmlspecialchars($categoryID);
            $categoryName = htmlspecialchars($categoryName);
            $str = <<<STR
        <li class="list-group-item">
            <div class="form-check mx-3">
                <input class="form-check-input" type="radio" name="category" value="$categoryID" id="category$categoryID">
                <label class="form-check-label" for="category$categoryID">
                    $categoryName
                </label>
            </div>
        </li>
STR;
            return $str;
        }
        ?>
        <!-- Dynamic accordion -->
        <div class="container mt-5">
            <div class="filter accordion" id="accordionExample" style="width: 200px">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Filter
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <form action="index.php" method="GET">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="form-check mx-3">
                                            <input class="form-check-input" type="radio" name="category" value="-1" id="allCategories">
                                            <label class="form-check-label" for="allCategories">
                                                All
                                            </label>
                                        </div>
                                    </li>
                                    <?php
                                    if ($categoriesDesc->num_rows > 0) {
                                        while ($row = $categoriesDesc->fetch_assoc()) {
                                            echo createFilterRadioButtons($row['CategoryID'], $row['CategoryName']);
                                        }
                                    } else {
                                        echo '<li class="list-group-item">No categories found.</li>';
                                    }
                                    ?>
                                </ul>
                                <button type="submit" class="btn btn-primary mt-3">Apply Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- END OF FILTER -->


        <!-- MAIN SHOP -->
        <!-- PHP FUNCTION TO DISPLAY ALL PRODUCTS -->
        <?php
        $products = getAllProducts($dbc);
        function createProductCard($productID, $productName, $productDescription, $stock, $price, $imageURL)
        {
            $formattedPrice = number_format($price, 2, '.', ',');
            $str = <<<STR
        <div class="col">
            <div class="card">
                <img src="$imageURL" class="card-img-top" alt="$productName image">
                <div class="card-body d-flex flex-column shop-card-body">
                    <h5 class="card-title">$productName</h5>
                    <div class="card-text">
                        <p>$productDescription</p>
                        <p class="price">$$formattedPrice</p>
                    </div>
                    <div class="mt-auto">
                    <form class="d-flex justify-content-evenly form-submit">
                        <label for="quantity$productID">Amount:</label>
                        <input class="quantity m-1" type="number" id="quantity$productID" name="quantity" min="1" value="1">

                        <input type="hidden" class="pid" value="$productID">
                            <input type="hidden" class="pname" value="$productName">
                            <input type="hidden" class="pprice" value="$price">
                            <input type="hidden" class="pimage" value="$imageURL">
                            <input type="hidden" class="pcode" value="$stock">

                            <button class="btn btn-info btn-block addItemBtn"><i class="fas fa-cart-plus"></i>Add to
                            cart</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
STR;
            return $str;
        }
        ?>
        <!-- Displaying Products End -->


        <!--  Dynamically changes the cards shown depending on the category selected -->
        <div class="content container px-4">
            <div id="shop" class="d-flex flex-wrap justify-content-evenly px-0">
                <div class="d-flex row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                    <?php

                    if (isset($_GET['category'])) {
                        $selectedCategoryID = intval($_GET['category']);
                        if ($selectedCategoryID == -1) {
                            $products = getAllProducts($dbc);
                        } else {
                            $products = getCategoryByID($dbc, $selectedCategoryID);
                        }
                    } else {
                        $products = getAllProducts($dbc);
                    }

                    if ($products->num_rows > 0) {
                        while ($row = mysqli_fetch_array($products)) {
                            print createProductCard($row['ProductID'], $row['Product_Name'], $row['ProductDescription'], $row['Stock'], $row['ProductPrice'], $row['ImageFile']);
                        }
                    } else {
                        print '<p>No products found.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- END OF MAIN SHOP -->


        <!-- MODALS FOR LOG IN -->
        <!--  If the ($_SESSION['openModal'] == 1, it means that the login failed-->
        <?php
        include('includes/log-in-modal.php');
        if (isset($_SESSION['openModal']) && ($_SESSION['openModal'] == 1)) {
            unset($_SESSION['openModal']);
            print '<script>var myModal = new bootstrap.Modal("#log-in-modal");myModal.show();</script>';
        }

        ?>


        <!-- jQuery script to add disabled class based on privacyAgreement -->
        <script type="text/javascript">
            $(document).ready(function() {
                let privacyAgreement = <?php echo isset($_SESSION['privacyAccepted']) && ($_SESSION['privacyAccepted'] == 0 || $_SESSION['privacyAccepted'] === null) ? 'true' : 'false'; ?>;

                if (privacyAgreement) {
                    $(".addItemBtn").addClass("disabled");

                    var alertMessage = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        If you changed your mind about our privacy policy, please visit Privacy Agreement under the Account tab.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
                    $("#message").html(alertMessage);


                } else {
                    $(".addItemBtn").removeClass("disabled");
                }

                var isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

                $(".addItemBtn").click(function(e) {
                    e.preventDefault();

                    if (!isLoggedIn) {
                        // User is not logged in, show the alert message
                        var alertMessage = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Please Log in to add items to the cart.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        $("#message").html(alertMessage);

                        // Scroll to the top of the page
                        window.scrollTo(0, 0);

                        // Listen for the close event on the alert
                        $('.alert').on('closed.bs.alert', function() {
                            // Show the login modal after the alert is closed
                            var myModal = new bootstrap.Modal(document.getElementById('log-in-modal'));
                            myModal.show();
                        });
                        return;
                    }

                    var $form = $(this).closest(".form-submit");
                    var pid = $form.find(".pid").val();
                    var pname = $form.find(".pname").val();
                    var pprice = $form.find(".pprice").val();
                    var pimage = $form.find(".pimage").val();
                    var pcode = $form.find(".pcode").val();
                    var pqty = $form.find(".quantity").val();

                    $.ajax({
                        url: 'cart-action.php',
                        method: 'post',
                        data: {
                            pid: pid,
                            pname: pname,
                            pprice: pprice,
                            pqty: pqty,
                            pimage: pimage,
                            pcode: pcode
                        },
                        success: function(response) {
                            window.scrollTo(0, 0);
                            load_cart_item_number();
                        }
                    });
                });
            });
        </script>
    </main>
    <!-- FOOTER -->
    <?php
    include('includes/footer.php');
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"> </script>
</body>

</html>