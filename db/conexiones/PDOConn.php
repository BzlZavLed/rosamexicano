<?php

try {
    $conn = new PDO("mysql:host=localhost;dbname=rosamexicano", 'rmWeb', 'rmpass*20200285', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
} catch (PDOException $pe) {
   echo die("Could not connect to the database:" . $pe->getMessage());
}

?>