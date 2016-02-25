<?php
namespace Sendworks;

class Connection {
  protected $api_token;
  protected $domain;
  protected $client;
  protected $guzzle_options;
  public $products;
  public $service_points;
  public $orders;
  public $shipments;

  function __construct($api_token, $domain = 'api.sandbox.sendworks.com', $guzzle_options = []) {
    $this->api_token = $api_token;
    $this->domain = $domain;
    $this->guzzle_options = $guzzle_options;
    $this->products = new ProductsCollection($this);
    $this->service_points = new ServicePointsCollection($this);
    $this->orders = new OrdersCollection($this);
    $this->shipments = new ShipmentsCollection($this);
    $this->labels = new LabelsCollection($this);
  }

  function client() {
    if (!$this->client) {
      $protocol = preg_match('/localhost/', $this->domain) ? 'http' : 'https';
      $options = ['base_uri' => $protocol . "://" . $this->domain . "/v1/", 'headers' => ['X-Api-Token' => $this->api_token]] + $this->guzzle_options;
      $this->client = new \GuzzleHttp\Client($options);
    }
    return $this->client;
  }
}