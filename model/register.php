<?php

require_once("../lib/functions.php");
require_once ('db.php');

function checkUserOne ($pdo)
{
    $sql = "SELECT login FROM user WHERE login=:login";
    $statement = $pdo->prepare($sql);
    $statement->execute(['login' => $_POST['login']]);

    if ($statement->fetchColumn()) {
        return false;
    }

    return true;
}

function checkUser ($pdo)
{
    $sql = "SELECT id, login FROM user WHERE login=:login AND password=:password";
    $statement = $pdo->prepare($sql);
    $statement->execute(['login' => $_POST['login'], 'password' => md5($_POST['password'])]);

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return [];
    }

    return ['id'=>$row['id'], 'login'=>$row['login']];
}

function insertUser ($pdo)
{
    $sql = "INSERT INTO user ( login, password ) VALUES (:login, :password);";
    $statement = $pdo->prepare($sql);
    $statement->execute(['login' => $_POST['login'], 'password' => md5($_POST['password'])]);
}
