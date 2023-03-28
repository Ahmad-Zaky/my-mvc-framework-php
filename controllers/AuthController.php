<?php

namespace App\controllers;

use App\Core\Controller;
use App\Core\Request;
use App\models\RegisterModel;

class AuthController extends Controller
{
    function __construct()
    {
        $this->setLayout("auth");
    }

    public function login(Request $request) 
    {
        if ($request->isGet()) return $this->render("login");
    }
    
    public function register(Request $request)
    {
        $registerModel = new RegisterModel;
        if ($request->isPost()) {
            $registerModel->fill($request->body());

            if ($registerModel->validate() && $registerModel->register()) {
                return "Successfully registered";
            }
        }
        
        return $this->render("register", ["model" => $registerModel]);
    }
}