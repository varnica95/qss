<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Core\Controller;
use Qss\QSymfonySkeletonApi\QssApiService;

class LoginController extends Controller
{
    public function index()
    {
        return $this->view("home.index", ["id" => 5]);
    }

    public function login(Request $request, QssApiService $qssApiService)
    {
        $email = $request->get("email");
        $password = $request->get("password");

        $qssApiService->auth($email, $password);
    }
}