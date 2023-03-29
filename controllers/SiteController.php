<?php

namespace App\controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;

class SiteController extends Controller
{
    public function home() 
    {
        $params = ["name" => Application::$app->authName()];

        return $this->render('home', $params);
    }
    
    public function contact()
    {
        return $this->render('contact');
    }

    public function handleContact(Request $request)
    {

        echo "<pre>";
        var_dump($request->body());
        echo "<pre>";
        
        return "Hey my new contact";
    }
}