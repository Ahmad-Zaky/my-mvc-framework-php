<?php

namespace App\controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\models\Auth;
use App\models\User;

class AuthController extends Controller
{
    function __construct()
    {
        $this->setLayout("auth");
    }

    public function login(Request $request, Response $response) 
    {
        $auth = new Auth;
        if ($request->isPost()) {
            $auth->fill($request->body());
            if ($auth->validate() && $auth->login()) {
                return $response->redirect("/");
            }
        }
        
        return $this->render("login", ["model" => $auth]);
    }

    public function register(Request $request)
    {   
        $user = new User;
        if ($request->isPost()) {
            $user->fill($request->body());

            if ($user->validate() && $user->save()) {
                
                Application::$app->session->setFlash("success", "Successfully registered");

                return Application::$app->response->redirect("/");
            }
        }

        return $this->render("register", ["model" => $user]);
    }

    public function logout(Response $response)
    {
        $auth = new Auth;
        if ($auth->logout()) {
            return $response->redirect("/");
        }
    }

    public function profile() 
    {
        return $this->render("profile");
    }
}