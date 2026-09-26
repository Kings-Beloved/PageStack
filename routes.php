<?php
require 'Router.php';
require_once 'Auth.php';


$router = new Router();
$auth = new Auth();


$router->add('POST', '/auth/createUser', [$auth, 'create_user']);
$router->add('POST', '/auth/loginUser', [$auth, 'login_user']);

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/PageStack', '', $path);

$router->dispatch($method, $path);