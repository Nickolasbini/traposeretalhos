<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Company;

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
	public function setUnitWebSiteURL($unitWebSiteURL){
		$this->unitWebSiteURL = $unitWebSiteURL;
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
	public function getCompany($asObject = false){
		if($asObject){
			$companyObj = (new Company())->findById($this->company);
			return $companyObj;
		}
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
	public function getUnitWebSiteURL(){
		return $this->unitWebSiteURL;
	}
	public function getIsCompanyHeadquarter(){
		return $this->isCompanyHeadquarter;
	}
	public function getLatitude(){
		return $this->latitude;
	}
	public function getLongitude(){
		return $this->longitude;
	}

	/**
     * Returns an array containing all data of companyUnit 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData()
	{
		$response = [
			'id' 		    => $this->getId(),
			'logoWebPath' 	=> $this->getLogoWebPath(),
			'companyName'   => $this->getCompanyName(),
			'companySlogan' => $this->getCompanySlogan(),
			'companyURL' 	=> $this->getCompanyURL(),
			'score' 	    => $this->getScore(),
			'document' 	    => $this->getDocument(),
		];
		return $response;
	}

	// gets company by its unit and return all data related to the company as a whole
	public function gatherCompaniesData($companyUnitId = null)
	{
		$companyUnit = $this->findById($companyUnitId);
		if(is_null($companyUnit))
			return null;
		$company =  $companyUnit->getCompany(true);
		if(is_null($company))
			return null;
		$companyData = $company->getFullData();
		$elements = [
			'companyName'   => $companyData['companyName'],
			'logoWebPath'   => $companyData['logoWebPath'],
			'companySlogan' => $companyData['companySlogan'],
			'companyURL'    => $companyData['companyURL'],
			'companyScore'  => $companyData['score'],
			'unitName' 		=> $companyUnit->getUnitName(),
			'unitScore'     => $companyUnit->getScore(),
			'latitude'		=> $companyUnit->getLatitude(),
			'longitude'		=> $companyUnit->getLongitude()
		];
		return $elements;
	}
}