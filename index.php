<?php
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=UTF-8");

require "bootstrap.php";

use Src\Routes\MainRoutes;

//Route instance
$route = new MainRoutes();

//route address and home.php file location

// Users routes 
$route->router("/user", "src/Controller/userscontroller.php");
$route->router("/user/{id}","src/Controller/userscontroller.php");
$route->router("/user/account/{action}", "src/Controller/authcontroller.php");
$route->router("/user/account/{action}/{user_id}", "src/Controller/userscontroller.php");
$route->router("/user/current/info", "src/Controller/authcontroller.php");
//write it at the last
//arg is 404 file location
$route->notFound("404.php");



?>