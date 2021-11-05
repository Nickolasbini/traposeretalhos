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

	// get the country data by a State ISO
    public function getCountryByState()
    {
        $stateISO = isset($_POST['stateISO']) ? strtoupper($_POST['stateISO']) : null;
        if(is_null($stateISO)){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('state iso is required'))
            ]);
        }
        $stateObj = new State();
        $state = $stateObj->getStateByIsoCode($stateISO);
        if(!$state){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('state iso is invalid'))
            ]);   
        }
        $country = $state->getCountry(true);
        if(!$state){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('state iso is invalid'))
            ]);   
        }
        $countryFullData = $country->getFullData();
        $translatedName = null;
        $languageISO = $_SESSION['userLanguage'];
        if(array_key_exists('translation', $countryFullData) && !is_null($countryFullData['translation'][$languageISO])){
        	$translatedName = $countryFullData['translation'][$languageISO];
        }
        return json_encode([
        	'success' => true,
        	'message' => ucfirst(translate('country found')),
        	'data'	  => $countryFullData,
        	'countryTranslation' => $translatedName
        ]);
    }
}