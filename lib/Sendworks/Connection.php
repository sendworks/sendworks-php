<?php
namespace Sendworks;

class Connection
{
    protected $api_token;
    protected $domain;
    protected $client;
    protected $http_options;
    public $products;
    public $service_points;
    public $orders;
    public $shipments;

    function __construct($api_token, $domain = 'api.sandbox.sendworks.com', $http_options = [])
    {
        $this->api_token = $api_token;
        $this->domain = $domain;
        $this->http_options = $http_options;
        $this->products = new ProductsCollection($this);
        $this->service_points = new ServicePointsCollection($this);
        $this->orders = new OrdersCollection($this);
        $this->shipments = new ShipmentsCollection($this);
        $this->labels = new LabelsCollection($this);
    }

    function client()
    {
        if (!$this->client) {
            $options = $this->http_options;
            if (!isset($options['http_client'])) {
                $options['http_client'] = '\Sendworks\Http\Client';
            }
            if (!isset($options['base_uri'])) {
                $protocol = preg_match('/localhost/', $this->domain) ? 'http' : 'https';
                $options['base_uri'] = $protocol . "://" . $this->domain . "/v1/";
            }
            if (!isset($options['headers'])) {
                $options['headers'] = [];
            }
            if (!isset($options['headers']['X-Api-Token'])) {
                $options['headers']['X-Api-Token'] = $this->api_token;
            }
            $klass = $options['http_client'];
            $this->client = new $klass($options);
        }
        return $this->client;
    }
}
