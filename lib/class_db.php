<?php
class DB {
    static private $sqlCountRows = 'SELECT COUNT(*) FROM ';
    // Подсчитываем количество строк в таблице $table
    static public function countRows($dbh, $table) {
        $sth = $dbh->prepare((self::$sqlCountRows).$table);
        $sth->execute();
        return $sth->fetchColumn(); // Для возвращения одного столбца
    }


    static public function deleteRow($dbh, $table, $id) {
        $sth = $dbh->prepare('DELETE FROM '.$table.' WHERE id = '.$id);
        $sth->execute();
    }

    static public function updateRow($dbh, $table, $text, $id) {
        $sth = $dbh->prepare('UPDATE '.$table.' SET text = :text WHERE id = '.$id);
        $sth->bindParam(':text', $text, PDO::PARAM_STR);
        $sth->execute();
    }

    static public function selectRow($dbh, $table, $id) {
        $sth = $dbh->prepare('SELECT * FROM '.$table.' WHERE id = '.$id);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    static private $sqlSelectNews = 'SELECT id, date, text FROM news ORDER BY id DESC LIMIT ';
    static public function selectNews($dbh, $countElements) {
        // Определяем значение первого аргумента LIMIT, с которого будет начинаться вывод следующих $countElements записей
        if($_GET['page'] != 0) {
            $limit = ($_GET['page'] * $countElements) - $countElements;
        } else {
            $limit = 1;
        }
        $sth = $dbh->prepare((self::$sqlSelectNews).$limit.', '.Core::DISPLAY_NEWS);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    static private $sqlSelectProject = 'SELECT code, name, address, customer FROM projects ORDER BY id DESC LIMIT ';

    static public function selectProject($dbh, $countElements) {
        // Определяем значение первого аргумента LIMIT, с которого будет начинаться вывод следующих $countElements записей
        if($_GET['page'] != 0) {
            $limit = ($_GET['page'] * $countElements) - $countElements;
        } else {
            $limit = 1;
        }
        $sth = $dbh->prepare((self::$sqlSelectProject).$limit.', '.$countElements);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    static private $sqlAddNews = 'INSERT INTO news(date, text) VALUES(:date, :text)';
    static public function addNews($dbh, $date, $p) {
        $sth = $dbh->prepare(self::$sqlAddNews);
        $sth->bindParam(':date', $date, PDO::PARAM_STR);
        $sth->bindParam(':text', $p, PDO::PARAM_STR);
        $sth->execute();
    }
}
