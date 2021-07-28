<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class Language extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('languages', ['name','isoCode'], 'id', false);
	}
	// SETTERS
	public function setName($name){
		$this->name = $name;
	}
	public function setIsoCode($isoCode){
		$this->isoCode = $isoCode;
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
	public function getLanguages(){
		return $this->languages;
	}

    // Documnet me
    public function getLanguageByIsoCode($isoCode)
    {
    	$languageObj = $this->find("isoCode = '$isoCode'")
		->limit(1)
		->fetch(true);
		return $languageObj ? $languageObj[0] : null;
    }

    /**
     * Returns an array containing all data of language 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData()
	{
		$response = [
			'id' 	  => $this->getId(),
			'name' 	  => $this->getName(),
			'isoCode' => $this->getIsoCode(),
		];
		return $response;
	}
}