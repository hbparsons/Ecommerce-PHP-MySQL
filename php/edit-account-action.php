<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<?php
session_start();

include('includes/connection.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //form validates input using html validation. 

    //cleans user input data & sets variables
    $password = trim(strip_tags($_POST['password']));
    $email = trim(strip_tags($_POST['email']));
    $phonenumber = trim(strip_tags($_POST['phonenumber']));
    $address = trim(strip_tags($_POST['address']));

    //hashes the password. 
    $password = password_hash($password, PASSWORD_BCRYPT);

    // Get the user ID from the session
    $userID = $_SESSION['userID'];


    // Check if the email has changed and is already in use
    $query = "SELECT Email FROM customer WHERE UserID = ?";
    $stmt = $dbc->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($currentEmail);
    $stmt->fetch();
    $stmt->close();

    if ($currentEmail !== $email) {
        $query = "SELECT * FROM customer WHERE Email = ?";
        $stmt = $dbc->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<script>alert("Email already in use.");</script>';
            echo '<script>window.location.href = "edit-account.php";</script>';
            exit;
        }
        $stmt->close();
    }

    // Update the customer's information in the database
    $query = "UPDATE customer SET Password = ?, Email = ?, PhoneNumber = ?, Address = ? WHERE UserID = ?";
    if ($stmt = $dbc->prepare($query)) {
        $stmt->bind_param("ssssi", $password, $email, $phonenumber, $address, $userID);
        $stmt->execute();
        $stmt->close();
    }


    // Show success message and redirect to login page
    $_SESSION['openModal'] = 1;
    echo '<script>
            alert("Account update successful! Redirecting to Acount Info page...");
            window.location.href = "account.php";
          </script>';
    exit;
}



?>