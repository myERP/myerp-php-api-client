<?php

namespace MyERP;

use Guzzle\Http\Message\Header\HeaderCollection;

class ApiResponse {

  public $headers;
  public $body;

  public function __construct(HeaderCollection $headers, array $body) {
    $this->headers = $headers;
    $this->body = $body;
  }

  public function getHeader($key) {
    return $this->headers->get($key);
  }

  public function hasNextPage() {
    $h = $this->headers->get('X-MyERP-Has-Next-Page');
    return is_null($h) ? false : $h->hasValue("true");
  }

}
