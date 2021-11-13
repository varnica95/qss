<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Core\Controller;
use Qss\QSymfonySkeletonApi\QssApiService;

class BookController extends Controller
{
    public function index(Request $request, QssApiService $qssApiService)
    {
        $authorsResponse = $qssApiService->fetchAuthorList("?limit=100");
        if($authorsResponse["error"] === true){
            return $this->getContainer()->get("response")->setCode(404)->setContent($authorsResponse["message"]);
        }

        $authors = $authorsResponse["response"]["items"];

        return $this->view('books.index', compact('authors'));
    }

     /**
     *
     */
    public function addNewBook(Request $request, QssApiService $qssApiService)
    {
        $validation = $request->validateParameters();
        if($validation["error"] === true){
            return $this->view("books.index", ["error_message" => $validation["message"]]);
        }

        $data = array();
        $data["author"]["id"] = (int) explode(" - ", $request->get("author"))[0];
        $data["title"] = $request->get("title");
        $data["release_date"] = date("Y-m-d\TH:i:s.000\Z", strtotime($request->get("release_date")));
        $data["description"] = $request->get("description");
        $data["isbn"] = $request->get("isbn");
        $data["format"] = $request->get("format");
        $data["number_of_pages"] = (int) $request->get("number_of_pages");

        $createdBookResponse = $qssApiService->addNewBook($data);
  
        if($createdBookResponse["error"] === true){
            return $this->getContainer()->get("response")->setCode((int)$createdBookResponse["code"])->setContent($createdBookResponse["message"]);
        }

        return $this->redirect("/authors/{$createdBookResponse["response"]["author"]["id"]}");
    }

    /**
     *
     */
    public function deleteBook(Request $request, QssApiService $qssApiService, $authorId, $bookId)
    {
        $deletedBookResponse = $qssApiService->deleteBook($bookId);
        if($deletedBookResponse["error"] === true){

            return $this->view("errors.404")->setCode(404);
        }

        return $this->redirect("/authors/{$authorId}");
    }
}