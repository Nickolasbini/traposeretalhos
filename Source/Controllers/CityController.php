<?php

namespace Source\Controllers;

use Source\Models\City;
use Source\Models\State;
use Source\Models\Country;

/**
 * 
 */
class CityController
{
	public function getAllCountryCities()
	{
		$countryObj = new Country();
		$countryISO = isset($_SESSION['userCountry']) ? $_SESSION['userCountry'] : null;
		if(is_null($countryISO)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('it was not possible to get your specific location'))
			]);
		}
		$country = $countryObj->getCountryByAlphaCode($countryISO);
		if(is_null($country)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('it was not possible to get your specific location'))
			]);
		}
		$cityObj = new City();
		$results = $cityObj->getAllStatesAndCitiesOfCountry($country->getId());
		$elements = [];
		if(count($results) > 0){
			$cityArray = $results['cities'];
			foreach($cityArray as $city){
				$elements['cities'][] = [
					'id'      => $city->getId(),
					'name'    => $city->getName(),
					'country' => $city->getCountry(false),
					'state'   => $city->getState(false)
				];
			}
			$statesArray = $results['states'];
			foreach($statesArray as $state){
				$elements['states'][] = [
					'id'      => $state->getId(),
					'name'    => $state->getName(),
					'country' => $state->getCountry(false)
				];
			}
			$country = $results['country'];
			$elements['country'][] = [
				'id'   => $country->getId(),
				'name' => $country->getName()
			];

		}
		exit(json_encode($elements));
	}

	public function getCitiesOfState($stateId = null)
	{
		if(is_null($stateId)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('state id is required'))
			]);
		}
		$cityObj = new City();
		$cities = $cityObj->getCitiesByState($stateId);
		$elements = [];
		$numberOfStates = 0;
		if(!is_null($cities)){
			$numberOfCities = count($cities);
			foreach($cities as $city){
				$elements[] = $city->getFullData(false, false);
			}
		}
		return json_encode([
			'success' 		 => true,
			'content' 		 => $elements, 
			'numberOfCities' => $numberOfCities

		]);
	}

	public function getByName()
	{
		$cityName = isset($_POST['cityName']) ? $_POST['cityName'] : null;
		if(!$cityName){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('a city name is required'))
			]);
		}
		$cityObj = new City();
		$city = $cityObj->getCityByName($cityName);
		if(!$city){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('no city found'))
			]);	
		}
		$element = $city->getFullData();
		return json_encode([
			'success' => true,
			'data'	  => $element
		]);
	}
}