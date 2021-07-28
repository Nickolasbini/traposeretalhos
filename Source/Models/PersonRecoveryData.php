<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Datetime;
use Source\Support\Mail;

/**
 * 
 */
class personRecoveryData extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('personrecoverydatas', ['dateOfUpdate','person'], 'id', false);
	}

	// SETTERS
	public function setLastUpdateIpAddress($LastUpdateIpAddress){
		$this->LastUpdateIpAddress = $LastUpdateIpAddress;
	}
	public function setDateOfLastRecovery($dateOfLastRecovery){
		$this->dateOfLastRecovery = $dateOfLastRecovery;
	}
	public function setPerson($person){
		$this->person = $person;
	}
	public function setDateOfUpdate($dateOfUpdate){
		$this->dateOfUpdate = $dateOfUpdate;
	}

	// GETTERS
	public function getId($id){
		return $this->id;
	}
	public function getLastUpdateIpAddress(){
		return $this->lastUpdateIpAddress;
	}
	public function getDateOfLastRecovery(){
		return $this->dateOfLastRecovery;
	}
	public function getPerson(){
		return $this->person;
	}
	public function getDateOfUpdadte(){
		return $this->dateOfUpdate;
	}

	/**
     * Creates or updates a PersonRecoveryData, relating it to sent Person id.
     * @version 1.0 - 20210406
     * @param  <string> person id (empty for new one)
     * @return <array> keys <bool>   'success'
     *					    <string> 'message'
     */
	public function saveRecoveryData($personId)
	{
		if(is_null($personId) || !is_numeric($personId)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('person id is invalid'))
			]);
		}
		// Fetch object
		$personRecoveryDataObj = $this->getPersonRecoveryDateByPerson($personId);
		if(is_null($personRecoveryDataObj)){
			$personRecoveryDataObj = $this;
			$personRecoveryDataObj->setPerson($personId);
		}
		// Getting current date
		$currentDate = date("Y-m-d h:i:sa");
		$personRecoveryDataObj->setDateOfUpdate($currentDate);
		// Getting user IpAddress
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$lastUpdateIpAddress = $personRecoveryDataObj->getLastUpdateIpAddress();
		$return = [];
		if(!is_null($lastUpdateIpAddress) && $lastUpdateIpAddress != $ipAddress){
			// think here how to comunicate the user of this
			$return['differentIp'] = [
				'lastIp' 	=> $lastUpdateIpAddress,
				'currentIp' => $ipAddress
			];
		}
		$personRecoveryDataObj->setLastUpdateIpAddress($ipAddress);
		$result = $personRecoveryDataObj->save();
		$return['saveResponse'] = $result;
		return $return;
	}

	/**
     * Default method informing the differenceInIpAddresses from former Person update
     * @version 1.0 - 20210406
     * @param  <string> the email address to send
     * @return <bool> 
     */
	public function sendDifferentIpMail($email)
	{
		$mail = new Mail();
		$sendTo = [$email];
		$title   = ucfirst(translate('account warning'));
		$message = ucfirst(translate('your account has recently been changed from a different place, if you did not do this, please change your password')); 
		$result = $mail->sendMail($sendTo, $title, $message);
		return $result ? true : false;
	}

	/**
     * Return PersonRecoveryData object found by 'personId'
     * @version 1.0 - 20210406
     * @param  <int>  the person Id
     * @return <obj> of PersonRecoveryData or <null> 
     */
	public function getPersonRecoveryDateByPerson($personId)
	{
		$personRecoveryDataObj = $this->find("person = :personId", "personId=$personId")
		->limit(1)
		->fetch(true);
		return $personRecoveryDataObj ? $personRecoveryDataObj[0] : null;
	}

	/**
     * Removes a PersonRecoveryData
     * @version 1.0 - 20210406
     * @return <bool> 
     */
	public function remove($personId)
	{
		$personRecoveryData = $this->getPersonRecoveryDateByPerson($personId);
		if(is_null($personRecoveryData)){
			return false;
		}
		$response = $personRecoveryData->destroy();
		return $response ? true : false; 
	}
}