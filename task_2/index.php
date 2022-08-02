<?php
/**
 * Импровизированный роутер
 */
require(__DIR__ . "/controller/Controller.php");
$requestURI = $_SERVER['REQUEST_URI'];
if (empty($requestURI)) {
    echo "Пустой запрос";
}
if (empty($_GET['action'])) {
    echo "Вы не указали действие";
}
$action = $_GET['action'];
$controller = new Controller();

switch ($action) {
    case "create":
        $controller->actionCreate();
        break;
    case "read":
        $controller->actionRead();
        break;
    case "update":
        $controller->actionUpdate();
        break;
    case "delete":
        $controller->actionDelete();
        break;
    default:
        return "Вы указали несуществующее действие";
}
