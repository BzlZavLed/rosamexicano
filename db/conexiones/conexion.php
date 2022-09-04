<?php
include '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../../');
$dotenv->load();

 $conn =  mysqli_connect($_ENV['DB_LOCAL_HOST'], $_ENV['DB_LOCAL_USER'],$_ENV['DB_LOCAL_PWD'],$_ENV['DB_LOCAL_DB']);
if (!$conn) {
     die('No pudo conectarse: ' . mysqli_error($err));
} 
/* $conn =  mysqli_connect('localhost', 'rmWeb', 'rmpass*20200285','rosamexicano');
if (!$conn) {
     die('No pudo conectarse: ' . mysqli_error());
} */
?>