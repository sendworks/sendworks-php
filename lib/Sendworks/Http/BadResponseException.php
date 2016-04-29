<?php
namespace Sendworks\Http;

class BadResponseException extends \Exception {
  protected $response;

  function setResponse($response) {
    $this->response = $response;
  }

  function getResponse() {
    return $this->response;
  }
}