<?php
namespace Sendworks;

class Parcel
{
    public $id;
    public $height_in_cm;
    public $width_in_cm;
    public $length_in_cm;
    public $weight_in_g;
    public $calculated_weight_in_g;

    public function __construct($data = [])
    {
        foreach (['id', 'height_in_cm', 'width_in_cm', 'length_in_cm', 'weight_in_g', 'calculated_weight_in_g'] as $prop) {
            if (isset($data[$prop])) {
                $this->$prop = $data[$prop];
            }
        }
    }

    public function toHash()
    {
        return [
        'id' => $this->id,
        'height_in_cm' => $this->height_in_cm,
        'width_in_cm' => $this->width_in_cm,
        'length_in_cm' => $this->length_in_cm,
        'weight_in_g' => $this->weight_in_g,
        'calculated_weight_in_g' => $this->calculated_weight_in_g,
        ];
    }

    public static function import($mixed)
    {
        if ($mixed instanceof Parcel) {
            return $mixed;
        }
        return new self($mixed);
    }
}
