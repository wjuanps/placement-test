<?php

namespace App\Controllers;

use App\Core\App;
use App\Models\Placement;

class PlacementController {

	protected $placement;

	public function __construct() {
		$this->placement = new Placement;
	}

	public function testYourEnglish() {
		return view('test-your-english');
	}

	public function store() {
		$name    = trim(strip_tags(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)));
		$email   = trim(strip_tags(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)));
		$phone   = trim(strip_tags(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING)));
		$country = trim(strip_tags(filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING)));
		$state   = trim(strip_tags(filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING)));
		$city    = trim(strip_tags(filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING)));
	
		$placement_key = __encrypt(time());
		try {
			$this->placement->insertPlacement($name, $email, $phone, $country, $state, $city, $placement_key);
	
			return view('redirect', \compact('placement_key'));
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function getPlacement() {
		$placementKey = trim(filter_input(INPUT_GET, 'placement', FILTER_SANITIZE_STRING));
		$placementKey = str_replace(" ", "+", $placementKey);

		$placementId = $this->placement->getPlacementId($placementKey);

		die(json_encode($placementId));
	}

	public function getQuestoes() {
		try {
			$initialQuestions = $this->placement->getInitialQuestions();
		
			$placementKey = trim(filter_input(INPUT_GET, 'placement', FILTER_SANITIZE_STRING));
			$placementKey = str_replace(" ", "+", $placementKey);
			
			$placementId = $this->placement->getPlacementId($placementKey);
		
			$this->placement->insertInitialQuestions($initialQuestions, $placementId);
		
			$questoes = $this->placement->getQuestions($initialQuestions);
		
			die(\json_encode($questoes));
		} catch (\Throwable $th) {
		  	throw $th;
		}
	}

	public function saveAnswer() {
		try {
			$placementKey = trim(filter_input(INPUT_POST, 'placement', FILTER_SANITIZE_STRING));
			$placementKey = str_replace(" ", "+", $placementKey);
		
			$question     = (int) trim(filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING));
			$answer       = (int) trim(filter_input(INPUT_POST, 'answer', FILTER_SANITIZE_STRING));
		
			$placementId    = $this->placement->getPlacementId($placementKey);
			$questionAnswer = $this->placement->getQuestionAnswer($placementId, $question);
		
			$this->placement->insertOrUpdateQuestionAnswer($questionAnswer, $placementId, $answer, $question);
		} catch (\Throwable $th) {
		  	dd($th);
		}
	}
	
	public function endPlacement() {
		try {
			$placementKey  = trim(filter_input(INPUT_POST, 'placement', FILTER_SANITIZE_STRING));
			$placementKey  = str_replace(" ", "+", $placementKey);
		
			$answers = json_decode(filter_input(INPUT_POST, 'answers'));
		
			$total   = $this->placement->getTotalHits($answers, $placementKey);
			$percent = $this->placement->getPercentHits($total);

			$this->placement->updateResult($placementKey, $percent);

			$placementId = $this->placement->getPlacementId($placementKey);
		
			return redirect("result?placement={$placementId}&total={$total}&percent={$percent}");
		} catch (\Throwable $th) {
		  	dd($th);
		}
	}

	public function showResult() {
		$placementId = (int) trim(strip_tags(filter_input(INPUT_GET, 'placement')));
		$total       = (int) trim(strip_tags(filter_input(INPUT_GET, 'total')));
		$percent     = (int) trim(strip_tags(filter_input(INPUT_GET, 'percent')));

		$placement = $this->placement->getPlacement($placementId);

		$unity = $this->placement->getUnity($placement->cidade);

		$unityId = is_null($unity) ? "38" : $unity->id;

		$url = "https://ur.really.education/matriculas?t=0&u={$unityId}";

		return view('result', \compact('url', 'percent', 'total'));
	}
}
