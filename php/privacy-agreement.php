<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CandyCraze - Privacy Agreement</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Slackey&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    ?>


    <div id="privacy-agreement-container" class="mb-3 p-5 container">
        <div id="privacy-message" class="text-center mb-3">The below privacy agreement must be agreed to before you are able to purchase from our website.</div>
        <div id="text" class="my-3">
            <div class="card-header text-light bg-dark text-center">Agreement for the Appreciation of STEM Student Struggles</div>

            <div class="article mt-2">
                <h5>Preamble</h5>
                <p>I acknowledge the heroic effort required to be a STEM student. This agreement aims to highlight the unique challenges faced by these brave individuals.</p>
            </div>

            <div class="article">
                <h5>Article I: The Struggle is Real</h5>
                <h6>1. Homework Hurdles</h6>
                <p>STEM homework feels like an epic quest. Googling answers is a legitimate survival tactic.</p>
                <h6>2. Lab Labyrinths</h6>
                <p>Lab experiments often turn into mysteries. Spending hours only to realize the equipment wasn't plugged in is a rite of passage.</p>
                <h6>3. Exam Exorcisms</h6>
                <p>Preparing for STEM exams is like training for an Ironman triathlon. Caffeine and all-nighters are essential.</p>
            </div>

            <div class="article">
                <h5>Article II: Emotional Equations</h5>
                <h6>1. Equation Ennui</h6>
                <p>Deriving equations can induce boredom. Daydreaming about simpler math problems is normal.</p>
                <h6>2. Graph Grievances</h6>
                <p>Plotting graphs can be frustrating. Mutters of "Why won't you work?!" are part of the process. Or worse yet, "why ARE you working?"</p>
            </div>

            <div class="article">
                <h5>Article III: Social Sacrifices</h5>
                <h6>1. Friends and Functions</h6>
                <p>Social events often lose out to studying. "Sorry, I have to fix my broken code," is an acceptable excuse.</p>
                <h6>2. Group Project Gauntlet</h6>
                <p>Coordinating group projects is challenging. Patience and humour are vital.</p>
            </div>

            <div class="article">
                <h5>Article IV: Celebrating Small Victories</h5>
                <h6>1. Micromilestone Moments</h6>
                <p>Every small victory, from debugging code to grasping quantum mechanics (sort of), deserves celebration.</p>
                <h6>2. Surviving and Thriving</h6>
                <p>Surviving each semester is an achievement. Support each other with empathy.</p>
            </div>

            <div class="article">
                <h5>Conclusion</h5>
                <p>By signing this agreement, I commit to appreciating the humour in our struggles and supporting each other through this challenging yet rewarding journey.</p>
            </div>

            <!--- Form starts here -->
            <?php
            
            $username = $_SESSION['username'];
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $privacyAccepted = mysqli_real_escape_string($dbc, trim(strip_tags(strtoupper($_POST['privacyAccepted']))));

                $query = "UPDATE customer SET privacyAccepted = '$privacyAccepted' WHERE Username = '$username';";

                if (mysqli_query($dbc, $query)) {
                    $_SESSION['privacyAccepted'] = $privacyAccepted;

            ?>
                    <script>
                        alert("Privacy agreement change successful.");
                        // Relocate user to index page upon successful change of privacy agreement
                        window.location.href = "index.php";
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("Unable to update agreement");
                    </script>
            <?php
                }
            }
            ?>
            <script>
                // variable to check if a radio button is selected
                $(document).ready(function() {
                    $('#privacy-agreement-form').on('submit', (event) => {
                        let isChecked = $('input[name="privacyAccepted"]:checked').length > 0;
                        if (!isChecked) {
                            event.preventDefault();
                            $('#error-message').show().text('Please select an option.');
                        }
                    });
                });

                $(document).ready(function() {
                    $("#privacy-disagree").on("change", function() {
                        confirm("WARNING: By disagreeing with our policy, you will be unable to purchase products from our shop. Are you sure you want to continue with your choice?");
                    });
                });
            </script>
            <div class="sign">
                <form action="privacy-agreement.php" id="privacy-agreement-form" method="POST">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="privacyAccepted" id="privacy-agree" value="1"> <!-- Agrees -->
                        <label class="form-check-label" for="privacy-agree">
                            I agree
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="privacyAccepted" id="privacy-disagree" value="0"> <!-- Disagrees -->
                        <label class="form-check-label" for="privacy-disagree">
                            I disagree
                        </label>
                    </div>
                    <div id="error-message" class="error"></div>
                    <button type="submit" id="agreement-btn" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
        <?php
        include('includes/footer.php');
        ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>