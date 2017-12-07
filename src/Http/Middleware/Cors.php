<?php
	declare(strict_types=1);

	namespace Jkirkby91\LumenPSR7Cors\Http\Middleware {

		use Closure;

		use Psr\{
			Http\Message\ServerRequestInterface
		};

		use Symfony\Component\HttpFoundation\{
			Response
		};

		/**
		 * Class Cors
		 *
		 * @package Jkirkby91\LumenPSR7Cors\Http\Middleware
		 * @author James Kirkby <Jkirkby91@gmail.com>
		 * @SEE https://www.html5rocks.com/static/images/cors_server_flowchart.png
		 */
		class Cors
		{
			/**
			 * @var array
			 */
			protected $settings = [
				'maxAge'            => 0,
				'origin'            => '*',
				'exposeHeaders'     => '*',
				'allowedHeaders'    => '*',
				'allowedMethods'    => ['GET','POST','PUT','DELETE']
			];

			/**
			 * setOrigin()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setOrigin(ServerRequestInterface $request, Response $response)
			{
				$origin = $this->settings['origin'];
				if (is_callable($origin)) {
					$origin = call_user_func($origin, $response->withAddedHeader('Origin',$origin));
				}
				$response->headers->set('Access-Control-Allow-Origin', $origin);
			}

			/**
			 * setExposeHeaders()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setExposeHeaders(ServerRequestInterface $request, Response $response)
			{
				if (isset($this->settings['exposeHeaders'])) {
					$exposeHeaders = $this->settings['exposeHeaders'];
					if (is_array($exposeHeaders)) {
						$exposeHeaders = implode(", ", $exposeHeaders);
					}
					$response->headers->set('Access-Control-Expose-Headers', $exposeHeaders);
				}
			}

			/**
			 * setMaxAge()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setMaxAge(ServerRequestInterface $request, Response $response)
			{
				if (isset($this->settings['maxAge'])) {
					$response->headers->set('Access-Control-Max-Age', (string) $this->settings['maxAge']);
				}
			}

			/**
			 * setAllowCredentials()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setAllowCredentials(ServerRequestInterface $request, Response $response)
			{
				if (isset($this->settings['allowCredentials']) && $this->settings['allowCredentials'] === True) {
					$response->headers->set('Access-Control-Allow-Credentials', 'true');
				}
			}

			/**
			 * setAllowMethods()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setAllowMethods(ServerRequestInterface $request, Response $response)
			{
				if (isset($this->settings['allowMethods'])) {
					$allowMethods = $this->settings['allowMethods'];
					if (is_array($allowMethods)) {
						$allowMethods = implode(",", $allowMethods);
					}
					$response->headers->set('Access-Control-Allow-Methods', $allowMethods);
				}
			}

			/**
			 * setAllowHeaders()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setAllowHeaders(ServerRequestInterface $request, Response $response)
			{
				if (isset($this->settings['allowedHeaders'])) {
					$allowedHeaders = $this->settings['allowedHeaders'];
					if (is_array($allowedHeaders)) {
						$allowedHeaders = implode(",", $allowedHeaders);
					}
				}
				else {
					$allowedHeaders = $request->hasHeader("Access-Control-Request-Headers");
				}
				if (isset($allowedHeaders)) {
					$response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
				}
			}

			/**
			 * setCorsHeaders()
			 * @param \Psr\Http\Message\ServerRequestInterface   $request
			 * @param \Symfony\Component\HttpFoundation\Response $response
			 */
			protected function setCorsHeaders(ServerRequestInterface $request, Response $response)
			{
				if ($request->getMethod('OPTIONS')) {
					$this->setOrigin($request, $response);
					$this->setMaxAge($request, $response);
					$this->setAllowCredentials($request, $response);
					$this->setAllowMethods($request, $response);
					$this->setAllowHeaders($request, $response);
				} else {
					$this->setOrigin($request, $response);
					$this->setExposeHeaders($request, $response);
					$this->setAllowCredentials($request, $response);
				}
			}

			/**
			 * handle()
			 * @param \Psr\Http\Message\ServerRequestInterface $request
			 * @param \Closure                                 $next
			 *
			 * @return \Illuminate\Http\Response|mixed
			 */
			public function handle(ServerRequestInterface $request, Closure $next)
			{
				//handle preflight request
				if ('OPTIONS' == $request->getMethod()) {
					$allowedHeaders = implode(",",config('cors.allowedHeaders'));
					$response =  new \Illuminate\Http\Response('',"204");
					$response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
					$response->headers->set('Access-Control-Allow-Methods', ['GET','POST','PUT','DELETE']);
					$response->headers->set('Access-Control-Allow-Origin', '*');
					$response->headers->set('Access-Control-Allow-Origin', '*');
					return $response;
				} else {
					$response = $next($request);
				}
				$this->setCorsHeaders($request, $response);
				return $response;
			}
		}
	}