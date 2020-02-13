<?php

namespace App\Models;

use App\Core\App;

class Placement {

	public function getInitialQuestions() {
		return (
			App::get('database')
				->select(
					"`questaos`", "*", 
					"ORDER BY RAND()", 
					"LIMIT " . __LIMIT_QUESTIONS__
				)
		);
	}

	public function getQuestions($initialQuestions) {
		return (
			array_map(function ($questao) use ($initialQuestions) {
				$alternativas = 
				  App::get('database')
						->select(
						  "`questao_alternativas`", 
						  "id, resposta, alternativa", 
						  "WHERE questao_alternativas.questao_id = {$questao->id}",
						  "ORDER BY RAND()"
						);
		
				return array(
				  "id"           => $questao->id,
				  "enunciado"    => $questao->enunciado,
				  "respondida"   => 0,
				  "resposta"     => '',
				  "alternativas" => $alternativas
				);
			}, $initialQuestions)
		);
	}

	public function getQuestionAnswer($placementId, $question) {
		return (
			App::get('database')
				->select(
					"avaliacao_respostas", "*",
					"WHERE avaliacao_id = {$placementId} AND questao_id = {$question}"
				)
		);
	}

	public function getPlacement($placementId) {
		return (
			App::get('database')
					->select(
						"`avaliacaos`", "*",
						"WHERE `id` = '{$placementId}'"
					)[0]
		);
	}

	public function getUnity($city) {
		return (
			App::get('database')
					->select(
						"`unidades`", "id",
						"WHERE `cidadeUnidade` = '{$city}'"
					)[0]
		);
	}

	public function getPlacementId($placementKey) {
		$placementId = 
			App::get('database')
					->select(
						"`avaliacaos`", "id", 
						"WHERE `avaliacao_key` = '{$placementKey}'"
					);

		return (int) $placementId[0]->id;
	}

	public function getTotalHits($answers, $placementKey) {
		return (
			array_reduce(get_object_vars($answers), function ($carry , $answer) use ($placementKey) {
				$temp = 
				  App::get('database')
					->select(
					  "`avaliacaos`
						INNER JOIN `avaliacao_respostas` ON (`avaliacaos`.id = `avaliacao_respostas`.`avaliacao_id`)
						INNER JOIN `questaos` ON (`avaliacao_respostas`.`questao_id` = `questaos`.`id`)",
					  "1",
					  "WHERE `avaliacaos`.`avaliacao_key` = '{$placementKey}'
						AND `questaos`.`id` = {$answer->questionId} 
						AND `questaos`.`correta` = '{$answer->resposta}'"
					);
		
				if (!is_null($temp) && !empty($temp)) {
				  $carry += 1;
				}
		
				return $carry;
			})
		);
	}

	public function getPercentHits($total) {
		return (
			(($total * 100) / __LIMIT_QUESTIONS__)
		);
	}

	public function insertPlacement($name, $email, $phone, $country, $state, $city, $placement_key) {
		App::get('database')
			->insert(
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
	}

	public function insertInitialQuestions($initialQuestions, $placementId) {
		foreach ($initialQuestions as $question) {
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
	}

	public function insertOrUpdateQuestionAnswer($questionAnswer, $placementId, $answer, $question) {
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
	}

	public function updateResult($placementKey, $percent) {
		App::get('database')
			->update(
				array('resultado', 'updated_at'),
				array($percent, date('Y-m-d H:i:s')),
				'avaliacaos',
				"WHERE avaliacao_key = '{$placementKey}'"
			);
	}
}
