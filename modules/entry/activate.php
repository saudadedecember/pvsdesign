<?php
if(isset ($_GET['id'])) {
    $res = 0;

    $sql = 'SELECT id FROM users WHERE hash = :hash AND status = 2 LIMIT 1';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':hash', $_GET['id'], PDO::PARAM_STR);
    $sth->execute();
    $res = $sth->fetch();

    if($res) {
        header ('Location: /homepage');
    }
    else {
        $sql = 'SELECT id FROM users WHERE hash = :hash LIMIT 1';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':hash', $_GET['id'], PDO::PARAM_STR);
        $sth->execute();
        $res = $sth->fetch();

        if($res) {
            $sql = 'UPDATE users SET status = 2 WHERE hash = :hash LIMIT 1';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':hash', $_GET['id'], PDO::PARAM_STR);
            $sth->execute();
            $res = $sth->fetch();

            $_SESSION['reginfo'] = 'Ваша учётная запись активирована';
        }
        else {
            $_SESSION['reginfo'] = 'Указана некорректная ссылка';
        }
    }
}
else {
    header ('Location: ./index.php?module=entry&page=main');
}
