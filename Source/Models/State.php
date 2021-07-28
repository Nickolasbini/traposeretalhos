<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Country;

/**
 * 
 */
class State extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('states', ['isoCode', 'country'], 'id', false);
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

	public function getStatesByCountry($countryId)
	{
		$statesObjArray = $this->find("country = :countryId", "countryId=$countryId")
		->fetch(true);
		return $statesObjArray;
	}

	public function getStateByIsoCode($isoCode, $countryISO = null)
	{
		$query = "isoCode = :isoCode";
		$parameters = "isoCode=$isoCode";
		if(!is_null($countryISO)){
			$countryObj = new Country();
			$country = $countryObj->getCountryByAlphaCode($countryISO);
			if(!is_null($country)){
				$query = "isoCode = :isoCode AND country = :countryId";
				$countryId = $country->getId();
				$parameters = "isoCode=$isoCode&countryId=$countryId";
			}
		}
		$stateObj = $this->find($query, $parameters)->limit(1)->fetch(true);
		return !is_null($stateObj) ? $stateObj[0] : null;
	}

	/**
     * Returns an array containing all data of country 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData($fetchCountry = false)
	{
		$response = [
			'id' 		  => $this->getId(),
			'isoCode'     => $this->getIsoCode(),
			'name' 		  => $this->getName(),
			'country'     => $this->getCountry($fetchCountry)
		];
		return $response;
	}
}