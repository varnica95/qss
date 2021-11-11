<?php

return [
  "router" => Qss\Core\Router::class,
  "view" => Qss\Core\View::class,
  "response" => Qss\Http\Response::class,
  "request" => Qss\Http\Request::class,
  "parameter_bag" => Qss\Bags\ParameterBag::class,
  "qss_api_service" =>  Qss\QSymfonySkeletonApi\QssApiService::class,
  "middleware" => Qss\Http\Middleware\Middleware::class,
];