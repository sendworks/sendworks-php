<?php
namespace Sendworks;

class ShipmentsCollection {
  protected $connection;

  function __construct($connection) {
    $this->connection = $connection;
  }

  function select() {
    $response = $this->client()->get('shipments');
    $result = array();
    foreach (json_decode($response->getBody(), true) as $struct) {
      $result[] = Shipment::import($struct, $this->connection);
    }
    return $result;
  }

  function fetch($mixed) {
    if ($mixed instanceOf ShipmentRef) {
      $path = $mixed->url;
    } else {
      $path = "shipments/$mixed";
    }
    $response = $this->client()->get($path);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    }
  }

  function rates($shipment) {
    if ($mixed instanceOf ShipmentRef) {
      $path = $mixed->url . "/rates";
    } else {
      $path = "shipments/$mixed/rates";
    }
    $response = $this->client()->get($path);
    $result = array();
    foreach (json_decode($response->getBody(), true) as $struct) {
      $result[] = new Product($struct, $this->connection);
    }
    return $result;
  }

  function buy($shipment) {
    if ($mixed instanceOf ShipmentRef) {
      $path = $mixed->url . "/buy";
    } else {
      $path = "shipments/$mixed/buy";
    }
    $response = $this->client()->post($path);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    }
  }

  function cancel($shipment) {
    if ($mixed instanceOf ShipmentRef) {
      $path = $mixed->url . "/cancel";
    } else {
      $path = "shipments/$mixed/cancel";
    }
    $response = $this->client()->post($path);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    }
  }

  function save($shipment) {
    if ($shipment->id) {
      $path = "shipments/" . $shipment->id;
    } else {
      $path = "shipments";
    }
    $response = $this->client()->post($path, ['json' => $shipment->toHash()]);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    }
  }

  protected function client() {
    return $this->connection->client();
  }

}