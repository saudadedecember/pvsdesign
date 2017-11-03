<?php
// определяем значение первого аргумента LIMIT, с которого будет начинаться вывод записей
$firstLimit = 0;
if($_GET['page'] != 0) {
    $firstLimit = $_GET['page'] * Core::DISPLAY_PROJECTS - Core::DISPLAY_PROJECTS;
}

$sql = 'SELECT code, name, address, customer FROM projects ORDER BY id DESC LIMIT '.$firstLimit.', '.Core::DISPLAY_PROJECTS;

$sql1 = 'SELECT COUNT(*) FROM projects';

$sth = $dbh->query($sql);
$sth->setFetchMode(PDO::FETCH_ASSOC);

$rows = getNumRows($dbh, $sql1);
$pages = ceil($rows / Core::DISPLAY_PROJECTS);
$splitBar = 1;
