<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class CompanyUnit extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('companyUnits', ['company','country', 'country', 'unitName'], 'id', false);
	}
	// SETTERS
	public function setId($id){
		$this->id = $id;
	}
	public function setCompany($company){
		$this->company = $company;
	}
	public function setCountry($country){
		$this->country = $country;
	}
	public function setUnitName($unitName){
		$this->unitName = $unitName;
	}
	public function setUnitAddress($unitAddress){
		$this->unitAddress = $unitAddress;
	}
	public function setUnitCEP($unitCEP){
		$this->unitCEP = $unitCEP;
	}
	public function setUnitAddressNumber($unitAddressNumber){
		$this->unitAddressNumber = $unitAddressNumber;
	}
	public function setScore($score){
		$this->score = $score;
	}
	public function setUnitURL($unitURL){
		$this->unitURL = $unitURL;
	}
	public function setIsCompanyHeadquarter($isCompanyHeadquarter){
		$this->isCompanyHeadquarter = $isCompanyHeadquarter;
	}
	public function setLocation($location){
		$this->location = $location;
	}
	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCompany(){
		return $this->company;
	}
	public function getCountry(){
		return $this->country;
	}
	public function getUnitName(){
		return $this->unitName;
	}
	public function getUnitAddress(){
		return $this->unitAddress;
	}
	public function getUnitCEP(){
		return $this->unitCEP;
	}
	public function getAddressNumber(){
		return $this->unitAddressNumber;
	}
	public function getScore(){
		return $this->score;
	}
	public function getUnitURL(){
		return $this->unitURL;
	}
	public function getIsCompanyHeadquarter(){
		return $this->isCompanyHeadquarter;
	}
	public function getLocation(){
		return $this->location;
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
		return $response;
	}
}