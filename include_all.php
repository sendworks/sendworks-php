<?php

$base_dir = __DIR__ . "/lib";
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir)) as $path) {
  $path = $path->__toString();
  if (preg_match('~/[A-Z][^/]*[.]php$~', $path)) {
    require_once($path);
  }
}
