<?php

namespace Qss\Http\Controllers;

use Qss\Http\Request;
use Qss\Core\Controller;
use Qss\QSymfonySkeletonApi\QssApiService;

class BookController extends Controller
{
    /**
     *
     */
    public function deleteBook(Request $request, QssApiService $qssApiService, $authorId, $bookId)
    {
        dd($authorId);
        $test = $qssApiService->deleteBook($bookId);


    }
}