<?php
namespace Sendworks;

class Order
{
    protected $connection;
    public $id;
    public $order_reference;
    public $shop_system;
    public $customer_number;
    public $recipient;
    public $billing_contact;
    public $product;
    public $service_point_reference;
    public $items;
    public $subtotal;
    public $shipping_cost;
    public $tax_value;
    public $shipments;
    public $weight_in_g;

    public function __construct($data = [], $connection = null)
    {
        $this->connection = $connection;
        foreach (['id', 'order_reference', 'shop_system', 'customer_number', 'service_point_reference', 'weight_in_g'] as $prop) {
            if (isset($data[$prop])) {
                $this->$prop = $data[$prop];
            }
        }
        foreach (['recipient', 'billing_contact'] as $prop) {
            $this->$prop = isset($data[$prop]) ? new Address($data[$prop]) : null;
        }
        foreach (['subtotal', 'shipping_cost', 'tax_value'] as $prop) {
            $this->$prop = isset($data[$prop]) ? Money::import($data[$prop]) : null;
        }
        $this->product = isset($data['product']) ? new Product($data['product'], $this->connection) : null;
        if (isset($data['items'])) {
            $this->items = [];
            foreach ($data['items'] as $item_data) {
                $this->items[] = new Item($item_data);
            }
        }
        if (isset($data['shipments'])) {
            $this->shipments = [];
            foreach ($data['shipments'] as $url) {
                $this->shipments[] = Shipment::import($url, $this->connection);
            }
        }
    }

    public function save()
    {
        return $this->connection->orders->save($this);
    }

    public function delete()
    {
        return $this->connection->orders->delete($this);
    }

    public function toHash()
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->toHash();
        }
        return [
        'id' => $this->id,
        'order_reference' => $this->order_reference,
        'customer_number' => $this->customer_number,
        'service_point_reference' => $this->service_point_reference,
        'weight_in_g' => $this->weight_in_g,
        'recipient' => $this->recipient ? $this->recipient->toHash() : null,
        'billing_contact' => $this->billing_contact ? $this->billing_contact->toHash() : null,
        'product' => $this->product ? ['code' => $this->product->code] : null,
        'items' => $items
        ];
    }

    public static function import($mixed, $connection = null)
    {
        if (is_string($mixed)) {
            return new OrderRef($mixed, $connection);
        }
        return new self($mixed, $connection);
    }
}
