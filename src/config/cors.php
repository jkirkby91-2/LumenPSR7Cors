<?php
	declare(strict_types=1);

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
