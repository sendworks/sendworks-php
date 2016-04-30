<?php
namespace Sendworks;

class Item
{
    public $id;
    public $description;
    public $sku;
    public $weight_in_g;
    public $quantity;
    public $value;

    function __construct($data = [])
    {
        foreach (['id', 'description', 'sku', 'weight_in_g', 'quantity'] as $prop) {
            if (isset($data[$prop])) {
                $this->$prop = $data[$prop];
            }
        }
        $this->value = isset($data['value']) ? Money::import($data['value']) : null;
    }

    function toHash()
    {
        return [
        'id' => $this->id,
        'description' => $this->description,
        'sku' => $this->sku,
        'weight_in_g' => $this->weight_in_g,
        'quantity' => $this->quantity,
        'value' => $this->value ? $this->value->toHash() : null,
        ];
    }
}
