<?php

$autoload_class_map = [];
$base_dir = dirname(__DIR__);
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir)) as $file) {
  $file = $file->__toString();
  if (preg_match('~/[A-Z].*[.]php$~', $file)) {
    $base = str_replace('.php', '', str_replace($base_dir.'/', '', $file));
    $fqn = str_replace('/', '\\', $base);
    $autoload_class_map[strtolower($fqn)] = $file;
  }
}

spl_autoload_register(function($class) use ($autoload_class_map) {
  $class = strtolower($class);
  if (isset($autoload_class_map[$class])) {
    include($autoload_class_map[$class]);
  }
});
