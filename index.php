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