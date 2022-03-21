<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: *');
header("Access-Control-Max-Age: 4600");
header('Access-Control-Allow-Headers: *');

require "bootstrap.php";

use Src\Routes\MainRoutes;

//Route instance
$route = new MainRoutes();

//route address and home.php file location
echo $_REQUEST['uri'];

// Users routes
$route->add("/user/login", "src/Controller/userscontroller.php");

// $route->add("/user/{id}","src/Controller/userscontroller.php");

//write it at the last
//arg is 404 file location
$route->notFound("404.php");



?>