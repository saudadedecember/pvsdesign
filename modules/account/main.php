<?php
// header ('Location: /admin');
if(isset($_SESSION['login'])) {
// определяем значение первого аргумента LIMIT, с которого будет начинаться вывод записей
    $firstLimit = 0;
    if($_GET['page'] != 0) {
        $firstLimit = $_GET['page'] * Core::DISPLAY_COMMENTS - Core::DISPLAY_COMMENTS;
    }

    $sql = 'SELECT * FROM comments WHERE user = :login ORDER BY id DESC LIMIT '.$firstLimit.', '.Core::DISPLAY_COMMENTS;;
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':login', $_SESSION['login'], PDO::PARAM_STR);
    $sth->execute();

    $sql1 = 'SELECT COUNT(*) FROM comments WHERE user = :login';
    $sth1 = $dbh->prepare($sql1);
    $sth1->bindParam(':login', $_SESSION['login'], PDO::PARAM_STR);
    $sth1->execute();

    $row1 = $sth1->fetch();
    $rows = $row1[0];

    //$rows = getNumRows($dbh, $sql1);
    $pages = ceil($rows / Core::DISPLAY_COMMENTS);
    $splitBar = 1;
}