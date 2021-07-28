<?php

namespace Source\Controllers;

use Source\Models\Country;
use Source\Models\State;
use Source\Models\City;
use Source\Helpers\FunctionsClass;

/**
 * 
 */
class StateController{

	public function getStatesOfCountry($countryId = null)
	{
		if(is_null($countryId)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('country id is required'))
			]);
		}
		$stateObj = new State();
		$states = $stateObj->getStatesByCountry($countryId);
		$elements = [];
		$numberOfStates = 0;
		if(!is_null($states)){
			$numberOfStates = count($states);
			foreach($states as $state){
				$elements[] = $state->getFullData();
			}
		}
		return json_encode([
			'success' 		 => true,
			'content' 		 => $elements, 
			'numberOfStates' => $numberOfStates

		]);
	}

}