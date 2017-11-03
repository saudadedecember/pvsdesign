<?php
// определяем значение первого аргумента LIMIT, с которого будет начинаться вывод записей
$firstLimit = 0;
if($_GET['page'] != 0) {
    $firstLimit = $_GET['page'] * Core::DISPLAY_NEWS - Core::DISPLAY_NEWS;
}

$sql = 'SELECT id, date, text FROM news ORDER BY id DESC LIMIT '.$firstLimit.', '.Core::DISPLAY_NEWS;

$sql1 = 'SELECT COUNT(*) FROM news';

$sth = $dbh->query($sql);
$sth->setFetchMode(PDO::FETCH_ASSOC);

$rows = getNumRows($dbh, $sql1);
$pages = ceil($rows / Core::DISPLAY_NEWS);
$splitBar = 1;
