<?php
namespace Sendworks;

class LabelRef {
  protected $connection;
  protected $label;
  public $url;
  function __construct($url, $connection = null) {
    $this->connection = $connection;
    $this->url = $url;
  }

  function __get($prop) {
    return $this->resolve()->$prop;
  }

  function __call($fn, $args) {
    return call_user_func_array([$this->resolve(), $fn], $args);
  }

  function resolve() {
    if (!$this->label) {
      $this->label = $this->connection->labels->fetch($this);
    }
    return $this->label;
  }

  function toHash() {
    return [
      'url' => $this->url
    ];
  }
}
