<?php
session_start();
//error_reporting(E_ALL);
//ini_set("error_log", "log.txt");
//ini_set("log_errors", true);

if(!isset($_SESSION['entry'])) {
    $href1 = 'Войти';
    $href2 = 'Регистрация';
}
else {
    $href1 = 'Выйти';
    if(isset($_SESSION['admin'])) {
        $href2 = 'Администрирование';
    } else {
        $href2 = 'Личный кабинет';
    }
}

include_once './config.php';
include_once './control.php';
include_once './lib.php';
include_once './view/default/index.html';

counter($dbh);
