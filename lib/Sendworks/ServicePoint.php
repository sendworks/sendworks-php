<?php
namespace Sendworks;

class ServicePoint {
  public $reference;
  public $name;
  public $street1;
  public $street2;
  public $post_code;
  public $city;
  public $region;
  public $country_code;
  public $lat;
  public $lng;

  function __construct($data = []) {
    foreach (['reference', 'name', 'street1', 'street2', 'post_code', 'city', 'region', 'country_code', 'lat', 'lng'] as $prop) {
      if (isset($data[$prop])) {
        $this->$prop = $data[$prop];
      }
    }
  }

  function getTitle() {
    if ($this->name) {
      return implode(", ", array_filter(array($this->name, $this->street1, $this->post_code, $this->city)));
    }
    return $this->reference;
  }
}
