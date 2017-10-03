<?php
session_start();

function updateTask($pdo)
{
    $sql = "UPDATE task SET description = :description WHERE id=:id;";
    $array = ['description'=>htmlspecialchars($_POST['var']), 'id'=>$_GET['id']];
    $statement = $pdo->prepare($sql);
    $statement->execute($array);
}

function insertTask($pdo)
{
    $sql = "INSERT INTO task (user_id, assigned_user_id, description, is_done, date_added) VALUES (:user_id, :assigned_user_id, :description, :is_done, :date_added)";
    $array = ['user_id'=>$_SESSION['id'],'assigned_user_id'=>$_SESSION['id'],'description'=>htmlspecialchars($_POST['var']), 'is_done'=>0, 'date_added'=>date('Y.m.d Hi:s:',time())];
    $statement = $pdo->prepare($sql);
    $statement->execute($array);

}

function assignedTask($pdo)
{
    $assigned_id = explode('_', $_POST['assigned_user_id']);
    $sql = $sql = "UPDATE task SET assigned_user_id = :assigned_user_id WHERE id=:id;";
    $statement = $pdo->prepare($sql);
    $statement->execute(['assigned_user_id'=>$assigned_id[0], 'id'=>$assigned_id[1]]);
}

function getDescription($pdo)
{
    $sql = "SELECT description FROM task WHERE id=:id;";
    $array = ['id'=>$_GET['id']];
    $statement = $pdo->prepare($sql);
    $statement->execute($array);
    $description = [];
    $description = $statement -> fetchall(PDO::FETCH_COLUMN, 0);
    return $description;
}

function deleteTask($pdo)
{
    $sql = "DELETE FROM task WHERE id=:id;";
    $array = ['id'=>$_GET['id']];
    $statement = $pdo->prepare($sql);
    $statement->execute($array);
}

function doneTask($pdo)
{
    $sql = "UPDATE task SET is_done = :is_done WHERE id=:id;";
    $array = ['is_done'=>1, 'id'=>$_GET['id']];
    $statement = $pdo->prepare($sql);
    $statement->execute($array);
}

function getTasks($pdo, $order)
{
    $sql = "SELECT t.id, t.user_id, t.assigned_user_id, t.description, t.is_done, t.date_added, u1.login AS author, u2.login AS assigned 
        FROM task t INNER JOIN user u1 ON t.user_id = u1.id INNER JOIN user u2 ON t.assigned_user_id = u2.id WHERE t.user_id=:id" . $order;

    $statement = $pdo->prepare($sql);
    $statement->execute(['id'=>$_SESSION['id']]);
    $results = [];

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }

    return $results;
}

function getAssigns($pdo)
{
    $sql2 = "SELECT id, login FROM user WHERE id<>:id";

    $statement = $pdo->prepare($sql2);
    $statement->execute(['id'=>$_SESSION['id']]);
    $assigns = [];

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $assigns[] = $row;
    }

    return $assigns;
}

function getAssignsTasks($pdo, $order)
{
    $sql = "SELECT t.id, t.user_id, t.assigned_user_id, t.description, t.is_done, t.date_added, u1.login AS author, u2.login AS assigned 
        FROM task t INNER JOIN user u1 ON t.user_id = u1.id INNER JOIN user u2 ON t.assigned_user_id = u2.id WHERE t.assigned_user_id=:id And t.user_id<>:id" . $order;

    $statement = $pdo->prepare($sql);
    $statement->execute(['id'=>$_SESSION['id']]);
    $assigned_results = [];

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $assigned_results[] = $row;
    }

    return $assigned_results;
}