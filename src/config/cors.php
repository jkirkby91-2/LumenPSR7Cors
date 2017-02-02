<?php
return [

    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
    'allowedHeaders' => [
        'Content-Type',
        'Authorization',
        'field',
        'X-Socket-ID'
    ],
            
];
