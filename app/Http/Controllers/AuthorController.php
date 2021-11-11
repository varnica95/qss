<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Core\Controller;
use Qss\QSymfonySkeletonApi\QssApiService;

class AuthorController extends Controller
{
    /**
     *
     */
    public function index(Request $request, QssApiService $qssApiService)
    {
        $authorsResponse = $qssApiService->get("authors", $request->getQueryString());

        $authors = $authorsResponse["response"]["items"];
        unset($authorsResponse["response"]["items"]);
        $meta = $authorsResponse["response"];

        return $this->view("authors.index", compact("authors", "meta"));
    }

    /**
     *
     */
    public function showAuthor(Request $request, QssApiService $qssApiService, $authorId)
    {
        $singleAuthorResponse = $qssApiService->get("authors/{$authorId}");

        if($singleAuthorResponse["error"] === true){
            $this->getContainer()->get("response")->setCode(404);
        }
        
        return $this->view("authors.show", ["author" => $singleAuthorResponse["response"]]);
    }

}