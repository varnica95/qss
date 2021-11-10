<?php

namespace Qss\Http\Middleware;

use Qss\Http\Request;

interface Middleware
{
    public function __invoke($callback, Request $request, $route);
}