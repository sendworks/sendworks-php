<?php
namespace Sendworks;

class ProductsCollection {
  protected $connection;

  function __construct($connection) {
    $this->connection = $connection;
  }

  function select($recipient = null, $order = null) {
    $query = [];
    if ($recipient) {
      $query['post_code'] = $recipient->post_code;
      $query['country_code'] = $recipient->country_code;
    }
    if ($order && $order->subtotal) {
      $query['order_value'] = $order->subtotal->toHash();
    }
    $response = $this->client()->get('products', ['query' => $query]);
    $result = array();
    foreach (json_decode($response->getBody(), true) as $struct) {
      $result[] = new Product($struct, $this->connection);
    }
    return $result;
  }

  protected function client() {
    return $this->connection->client();
  }
}