<?php 
spl_autoload_register(function ($class)
{

  // replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = __DIR__ . "/" . str_replace('\\', '/', $class) . '.php';

  // if the file exists, require it
  if (file_exists($file)) {
    require $file;
  }
});