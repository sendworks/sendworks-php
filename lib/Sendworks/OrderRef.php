<?php
namespace Sendworks;

class OrderRef
{
    protected $connection;
    protected $order;
    public $url;
    function __construct($url, $connection = null)
    {
        $this->connection = $connection;
        $this->url = $url;
    }

    function __get($prop)
    {
        return $this->resolve()->$prop;
    }

    function resolve()
    {
        if (!$this->order) {
            $this->order = $this->connection->orders->fetch($this);
        }
        return $this->order;
    }

    function toHash()
    {
        return [
        'url' => $this->url
        ];
    }
}
