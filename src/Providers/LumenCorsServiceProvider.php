<?php
	declare(strict_types=1);

	namespace Jkirkby91\LumenPSR7Cors\Providers {

		use Jkirkby91\{
			LumenPSR7Cors\Http\Middleware\Cors
		};

		use Zend\{
			Diactoros
		};

		use Psr\{
			Http\Message\ResponseInterface, Http\Message\ServerRequestInterface
		};

		use Illuminate\{
			Support\ServiceProvider
		};

		/**
		 * Class LumenCorsServiceProvider
		 *
		 * @package Jkirkby91\LumenPSR7Cors\Providers
		 * @author  James Kirkby <jkirkby@protonmail.ch>
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
				$this->app->middleware(Cors::class);
			}
		}
	}
