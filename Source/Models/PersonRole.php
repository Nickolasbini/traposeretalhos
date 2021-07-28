<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Role;
use Source\Models\PersonalPage;

/**
 * 
 */
class PersonRole extends DataLayer
{
	const BASE_URL = 'person/personalpage/id';

	function __construct()
	{
		parent::__construct('personroles', ['role','person'], 'id', false);
	}

	// SETTERS
	public function setRole($role){
		$this->role = $role;
	}
	public function setScore($score){
		$this->score = $score;
	}
	public function setPerson($person){
		$this->person = $person;
	}
	public function setPersonalPage($personalPage){
		$this->personalPage = $personalPage;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getRole($asObject = false){
		if($asObject){
			$roleObj = (new Role())->findById($this->role);
			return $roleObj;
		}
		return $this->role;
	}
	public function getScore(){
		return $this->score;
	}
	public function getPerson($asObject = false){
		if($asObject){
			$personObj = (new Person())->findById($this->person);
			return $personObj;
		}
		return $this->person;
	}
	public function getPersonalPage($asObject = false){
		if($asObject){
			$personalPageObj = (new PersonalPage())->findById($this->personalPage);
			return $personalPageObj;
		}
		return $this->personalPage;
	}

	public function getPersonByPersonalPage($personalPageId)
	{
		$personObj = $this->find("personalPage = :personalPage", "personalPage=$personalPageId")
		->limit(1)
		->fetch(true);
		return $personObj ? $personObj[0] : null;
	}

	public function getTotal($roleId)
	{
		$total = $this->find("role = :role", "role=$roleId")->count();
		return $total;
	}

	// returns this $personId correspondent PersonRole obj
	public function getPersonRoleByPerson($personId = null)
	{
		$personRoleObj = $this->find("person = :personId", "personId=$personId")
		->limit(1)
		->fetch(true);
		return $personRoleObj ? $personRoleObj[0] : null;
	}
}