<?php
function getDBConnect ()
{
    $database = 'global';

    try {
        $user = "root";
        $pdo = new PDO("mysql:host=localhost;dbname=$database;charset=utf8", $user);
    }
    catch (PDOException $e) {
        $user = "estukalov";
        $password = "neto1205";
        $pdo = new PDO("mysql:host=localhost;dbname=$database;charset=utf8", $user, $password);
    }

    return $pdo;
}