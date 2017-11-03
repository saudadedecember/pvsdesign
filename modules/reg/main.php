<?php
$errors = array();

if(isset($_SESSION['entry'])) {
    header ('Location: /account');
}

if(isset($_POST['login'], $_POST['email'], $_POST['pass'], $_POST['passrep'])) {

    if(empty($_POST['login'])) {
        $errors['login'] = 'Не указано имя учетной записи.<br>';
    }
    elseif(iconv_strlen($_POST['login']) < 2) {
        $errors['login'] = 'Имя учетной записи не может быть меньше 2 символов.<br>';
    }
    elseif(iconv_strlen($_POST['login']) > 30) {
        $errors['login'] = 'Имя учетной записи не должно быть больше 30 символов.<br>';
    }

    if(empty($_POST['email'])) {
        $errors['email'] = 'Не указан адрес электронной почты.<br>';
    }
    elseif(iconv_strlen($_POST['email']) > 30) {
        $errors['email'] = 'Адрес электронной почты не должен быть больше 30 символов.<br>';
    }
    elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Адрес электронной почты указан некорректно.<br>';
    }

    if(empty($_POST['pass'])) {
        $errors['pass'] = 'Не указан пароль.<br>';
    }
    elseif(iconv_strlen($_POST['pass']) < 6) {
        $errors['pass'] = 'Минимальная длина пароля должна быть не менее 6 символов.<br>';
    }
    elseif(iconv_strlen($_POST['pass']) > 30) {
        $errors['pass'] = 'Максимальная длина пароля должна быть не более 30 символов.<br>';
    }
    elseif(empty($_POST['passrep'])) {
        $errors['passrep'] = 'Необходимо подтвердить пароль.<br>';
    }
    elseif(strcmp($_POST['passrep'], $_POST['pass'])) {
        $errors['passrep'] = 'Пароли не совпадают.<br>';
    }

    if(!count($errors)) {
        $res = 0;

        $sql = 'SELECT id FROM users WHERE login = :login LIMIT 1';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
        $sth->execute();
        $res = $sth->fetch();

        if($res) {
            $errors['login'] = 'Данный логин уже занят<br>';
        }

        $res = 0;

        $sql = 'SELECT id FROM users WHERE email = :email LIMIT 1';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->execute();
        $res = $sth->fetch();

        if($res) {
            $errors['email'] = 'Данный почтовый ящик уже занят<br>';
        }
    }

    if(!count($errors)) {
        $sql = 'INSERT INTO users(login, email, pass, hash, status) VALUES(:login, :email, :pass, :hash, 1)';

        $tmp1 = myHash($_POST['pass']);
        $tmp2 = myHash($_POST['login'] . '&' . $_POST['email']);

        $sth = $dbh->prepare($sql);

        $sth->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
        $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $sth->bindParam(':pass', $tmp1, PDO::PARAM_STR);
        $sth->bindParam(':hash', $tmp2, PDO::PARAM_STR);

        $sth->execute();

        Mail::Send($_POST['email']);
        $_SESSION['reginfo'] = 'success';
        header ('Location: /reg');
        exit;
    }
}

