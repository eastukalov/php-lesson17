<?php
session_start();
require_once("../lib/functions.php");
require_once('../vendor/autoload.php');
require_once('../model/register.php');

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader, array(
    'cache'=>'../compilation_cache',
    'auto_reload'=>true
));

$error = "";

if (isPost()) {
    $pdo = getDBConnect();

    if (isset($_POST['register']))
    {
        if (empty($_POST['login']) || empty($_POST['password'])) {
            $error = "Ошибка регистрации. Введите все необходимые данные.";
        }
        else {

            if (!checkUserOne($pdo)) {
                $error = 'Такой пользователь уже существует в базе данных.';
            }
        }

        if (empty($error)) {
            insertUser ($pdo);
        }

    }

    if (isset($_POST['sign_in'])) {

        if (empty($_POST['login']) || empty($_POST['password'])) {
            $error = "Ошибка входа. Введите все необходимые данные.";
        }
        else {
            $user = checkUser ($pdo);
            if (empty($user)) {
                $error = "Такой пользователь не существует, либо неверный пароль.";
            }
            else {
                $_SESSION['id'] = $user['id'];
                $_SESSION['login'] = $user['login'];
                header('Location: ../index.php');
            }

        }
    }

}

echo $twig->render('register.twig', ['error' => $error]);

