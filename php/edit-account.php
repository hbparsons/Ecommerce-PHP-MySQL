<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CandyCraze-Create-Account</title>
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
  ?>


  <!-- END OF BANNER AND HEADER -->
  <form id="create-account-form" action="edit-account-action.php" method="POST">
    <div class="d-flex justify-content-center">
      <div class="card m-5" style="max-width:1000px">
        <div class="card-header text-light bg-dark" id="create-account">
          Edit Account
        </div>
        <div class="card-body">
          <div class="card-text">
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" id="password" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="phonenumber" class="form-label">Phone Number</label>
              <input type="tel" name="phonenumber" class="form-control" id="phonenumber" value="<?php if (isset($_POST['phonenumber'])) echo $_POST['phonenumber']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" name="address" class="form-control" id="address" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>" required>
            </div>
            <div class="d-flex flex-wrap justify-content-end my-3">
              <button type="submit" class="btn btn-primary mt-3" id="submit-btn">Save</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </form>

  <script>
    const submitBtn = document.getElementById('submit-btn');

    const validate = (e) => {
      const password = document.getElementById('password');
      const email = document.getElementById('email');
      const phonenumber = document.getElementById('phonenumber');
      const address = document.getElementById('address');

      if (password.value === "" || password.value.length < 6) {
        alert("Please enter a password with at least 6 characters.");
        password.focus();
        e.preventDefault();
        return false;
      }

      if (email.value === "") {
        alert("Please enter your email address.");
        email.focus();
        e.preventDefault();
        return false;
      }

      if (!emailIsValid(email.value)) {
        alert("Please enter a valid email address.");
        email.focus();
        e.preventDefault();
        return false;
      }

      if (phonenumber.value === "" || !phoneNumberIsValid(phonenumber.value)) {
        alert("Please enter a valid phone number (8888888888 or 888-888-8888).");
        phonenumber.focus();
        e.preventDefault();
        return false;
      }

      if (address.value === "" || !addressIsValid(address.value)) {
        alert("Please enter a valid address (letters and numbers).");
        address.focus();
        e.preventDefault();
        return false;
      }

      return true; // Can submit the form data to the server
    }

    const emailIsValid = email => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    // Added the phoneNumberIsValid function to validate the phone number format, allowing either 8888888888 or 888-888-8888 formats.
    const phoneNumberIsValid = phone => /^(\d{10}|\d{3}-\d{3}-\d{4})$/.test(phone);

    // Added the addressIsValid function to validate the address input
    const addressIsValid = address => /^[a-zA-Z0-9 ]+$/.test(address);

    submitBtn.addEventListener('click', validate);
  </script>

  <!-- FOOTER -->
  <?php
  include('includes/footer.php');
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"> </script>
</body>

</html>