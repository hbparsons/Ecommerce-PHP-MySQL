<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<?php
session_start();

include('includes/connection.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //form validates input using html validation. 

    //cleans user input data & sets variables
    $username = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['username'])));
    $firstname = trim(strip_tags($_POST['firstname']));
    $lastname = trim(strip_tags($_POST['lastname']));
    $password = trim(strip_tags($_POST['password']));
    $email = trim(strip_tags($_POST['email']));
    $phonenumber = trim(strip_tags($_POST['phonenumber']));
    $address = trim(strip_tags($_POST['address']));

    //hashes the password. 
    $password = password_hash($password, PASSWORD_BCRYPT);

    if (isset($username)) {

        //query to check username
        $query = "SELECT * FROM customer WHERE username='$username'";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check result
        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Username already in use.");</script>';
            echo '<script>window.location.href = "create-account.php"</script>';
            exit;
        }
    }

    if (isset($email)) {
        // Query to check email
        $query = "SELECT * FROM customer WHERE email= '$email'";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Email already in use.");</script>';
            echo '<script>window.location.href = "create-account.php"</script>';
            exit;
        }
    }

    //save to database
    $query = "INSERT INTO customer (username,firstname,lastname,password,email,phonenumber,address,isadmin,privacyaccepted) 
    values ('$username','$firstname','$lastname','$password','$email','$phonenumber','$address',0,null)";

    mysqli_query($dbc, $query);


    // Show success message and redirect to login page
    $_SESSION['openModal'] = 1;
    echo '<script>
            alert("Registration successful! Redirecting to login page...");
            window.location.href = "index.php";
          </script>';
    exit;
}



?>