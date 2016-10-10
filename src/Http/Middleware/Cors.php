<?php

namespace Jkirkby91\LumenPSR7Cors\Http\Middleware;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class LumenCors
 *
 * @package Jkirkby91\LumenPSR7Cors\Http\Middleware
 * @author James Kirkby <Jkirkby91@gmail.com>
 */
class Cors
{
    /**
     * @var array
     */
    protected $settings = [
        'maxAge'            => 0,
        'origin'            => '*',
        'allowMethods'      => '*',
        'exposeHeaders'     => '*',
        'allowedHeaders'    => '*'
        ];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setOrigin(ServerRequestInterface $request,$response)
    {
        $origin = $this->settings['origin'];
        if (is_callable($origin)) {
            // Call origin callback with request origin
            $origin = call_user_func($origin,$response->withAddedHeader('Origin',$origin));
        }
        $response->headers->set('Access-Control-Allow-Origin', $origin);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setExposeHeaders(ServerRequestInterface $request,$response)
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setMaxAge(ServerRequestInterface $request,$response)
    {
        if (isset($this->settings['maxAge'])) {
            $response->headers->set('Access-Control-Max-Age', (string) $this->settings['maxAge']);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setAllowCredentials(ServerRequestInterface $request,$response)
    {
        if (isset($this->settings['allowCredentials']) && $this->settings['allowCredentials'] === True) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setAllowMethods(ServerRequestInterface $request,$response)
    {
        if (isset($this->settings['allowMethods'])) {
            $allowMethods = $this->settings['allowMethods'];
            if (is_array($allowMethods)) {
                $allowMethods = implode(", ", $allowMethods);
            }
            $response->headers->set('Access-Control-Allow-Methods', $allowMethods);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setAllowHeaders(ServerRequestInterface $request,$response)
    {
        if (isset($this->settings['allowedHeaders'])) {
            $allowedHeaders = $this->settings['allowedHeaders'];
            if (is_array($allowedHeaders)) {
                $allowedHeaders = implode(", ", $allowedHeaders);
            }
        }
        else {  // Otherwise, use request headers
            $allowedHeaders = $request->hasHeader("Access-Control-Request-Headers");
        }

        if (isset($allowHeaders)) {
            $response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    protected function setCorsHeaders(ServerRequestInterface $request,$response)
      {

        // http://www.html5rocks.com/static/images/cors_server_flowchart.png
        // Pre-flight
        if ($request->getMethod('OPTIONS')) {
            $this->setOrigin($request, $response);
            $this->setMaxAge($request, $response);
            $this->setAllowCredentials($request, $response);
            $this->setAllowMethods($request, $response);
            $this->setAllowHeaders($request, $response);
        }
        else {
            $this->setOrigin($request, $response);
            $this->setExposeHeaders($request, $response);
            $this->setAllowCredentials($request, $response);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(ServerRequestInterface $request, Closure $next)
    {

        if ('OPTIONS' == $request->getMethod()) {
            $response =  new \Illuminate\Http\Response('',"204");
            $this->setOrigin($request, $response);
            return $response;
        }
        else {
            $response = $next($request);
        }
        $this->setCorsHeaders($request, $response);
        return $response;
    }
}