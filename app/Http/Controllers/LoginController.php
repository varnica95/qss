<?php

namespace Qss\Http\Controllers;

use Qss\Core\Controller;


class LoginController extends Controller
{
    public function index()
    {
        return $this->view("home.index", ["id" => 5]);
    }

    public function login()
    {
        dd($_GET);
    }
}