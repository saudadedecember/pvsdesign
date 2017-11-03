<?php
function showCopyrightInfo()
{
    if(Core::CREATED == date("Y")) {
        return 'Copyright &copy '.Core::CREATED.' '.Core::SITE_NAME;
    } elseif (Core::CREATED > date("Y")) {
        return 'Wrong Year - '.Core::CREATED.'. Check It.';
    } else {
        return 'Copyright &copy '.Core::CREATED.'-'.date("Y").' '.Core::SITE_NAME;
    }
}

// приводим строку к нижнему регистру, если файловая система чувствительна к регистру
spl_autoload_register(function($class) {
    include strtolower('./lib/class_'.$class.'.php');
});

function display($n)
{
    echo '<pre>'.print_r($n).'</pre>';
}

// возращает общее количество записей в таблице
function getNumRows($dbh, $sql) {
    $sth = $dbh->query($sql);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $row = $sth->fetch();
    return $row[0];
}

function heading_active($module, $text) {
    if($_GET['module'] == $module)
        return $text;
}

function pagination($pages, $module)
{
    $i = 0;

    if(!isset($_GET['page'])) {
        $_GET['page'] = 1;
    }

    if($pages > 7) {
        if($_GET['page'] > 4 && $_GET['page'] < $pages-3) {
            echo '<a class="ref d1 " href = "/'.$module.'?page=1"> 1 </a>  ...  ';
            $i = $_GET['page'] - 3;
            while ($i < $_GET['page']+2) {
                ++$i;
                echo '<a class="ref d1 ';
                if ($_GET['page'] == $i)
                    echo 'd1-active';
                echo '" href = "/'.$module.'?page=' . $i . '">' . $i . '</a>  ';
            }
            echo '  ...  <a class="ref d1" href = "/'.$module.'?page=' . $pages . '">' . $pages . '</a>  ';
        } elseif($_GET['page'] < 5) {
            $i = 0;
            while ($i < 5) {
                ++$i;
                echo '<a class="ref d1 ';
                if ($_GET['page'] == $i)
                    echo 'd1-active';
                echo '" href = "/'.$module.'?page=' . $i . '">' . $i . '</a>  ';
            }
            echo '  ...  <a class="ref d1" href = "/'.$module.'?page=' . $pages . '">' . $pages . '</a>  ';
        } elseif($_GET['page'] > $pages-4) {
            echo '<a class="ref d1 " href = "/'.$module.'?page=1"> 1 </a>  ...  ';
            $i = $pages-5;
            while ($i < $pages) {
                ++$i;
                echo '<a class="ref d1 ';
                if ($_GET['page'] == $i)
                    echo 'd1-active';
                echo '" href = "/'.$module.'?page=' . $i . '">' . $i . '</a>  ';
            }
        }
    } elseif ($pages > 1 && $pages < 8) {
        while($i < $pages) {
            ++$i;
            echo '<a class="ref d1 ';
            if ($_GET['page'] == $i)
                echo 'd1-active';
            echo '" href = "/'.$module.'?page=' . $i . '">' . $i . '</a>  ';
        }
    }
}

// Счетчик посещения сайта
function counter($dbh) {
    $visitor_ip = $_SERVER['REMOTE_ADDR'];
    $date = date("Y-m-d");
    $rows = array(); $rows[0] = 0;

    // Были ли сегодня посещения
    $sql = 'SELECT visit_id FROM visits WHERE date = :date';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':date', $date, PDO::PARAM_STR);
    $sth->execute();

    $rows = $sth->fetch();

    // Если посещений не было
    if($rows[0] == 0) {
        // Очищаем таблицу ips
        $sql = 'DELETE FROM ips';
        $sth = $dbh->prepare($sql);
        $sth->execute();

        // Заносим в базу IP-адрес текущего посетителя
        $sql = 'INSERT INTO ips(ip_address) VALUES (:ip_address)';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':ip_address', $visitor_ip, PDO::PARAM_STR);
        $sth->execute();

        // Заносим в базу дату посещения и устанавливаем кол-во просмотров и уник. посещений в значение
        $sql = 'INSERT INTO visits(date, hosts, views) VALUES (:date, 1, 1)';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':date', $date, PDO::PARAM_STR);
        $sth->execute();
    } else { // Если посещений сегодня были уже
        // Проверяем, есть ли уже в базе IP-адрес, с которого происходит обращение
        $sql = 'SELECT ip_id FROM ips WHERE ip_address = :visitor_ip';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':visitor_ip', $visitor_ip, PDO::PARAM_STR);
        $sth->execute();
        $rows[0] = 0;
        $rows = $sth->fetch();

        // Если такой IP-адрес уже сегодня был (т.е. это не уникальный посетитель)
        if($rows[0]) {
            // Добавляем для текущей даты +1 просмотр (хит)
            $sql = 'UPDATE visits SET views = views+1 WHERE date = :date';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);
            $sth->execute();

        } else {
            // Если сегодня такого IP-адреса еще не было (т.е. это уникальный посетитель)
            $sql = 'INSERT INTO ips(ip_address) VALUES (:ip_address)';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':ip_address', $visitor_ip, PDO::PARAM_STR);
            $sth->execute();

            // Добавляем в базу +1 уникального посетителя (хост) и +1 просмотр (хит)
            $sql = 'UPDATE visits SET hosts = hosts+1, views = views+1 WHERE date = :date';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);
            $sth->execute();
        }
    }
}

function displayStat($dbh) {
    $date = date("Y-m-d");
    $sql = 'SELECT hosts, views FROM visits WHERE date = :date';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':date', $date, PDO::PARAM_STR);
    $sth->execute();

    $rows = $sth->fetch();

    echo 'Уникальных посетителей: '.$rows['hosts'].'<br>';
    echo 'Просмотров: '.$rows['views'];
}

function showWorkTime() {
    printf("Время работы скрипта: %.5f c", microtime(true)-START_TIME);
}

function printTree($level=1) {
    $d = @opendir(".");
    if(!$d) return;

    while(($e=readdir($d)) !== false) {
        if ($e == '.' || $e == '..') continue;
        if (!@is_dir($e)) continue;
        for ($i = 0; $i < $level; $i++) echo " ";
        echo "$e\n";
        if (!chdir($e)) continue;
        printTree($level + 1);
        chdir("..");
        flush();
    }
    closedir($d);
}

// обработчик ошибок
function myErrorHandler($errno, $msg, $file, $line) {
    // если используется оператор @, ничего не делать
    if(error_reporting() == 0) return;
    ob_start();
    echo 'Произошла ошибка с кодом: '.$errno;
    echo '<br>Файл: '.$file;
    echo '<br>Текст ошибки: '.$msg.' строка: '.$line;
}
set_error_handler("myErrorHandler", E_ALL); // использовать для обработки ошибок пользовательскую функцию

