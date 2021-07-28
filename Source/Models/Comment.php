<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Post;
use Source\Models\Person;
use Source\Helpers\FunctionsClass;

/**
 * 
 */
class Comment extends DataLayer
{

	function __construct()
	{
		parent::__construct('comments', ['dateOfCreation', 'userComment', 'post', 'person'], 'id', false);
	}
	// SETTERS
	public function setDateOfCreation($dateOfCreation){
		$this->dateOfCreation = $dateOfCreation;
	}
	public function setUserComment($userComment){
		$this->userComment = $userComment;
	}
	public function setDateOfLastUpdate($dateOfLastUpdate){
		$this->dateOfLastUpdate = $dateOfLastUpdate;
	}
	public function setPost($post){
		$this->post = $post;
	}
	public function setPerson($person){
		$this->person = $person;
	}
	public function setLikes($likes){
		$this->likes = $likes;
	}
	public function setPeopleWhichLiked($peopleWhichLiked){
		$this->peopleWhichLiked = $peopleWhichLiked;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getDateOfCreation(){
		return $this->dateOfCreation;
	}
	public function getUserComment(){
		return $this->userComment;
	}
	public function getDateOfLastUpdate(){
		return $this->dateOfLastUpdate;
	}
	public function getPost($asObject = false){
		if($asObject){
			$postObj = (new Post())->findById($this->post);
			return $postObj;
		}
		return $this->post;
	}
	public function getPerson($asObject = false){
		if($asObject){
			$personObj = (new Person())->findById($this->person);
			return $personObj;
		}
		return $this->person;
	}
	public function getLikes(){
		return $this->likes;
	}
	public function getPeopleWhichLiked($decode = true){
		if($decode){
			$peopleWhichLiked = json_decode($this->peopleWhichLiked, true);
			return is_array($peopleWhichLiked) ? $peopleWhichLiked : [];
		}
		return $this->peopleWhichLiked;
	}

	// return the comments realted to sent Post id
	public function getByPost($postId)
	{
		$commentsObjects = $this->find("post = :postId", "postId=$postId")
		->fetch(true);
		return $commentsObjects;
	}

	public function getFullData($gatherPersonObjectData = false, $gatherPostObjectData = false)
	{
		$response = [
			'id' 			   => $this->getId(),
			'userComment' 	   => $this->getUserComment(),
			'dateOfCreation'   => FunctionsClass::formatDate($this->getDateOfCreation()),
			'dateOfLastUpdate' => FunctionsClass::formatDate($this->getDateOfLastUpdate()),
			'post' 			   => $this->getPost(),
			'person' 		   => $this->getPerson(),
			'isOwner'		   => $this->isOwnerOfCommnet(),
			'likes'			   => $this->getLikes(),
			'peopleWhichLiked' => $this->getPeopleWhichLiked(true),
			'userIsOwnerOfLike'=> null
		];
		if(isset($_SESSION['personId']) && in_array($_SESSION['personId'], $this->getPeopleWhichLiked(true))){
			$response['userIsOwnerOfLike'] = true;
		}
		if($gatherPersonObjectData){
			$personObj = $this->getPerson(true);
			$response['person'] = [
				'id' 		   => $personObj->getId(),
				'fullName'     => $personObj->getFullName(),
				'profilePhoto' => !is_null($personObj->getProfilePhoto()) ? $personObj->getProfilePhoto()
																	      : null
			];
		}
		if($gatherPostObjectData){
			$postObj = $this->getPost(true);
			$response['post'] = [
				'id' 	  => $postObj->getId(),
				'isOwner' => $postObj->isOwnerOfPost(),
			];
		}
		return $response;
	}

	// retunr whhether comment belong or not to the logged in user
	public function isOwnerOfCommnet()
	{
		$personId = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
		if(is_null($personId))
			return false;
		if($this->getPerson() == $personId)
			return true;
		return false;
	}

	public function removeComments($postId)
	{
		$comments = $this->getByPost($postId);
		if(empty($comments))
			return true;
		foreach($comments as $comment){
			$result = $comment->destroy();
			if(!$result){
				return false;
			}
		}
		return true;
	}

	public function updatePersonWhichLiked($personId, $add = true)
	{
		$currentPeopleWhichLiked = $this->getPeopleWhichLiked(true);
		if(is_null($currentPeopleWhichLiked))
			$currentPeopleWhichLiked = [];
		if($add){
			$currentPeopleWhichLiked[] = $personId;
		}else{
			$newPeopleWhichLiked = [];
			foreach($currentPeopleWhichLiked as $idOfPerson){
				if($idOfPerson == $personId)
					continue;
				$newPeopleWhichLiked[] = $idOfPerson;
			}
			$currentPeopleWhichLiked = $newPeopleWhichLiked;
		}
		$this->setPeopleWhichLiked(json_encode($currentPeopleWhichLiked));
		return true;
	}
}