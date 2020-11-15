<?php
/**
 * User : Henry
 * Date : 14/11/2020
 */
// c1: without `use` syntax, you need to use `\app\controllers\SiteController::class` to access class SiteController. if you use it, can just call `SiteController::class`.

require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\SiteController; //c1
use app\core\Application;

$app = new Application(dirname(__DIR__));

$app->router->get('/', [SiteController::class,'home']);
$app->router->get('/contact', [SiteController::class,'contact']);
$app->router->post('/contact', [SiteController::class,'handleContact']);

$app->router->get('/login', [AuthController::class,'login']);
$app->router->post('/login', [AuthController::class,'login']);
$app->router->get('/register', [AuthController::class,'register']);
$app->router->post('/register', [AuthController::class,'register']);

$app->run();
