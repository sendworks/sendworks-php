<?php
namespace Sendworks;

class Carrier
{
    public $id;
    public $name;
    public function __construct($data = [])
    {
        foreach (['id', 'name'] as $prop) {
            if (isset($data[$prop])) {
                $this->$prop = $data[$prop];
            }
        }
    }
}
