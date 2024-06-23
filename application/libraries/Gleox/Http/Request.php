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

/**
 * Class Request
 *
 * This class represents an HTTP request. It includes methods for setting and getting
 * the request method, URL, headers, and body.
 */
class Request {
    private $method;
    private $uri;
    private $headers = [];
    private $body;
    private $query = [];

    /**
     * Request constructor.
     *
     * @param string $method The HTTP method for the request.
     * @param string $uri The URI for the request.
     * @param array $options An associative array of options for the request. The following options are supported: headers, query, json, form_params, and multipart.
     */
    public function __construct(string $method, string $uri, array $options = []) {
        $this->method = $method;
        $this->uri = $uri;
        $this->processOptions($options);
    }

    /**
     * Set a header for the request.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     */
    public function setHeader(string $name, string $value) {
        $this->headers[$name] = $value;
    }

    /**
     * Add multiple headers to the request.
     *
     * @param array $headers An associative array of headers to add.
     */
    public function addHeaders(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Remove a header from the request.
     *
     * @param string $name The name of the header to remove.
     */
    public function removeHeader(string $name) {
        unset($this->headers[$name]);
    }

    /**
     * Clear all headers from the request.
     */
    public function clearHeaders() {
        $this->headers = [];
    }

    /**
     * Get a header from the request.
     *
     * @param string $name The name of the header.
     * @return string The value of the header, or an empty string if the header is not set.
     */
    public function getHeader(string $name): string {
        return $this->headers[$name] ?? '';
    }

    /**
     * Get all headers from the request.
     *
     * @return array An array of all headers.
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * Set the body of the request.
     *
     * @param mixed $body The body of the request.
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * Set the body of the request as JSON.
     *
     * @param mixed $body The body of the request.
     */
    public function setJsonBody($body) {
        $this->setHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($body));
    }

    /**
     * Set the body of the request as form data.
     *
     * @param mixed $body The body of the request.
     */
    public function setFormBody($body) {
        $this->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $this->setBody(http_build_query($body));
    }

    /**
     * Set the body of the request as multipart form data.
     *
     * @param mixed $body The body of the request.
     */
    public function setMultipartBody($body) {
        $this->setHeader('Content-Type', 'multipart/form-data');
        $this->setBody($body);
    }

    /**
     * Get the body of the request.
     *
     * @return mixed The body of the request.
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Get the queries of the request.
     *
     * @return array The queries of the request.
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Get the HTTP method of the request.
     *
     * @return string The HTTP method of the request.
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * Get the URI of the request.
     *
     * @return string The URL of the request.
     */
    public function getUri(): string {
        return $this->uri;
    }

    protected function processOptions(array $options) {
        if (isset($options['json'])) {
            $this->headers['Content-Type'] = 'application/json';
            $this->body = json_encode($options['json']);
        }
        elseif (isset($options['form_params'])) {
            $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $this->body = http_build_query($options['form_params']);
        }
        elseif (isset($options['multipart'])) {
            $this->headers['Content-Type'] = 'multipart/form-data';
            $this->body = $options['multipart'];
        }

        if (isset($options['headers'])) {
            foreach ($options['headers'] as $name => $value) {
                $this->setHeader($name, $value);
            }
        }

        if (isset($options['query'])) {
            $this->query = $options['query'];
        }
    }
}