<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class CountryLanguage extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('countrylanguages', ['country','language'], 'id', false);
	}
	// SETTERS
	public function setCountry($country){
		$this->country = $country;
	}
	public function setLanguage($language){
		$this->language = $language;
	}
	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCountry(){
		return $this->country;
	}
	public function getLanguage(){
		return $this->language;
	}

	public function isInUse()
	{
		$countryLanguageObj = $this->find("country = '$this->getCountry()' && language = '$this->getLanguage()'")
		->fetch(true);
		return !empty($countryLanguageObj) ? $countryLanguageObj : null;
	}

	public function getCountryLanguages($countryId)
	{
		$countryLanguagesObj = $this->find("country = :country", "country=$countryId")
		->fetch(true);
		return !empty($countryLanguagesObj) ? $countryLanguagesObj : null;
	}
}