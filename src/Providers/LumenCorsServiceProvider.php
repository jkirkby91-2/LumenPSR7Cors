<?php

namespace Jkirkby91\LumenPSR7Cors\Providers;

use Zend\Diactoros;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class LumenCorsServiceProvider
 *
 * @package Jkirkby91\LumenRestServerComponent\Providers
 * @author James Kirkby <jkirkby91@gmail.com>
 */
class LumenCorsServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Psr\Http\Message\ServerRequestInterface',
            'Zend\Diactoros\ServerRequest'
        );

        $this->app->bind(
            'Psr\Http\Message\ResponseInterface',
            'Zend\Diactoros\Response'
        );

    }
}