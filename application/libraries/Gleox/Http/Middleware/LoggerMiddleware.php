<?php
/*
   ____ _
  / ___| | ___  _____  __
 | |  _| |/ _ \/ _ \ \/ /
 | |_| | |  __/ (_) >  <
  \____|_|\___|\___/_/\_\

  Gleox Request Library

    https://gleox.com
*/

namespace Gleox\Http\Middleware;

use Gleox\Http\Request;

class LoggerMiddleware implements MiddlewareInterface
{
    protected $logger;
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function handle(Request $request, callable $next)
    {
        $this->logger->info('Request: ' . $request->getMethod() . ' ' . $request->getUri());
        $response = $next($request);
        $this->logger->info('Response: ' . $response->getStatusCode() . ' ' . $response->getBody());
        return $response;
    }
}