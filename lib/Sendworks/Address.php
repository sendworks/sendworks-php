<?php
namespace Sendworks;

class Address {
  public $id;
  public $residential = true;
  public $name;
  public $street1;
  public $street2;
  public $post_code;
  public $city;
  public $region;
  public $country_code;
  public $phone;
  public $email;
  public $lat;
  public $lng;

  function __construct($data = []) {
    foreach (['id', 'residential', 'name', 'street1', 'street2', 'post_code', 'city', 'region', 'country_code', 'phone', 'email', 'lat', 'lng'] as $prop) {
      if (isset($data[$prop])) {
        $this->$prop = $data[$prop];
      }
    }
  }

  function toHash() {
    return [
      'id' => $this->id,
      'residential' => $this->residential,
      'name' => $this->name,
      'street1' => $this->street1,
      'street2' => $this->street2,
      'post_code' => $this->post_code,
      'city' => $this->city,
      'region' => $this->region,
      'country_code' => $this->country_code,
      'phone' => $this->phone,
      'email' => $this->email,
      'lat' => $this->lat,
      'lng' => $this->lng
     ];
  }

  static function import($mixed) {
    if ($mixed instanceOf Address) {
      return $mixed;
    }
    return new self($mixed);
  }
}