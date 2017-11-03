<?php
if(isset($_POST['pieceOfNews'])) {
    /*
    $sql = 'INSERT INTO news(date, text) VALUES(:date, :text)';
    $sth = $dbh->prepare($sql);
    $date = date("Y-m-d H:i:s");
    $sth->bindParam(':date', $date, PDO::PARAM_STR);
    $sth->bindParam(':text', $_POST['pieceOfNews'], PDO::PARAM_STR);
    $sth->execute();(*/
    $date = date("Y-m-d H:i:s");

    DB::addNews($dbh, $date, $_POST['pieceOfNews']);

    header('Location: /homepage');
    exit;
} else {
    // Если в массиве $_POST отсутствуют данные о новости, не производим никаких действий
}
