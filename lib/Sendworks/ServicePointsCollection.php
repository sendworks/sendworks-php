<?php
namespace Sendworks;

class ServicePointsCollection
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function find($product, $reference)
    {
        $query = [];
        if ($product instanceof Product) {
            $product_code = $product->code;
        } else {
            $product_code = $product;
        }
        if ($reference instanceof ServicePoint) {
            $reference = $reference->reference;
        }
        $response = $this->client()->get(implode('/', array('service_points', urlencode($product_code), urlencode($reference))));
        return new ServicePoint(json_decode($response->getBody(), true));
    }

    public function select($product, $recipient, $limit = null)
    {
        $query = [];
        if ($product instanceof Product) {
            $query['product_code'] = $product->code;
        } else {
            $query['product_code'] = $product;
        }
        if ($limit) {
            $query['limit'] = $limit;
        }
        $query['street1'] = $recipient->street1;
        $query['street2'] = $recipient->street2;
        $query['city'] = $recipient->city;
        $query['post_code'] = $recipient->post_code;
        $query['region'] = $recipient->region;
        $query['country_code'] = $recipient->country_code;
        $response = $this->client()->get('service_points', ['query' => $query]);
        $result = array();
        foreach (json_decode($response->getBody(), true) as $struct) {
            $result[] = new ServicePoint($struct);
        }
        return $result;
    }

    protected function client()
    {
        return $this->connection->client();
    }
}
