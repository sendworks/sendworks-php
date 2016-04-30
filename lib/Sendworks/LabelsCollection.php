<?php
namespace Sendworks;

class LabelsCollection {
  protected $connection;

  function __construct($connection) {
    $this->connection = $connection;
  }

  function fetch($mixed) {
    if ($mixed instanceOf LabelRef) {
      $path = $mixed->url;
    } else {
      $path = "labels/$mixed";
    }
    $response = $this->client()->get($path);
    if ($response->getStatusCode() == 200) {
      $content_disposition = $response->getHeader('Content-Disposition');
      $filename = null;
      if (preg_match('/filename="([^"]+)"/', $content_disposition[0], $mm)) {
        $filename = $mm[1];
      }
      if ($filename) {
        return Label::import(['filename' => $filename, 'content' => $response->getBody()], $this->connection);
      }
    }
  }

  protected function client() {
    return $this->connection->client();
  }
}
