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

namespace Gleox\Http;

use Gleox\Http\Helpers\CookieJar;
use Gleox\Http\Helpers\Utils;
use Gleox\Http\Middleware\MiddlewareInterface;

class Client {
    protected $baseUrl;
    protected $defaultHeaders;
    protected $cookies;
    protected $timeout;
    protected $allowRedirects;
    protected $proxy;
    protected $verify;
    protected $userAgent;
    protected $httpVersion;
    protected $verbose;

    /**
     * @var MiddlewareInterface[]
     */
    protected $middlewares = [];

    public function __construct(array $config = []) {
        $this->baseUrl = $config['base_uri'] ?? '';
        $this->defaultHeaders = $config['headers'] ?? [];
        $this->cookies = new CookieJar($config['cookies'] ?? []);
        $this->timeout = $config['timeout'] ?? 30;
        $this->allowRedirects = $config['allow_redirects'] ?? true;
        $this->proxy = $config['proxy'] ?? null;
        $this->verify = $config['verify'] ?? true;
        $this->userAgent = $config['user_agent'] ?? 'Gleox HttpClient/2.0';
        $this->httpVersion = $config['http_version'] ?? CURL_HTTP_VERSION_2_0;
        $this->verbose = $config['verbose'] ?? false;
    }

    public function get(string $url, array $options = []) {
        return $this->request('GET', $url, $options);
    }

    public function post(string $url, array $options = []) {
        return $this->request('POST', $url, $options);
    }

    public function put(string $url, array $options = []) {
        return $this->request('PUT', $url, $options);
    }

    public function delete(string $url, array $options = []) {
        return $this->request('DELETE', $url, $options);
    }

    public function patch(string $url, array $options = []) {
        return $this->request('PATCH', $url, $options);
    }

    public function setCookieJar(CookieJar $cookies) {
        $this->cookies = $cookies;
    }

    public function setCookie($name, $value) {
        $this->cookies->set($name, $value);
    }

    public function addMiddleware(MiddlewareInterface $middleware) {
        $this->middlewares[] = $middleware;
    }

    public function send(Request $request) {
        $method = $request->getMethod();
        $uri_params = $request->getQuery();
        $uri = $this->buildUrl($request->getUri(), $uri_params);
        $headers = $this->prepareHeaders($request->getHeaders());
        $body = $request->getBody();

        $stack = array_reduce(array_reverse($this->middlewares), function ($next, $middleware) {
            return function ($request) use ($middleware, $next) {
                return $middleware->handle($request, $next);
            };
        }, function ($request) {
            $method = $request->getMethod();
            $uri_params = $request->getQuery();
            $uri = $this->buildUrl($request->getUri(), $uri_params);
            $headers = $this->prepareHeaders($request->getHeaders());
            $body = $request->getBody();
            return $this->doSend($method, $uri, $headers, $body);
        });

        return $stack($request);
    }
    protected function buildUrl($uri, $uri_params = []) {
        $base = str_replace(['http://', 'https://'], '', $this->baseUrl);
        $return_uri = $this->baseUrl . $uri;
        if ($base !== "" && Utils::startsWith($uri, $base)) {
            $return_uri = $uri;
        }
        if (count($uri_params) > 0) {
            $return_uri .= '?' . http_build_query($uri_params);
        }
        return $return_uri;
    }

    protected function prepareHeaders(array $headers) {
        $merged = array_merge($this->defaultHeaders, $headers);
        return array_map(function ($key, $value) {
            return $key . ': ' . $value;
        }, array_keys($merged), $merged);
    }

    public function request($method, $uri, array $options = []) {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
            throw new \InvalidArgumentException('Invalid HTTP method');
        }
        $request = new Request($method, $uri, $options);
        return $this->send($request);
    }

    private function doSend($method, $uri, $headers, $body) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->allowRedirects);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, $this->httpVersion);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_COOKIE, $this->cookies->toHeaderString());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify);
        if ($this->proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }
        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        if ($this->verbose) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            $verboseLog = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verboseLog);
        }

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        if ($this->verbose) {
            rewind($verboseLog);
            $verboseLogContents = stream_get_contents($verboseLog);
            return new Response($statusCode, $headers, $body, $verboseLogContents);
        }

        return new Response($statusCode, $headers, $body);
    }
}