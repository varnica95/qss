<?php

$app->get("/login", [Qss\Http\Controllers\LoginController::class, "index"]);
$app->get("/home", [Qss\Http\Controllers\HomeController::class, "index"]);

return $app;