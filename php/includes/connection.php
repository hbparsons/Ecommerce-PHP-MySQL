<?php


$server = 'localhost';
$user = 'candy_user';
$pswd = 'candy';
$db='candycraze_db';

$dbc = mysqli_connect($server,$user,$pswd,$db);

if (!$dbc) {
    die ('MySQL Error:' . mysqli_connect_error());
}



?>