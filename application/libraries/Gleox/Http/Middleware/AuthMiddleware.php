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

class AuthMiddleware implements MiddlewareInterface {
    private $token;
    private $key_name;
    private $has_bearer;

    public function __construct($token, $key_name = 'Authorization', $has_bearer = true) {
        $this->token = $token;
        $this->key_name = $key_name;
        $this->has_bearer = $has_bearer;
    }

    public function handle(Request $request, callable $next) {
        $request->setHeader($this->key_name, (
            ($this->has_bearer ? 'Bearer ' : '') . $this->token
        ));
        return $next($request);
    }
}
