<?php

namespace App\Core;

use App\Core\Exceptions\NotFoundException;

class Router
{

    protected Request $request;
    
    protected Response $response;

    function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected array $routes = [];

    public function get($path, $callback) 
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) 
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() 
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            
            throw new NotFoundException();

            $this->response->setStatusCode(404);
            return $this->renderView("_404");
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            Application::$app->controller = $callback[0] = new $callback[0];
        }

        return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params = [])
    {
        return $this->renderContent($this->renderOnlyView($view, $params));
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent() 
    {
        $layout = Application::$app->controller 
            ? Application::$app->controller->layout
            : Application::$app->layout;

        ob_start();
        require_once Application::$ROOT_DIR . "/views/layouts/{$layout}.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params = []) 
    {
        foreach ($params as $key => $value) ${$key} = $value;

        ob_start();
        require_once Application::$ROOT_DIR . "/views/{$view}.php";
        return ob_get_clean();    
    }
}