<?php
namespace Sendworks;

class Shipment {
  protected $connection;
  public $id;
  public $shipment_reference;
  public $sender;
  public $recipient;
  public $product;
  public $parcels = [];
  public $tracking_url;
  public $labels = [];
  public $order;

  function __construct($data = [], $connection = null) {
    $this->connection = $connection;
    foreach (['id','shipment_reference','tracking_url'] as $prop) {
      if (isset($data[$prop])) {
        $this->$prop = $data[$prop];
      }
    }
    foreach (['sender','recipient'] as $prop) {
      $this->$prop = isset($data[$prop]) ? Address::import($data[$prop]) : null;
    }
    $this->product = isset($data['product']) ? new Product($data['product']) : null;
    if (isset($data['parcels'])) {
      $this->parcels = [];
      foreach ($data['parcels'] as $parcel_data) {
        $this->parcels[] = Parcel::import($parcel_data);
      }
    }
    if (isset($data['labels'])) {
      $this->labels = [];
      foreach ($data['labels'] as $url) {
        $this->labels[] = Label::import($url, $connection);
      }
    }
    if (isset($data['order'])) {
      $this->order = Order::import($data['order'], $connection);
    }
  }

  function rates() {
    return $this->connection->shipments->rates($this);
  }

  function buy() {
    return $this->connection->shipments->buy($this);
  }

  function cancel() {
    return $this->connection->shipments->cancel($this);
  }

  function save() {
    return $this->connection->shipments->save($this);
  }

  function toHash() {
    $parcels = [];
    foreach ($this->parcels as $parcel) {
      $parcels[] = $parcel->toHash();
    }
    $labels = [];
    foreach ($this->labels as $label) {
      $labels[] = $label->toHash();
    }
    return [
      'id' => $this->id,
      'shipment_reference' => $this->shipment_reference,
      'tracking_url' => $this->tracking_url,
      'sender' => $this->sender ? $this->sender->toHash() : null,
      'recipient' => $this->recipient ? $this->recipient->toHash() : null,
      'product' => $this->product ? ['id' => $this->product->id] : null,
      'parcels' => $parcels,
      'labels' => $labels,
    ];
  }

  static function import($mixed, $connection = null) {
    if (is_string($mixed)) {
      return new ShipmentRef($mixed, $connection);
    }
    return new self($mixed, $connection);
  }
}