<?php

namespace Source\Models;

use Source\Models\Document;
use Source\Models\Post;
use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class PostPhoto extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('postphotos', ['post', 'document'], 'id', false);
	}

	// SETTERS
	public function setPost($post){
		$this->post = $post;
	}
	public function setDocument($document){
		$this->document = $document;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getPost(){
		return $this->post;
	}
	public function getDocument($asObject = false){
		if($asObject){
			$documentObj = (new Document())->findById($this->document);
			return $documentObj;
		}
		return $this->document;
	}

	// this saves the relation of the Post with a Photo
	// by calling the saveDocuments it creates either the 'file'
	// and the Document obj, relating both Document and Post 
	public function savePostPhoto($postId)
	{
		if(is_null($postId))
			return json_encode([
                'success' => false,
                'message' => ucfirst(translate('invalid id')),
           ]); 
		$postObj = new Post();
		$postPhoto = $postObj->findById($postId);
		if(!$postPhoto)
			return json_encode([
                'success' => false,
                'message' => ucfirst(translate('invalid id')),
           ]); 
		$documentObj = new Document();
		$responseIds = $documentObj->saveDocuments();
		$postPhotosSavedIds = [];
		foreach($responseIds as $documentId){
			$postPhotoObj = new PostPhoto();
			$postPhotoObj->setPost($postId);
			$postPhotoObj->setDocument($documentId);
			$savingResult = $postPhotoObj->save();
			if($savingResult){
				$postPhotosSavedIds[] = $postPhotoObj->data->id;
			}else{
				$postPhotoObj->removePostPhotos($postPhotosSavedIds);
				$postPhotosSavedIds = [];
			}
		}
		return $postPhotosSavedIds;
	}

	// removes each sent id
	public function removePostPhotos($idsArray = [])
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
	public function removePostPhotosAndDocuments($postId)
	{
		$objectArray = $this->getByPost($postId);
		$documentObj = new Document();
		if(empty($objectArray)){
			return true;
		}
		foreach($objectArray as $object){
			$documentId = $object->getDocument();
			$result = $object->destroy();
			if(!$result){
				$result = false;
				break;
			}
			$document = $documentObj->findById($documentId);
			$result = $document ? $document->destroy() : null;
			if(!$result){
				continue;
			}
		}
		return $result ? true : false;
	}

	// get all by Posts
	public function getByPost($postId)
	{
		$objects = $this->find("post = :postId", "postId=$postId")
		->fetch(true);
		return $objects;
	}
}