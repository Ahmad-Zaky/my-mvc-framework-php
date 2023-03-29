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

    public ?Controller $controller;
    
    public array $config;
    
    public Database $db;
    
    public Migration $migration;
    
    public Session $session;

    public ?DBModel $auth;

    public string $authClass;

    public string $layout = 'main';

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
        $this->session = new Session;
        $this->authClass = $config["authClass"];
        $this->auth = null;
        $this->controller = null;

        if ($primaryValue = $this->session->get('auth')) {
            $primaryKey = (new $this->authClass)->primaryKey();
            $this->auth = (new $this->authClass)->findOne([$primaryKey => $primaryValue]);
        }

    }

    public function run() 
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->router->renderView("_error", [
                'exception' => $e
            ]);
        }
    }

    public function authName() 
    {
        return $this->auth ? $this->auth->name() : "Guest";
    }

    public function login(DBModel $auth) 
    {
        $this->auth = $auth;
        $primaryKey = $auth->primaryKey();
        $primaryValue = $auth->{$primaryKey};
        $this->session->set('auth', $primaryValue);

        return true;
    }

    public function logout() 
    {
        $this->auth = null;
        $this->session->remove('auth');
        return true;
    }

    public static function auth() 
    {
        return self::$app->auth;
    }

    public static function guest() 
    {
        return ! self::auth();
    }
}