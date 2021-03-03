<?php

use Controller\MapController;

return [
    '/statistics/update' => [
        'controller' => MapController::class,
        'action' => 'updateStatistics'
    ],
    '/page/render' => [
        'controller' => MapController::class,
        'action' => 'renderPage'
    ],
    '/mark/save' => [
        'controller' => MapController::class,
        'action' => 'saveMark'
    ],
    '/reset' => [
        'controller' => MapController::class,
        'action' => 'reset'
    ],
    '/' => [
        'controller' => MapController::class,
        'action' => 'index'
    ]
];