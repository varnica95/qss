<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Includes\Cookie;
use Qss\Core\Controller;
use Qss\Includes\Session;
use Qss\QSymfonySkeletonApi\QssApiService;

class LoginController extends Controller
{
    /**
     * Login index page
     */
    public function index()
    {
        return $this->view("login.index");
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param QssApiService $qssApiService
     * @return void
     */
    public function login(Request $request, QssApiService $qssApiService)
    {
        
        $validation = $request->validateParameters();
        if($validation["error"] === true){
            return $this->view("login.index", ["error_message" => $validation["message"]]);
        }

        $email = $request->get("email");
        $password = $request->get("password");

        $response = $qssApiService->auth($email, $password);
      
        if($response["error"] === true){
            return $this->view("login.index", ["error_message" => $response["message"]]);
        }

        $tokenKey = $response["response"]["token_key"];

        Session::set('session_token', $tokenKey);

        if($request->has("remember_me") === true){
            Cookie::set('cookie_token', $tokenKey);
        }
    
   
        return $this->redirect("/");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function logout()
    {
        Session::destroy();
        Cookie::unset("cookie_token");

        return $this->redirect("/login");
    }
}