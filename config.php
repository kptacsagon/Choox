<?php
// Database connection to a_winventory_system
$conn = mysqli_connect('localhost', 'root', '', 'a_winventory_system');

if (!$conn) {
    die('Connection Failed: ' . mysqli_connect_error());
}
?>
