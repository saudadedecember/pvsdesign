<?php
class Core {
    const CREATED = 2016;
    const SITE_NAME = 'PVSDESIGN';
    const DSN = 'mysql:dbname=pvsdesign;host=localhost';
    const USER = 'root';
    const PASS = '';

    //const DSN = 'mysql:dbname=u503627801_pvs;host=localhost';
    //const USER = 'u503627801_root';
    //const PASS = 'jungle';
    const DISPLAY_NEWS = 5; // количество выводимых новостей
    const DISPLAY_PROJECTS = 5;
    const DISPLAY_COMMENTS = 5;
}

define("START_TIME", microtime(true));

try {
    $dbh = new PDO(Core::DSN, Core::USER, Core::PASS);
} catch (PDOException $e) {
    echo 'Unable to connect. '.$e->getMessage();
    exit;
}

function myHash($str) {
    return crypt(md5("pvs&design?{$str}1948-01-23?"), sha1("pavlov?vasily?{$str}sergeyevich?"));
}

