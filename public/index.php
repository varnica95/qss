<?php

use Qss\App;

require dirname(__DIR__) . "/bootstrap/autoload.php";

$app = new App();

$app = require dirname(__DIR__) . "/config/web.php";

$app->run();