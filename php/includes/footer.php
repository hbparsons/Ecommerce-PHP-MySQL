<footer class="fixed-bottom">
    
    <?php
    if (!isset($_SESSION['username']) || $_SESSION['username'] == "") {	
    
        echo'<p class="my-1 mx-2">You are not logged in.</p>';
    } else {
        $username = $_SESSION['username'];
        echo'<p class="my-1 mx-2">You are logged in as: <span class ="username-color">'.$username.'</span></p>';
}
    ?>
    
</footer>
