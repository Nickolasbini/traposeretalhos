<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\CountryLanguage;
use Source\Models\Translation;

/**
 * 
 */
class Country extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('countries', ['name','alphaCode2'], 'id', false);
	}
	// SETTERS
	public function setName($name){
		$this->name = $name;
	}
	public function setAlphaCode2($alphaCode2){
		$this->alphaCode2 = $alphaCode2;
	}
	public function setAlphaCode3($alphaCode3){
		$this->alphaCode3 = $alphaCode3;
	}
	public function setRegion($region){
		$this->region = $region;
	}
	public function setTranslation($translation){
		$this->translation = $translation;
	}
	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getAlphaCode2(){
		return $this->alphaCode2;
	}
	public function getAlphaCode3(){
		return $this->alphaCode3;
	}
	public function getRegion(){
		return $this->region;
	}
	public function getTranslation($asObject = null){
		if($asObject){
			if(!$this->translation){
				return null;
			}
			$translationObj = (new Translation)->findById($this->translation);
			return $translationObj;
		}
		return $this->translation;
	}

	// gets this country languages
	public function getCountryLanguages($justThisFields = [])
	{
		$countryLanguageObj = new CountryLanguage();
		$countryLanguagesObj = $countryLanguageObj->getCountryLanguages($this->getId());
		if(empty($justThisFields))
			return $countryLanguagesObj;
		$objectData = [];
		foreach($countryLanguagesObj as $language){
			$languageValues = [];
			foreach($justThisFields as $attributeName){
				$getMethod = 'get'.ucfirst($attributeName);

				$valueGot = $language->{$getMethod}();
				if(is_null($valueGot))
					continue;
				$languageValues[] = [$valueGot];
			}
			$objectData[] = $languageValues;
		}
		return $objectData;
	}

	// Document me
	public function getCountryByAlphaCode($alphaCode = '')
	{
		$countryObj = $this->find("alphaCode2 = '$alphaCode' OR alphaCode3 = '$alphaCode'")
		->limit(1)
		->fetch(true);
		return $countryObj ? $countryObj[0] : null;
	}

	/**
     * Returns an array containing all data of country 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData()
	{
		$response = [
			'id' 		  => $this->getId(),
			'name' 		  => $this->getName(),
			'alphaCode2'  => $this->getAlphaCode2(),
			'alphaCode3'  => $this->getAlphaCode3(),
			'region' 	  => $this->getRegion()
		];
		$translation = $this->getTranslation(true);
		$translationData = null;
		if($translation){
			$translationData = $translation->getFullData();
		}
		$response['translation'] = $translationData;
		return $response;
	}
}