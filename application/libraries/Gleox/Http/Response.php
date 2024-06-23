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
class Response {
    protected $statusCode;
    protected $body;
    protected $headers;
    protected $verbose;

    public function __construct($statusCode, $headers, $body, $verbose = null) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $this->prepareHeaders($headers);
        $this->verbose = $verbose;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getBody() {
        return $this->body;
    }

    public function getJson($assoc = true) {
        return json_decode($this->body, $assoc);
    }

    public function getXML() {
        return simplexml_load_string($this->body);
    }

    public function getVerbose() {
        return $this->verbose;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getHeader($name) {
        return $this->headers[$name] ?? null;
    }

    public function hasHeader($name) {
        return isset($this->headers[$name]);
    }

    private function prepareHeaders($headers) {
        $result = [];
        $lines = explode("\n", $headers);
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $result[trim($parts[0])] = trim($parts[1]);
            }
        }
        return $result;
    }
}