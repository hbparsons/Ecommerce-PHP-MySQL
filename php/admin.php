<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CandyCraze-Admin</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Slackey&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

  <!-- Header -->
  <?php
  include('includes/header.php');
  include('includes/connection.php');
  include('includes/db-functions.php');
  include('includes/log-in-modal.php');     // checking for login functionality later

  // redirects user if not an admin
  if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    header('Location: index.php');
    exit;
  }

  // Insert data into the product table

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['Product_Name'])));                    // Name
    $description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['ProductDescription'])));    // Description
    $quantity = mysqli_real_escape_string($dbc, trim(strip_tags(strtoupper($_POST['Stock']))));   // Product/Stock
    $imageURL =  mysqli_real_escape_string($dbc, trim(strip_tags($_POST['ImageFile'])));             // Image
    $price = mysqli_real_escape_string($dbc, trim(strip_tags(strtoupper($_POST['ProductPrice']))));  // Price

    // Insert into the product table
    $productQuery = "INSERT INTO product (Product_Name, ProductDescription, Stock, ProductPrice, ImageFile) VALUES ('$name', '$description', '$quantity', '$price', 'images/products/$imageURL')";

    if (!mysqli_query($dbc, $productQuery)) {
  ?> <script>
        alert("Unable to add product");
      </script><?php
              } else {
                ?> <script>
        alert("Product successfully added");
      </script><?php
              }

              // Inserting the Checkbox selections into the product_category table
              // if a checkbox is not empty
              if (!empty($_POST['flavour'])) {
                $productId = mysqli_insert_id($dbc); // Get the last inserted product ID
                // iterate through each checked box and assign its value to $categoryID
                foreach ($_POST['flavour'] as $categoryID) {
                  // Insert in product_category table
                  $categoryQuery = "INSERT INTO product_category (CategoryID, ProductID)  VALUES ('$categoryID', '$productId')";
                  if (!mysqli_query($dbc, $categoryQuery)) {
                ?> <script>
            alert("Unable to add category with ID: <?php echo $categoryID; ?>");
          </script><?php
                  }
                }
              } else {
                    ?> <script>
        alert("Please select a category");
      </script><?php
              }
            }
                ?>
  <!--End of Header -->

  <!--ADD PRODUCTS-->
  <main>

    <!-- Add Products Form-->
    <form id="add-product-form" class="container mt-4" action="admin.php" method="POST">
      <h2 class="mb-3" id="add-products-header">Add Products</h2>
      <div class="info-block mt-3 mb-5 p-3 pb-4">
        <h4>General Information</h4> <!-- General Info-->
        <div class="row mb-3">
          <!--Row 1-->
          <div class="col">
            <label for="product-name" class="form-label">Product name</label>
            <input type="text" id="product-name" class="form-control" placeholder="Our next best seller!" aria-label="Product name" name="Product_Name" required>
          </div>
          <div class="col my-4" id="category-checkboxes">
            <label for="product-category" class="form-label">Category</label>
            <div class="btn-group" role="group" aria-label="Checkboxes for product categories">
              <input type="checkbox" class="btn-check" id="checkbox1" name="flavour[]" value="101">
              <label class="btn btn-outline-dark" for="checkbox1">Hard</label>

              <input type="checkbox" class="btn-check" id="checkbox2" name="flavour[]" value="104">
              <label class="btn btn-outline-dark" for="checkbox2">Sour</label>

              <input type="checkbox" class="btn-check" id="checkbox3" name="flavour[]" value="100">
              <label class="btn btn-outline-dark" for="checkbox3">Soft</label>

              <input type="checkbox" class="btn-check" id="checkbox4" name="flavour[]" value="102">
              <label class="btn btn-outline-dark" for="checkbox4">Vegan</label>

              <input type="checkbox" class="btn-check" id="checkbox5" name="flavour[]" value="103">
              <label class="btn btn-outline-dark" for="checkbox5">Chocolate</label>
            </div>
          </div>
        </div>
        <div class="row">
          <!--Row 2-->
          <div class="col">
            <label for="product-description" class="form-label">Product description</label>
            <textarea name="ProductDescription" id="product-description" rows="3" class="form-control" required></textarea>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="formFile" class="form-label">Upload product image</label>
              <input class="form-control" type="file" id="image-file" name="ImageFile" required>
            </div>
          </div>
        </div>

      </div>
      <div class="info-block mt-2 mb-3 p-3 pb-4">
        <!-- Inventory Info -->
        <h4>Inventory Information</h4>
        <div class="row">
          <div class="col">
            <label for="product-quantity" class="form-label">Product quantity</label>
            <input type="number" class="form-control" placeholder="0" aria-label="Product Quantity" name="Stock" id="product-quantity" required>
          </div>
          <div class="col-6">
            <label for="product-price" class="form-label">Product price</label>
            <input type="text" class="form-control" placeholder="$" aria-label="Product Price" size="4" name="ProductPrice" id="product-price" required>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Add Product</button>
    </form>
    <!-- Form End -->
  </main>
  <script>
    $(document).ready(function() {
      $('#add-product-form').submit(function(event) {
        let checkboxes = $('input[name="flavour[]"]');
        let isChecked = checkboxes.is(':checked');

        if (!isChecked) {
          alert("Please select at least one category.");
          event.preventDefault(); // Prevent the form from being submitted
        }
      });
    });
  </script>
  <?php

  mysqli_close($dbc);

  include('includes/footer.php');

  ?>

 
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"> </script>
</body>

</html>