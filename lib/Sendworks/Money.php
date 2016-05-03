<?php
namespace Sendworks;

class Money
{
    public $cents;
    public $currency;

    public function __construct($data)
    {
        $this->cents = $data['cents'];
        $this->currency = $data['currency'];
    }

    public function toHash()
    {
        return [
        'cents' => $this->cents,
        'currency' => $this->currency,
        ];
    }

    public function toFloat()
    {
        return $this->cents / 100;
    }

    public static function import($mixed)
    {
        if (is_array($mixed)) {
            return new self($mixed);
        }
        if (preg_match('/^([A-Z]{3})\s+([,.0-9]+)$/', $mixed, $mm)) {
            $value = $mm[2];
            $currency = $mm[1];
        } elseif (preg_match('/^([,.0-9]+)\s+([A-Z]{3})$/', $mixed, $mm)) {
            $value = $mm[1];
            $currency = $mm[2];
        } else {
            return null;
        }
        return new self(['cents' => preg_replace('/[^0-9]/', '', $value) * 100, 'currency' => $currency]);
    }
}
