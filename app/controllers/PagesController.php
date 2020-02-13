<?php

namespace App\Controllers;

use App\Core\App;

class PagesController {

  public function home() {
    return view('home');
  }

  public function register() {
    return view('register');
  }

  public function getGeoNames() {
    try {
      $gid = utf8_decode(trim(strip_tags(filter_input(INPUT_GET, 'gid', FILTER_SANITIZE_STRING))));
      $url = "http://api.geonames.org/childrenJSON?geonameId={$gid}&maxRows=1000&username=wjuan&featureCode=ADM2";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $responseJSON = curl_exec($ch);

      if ($responseJSON == false) {
        die(curl_error($ch));
      }

      curl_close($ch);

      die($responseJSON);
    } catch (\Throwable $th) {
      dd('Erro');
    }
  }
}
