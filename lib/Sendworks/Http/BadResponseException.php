<?php
namespace Sendworks\Http;

class BadResponseException extends \Exception
{
    protected $response;

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
