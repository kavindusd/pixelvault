<?php

declare(strict_types=1);

use App\Controllers\ApiController;

$router->get('/api/{dataset}', [ApiController::class, 'show']);
