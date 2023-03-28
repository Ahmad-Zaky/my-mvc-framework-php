<?php

namespace App\Core;

use App\Core\Database;

class Application
{
    public static string $ROOT_DIR;
    
    public static self $app;

    public Request $request;
    
    public Response $response;
    
    public Router $router;

    public Controller $controller;
    
    public array $config;
    
    public Database $db;
    
    public Migration $migration;

    public function __construct($rootPath, array $config) 
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;

        $this->request = new Request;
        $this->response = new Response;
        $this->router = new Router($this->request, $this->response);
        $this->config = $config;
        $this->db = new Database($config["db"]);
        $this->migration = new Migration;
    }

    public function run() 
    {
        echo $this->router->resolve();
    }
}