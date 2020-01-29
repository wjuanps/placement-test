<?php

define('__KEY', 'ZnlLPaZCe04LX-23OBh8wfG0ie-2ksFkYeNAqdaGf46-aGTS9iUgh');
define('__METHOD', 'AES-256-CBC');

define('__LIMIT_QUESTIONS__', 50);

function __encrypt(string $data, string $key = __KEY, string $method = __METHOD): string {
  try {
      $ivSize = openssl_cipher_iv_length($method);
      $iv     = openssl_random_pseudo_bytes($ivSize);

      $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
      $encrypted = base64_encode($iv . $encrypted);

      return $encrypted;
  } catch (\Throwable $th) {
      return false;
  }
  return false;
}

function __decrypt(string $data, string $key = __KEY, string $method = __METHOD): string {
  try {
      $data   = base64_decode($data);
      $ivSize = openssl_cipher_iv_length($method);
      $iv     = substr($data, 0, $ivSize);
      $data   = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

      return $data;
  } catch (\Throwable $th) {
      return false;
  }
  return false;
}

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
