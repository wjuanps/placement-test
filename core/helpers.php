<?php

function view($name, $data = []) {
  extract($data);

  return require_once "app/views/{$name}.view.php";
}

function redirect($path) {
  header("Location: {$path}");
}

function dd($data) {
  echo '<pre>';
    die(var_dump($data));
  echo '</pre>';
}
