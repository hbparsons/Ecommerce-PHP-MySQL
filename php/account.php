<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CandyCraze- Account</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Slackey&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php
    include('includes/header.php');
    include('includes/connection.php');


    $userID = $_SESSION['userID'];

    // redirects user if not logged in
    if (!isset($userID)) {
        header('Location: index.php');
        exit;
    }

    // Query the customer table
    $query = "SELECT Username, Firstname, Lastname, Email, PhoneNumber, Address FROM customer WHERE UserID = ?";
    if ($stmt = $dbc->prepare($query)) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($username, $firstname, $lastname, $email, $phone_number, $address);
        $stmt->fetch();
        $stmt->close();
    }
    ?>

    <main>

        <!-- Account Info-->
        <h3 class="mx-5 mt-5 mb-4" id="account-info-title">Account Info</h3>

        <!-- Pencil Icon Button with Tooltip -->

        <div class="d-inline-flex">
            <table class="table table-dark mx-5 table-bordered border-dark rounded" id="account-menu">
                <tbody>
                    <tr class="d-flex justify-content-center">
                        <td class="account-td"><a href="order-history.php" class="account-link">My Orders</a></td>
                        <td class="account-td"><a href="privacy-agreement.php" class="account-link">Privacy Agreement</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
       

        <div class="d-flex justify-content-center">
            <div class="card" style="min-width: 80vw;">
                <div class="card-body">
                     <!-- Pencil Icon Button with Tooltip -->
        <div class="d-flex justify-content-end">
            <div class="d-flex justify-content-end m-1 mt-0">
                <button type="button" id="edit-info-button" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Info">
                    <i class="bi bi-pencil-square fs-4"></i>
                </button>
            </div>
        </div>
                    <table class="table mb-4 table-warning table-striped-columns table-bordered border-white">
                        <tbody>
                            <tr>
                                <th class="account-th" scope="row">Username</th>
                                <td><?php echo htmlspecialchars($username); ?></td>
                            </tr>
                            <tr>
                                <th class="account-th" scope="row">Firstname</th>
                                <td><?php echo htmlspecialchars($firstname); ?></td>
                            </tr>
                            <tr>
                                <th class="account-th" scope="row">Lastname</th>
                                <td colspan="2"><?php echo htmlspecialchars($lastname); ?></td>
                            </tr>
                            <tr>
                                <th class="account-th" scope="row">Email</th>
                                <td colspan="2"><?php echo htmlspecialchars($email); ?></td>
                            </tr>
                            <tr>
                                <th class="account-th" scope="row">Phone Number</th>
                                <td colspan="2"><?php echo htmlspecialchars($phone_number); ?></td>
                            </tr>
                            <tr>
                                <th class="account-th" scope="row">Address</th>
                                <td colspan="2"><?php echo htmlspecialchars($address); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </main>


    <?php
    include('includes/footer.php');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.getElementById('edit-info-button').addEventListener('click', function() {
            window.location.href = "edit-account.php";
        });
    </script>
</body>

</html>