<?php
$error = 0;
if(isset($_POST['comment'], $_SESSION['login'])) {

    if(iconv_strlen(trim(utf8_decode($_POST['comment']))) < 10) {
        $error = 1;
    } elseif (substr_count((trim($_POST['comment'])), ' ') < 1) {
        $error = 1;
    }

    if(!$error) {
        $sql = 'INSERT INTO comments(date, user, comment) VALUES(:date, :user, :comment)';

        $sth = $dbh->prepare($sql);
        $date = date("Y-m-d H:i:s");

        $sth->bindParam(':date', $date, PDO::PARAM_STR);
        $sth->bindParam(':user', $_SESSION['login'], PDO::PARAM_STR);
        $sth->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);

        $sth->execute();
        header('Location: /comments');
        exit;
    }
}

// определяем значение первого аргумента LIMIT, с которого будет начинаться вывод записей
$firstLimit = 0;
if($_GET['page'] != 0) {
    $firstLimit = $_GET['page'] * Core::DISPLAY_COMMENTS - Core::DISPLAY_COMMENTS;
}

$sql = 'SELECT * FROM comments ORDER BY id DESC LIMIT '.$firstLimit.', '.Core::DISPLAY_COMMENTS;

$sql1 = 'SELECT COUNT(*) FROM comments';

$sth = $dbh->query($sql);
$sth->setFetchMode(PDO::FETCH_ASSOC);

$rows = getNumRows($dbh, $sql1);
$pages = ceil($rows / Core::DISPLAY_COMMENTS);
$splitBar = 1;