<?php

//phpinfo();
$hostnmae = 'localhost';
$username = '';
$pass = '';
$db = 'billing';

$link = mysqli_connect('localhost', 'root', '','billing');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

?>