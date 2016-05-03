<?php
namespace Sendworks\Http;

class Response
{
    public function __construct($raw_header, $body, $curl_info)
    {
        $this->raw_header = $raw_header;
        $this->body = $body;
        $this->curl_info = $curl_info;
    }

    public function getStatusCode()
    {
        return $this->curl_info['http_code'];
    }

    public function getHeader($name)
    {
        $result = [];
        if (preg_match('/^'.$name.': (.+)$/im', $this->raw_header, $mm)) {
            $result[] = trim($mm[1]);
        }
        return $result;
    }

    public function getBody()
    {
        return $this->body;
    }
}
