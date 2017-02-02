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
        $this->registerConfigs();
        $this->registerMiddlewares();
    }


    /**
     * Register configs for this component and merge and vendor configs
     */
    public function registerConfigs()
    {
       $this->app->configure('cors');
    }

        /**
     * Register any component middlewares
     */
    public function registerMiddlewares()
    {
        $this->app->middleware(\Jkirkby91\LumenPSR7Cors\Http\Middleware\Cors::class);
    }
}