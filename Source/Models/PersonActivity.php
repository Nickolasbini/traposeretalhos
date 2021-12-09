<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Person;
use DateTime;

/**
 * 
 */
class PersonActivity extends DataLayer
{
	function __construct()
	{
		parent::__construct('personActivity', ['person','lastEntryTime'], 'id', false);
	}
	

	// SETTERS
	public function setId($id){
		$this->id = $id;
	}
	public function setPerson($person){
		$this->person = $person;
	}
	public function setLastEntryTime($lastEntryTime){
		$this->lastEntryTime = $lastEntryTime;
	}
	public function setRouteName($routeName){
		$this->routeName = $routeName;
	}


	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getPerson($asObject = null){
		if($asObject){
			return (new Person())->findById($this->person);
		}
		return $this->person;
	}
	public function getLastEntryTime(){
		return $this->lastEntryTime;
	}
	public function getRouteName(){
		return $this->routeName;
	}


	/**
     * Returns an array containing all data of Company 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData($asObject = true)
	{
		$response = [
			'id' 		    => $this->getId(),
			'person' 	    => $this->getPerson($asObject),
			'lastEntryTime' => $this->getLastEntryTime(),
		];
		return $response;
	}

	// saves the last entry time (of a view) of person in order to check if it is online or not
	public function updateActivity($personId, $currentRoute)
	{
		if(!$personId){
			return null;
		}
		$this->setPerson($personId);
		$currentTime = (new DateTime())->format('Y-m-d H:i:s');
		$this->setLastEntryTime($currentTime);
		$this->setRouteName($currentRoute);
		$result = $this->save($this);
		return $result;
	}

	// removes the sixth PersonActivity in order to maintain a maximun of 5 activities stored by person
	public function verifyLimitOfActivitiesByPerson($personId)
	{
		$personActivities = $this->getPersonActivitiesByPerson($personId, true);
		$total = $personActivities ? count($personActivities) : 1;
		if($total >= 5){
			$personActivities[0]->destroy();
		}
		return true;
	}

	public function getPersonActivitiesByPerson($personId)
	{
		$results = $this->find("person = :personId", "personId=$personId")->order('lastEntryTime ASC')->fetch(true);
		return $results;
	}
}