<?php
session_start();

include('includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['username'])));
        $password = trim($_POST['password']); // Do not hash here


        // Create query using prepared statement
        $query = "SELECT * FROM customer WHERE Username=?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check result
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            // Verify the password
            if (password_verify($password, $row['Password'])) {
                // Set session variables
                $_SESSION['username'] = $username;
                $_SESSION['userID'] = $row['UserID'];
                $_SESSION['isAdmin'] = $row['isAdmin'];
                $_SESSION['userEmail'] = $row['Email'];
                $_SESSION['privacyAccepted'] = $row['privacyAccepted'] == null ? -1 : $row['privacyAccepted'];

                // Redirect to privacy-agreement.php if privacyAgreement is 0 or null (-1)
                if ($row['privacyAccepted'] === null || $row['privacyAccepted'] === 0) {
                    header('Location: privacy-agreement.php');
                    exit;
                }

                // Redirect to index.php
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['openModal'] = 1;
                echo '<script>alert("Login Unsuccessful: Invalid password.");</script>';
                echo '<script>window.location.href = "index.php"</script>';
                exit;
            }
        } else {
            $_SESSION['openModal'] = 1;
            echo '<script>alert("Login Unsuccessful: User not found.");</script>';
            echo '<script>window.location.href = "index.php"</script>';
            exit;
        }
        mysqli_stmt_close($stmt);
    }
}
