<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Country;
use Source\Models\State;

/**
 * 
 */
class City extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('cities', ['name', 'isoCode', 'state', 'country'], 'id', false);
	}
	// SETTERS
	public function setName($name){
		$this->name = $name;
	}
	public function setIsoCode($isoCode){
		$this->isoCode = $isoCode;
	}
	public function setCountry($country){
		$this->country = $country;
	}
	public function setState($state){
		$this->state = $state;
	}
	public function setCoordinates($coordinates){
		$this->coordinates = $coordinates;
	}
	public function setRegionalName($regionalName){
		$this->regionalName = $regionalName;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getIsoCode(){
		return $this->isoCode;
	}
	public function getCountry($asObject = true){
		if($asObject){
			$countryObj = (new Country())->findById($this->country);
			return $countryObj;
		}
		return $this->country;
	}
	public function getState($asObject = true){
		if($asObject){
			$countryObj = (new State())->findById($this->state);
			return $countryObj;
		}
		return $this->state;
	}
	public function getCoordinates($asLatitudeAndLongitude = true){
		if($asLatitudeAndLongitude){
			$coordinates = $this->coordinates;
			 return $this->formateDegreeCoordinatesToMapCoordinates($coordinates);
		}
		return $this->coordinates;
	}
	public function getRegionalName(){
		return $this->regionalName;
	}

	// get city latitude and longitude to be used at goole maps
	public function formateDegreeCoordinatesToMapCoordinates($coordinatesAsDegree)
	{
		$coordinatesArray = explode(' ', $coordinatesAsDegree);
		$formatedCoordinates = '';
		foreach($coordinatesArray as $position){
			if($position[0] == '0')
				$position = substr($position, 1);
			if(strlen($position) == 5){
				$degress  = $position[0].$position[1];
				$minutes  = $position[2].$position[3];
				$operator = $position[4];
				$seconds  = 0;
			}else{
				$degress  = $position[0].$position[1];
				$minutes  = $position[2].$position[3];
				$seconds  = substr($minutes, 3, strlen($position - 2));
				$operator = $position[strlen($position) - 1];
			}
			$newCoordinates = $degress + ($minutes / 60) + ($seconds / 3600);
			if($operator == 'S' || $operator == 'W')
				$newCoordinates = '-'.$newCoordinates;
			$formatedCoordinates .= empty($formatedCoordinates) ? $newCoordinates.' '
								        					    : $newCoordinates;
		}
		return $formatedCoordinates;
	}

	// document me
	public function getCitiesByCountry($countryId, $asArray = false)
	{
		$citiesObjArray = $this->find("country = :countryId", "countryId=$countryId")
		->fetch(true);
		return $citiesObjArray;
	}

	public function getAllStatesAndCitiesOfCountry($countryId = null)
	{
		$cities = $this->getCitiesByCountry($countryId);
		$stateObj = new State();
		$states = $stateObj->getStatesByCountry($countryId);
		$countryObj = new Country();
		$country = $countryObj->findById($countryId);
		$response = [
			'cities'  => $cities,
			'states'  => $states,
			'country' => $country
		];
		return $response;
	}

	/**
     * Returns an array containing all data of city 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData($fetchObjects = true, $coordinatesAsLatitudeAndLongitude = true)
	{
		$response = [
			'id' 		  => $this->getId(),
			'isoCode'     => $this->getIsoCode(),
			'name' 		  => $this->getName(),
			'state'       => $fetchObjects ? $this->getState(true)->getFullData() : $this->getState(),
			'country'     => $fetchObjects ? $this->getCountry(true)->getFullData() : $this->getCountry(),
			'coordinates' => $this->getCoordinates($coordinatesAsLatitudeAndLongitude),
			'regionName'  => $this->getRegionalName()
		];
		return $response;
	}

	public function getCityByName($cityName)
	{
		$citiesObj = $this->find("name = :cityName", "cityName=$cityName")->limit(1)->fetch();
		return $citiesObj;
	}

	public function getAllCountryCities($countryId = null)
	{

		$cities = $this->getCitiesByCountry($countryName);
		return $cities;
	}

	public function getCitiesByState($stateId = null)
	{
		$citiesObj = $this->find("state = :stateId", "stateId=$stateId")->fetch(true);
		return $citiesObj;
	}
}