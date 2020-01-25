<?php

namespace App\Controllers;

use App\Core\App;

class PagesController {

  public function home() {
    return view('home');
  }

  public function test() {

    $temp = App::get('database')->select("`questaos`", "*", "order by rand()");

    $questoes = array_map(function ($questao) {
      $alternativas = 
        App::get('database')
              ->select(
                "`questao_alternativas`", 
                "id, resposta", 
                "where questao_alternativas.questao_id = {$questao->id}",
                "order by rand()"
              );

      return array(
        "id" => $questao->id,
        "enunciado" => $questao->enunciado,
        "alternativas" => $alternativas
      );
    }, $temp);

    // die(\json_encode($array));

    return view('test', compact('questoes'));
  }
}
