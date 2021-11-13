<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Document;

/**
 * 
 */
class PersonPhoto extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('personphotos', ['person', 'document'], 'id', false);
	}

	// SETTERS
	public function setPerson($person){
		$this->person = $person;
	}
	public function setDocument($document){
		$this->document = $document;
	}
	public function setIsProfileMainPicture($isProfileMainPicture){
		$this->isProfileMainPicture = $isProfileMainPicture;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getPerson(){
		return $this->person;
	}
	public function getDocument($asObject = false){
		if($asObject){
			$documentObj = (new Document())->findById($this->document);
			return $documentObj;
		}
		return $this->document;
	}
	public function getIsProfileMainPicture(){
		return $this->isProfileMainPicture;
	}

	// this saves the relation of the Person with a Photo
	// by calling the saveDocuments it creates either the 'file'
	// and the Document obj, relating both Document and Post 
	public function savePersonPhoto($personId, $personObj = null, $photo = null, $directoryName = null)
	{
		if(is_null($personId) && !$personObj)
			return json_encode([
                'success' => false,
                'message' => ucfirst(translate('invalid id')),
           ]); 
		if($personObj){
			$person = $personObj;
		}else{
			$personObj = new Person();
			$person = $personObj->findById($personId);
		}
		if(!$person)
			return json_encode([
                'success' => false,
                'message' => ucfirst(translate('invalid id')),
           ]); 
		$documentObj = new Document();
		$responseIds = $documentObj->saveDocuments($photo, $directoryName);
		$personPhotosSavedIds = [];
		$mainProfilePhoto = true;
		foreach($responseIds as $documentId){
			$personPhotoObj = new PersonPhoto();
			$personPhotoObj->setPerson($personId);
			$personPhotoObj->setDocument($documentId);
			$personPhotoObj->setIsProfileMainPicture($mainProfilePhoto);
			$savingResult = $personPhotoObj->save();
			if($savingResult){
				$personPhotosSavedIds[] = $personPhotoObj->data->id;
			}else{
				$personPhotoObj->removePersonPhotos($personPhotosSavedIds);
				$personPhotosSavedIds = [];
			}
			$mainProfilePhoto = false;
		}
		return $personPhotosSavedIds;
	}

	// removes each sent id
	public function removePersonPhotos($idsArray = [])
	{
		foreach($idsArray as $id){
			$obj = $this->findById($id);
			if($obj){
				$obj->destroy();
			}
		}
		return true;
	}

	// remove PostPhoto and its related Documents
	// removes one or more photos
	public function removePersonPhotosAndDocuments($removalArray)
	{
		$documentObj = new Document();
		foreach($removalArray as $data){
			$personPhoto = $this->getByPerson($data['personId']);
			if(!$personPhoto){
				$result = false;
				break;
			}
			$result = $personPhoto->destroy();
			if(!$result){
				$result = false;
				break;
			}
			$document = $documentObj->findById($data['documnetId']);
			$result = $document->destroy();
			if(!$result)
				continue;
		}
		return $result ? true : false;
	}

	// get all by PersonId
	public function getByPerson($personId, $mainPicture = false)
	{
		if($mainPicture){
			$object = $this->find("person = :personId AND isProfileMainPicture = :mainPicture", "personId=$personId&mainPicture=$mainPicture")
			->limit(1)->fetch();
			return $object;
		}else{
			$objects = $this->find("person = :personId", "personId=$personId")
			->fetch(true);
		}
		return $objects;
	}
}