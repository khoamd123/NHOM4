<?php
$controller = $_GET['controller'] ?? 'user';
$action = $_GET['action'] ?? 'login';

require_once __DIR__ . "/controllers/" . ucfirst($controller) . "Controller.php";
$controllerClass = ucfirst($controller) . "Controller";
$controllerObject = new $controllerClass();
$controllerObject->$action();
