<?php
namespace Sendworks;

class ShipmentRef {
  protected $connection;
  protected $shipment;
  public $url;
  function __construct($url, $connection = null) {
    $this->connection = $connection;
    $this->url = $url;
  }

  function __get($prop) {
    return $this->resolve()->$prop;
  }

  function resolve() {
    if (!$this->shipment) {
      $this->shipment = $this->connection->shipments->fetch($this);
    }
    return $this->shipment;
  }

  function toHash() {
    return [
      'url' => $this->url
    ];
  }
}