<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class Company extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('companies', ['companyName','companySlogan', 'score'], 'id', false);
	}
	// SETTERS
	public function setId($id){
		$this->id = $id;
	}
	public function setCompanyName($companyName){
		$this->companyName = $companyName;
	}
	public function setCompanySlogan($companySlogan){
		$this->companySlogan = $companySlogan;
	}
	public function setCompanyURL($companyURL){
		$this->companyURL = $companyURL;
	}
	public function setScore($score){
		$this->score = $score;
	}
	public function setCompanyLogo($companyLogo){
		$this->companyLogo = $companyLogo;
	}
	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCompanyName(){
		return $this->companyName;
	}
	public function getCompanySlogan(){
		return $this->companySlogan;
	}
	public function getCompanyURL(){
		return $this->companyURL;
	}
	public function getScore(){
		return $this->region;
	}
	public function getCompanyLogo(){
		return $this->region;
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