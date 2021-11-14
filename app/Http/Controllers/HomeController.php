<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Core\Controller;
use Qss\QSymfonySkeletonApi\QssApiService;


class HomeController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param QssApiService $qssApiService
     * @return void
     */
    public function index(Request $request, QssApiService $qssApiService)
    {
        $userResponse = $qssApiService->getCurrentlyLoggedUser();

        if($userResponse["error"] === true) {    
            return $this->redirect("/login");
        }

        return $this->view('home.index', [
            "first_name" => $userResponse["response"]["first_name"],
            "last_name" => $userResponse["response"]["last_name"]
        ]);
    }
}