<?php

$app->get("/", [Qss\Http\Controllers\HomeController::class, "index"])->withAuthMiddleware("/");

$app->get("/login", [Qss\Http\Controllers\LoginController::class, "index"]);
$app->get("/logout", [Qss\Http\Controllers\LoginController::class, "logout"]);
$app->post("/login/store-token", [Qss\Http\Controllers\LoginController::class, "login"]);

$app->get("/authors", [Qss\Http\Controllers\AuthorController::class, "index"])->withAuthMiddleware("/authors");
$app->get("/authors/{authorId}", [Qss\Http\Controllers\AuthorController::class, "showAuthor"])->withAuthMiddleware("/authors/{authorId}");

$app->post("/author/{authorId}/books/{bookId}/delete", [Qss\Http\Controllers\BookController::class, "deleteBook"])->withAuthMiddleware("/author/{authorId}/books/{bookId}/delete");

return $app;