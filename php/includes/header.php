<!-- BANNER IMAGE -->
<div id="hero-image">
  <div id="hero-text">
    <h1>CandyCraze</h1>
    <h5>We're just crazy about candy</h5>
  </div>
</div>
<!-- will have title & subheading overlaid -->

<!-- END OF BANNER IMAGE -->

<?php
session_start();
?>

<!--              NAV BAR 
  this holds the links to the other pages.-->

<nav class="navbar bg-dark border-bottom border-body navbar-expand-lg bg-body-tertiary justify-content-end" data-bs-theme="dark">
  <div class="container-fluid justify-content-end">
    <!--<a class="navbar-brand" href="#">Navbar</a>-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php">Cart<span id="cart-items" class="badge text-bg-secondary ms-2">0</span></a>
        </li>

        <?php
        if (!isset($_SESSION['username'])) {
          echo '<li class="nav-item">
                <a class="nav-link" data-bs-toggle="modal" data-bs-target="#log-in-modal">Log In</a>
                </li>';
        } else {
          echo '<li class="nav-item">
                <a class="nav-link" href="account.php">Account</a>
                </li>';

          if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
            echo '<li class="nav-item">
                  <a class="nav-link" href="admin.php">Admin</a>
                </li>';
          }

          echo '<li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
                </li>';
        }
        ?>

      </ul>
    </div>
  </div>
</nav>

<!-- END OF NAV BAR -->

<script type="text/javascript">
  function load_cart_item_number() {
    $.ajax({
      url: 'cart-action.php',
      method: 'get',
      data: {
        cartItems: "cart_items"
      },
      success: function(response) {
        $("#cart-items").html(response);
      }
    });
  }

  $(document).ready(function() {
    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();
  });
</script>