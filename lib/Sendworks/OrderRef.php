<?php
namespace Sendworks;

class OrderRef
{
    protected $connection;
    protected $order;
    public $url;
    public function __construct($url, $connection = null)
    {
        $this->connection = $connection;
        $this->url = $url;
    }

    public function __get($prop)
    {
        return $this->resolve()->$prop;
    }

    public function resolve()
    {
        if (!$this->order) {
            $this->order = $this->connection->orders->fetch($this);
        }
        return $this->order;
    }

    public function toHash()
    {
        return [
        'url' => $this->url
        ];
    }
}
