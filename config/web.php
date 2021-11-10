<?php

$app->get("/login", [Qss\Http\Controllers\LoginController::class, "index"]);
$app->get("/logout", [Qss\Http\Controllers\LoginController::class, "logout"]);
$app->post("/login/store-token", [Qss\Http\Controllers\LoginController::class, "login"]);

$app->get("/", [Qss\Http\Controllers\HomeController::class, "index"])->middleware(true, true, "/");

return $app;