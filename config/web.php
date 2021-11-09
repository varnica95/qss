<?php

$app->get("/login", [Qss\Http\Controllers\LoginController::class, "index"]);
$app->post("/login/store-token", [Qss\Http\Controllers\LoginController::class, "login"]);
$app->get("/home", [Qss\Http\Controllers\HomeController::class, "index"]);

return $app;