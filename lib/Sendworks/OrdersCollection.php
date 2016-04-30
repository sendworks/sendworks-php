<?php
namespace Sendworks;

class OrdersCollection
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function select()
    {
        $response = $this->client()->get('orders');
        $result = array();
        foreach (json_decode($response->getBody(), true) as $struct) {
            $result[] = Order::import($struct, $this->connection);
        }
        return $result;
    }

    public function save($order)
    {
        if ($order->id) {
            $path = "orders/" . $order->id;
        } else {
            $path = "orders";
        }
        $response = $this->client()->post($path, ['json' => $order->toHash()]);
        if ($response->getStatusCode() == 200) {
            return Order::import(json_decode($response->getBody(), true), $this->connection);
        }
    }

    public function fetch($mixed)
    {
        if ($mixed instanceof OrderRef) {
            $path = $mixed->url;
        } else {
            $path = "orders/$mixed";
        }
        $response = $this->client()->get($path);
        if ($response->getStatusCode() == 200) {
            return Order::import(json_decode($response->getBody(), true), $this->connection);
        }
    }

    public function delete($mixed)
    {
        if ($mixed instanceof OrderRef) {
            $path = $mixed->url;
        } else {
            $path = "orders/$mixed";
        }
        $response = $this->client()->delete($path);
        return $response->getStatusCode() == 200;
    }

    protected function client()
    {
        return $this->connection->client();
    }
}
