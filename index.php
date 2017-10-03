<?php
require_once("lib/functions.php");
require_once("model/db.php");
require_once('model/main.php');
require_once('vendor/autoload.php');

if (alien()) {
    echo '<a href="controller/register.php">Войдите на сайт</a>';
    exit;
}

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache'=>'compilation_cache',
    'auto_reload'=>true
));

$pdo = getDBConnect ();

$array = [];
$description = [];
$order = ' ORDER BY date_added;';
$sort_array = ['date_added', 'is_done', 'description'];
$add_edit = 'add';

if (isPost()) {

    if (isAddEdit() && isset($_POST['var']) & !empty($_POST['var'])) {

        if (isset($_POST['add_edit']) && $_POST['add_edit'] == 'edit' && isset($_GET['id'])) {
            updateTask($pdo);
        }
        else {
            insertTask($pdo);
        }

    }

    if (isSort() && isset($_POST['my_sort']) && !empty($_POST['my_sort']) && in_array($_POST['my_sort'], $sort_array)) {
        $order = ' ORDER BY ' . ($_POST['my_sort']) . ';';
    }

    if (isAssign() && isset($_POST['assigned_user_id']) && !empty($_POST['assigned_user_id'])) {
        assignedTask($pdo);
    }

}

if (isset($_GET['action']) && $_GET['action']=='edit' && !isset($_POST['my_sort']) && !isset($_POST['var'])) {
    $add_edit = 'edit';
}

if (isGet()) {

    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'edit'  & $add_edit == 'edit') {
            $description = getDescription($pdo);
        }
        elseif ($_GET['action'] == 'delete') {
            deleteTask($pdo);
        }
        elseif ($_GET['action'] == 'done') {
            doneTask($pdo);
        }

    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' & !empty($_POST) & isset($_POST['addedit'])) {
    header("Location: index.php");
    exit;
}

$results = getTasks($pdo, $order);

$assigns = getAssigns($pdo);

$assigned_results = getAssignsTasks($pdo, $order);

echo $twig->render('main.twig', [
    'description' => !empty($description) ? $description[0] : '',
    'add_edit'=>$add_edit,
    'results'=>$results,
    'assigns'=>$assigns,
    'assigned_results'=>$assigned_results,
    'session_id'=>$_SESSION['id'],
    'login'=>$_SESSION['login']
]);

