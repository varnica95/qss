<?php

$app->get("/", [Qss\Http\Controllers\HomeController::class, "index"])->withAuthMiddleware("/");

$app->get("/login", [Qss\Http\Controllers\LoginController::class, "index"]);
$app->get("/logout", [Qss\Http\Controllers\LoginController::class, "logout"]);
$app->post("/login/store-token", [Qss\Http\Controllers\LoginController::class, "login"]);

$app->get("/authors", [Qss\Http\Controllers\AuthorController::class, "index"])->withAuthMiddleware("/authors");
$app->get("/authors/{authorId}", [Qss\Http\Controllers\AuthorController::class, "showAuthor"])->withAuthMiddleware("/authors/{authorId}");
$app->post("/delete-author/{authorId}", [Qss\Http\Controllers\AuthorController::class, "deleteAuthor"])->withAuthMiddleware("/delete-author/{authorId}");

$app->get("/add-a-book", [Qss\Http\Controllers\BookController::class, "index"])->withAuthMiddleware("/add-a-book");
$app->post("/books", [Qss\Http\Controllers\BookController::class, "addNewBook"])->withAuthMiddleware("/add-a-book");
$app->post("/books/{authorId}/{bookId}", [Qss\Http\Controllers\BookController::class, "deleteBook"])->withAuthMiddleware("/author/{authorId}/books/{bookId}/delete");

return $app;