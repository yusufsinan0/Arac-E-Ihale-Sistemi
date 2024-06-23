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

namespace Gleox\Http\Helpers;

class CookieJar {
    protected $cookies = [];

    public function __construct(array $cookies = []) {
        $this->cookies = $cookies;
    }

    public function set($name, $value, $expire = 0, $path = '/', $domain = null, $secure = false, $httponly = false) {
        $this->cookies[$name] = [
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly
        ];
    }

    public function get($name) {
        return $this->cookies[$name]['value'] ?? null;
    }

    public function getAll() {
        return $this->cookies;
    }

    public function clear($name) {
        unset($this->cookies[$name]);
    }

    public function clearExpired() {
        foreach ($this->cookies as $name => $cookie) {
            if ($cookie['expire'] < time()) {
                unset($this->cookies[$name]);
            }
        }
    }

    public function toArray() {
        $cookieArray = [];
        foreach ($this->cookies as $name => $cookie) {
            $cookieArray[] = "$name=" . $cookie['value'];
        }
        return $cookieArray;
    }

    public function toHeaderString() {
        return implode('; ', $this->toArray());
    }
}