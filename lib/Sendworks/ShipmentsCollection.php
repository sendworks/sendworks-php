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
    $path = $this->buildUrl($mixed);
    $response = $this->client()->get($path);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    }
  }

  function rates($mixed) {
    $path = $this->buildUrl($mixed) . "/rates";
    $response = $this->client()->get($path);
    $result = array();
    foreach (json_decode($response->getBody(), true) as $struct) {
      $result[] = new Product($struct, $this->connection);
    }
    return $result;
  }

  function buy($mixed) {
    $path = $this->buildUrl($mixed) . "/buy";
    $response = $this->client()->post($path);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    } else {
      $this->raiseError($response);
    }
  }

  function cancel($mixed) {
    $path = $this->buildUrl($mixed) . "/cancel";
    $response = $this->client()->post($path);
    if ($response->getStatusCode() == 200) {
      return Shipment::import(json_decode($response->getBody(), true), $this->connection);
    } else {
      $this->raiseError($response);
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
    } else {
      $this->raiseError($response);
    }
  }

  protected function client() {
    return $this->connection->client();
  }

  protected function buildUrl($mixed) {
    if ($mixed instanceOf ShipmentRef) {
      return $mixed->url;
    } else if ($mixed instanceOf Shipment) {
      return "shipments/" . $mixed->id;
    } else {
      return "shipments/$mixed";
    }
    throw new \Exception("Unexpected input");
  }

  protected function raiseError($response) {
    $http_code = $response->getStatusCode();
    $json = json_decode($response->getBody(), true);
    if (isset($json['message'])) {
      throw new \Exception("Error ($http_code): " . $json['message']);
    }
    throw new \Exception("Error ($http_code)");
  }

}
