<?php
namespace Sendworks\Http;

/**
 * Drop-in replacement for GuzzleHttp
 */
class Client {

  protected $curl;

  function __construct($options = []) {
    if (!extension_loaded('curl')) {
      trigger_error("curl extension required", E_USER_ERROR);
    }
    $this->debug = isset($options['debug']) ? $options['debug'] : null;
    $this->base_uri = isset($options['base_uri']) ? $options['base_uri'] : '';
    $this->headers = isset($options['headers']) ? $options['headers'] : [];
    $this->timeout = 10;
    $this->http_errors = isset($options['http_errors']) ? $options['http_errors'] : true;
    $this->user_agent = 'SendworksHttpClient/1.1.3';
    $this->user_agent .= ' curl/' . \curl_version()['version'];
    $this->user_agent .= ' PHP/' . PHP_VERSION;
  }

  function get($path, $options = []) {
    return $this->send('GET', $path, $options);
  }

  function post($path, $options = []) {
    return $this->send('POST', $path, $options);
  }

  function put($path, $options = []) {
    return $this->send('PUT', $path, $options);
  }

  function delete($path, $options = []) {
    return $this->send('DELETE', $path, $options);
  }

  protected function buildRequest($path, $options) {
    $body = $query = null;
    $headers = $this->headers;
    if (isset($options['headers'])) {
      $headers = array_merge($headers, $options['headers']);
    }
    if (isset($options['query'])) {
      $value = $options['query'];
      if (is_array($value)) {
        $value = http_build_query($value, null, '&', PHP_QUERY_RFC3986);
      }
      if (!is_string($value)) {
        throw new \InvalidArgumentException('query must be a string or array');
      }
      $query = $value;
    }
    if (isset($options['json'])) {
      $body = json_encode($options['json']);
      $headers['Content-Type'] = 'application/json';
    }
    if (!isset($headers['X-Request-Id'])) {
      $headers['X-Request-Id'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
    if (preg_match('~^http~i', $path)) {
      $url = $path;
    } else {
      $url = $this->base_uri . $path;
    }
    if ($query) {
      $url .= "?" . $query;
    }
    return [$url, $headers, $body];
  }

  protected function send($method, $path, $options) {
    list($url, $headers, $body) = $this->buildRequest($path, $options);

    $curl = $this->getCurlHandle();
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agent);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_VERBOSE, !!$this->debug);
    if ($this->debug) {
      curl_setopt($curl, CURLOPT_STDERR, is_string($this->debug) ? fopen($this->debug, "a+") : STDOUT);
    }
    curl_setopt($curl, CURLOPT_HEADER, true);

    switch ($method) {
    case 'PUT':
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
      break;
    case 'DELETE':
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
      break;
    case 'POST':
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
      break;
    case 'GET':
      curl_setopt($curl, CURLOPT_HTTPGET, true);
      break;
    }
    $raw_request_headers = [];
    foreach ($headers as $key => $value) {
      $raw_request_headers[] = "$key: $value";
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $raw_request_headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    $result = curl_exec($curl);
    $curl_info = curl_getinfo($curl);
    list($header, $body) = explode("\r\n\r\n", $result, 2);
    if ($body && $this->debug) {
      error_log("$body\n", 3, is_string($this->debug) ? fopen($this->debug, "a+") : STDOUT);
    }
    $response = new Response($header, $body, $curl_info);
    if ($this->http_errors && $response->getStatusCode() >= 400) {
      $exception = new BadResponseException();
      $exception->setResponse($response);
      throw $exception;
    }
    return $response;
  }

  protected function getCurlHandle() {
    if (!isset($this->curl)) {
      $this->curl = curl_init();
    }
    return $this->curl;
  }
}
