<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class Role extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('roles', ['roleName','dateOfCreation'], 'id', false);
	}

	// SETTERS
	public function setRoleName($roleName){
		$this->roleName = $roleName;
	}
	public function setDescription($description){
		$this->description = $description;
	}
	public function setIconUrl($iconUrl){
		$this->iconUrl = $iconUrl;
	}
	public function setDateOfCreation($dateOfCreation){
		$this->dateOfCreation = $dateOfCreation;
	}
	public function setColorOnMap($colorOnMap){
		$this->colorOnMap = $colorOnMap;
	}
	public function setIsUsedOnMap($isUsedOnMap){
		$this->isUsedOnMap = $isUsedOnMap;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getRoleName(){
		return $this->roleName;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getIconUrl(){
		return $this->iconUrl;
	}
	public function getDateOfCreation(){
		return $this->dateOfCreation;
	}
	public function getColorOnMap(){
		return $this->colorOnMap;
	}
	public function getIsUsedOnMap(){
		return $this->isUsedOnMap;
	}

	/**
     * Returns an array with all avaliable roles constants 
     * @version 1.0 - 20210406
     * @return <array> keys: 'constantRoleName' => 'constantRoleValue' 
     */
	public function getAllRoles()
	{
		return $roles = $this->find()->fetch(true);
	}

	/**
     * Returns an array containing all data of role 
     * @version 1.0 - 20210605
     * @return <array> of Role data 
     */
	public function getFullData()
	{
		$response = [
			'id' 		     => $this->getId(),
			'roleName'       => $this->getRoleName(),
			'description'    => $this->getDescription(),
			'iconURL' 	     => $this->getIconURL(),
			'dateOfCreation' => $this->getDateOfCreation(),
			'colorOnMap'	 => $this->getColorOnMap()
		];
		return $response;
	}
}