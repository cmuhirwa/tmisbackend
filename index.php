<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type,Authorization');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

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
// Plans routes
$route->router("/plans", "src/Controller/planController.php");
$route->router("/plan/{id}", "src/Controller/planController.php");

//write it at the last
//arg is 404 file location
$route->notFound("404.php");



?>