<?php
/**
 * Configuration de l'application
 */

return [
    'name' => 'Bootstrap MVC',
    'debug' => true,
    'base_url' => 'http://localhost:8080',
    'session' => [
        'name' => 'bootstrap_mvc_session',
        'lifetime' => 3600 * 24 * 7, // 7 jours
    ]
];
