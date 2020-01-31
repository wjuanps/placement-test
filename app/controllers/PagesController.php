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

  public function store() {
    $name    = trim(strip_tags(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)));
    $email   = trim(strip_tags(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)));
    $phone   = trim(strip_tags(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING)));
    $country = trim(strip_tags(filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING)));
    $state   = trim(strip_tags(filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING)));
    $city    = trim(strip_tags(filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING)));

    $placement_key = __encrypt(time());

    App::get('database')->insert(
      'avaliacaos',
      array(
        'email'           => $email,
        'avaliacao_key'   => $placement_key,
        'nome'            => $name,
        'data_nascimento' => null,
        'whatsapp'        => $phone,
        'pais'            => \explode('_', $country)[0],
        'cidade'          => \explode('_', $city)[0],
        'estado'          => \explode('_', $state)[0],
        'resultado'       => -1,
        'created_at'      => date('Y-m-d H:i:s'),
        'updated_at'      => date('Y-m-d H:i:s')
      )
    );

    return view('redirect', \compact('placement_key'));
  }

  public function test() {
    return view('test-your-english');
  }

  public function getQuestoes() {
    try {
      $questionsTemp = App::get('database')->select("`questaos`", "*", "ORDER BY RAND()", "LIMIT " . __LIMIT_QUESTIONS__);
      $placementKey  = trim(filter_input(INPUT_GET, 'placement', FILTER_SANITIZE_STRING));
      $placementKey  = str_replace(" ", "+", $placementKey);
      
      $placementId = App::get('database')->select("`avaliacaos`", "id", "WHERE `avaliacao_key` = '{$placementKey}'");
      $placementId = (int) $placementId[0]->id;

      foreach ($questionsTemp as $question) {
        App::get('database')->insert(
          "avaliacao_questaos",
          array(
            "avaliacao_id" => $placementId,
            "questao_id"   => $question->id,
            "created_at"   => date('Y-m-d H:i:s'),
            "updated_at"   => date('Y-m-d H:i:s')
          )
        );
      }

      $questoes = array_map(function ($questao) {
        $alternativas = 
          App::get('database')
                ->select(
                  "`questao_alternativas`", 
                  "id, resposta, alternativa", 
                  "where questao_alternativas.questao_id = {$questao->id}",
                  "order by rand()"
                );

        return array(
          "id"           => $questao->id,
          "enunciado"    => $questao->enunciado,
          "respondida"   => 0,
          "resposta"     => '',
          "alternativas" => $alternativas
        );
      }, $questionsTemp);

      die(\json_encode($questoes));
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function saveAnswer() {
    try {
      $placement = trim(filter_input(INPUT_POST, 'placement', FILTER_SANITIZE_STRING));
      $question  = (int) trim(filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING));
      $answer    = (int) trim(filter_input(INPUT_POST, 'answer', FILTER_SANITIZE_STRING));

      $placementId = App::get('database')->select('`avaliacaos`', 'id', "WHERE avaliacao_key = '{$placement}'");
      $placementId = (int) $placementId[0]->id;

      $questionAnswer = App::get('database')->select(
        "avaliacao_respostas", "*",
        "WHERE avaliacao_id = {$placementId} AND questao_id = {$question}"
      );

      if (is_null($questionAnswer) || empty($questionAnswer)) {
        App::get('database')->insert(
          "avaliacao_respostas",
          array(
            "avaliacao_id" => $placementId,
            "questao_id" => $question,
            "questao_alternativas_id" => $answer,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
          )
        );
      } else {
        App::get('database')->update(
          array('questao_alternativas_id', 'updated_at'),
          array($answer, date('Y-m-d H:i:s')),
          'avaliacao_respostas',
          "WHERE avaliacao_id = {$placementId} AND questao_id = {$question}"
        );
      }
    } catch (\Throwable $th) {
      dd($th);
    }
  }

  public function endPlacement() {
    try {
      $placement  = trim(filter_input(INPUT_POST, 'placement', FILTER_SANITIZE_STRING));
      $placement  = str_replace(" ", "+", $placement);

      $answers   = json_decode(filter_input(INPUT_POST, 'answers'));

      $total = array_reduce(get_object_vars($answers), function ($carry , $answer) use ($placement) {
        $temp = 
          App::get('database')
            ->select(
              "`avaliacaos`
                INNER JOIN `avaliacao_respostas` ON (`avaliacaos`.id = `avaliacao_respostas`.`avaliacao_id`)
                INNER JOIN `questaos` ON (`avaliacao_respostas`.`questao_id` = `questaos`.`id`)",
              "1",
              "WHERE `avaliacaos`.`avaliacao_key` = '{$placement}'
                AND `questaos`.`id` = {$answer->questionId} 
                AND `questaos`.`correta` = '{$answer->resposta}'"
            );

        if (!is_null($temp) && !empty($temp)) {
          $carry += 1;
        }

        return $carry;
      });

      $percent = (($total * 100) / __LIMIT_QUESTIONS__);

      App::get('database')->update(
        array('resultado', 'updated_at'),
        array($percent, date('Y-m-d H:i:s')),
        'avaliacaos',
        "WHERE avaliacao_key = '{$placement}'"
      );

      return redirect("result?total={$total}&percent={$percent}");
    } catch (\Throwable $th) {
      dd($th);
    }
  }

  public function showResult() {
    $total   = (int) trim(strip_tags(filter_input(INPUT_GET, 'total')));
    $percent = (int) trim(strip_tags(filter_input(INPUT_GET, 'percent')));

    return view('result', \compact('percent', 'total'));
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
