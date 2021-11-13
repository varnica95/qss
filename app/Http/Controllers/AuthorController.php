<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Core\Controller;
use Qss\QSymfonySkeletonApi\QssApiService;

class AuthorController extends Controller
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
        $authorsResponse = $qssApiService->fetchAuthorList($request->getQueryString());
        if($authorsResponse["error"] === true){
            return $this->getContainer()->get("response")->setCode(404)->setContent($authorsResponse["message"]);
        }

        $authors = $authorsResponse["response"]["items"];
        unset($authorsResponse["response"]["items"]);
        $meta = $authorsResponse["response"];

        return $this->view("authors.index", compact("authors", "meta"));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param QssApiService $qssApiService
     * @param [type] $authorId
     * @return void
     */
    public function showAuthor(Request $request, QssApiService $qssApiService, $authorId)
    {
        $singleAuthorResponse = $qssApiService->getAuthorDetails($authorId);

        if($singleAuthorResponse["error"] === true){
            return $this->getContainer()->get("response")->setCode(404)->setContent($singleAuthorResponse["message"]);
        }
        
        return $this->view("authors.show", ["author" => $singleAuthorResponse["response"]]);
    }

    /**
     *
     */
    public function deleteAuthor(Request $request, QssApiService $qssApiService, $authorId)
    {
        $singleAuthorResponse = $qssApiService->getAuthorDetails($authorId);

        if($singleAuthorResponse["error"] === true){
            return $this->getContainer()->get("response")->setCode(404)->setContent($singleAuthorResponse["message"]);
        }

        if(!empty($singleAuthorResponse["response"]["books"])){
            return $this->getContainer()->get("response")->setCode(200)->setContent("Cannot delete the author because he or she has books.");
        }
        
        $deletedAuthorResponse = $qssApiService->deleteAuthor($authorId);
        if($singleAuthorResponse["error"] === true){
            return $this->getContainer()->get("response")->setCode(404)->setContent($deletedAuthorResponse["message"]);
        }

        
        return $this->redirect("/authors");
    }

}