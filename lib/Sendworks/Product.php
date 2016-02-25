<?php
namespace Sendworks;

class Product {
  protected $connection;
  public $id;
  public $name;
  public $carrier;
  public $service_points = [];
  function __construct($data = [], $connection = null) {
    $this->connection = $connection;
    foreach (['id', 'name'] as $prop) {
      if (isset($data[$prop])) {
        $this->$prop = $data[$prop];
      }
    }
    $this->carrier = new Carrier($data['carrier']);
    if (isset($data['price'])) {
      $this->price = Money::import($data['price']);
    }
    if (isset($data['cost_price'])) {
      $this->cost_price = Money::import($data['cost_price']);
    }
    if (isset($data['service_points'])) {
      $this->service_points = [];
      foreach ($data['service_points'] as $service_point_data) {
        $this->service_points[] = new ServicePoint($service_point_data);
      }
    }
  }
}