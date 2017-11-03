<?php
$allowed = array('homepage', 'projects', 'doc', 'comments', 'contacts', 'reg', 'entry', 'account', '404', 'admin');

$module = 'homepage';
$action = 'main';
$params = array();

if($_SERVER['REQUEST_URI'] != '/') {
    try {
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url_path = explode('/', trim($url_path, ' /'));

        if (count($url_path) > 1) {
            throw new Exception();
        }

        $module = array_shift($url_path);
        $action = array_shift($url_path);

        for ($i = 0; $i < count($url_path); $i++) {
            $params[$url_path[$i]] = $url_path[++$i];
        }
    } catch (Exception $e) {
        $module = '404';
        $action = 'main';
    }
}

if(!isset($_GET['module'])) {
    $_GET['module'] = $module;
} elseif (!in_array($_GET['module'], $allowed)) {
    $_GET['module'] = '404';
}

if(!isset($_GET['form'])) {
    $_GET['form'] = 'main';
}

if(!isset($_GET['page'])) {
    $_GET['page'] = '1';
}
