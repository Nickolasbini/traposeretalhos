<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class Company extends DataLayer
{
	const BASE_URL = 'company/personalpage/identification';

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
	public function setLogoWebPath($logoWebPath){
		$this->logoWebPath = $logoWebPath;
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
		return $this->score;
	}
	public function getLogoWebPath(){
		return $this->logoWebPath;
	}

	/**
     * Returns an array containing all data of Company 
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
}