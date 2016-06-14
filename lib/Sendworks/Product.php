<?php
namespace Sendworks;

class Product
{
    protected $connection;
    public $code;
    public $name;
    public $carrier;
    public $service_points = [];
    public function __construct($data = [], $connection = null)
    {
        $this->connection = $connection;
        foreach (['code', 'name'] as $prop) {
            if (isset($data[$prop])) {
                $this->$prop = $data[$prop];
            }
        }
        if (isset($data['carrier'])) {
            $this->carrier = new Carrier($data['carrier']);
        }
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

    public static function import($mixed, $connection = null)
    {
        if ($mixed instanceof Product) {
            return $mixed;
        }
        return new self($mixed, $connection);
    }
}
