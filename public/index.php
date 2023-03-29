<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\controllers\SiteController;
use App\controllers\AuthController;
use App\Core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = require_once dirname(__DIR__) . '/config.php';

$app = new Application(dirname(__DIR__), $config);

$app->router->get("/callback", function () {
    return 'Hello World';
});

$app->router->get("/", [SiteController::class, "home"]);
$app->router->get("/contact", [SiteController::class, "contact"]);
$app->router->post("/contact", [SiteController::class, "handleContact"]);

$app->router->get("/login", [AuthController::class, "login"]);
$app->router->post("/login", [AuthController::class, "login"]);
$app->router->get("/register", [AuthController::class, "register"]);
$app->router->post("/register", [AuthController::class, "register"]);
$app->router->post("/logout", [AuthController::class, "logout"]);
$app->router->get("/profile", [AuthController::class, "profile"]);

$app->run();
