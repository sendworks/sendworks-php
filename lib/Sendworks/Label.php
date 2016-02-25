<?php
namespace Sendworks;

class Label {
  protected $connection;
  public $filename;
  public $content;

  function __construct($data, $connection) {
    $this->connection = $connection;
    $this->filename = $data['filename'];
    $this->content = $data['content'];
  }

  function write($destination) {
    file_put_contents($destination, $this->content);
  }

  static function import($mixed, $connection = null) {
    if (is_array($mixed)) {
      return new self($mixed, $connection);
    }
    return new LabelRef($mixed, $connection);
  }
}