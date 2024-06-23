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
use Gleox\Http\Response;

class RetryMiddleware implements MiddlewareInterface {
    protected $retries;
    protected $delay;
    protected $retryCallback;

    /**
     * RetryMiddleware constructor.
     * @param int $retries
     * @param int $delay
     * @param callable|null $retryCallback
     */
    public function __construct(int $retries = 3, int $delay = 1000, callable $retryCallback = null) {
        if ($this->retries < 0) {
            throw new \InvalidArgumentException('Retries must be greater than or equal to 0');
        }
        if ($this->delay < 0) {
            throw new \InvalidArgumentException('Delay must be greater than or equal to 0');
        }
        if ($retryCallback === null) {
            $retryCallback = function (Request $request, Response $response, int $retries, int $delay, int $currentRetries) {
                return $response->getStatusCode() >= 500;
            };
        }
        if (!is_callable($retryCallback)) {
            throw new \InvalidArgumentException('Retry callback must be callable');
        }
        $this->retries = $retries;
        $this->delay = $delay;
        $this->retryCallback = $retryCallback;
    }

    public function handle(Request $request, callable $next) {
        $retries = $this->retries;
        $delay = $this->delay;

        do {
            $response = $next($request);
            if (!call_user_func($this->retryCallback, $request, $response, $retries, $delay, $this->retries - $retries)) {
                return $response;
            }
            usleep($delay * 1000);
        } while ($retries-- > 0);

        return $response;
    }
}