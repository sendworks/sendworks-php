<?php
namespace Sendworks;

class ShipmentRef
{
    protected $connection;
    protected $shipment;
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
        if (!$this->shipment) {
            $this->shipment = $this->connection->shipments->fetch($this);
        }
        return $this->shipment;
    }

    public function toHash()
    {
        return [
        'url' => $this->url
        ];
    }
}
