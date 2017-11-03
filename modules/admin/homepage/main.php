<?php
//$value = 0;
if(isset($_POST['add'])) {
    $date = date("Y-m-d H:i:s");

    DB::addNews($dbh, $date, $_POST['add']);

    /*header('Location: /homepage');
    exit;*/
} else {
    // Если в массиве $_POST отсутствуют данные о новости, не производим никаких действий
}

if(isset($_POST['delete'])) {

    DB::deleteRow($dbh, 'news', $_POST['delete']);
    //header('Location: /homepage');
    //exit;
} else {
    // Если в массиве $_POST отсутствуют данные о новости, не производим никаких действий
}

if(isset($_POST['edit'])) {

    $value = $_POST['edit'];
    $sth1 = DB::selectRow($dbh, 'news', $_POST['edit']);
    //header('Location: /homepage');
    //exit;
} else {
    // Если в массиве $_POST отсутствуют данные о новости, не производим никаких действий
}


if(isset($_POST['editUPDATE'])) {

    DB::updateRow($dbh, 'news', $_POST['editUPDATE'], $_POST['editID']);
    //header('Location: /homepage');
    //exit;
} else {
    // Если в массиве $_POST отсутствуют данные о новости, не производим никаких действий
}




$sth = DB::selectNews($dbh, Core::DISPLAY_NEWS);

$pages = ceil(DB::countRows($dbh, 'news') / Core::DISPLAY_NEWS);
