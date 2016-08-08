<?php
namespace Sendworks;

class ProductsCollection
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function select($recipient = null, $order = null)
    {
        $query = [];
        if ($recipient) {
            $query['post_code'] = $recipient->post_code;
            $query['country_code'] = $recipient->country_code;
        }
        if ($order && $order->subtotal) {
            $query['order_value'] = $order->subtotal->toHash();
        }
        if ($order && $order->weight_in_g) {
            $query['weight_in_g'] = $order->weight_in_g;
        }
        $response = $this->client()->get('products', ['query' => $query]);
        $result = array();
        foreach (json_decode($response->getBody(), true) as $struct) {
            $result[] = new Product($struct, $this->connection);
        }
        return $result;
    }

    protected function client()
    {
        return $this->connection->client();
    }
}
