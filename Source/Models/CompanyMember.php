<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class CompanyMember extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('companyMember', ['companyUnit','company', 'person'], 'id', false);
	}
	// SETTERS
	public function setId($id){
		$this->id = $id;
	}
	public function setCompanyUnit($companyUnit){
		$this->companyUnit = $companyUnit;
	}
	public function setCompany($company){
		$this->company = $company;
	}
	public function setPerson($person){
		$this->person = $person;
	}
	public function setIsOwner($isOwner){
		$this->isOwner = $isOwner;
	}
	public function setIsAdmin($isAdmin){
		$this->isAdmin = $isAdmin;
	}
	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCompanyUnity(){
		return $this->companyUnit;
	}
	public function getCompany(){
		return $this->company;
	}
	public function getPerson(){
		return $this->person;
	}
	public function getIsOwner(){
		return $this->isOwner;
	}
	public function getIsAdmin(){
		return $this->isAdmin;
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