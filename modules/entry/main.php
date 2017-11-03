<?php
$errors = array();

// выход из личного кабинета и уничтожение сессии
if(isset($_SESSION['entry'])) {
    $_SESSION = array();
    unset($_COOKIE[session_name()]);
    session_destroy();
    header ('Location: /entry');
}

if(isset($_POST['login'], $_POST['pass'])) {
    $tmp1 = myHash($_POST['pass']);
    $res = 0;
    $sql = 'SELECT * FROM users WHERE login = :login AND pass = :pass AND (status = 0 OR status = 2) OR email = :email AND pass = :pass AND (status = 0 OR status = 2) LIMIT 1';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':login', $_POST['login'], PDO::PARAM_INT);
    $sth->bindParam(':email', $_POST['login'], PDO::PARAM_INT);
    $sth->bindParam(':pass', $tmp1, PDO::PARAM_INT);
    $sth->execute();
    $res = $sth->fetch();

    if($res) {
        $_SESSION['entry'] = $res['email'];
        $_SESSION['login'] = $res['login'];
        if($res['status'] == 0) {
            $_SESSION['admin'] = 'true';
        }
        header ('Location: /homepage');
        exit;
    } else {
        $errors['login'] = 'Указанное сочетание не найдено';
    }
}

